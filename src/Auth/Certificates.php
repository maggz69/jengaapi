<?php


namespace maggz69\JengaApi\Auth;

use maggz69\JengaApi\Exceptions\MissingPrivateKey;

class Certificates
{

    /**
     *
     */
    const LOCAL_PRIVATE_KEY_PATH = "phpseclib/local_private_key.pem";
    /**
     *
     */
    const LOCAL_PUBLIC_KEY_PATH = "phpseclib/local_public_key.pem";

    /**
     * Read the private key file information.
     * @return string
     * @throws MissingPrivateKey
     */
    public static function readPrivateKey(): string
    {
        if (config('jenga.certificates.local')) {
            if (\Storage::exists(self::LOCAL_PRIVATE_KEY_PATH)) {
                return \Storage::get(self::LOCAL_PRIVATE_KEY_PATH);
            } else {
                if (isset(self::generateCertificate()['local_private_key'])){
                    return \Storage::get(self::LOCAL_PRIVATE_KEY_PATH);
                }
            }
        } else {
            if (\Storage::exists(config('jenga.certificates.path'))) {
                return \Storage::path(config('jenga.certificates.path'));
            } else {
                throw new MissingPrivateKey(config('jenga.certificates.path'));
            }
        }
    }

    /**
     *  The following code is used to generate the certificate that will be used in signing requests.
     *
     *  Code sourced from https://phpseclib.com/docs/x509#creating-certificates
     */
    public static function generateCertificate(): array
    {
        $private = (new \phpseclib\Crypt\RSA)->createKey();

        $certificateWhole = ['private_key' => $private['privatekey'], 'public_key' => $private['publickey']];

        \Storage::put(self::LOCAL_PRIVATE_KEY_PATH, $certificateWhole['private_key']);
        \Storage::put(self::LOCAL_PUBLIC_KEY_PATH, $certificateWhole['public_key']);

        return $certificateWhole;
    }

    /**
     * Get the private key path.
     *
     * Read the path to the private signing key and return it.
     * @return string
     * @throws MissingPrivateKey
     */
    public static function readPrivateKeyPath(): string
    {
        if (config('jenga.certificates.local')) {
            if (\Storage::exists(self::LOCAL_PRIVATE_KEY_PATH)) {
                return \Storage::path(self::LOCAL_PRIVATE_KEY_PATH);
            } else {
                if (isset(self::generateCertificate()['local_private_key'])){
                    return \Storage::path(self::LOCAL_PRIVATE_KEY_PATH);
                }
            }
        } else {
            if (\Storage::exists(config('jenga.certificates.path'))) {
                return \Storage::path(config('jenga.certificates.path'));
            } else {
                throw new MissingPrivateKey(config('jenga.certificates.path'));
            }
        }
    }

}
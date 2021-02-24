<?php

namespace maggz69\JengaApiCommands;

use maggz69\JengaApi\Auth\Certificates;

class CreateCertificateCommand extends \Illuminate\Console\Command
{
    protected $signature = 'jenga:create-certificate';

    protected $description = 'Create a local certificate to make use of for signing requests to the JengaAPI';

    public function handle()
    {
        $certificate = Certificates::generateCertificate();

        $this->output->success('Created your certificate successfully');

        dd($certificate);
    }
}

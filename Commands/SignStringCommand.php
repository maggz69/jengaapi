<?php


namespace maggz69\JengaApiCommands;


use maggz69\JengaApi\Network\NetworkPipeline;

class SignStringCommand extends \Illuminate\Console\Command
{
    protected $signature = 'jenga:sign {string}';

    protected $description = 'Sign a string using the certificate provided';

    public function handle()
    {
        $string = $this->argument('string');

        $this->output->text("Signing the following text:" . $string);

        $signedString = NetworkPipeline::signString($string);

        $this->output->text("Your signed string is : " . $signedString);
    }

}
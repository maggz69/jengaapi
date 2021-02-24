<?php

namespace maggz69\JengaApiProviders;

use maggz69\JengaApiCommands\ApiCommand;
use maggz69\JengaApiCommands\CreateCertificateCommand;
use maggz69\JengaApiCommands\SignStringCommand;

class JengaApiServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/jenga.php' => config_path('jenga.php')
        ]);

        $this->commands([
            CreateCertificateCommand::class,
            SignStringCommand::class,
            ApiCommand::class
        ]);
    }

}
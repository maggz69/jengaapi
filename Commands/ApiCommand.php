<?php

namespace maggz69\JengaApiCommands;

use Illuminate\Console\Command;
use maggz69\JengaApi\Api\AccountBalance;
use maggz69\JengaApi\Api\AccountMiniStatement;
use maggz69\JengaApi\Api\OpeningClosingAccountBalance;

class ApiCommand extends Command
{
    protected $signature = 'jenga:api {api} {--params=*}';

    protected $description = 'Run a command that directly calls the API';

    private string $longText;

    public function handle()
    {
        $command = $this->argument('api');

        $result = $this->convertShortCommandToLong();

        if (!isset($result)) {
            $this->output->error($this->longText);
        } else {
            $this->output->text($this->longText);
            dd($result);
        }

        return 0;
    }

    private function convertShortCommandToLong(): ?array
    {
        switch ($this->argument('api')) {
            case 'ab':
                $this->longText = 'Account Balance';

                return (new AccountBalance())->checkBalance();
            case 'ams':
                $this->longText = 'Account Mini Statement';

                return (new AccountMiniStatement())->getMiniStatement();
            case 'ocab':
                $this->longText = 'Account Mini Statement';

                return (new OpeningClosingAccountBalance(null, null, $this->option('params')[0]))->getBalance();
            default:
                $this->longText = 'No command found';

                return null;
        }
    }
}

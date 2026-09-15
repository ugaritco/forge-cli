<?php

namespace App\Commands;

use function Ugarit\Prompts\info;

class LogoutCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'logout';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Logout from Ugarit Forge';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->config->flush();

        info('Logged out successfully');
    }
}

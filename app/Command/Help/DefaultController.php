<?php

namespace App\Command\Help;

use Minicli\App;
use Minicli\Command\CommanController;
use Minicli\Command\CommandRegistry;

class DefaultController extends CommandController
{
	protected $commandMAp = [];

	public function boot(App $app)
	{
		parent::boot($app);
		$this->commandMap = $app->commandRegistry->getCommandMap();
	}

	public function handle()
	{
		$this->getPrinter()->info('Available Commands');

		foreach ($this->commandMap as $command => $sub) {

			$this->getPrinter()->newline();
			$this->getPrinter()->out($command, 'info_alt');

			if (is_array($sub)) {
				foreach ($sub as $subCommand) {
					if ($subCommand !== 'default') {
						$this->getPrinter()->newline();
						$this->getPrinter()->out(sprintf('%s%s', '└──', $subCommand));
					}
				}
			}
			$this->getPrinter()->newline();
		}

		$this->getPrinter()->newline();
		$this->getPrinter()->newline();
	}
}

<?php

namespace Minicli;

class App
{
	protected $printer;

	protected $commandRegistry = [];

	public function __construct()
	{
		$this->printer = new CliPrinter();
		$this->commandRegistry = new CommandRegistry();
	}

	public function getPrinter()
	{
		return $this->printer;
	}

	public function registerCommand($name, $callable)
	{
		$this->commandRegistry->registerCommand($name, $callable);
	}

	public function runCommand(array $argv)
	{
		$commandName = "help";
		
		if (isset($argv[1])) {
			$commandName = $argv[1];
		}

		$command = $this->commandRegistry->getCommand($commandName);
		if ($command === null) {
			$this->getPrinter()->display("ERROR: Command \"$commandName\" not found.");
			exit;
		}

		call_user_func($command, $argv);
	}
}

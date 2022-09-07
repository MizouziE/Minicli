<?php

namespace Minicli;

use Minicli\Command\CommandCall;
use Minicli\Command\CommandRegistry;
use Minicli\Output\BasicPrinter;
use Minicli\Output\CliPrinter;

class App
{
	protected $printer;

	protected $commandRegistry = [];

	protected $appSignature;

	public function __construct($appPath = null, OutputInterface $output = null)
	{
		$this->printer = $output ?? new BasicPrinter();

		$this->commandRegistry = new CommandRegistry($appPath ?? __DIR__ . '/../app/Command');
	}

	public function getPrinter()
	{
		return $this->printer;
	}

	public function getSignature()
	{
		return $this->appSignature;
	}

	public function printSignature()
	{
		$this->getPRinter()->display(sprintf("usage: %s", $this->getSignature()));
	}

	public function setSignature($appSignature)
	{
		$this->appSignature = $appSignature;
	}

	public function registerCommand($name, $callable)
	{
		$this->commandRegistry->registerCommand($name, $callable);
	}

	public function runCommand(array $argv)
	{
		$input = new CommandCall($argv);

		if (count($input->args) < 2) {
			$this->printSignature();
			exit;
		}

		$controller = $this->commandRegistry->getCallableController($input->command, $input->subCommand);

		if ($controller instanceof ControllerInterface) {
			$controller->boot($this);
			$controller->run($input);
			$controller->teardown();
			exit;
		}

		$this->runSingle($input);
	}

	protected function runSingle(CommandCall $input)
	{
		try {
			$callable = $this->commandRegistry->getCallable($input->command);
			call_user_func($callable, $input);
		} catch (\Exception $e) {
			$this->getPrinter()->display("ERROR: " . $e->getMessage());
			$this->printSignature();
			exit;
		}
	}
}

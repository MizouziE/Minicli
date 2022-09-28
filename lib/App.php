<?php

namespace Minicli;

use Minicli\Command\CommandCall;
use Minicli\Command\CommandRegistry;
use Minicli\Output\CliPrinter;

class App
{
	protected $appSignature;

	protected $services = [];

	protected $loadedServices = [];

	public function __construct(array $config = null)
	{
		$config = array_merge([
			'appPath' => __DIR__ . '/../app/Command',
			'theme' => 'regular',
		], $config);

		$this->setSignature('./minicli help');

		$this->addService('config', new Config($config));
		$this->addService('commandRegistry', new CommandRegistry($this->config->appPath));
		$this->addService('printer', new CliPrinter());
	}

	public function __get($name)
	{
		if (!array_key_exists($name, $this->services)) {
			return null;
		}

		if (!array_key_exists($name, $this->loadedServices)) {
			$this->loadService($name);
		}

		return $this->services[$name];
	}

	public function addService($name, ServiceInterface $service)
	{
		$this->services[$name] = $service;
	}

	public function loadService($name)
	{
		$this->loadedServices[$name] = $this->services[$name]->load($this);
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

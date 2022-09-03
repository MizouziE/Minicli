<?php

namespace Minicli;

class CommandRegistry
{
	protected $registry = [];

	protected $controllers = [];

	public function registerController($commandName, CommandController $controller)
	{
		$this->controllers = [ $commandName => $controller ];
	}

	public function registerCommand($name, $callable)
	{
		$this->registry[$name] = $callable;
	}

	public function getController($command)
	{
		return $this->controllers[$command] ?? null;
	}

	public function getCommand($command)
	{
		return $this->registry[$command] ?? null;
	}

	public function getCallable($commandName)
	{
		$controller = $this->getController($commandName);

		if ($controller instanceof CommandController) {
			return [ $controller, 'run' ];
		}

		$command = $this->getCommand($commandName);

		if ($command === null) {
			throw new \Exception("Command \"$commandName\" not found.");
		}

		return $command;
	}
}

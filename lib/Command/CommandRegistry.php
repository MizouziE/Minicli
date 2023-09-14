<?php

namespace Minicli\Command;

use Minicli\App;
use Minicli\Exception\CommandNotFoundException;
use Minicli\ServiceInterface;

class CommandRegistry implements ServiceInterface
{
	protected $commandsPath;

	protected $namespaces = [];

	protected $defaultRegistry = [];

	public function __construct($commandsPath)
	{
		$this->commandsPath = $commandsPath;
	}

	public function load(App $app)
	{
		$this->autoloadNamespaces();
	}

	public function autoloadNamespaces()
	{
		foreach (glob($this->getCommandsPath() . '/*', GLOB_ONLYDIR) as $namespacePath) {
			$this->registerNamespace(basename($namespacePath));
		}
	}

	public function registerNamespace($commandNamespace)
	{
		$namespace = new CommandNamespace($commandNamespace);
		$namespace->loadControllers($this->getCommandsPath());
		$this->namespaces[strtolower($commandNamespace)] = $namespace;
	}

	public function getNamespace($command)
	{
		return $this->namespaces[$command] ?? null;
	}

	public function getCommandsPath()
	{
		return $this->commandsPath;
	}

	public function registerCommand($name, $callable)
	{
		$this->defaultRegistry[$name] = $callable;
	}

	public function getCommand($command)
	{
		return $this->defaultRegistry[$command] ?? null;
	}

	public function getCallableController($command, $subCommand = null)
	{
		$namespace = $this->getNamespace($command);

		if ($namespace !== null) {
			return $namespace->getController($subCommand);
		}

		return null;
	}

	public function getCallable($command)
	{
		$singleCommand = $this->getCommand($command);

		if ($singleCommand === null) {
			throw new CommandNotFoundException(sprintf("Command \"%s\" not found.", $command));
		}

		return $singleCommand;
	}

	public function getCommandMap()
	{
		$map = [];

		foreach ($this->defaultRegistry as $command => $callback) {
			$map[$command] = $callback;
		}

		/**
		* @var string $command
		* @var CommandNamespace $namespace
		*/

		foreach ($this->namespaces as $command => $namespace) {
			$controllers = $namespace->getControllers();
			$subs = [];
			foreach ($controllers as $subCommand => $controller) {
				$subs[] = $subCommand;
			}

			$map[$command] = $subs;
		}

		return $map;
	}
}

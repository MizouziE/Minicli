<?php

namespace Minicli\Command;

class CommandNamespace
{
	protected $name;

	protected $controllers = [];

	public function __construct($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

	public function loadControllers($commandsPath)
	{
		foreach (glob($commandsPath . '/' . $this->getName() . '/*Controller.php') as $controllerFile) {
			$this->loadCommandMap($controllerFile);
		}

		return $this->getControllers();
	}

	public function getControllers()
	{
		return $this->controllers;
	}

	public function getController($commandName)
	{
		return $this->controllers[$commandName] ?? null;
	}

	protected function loadCommandMap($controllerFile)
	{
		$fileName = basename($controllerFile);

		$controllerClass = str_replace('.php', '', $fileName);

		$commandName = strtolower(str_replace('Controller', '', $controllerClass));

		$fullClassName = sprintf("%s\\%s", $this->getNamespace($controllerFile), $controllerClass);

		/** @var CommandController $controller */
		$controller = new $fullClassName();
		$this->controllers[$commandName] = $controller;
	}

	protected function getNamespace($fileName)
	{
		$lines = preg_grep('/^namespace /', file($fileName));
		$namespaceLine = array_shift($lines);
		$match = [];
		preg_match('/^namespace (.*);$/', $namespaceLine, $match);

		return array_pop($match);
	}
}

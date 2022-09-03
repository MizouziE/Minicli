<?php

namespace Minicli;

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

		$fullClassName = sprintf("App\\Command\\%s\\%s", $this->getName(), $controllerClass);

		/** @var CommandController $controller */
		$controller = new $fullClassName();
		$this->controllers[$commandName] = $controller;
	}
}

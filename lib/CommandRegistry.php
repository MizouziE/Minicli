<?php

namespace Minicli;

class CommandRegistry
{
	protected $registry = [];

	public function registerCommand($name, $callable)
	{
		$this->registry[$name] = $callable;
	}

	public function getCommand($command)
	{
		return $this->registry[$command] ?? null;
	}
}

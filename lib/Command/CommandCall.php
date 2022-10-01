<?php

namespace Minicli\Command;

class CommandCall
{
	public $command;

	public $subCommand;

	public $args = [];

	public $params = [];

	public function __construct(array $argv)
	{
		$this->args = $argv;
		$this->command = $argv[1] ?? null;
		$this->subCommand = $argv[2] ?? 'default';

		$this->loadParams($argv);
	}

	protected function loadParams(array $args)
	{
		foreach ($args as $arg) {
			$pair = explode('=', $arg);
			if (count($pair) == 2) {
				$this->params[$pair[0]] = $pair[1];
			}
		}
	}

	public function getParam($param)
	{
		return $this->params[$param] ?? null;
	}

	public function hasFlag($flag)
	{
		return in_array($flag, $this->args);
	}
}

<?php

namespace App\Command;

use Minicli\CommandController;

class HelloController extends CommandController
{
	public function run($argv)
	{
		$name = $argv[2] ?? "there";
		$this->getApp()->getPrinter()->display("Hello $name!");
	}
}

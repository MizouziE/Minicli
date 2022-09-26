<?php

namespace App\Command\Help;

use Minicli\Command\CommandController;

class TestController extends CommandController
{
	public function handle()
	{
		$name = $this->getParam('user') ?? "there";

		$this->getPrinter()->display(sprintf("Hello, %s!", $name));

		print_r($this->getParams());
	}
}

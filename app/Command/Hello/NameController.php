<?php

namespace App\Command\Hello;

use Minicli\Command\CommandController;

class NameController extends CommandController
{
	public function handle()
	{
		$name = $this->getParam('user') ?? "there";

		$this->getPrinter()->display(sprintf("Hello, %s!", $name));
	}
}

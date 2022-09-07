<?php

namespace Minicli;

use Minicli\Command\CommandCall;

interface ControllerInterface
{
	public function boot(App $app);

	public function run(CommandCall $input);

	public function teardown();
}

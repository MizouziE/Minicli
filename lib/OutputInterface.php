<?php

namespace Minicli;

interface OutputInterface
{
	public function out($message);

	public function newLine();

	public function display($message);
}

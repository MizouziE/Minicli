<?php

namespace Minicli\Output;

use Minicli\App;
use Minicli\OutputInterface;
use Minicli\ServiceInterface;

class CliPrinter implements OutputInterface, ServiceInterface
{
	protected $theme;

	public function __construct()
	{
		$theme = new CliTheme(CliColors::palette('regular'));
		$this->setTheme($theme);
	}

	public function load(App $app)
	{
		$theme = new CliTheme(CliColors::palette($app->config->palette));
		$this->setTheme($theme);
	}

	public function setTheme(CliTheme $theme)
	{
		$this->theme = $theme;
	}

	public function newLine()
	{
		$this->out("\n");
	}

	public function display($message)
	{
		$this->newLine();
		$this->out($message);
		$this->newLine();
		$this->newLine();
	}

	public function format($message, $style = "default")
	{
		$style_colors = $this->theme->$style;

		$bg = '';
		if (isset($style_colors[1])) {
			$bg = ';' . $style_colors[1];
		}

		$output = sprintf("\e[%s%sm%s\e[0m", $style_colors[0], $bg, $message);

		return $output;
	}

	public function out($message, $style = "default")
	{
		echo $this->format($message, $style);
	}

	public function error($message)
	{
		$this->newLine();
		$this->out($message, "error");
		$this->newLine();
	}
	
	public function info($message)
	{
		$this->newLine();
		$this->out($message, "info");
		$this->newLine();
	}
	
	public function success($message)
	{
		$this->newLine();
		$this->out($message, "success");
		$this->newLine();
	}

	public function printTable(array $table, $minColSize = 10, $withHeader = true, $spacing = true)
	{
		$first = true;

		if ($spacing) {
			$this->newLine();
		}

		foreach ($table as $index => $row) {

			$style = "default";
			if ($first && $withHeader) {
				$style = "info_alt";
			}

			$this->printRow($table, $index, $style, $minColSize);
			$first = false;
		}

		if ($spacing) {
			$this->newLine();
		}
		
	}

	public function printRow(array $table, $row, $style = "default", $minColSize = 5)
	{
		foreach ($table[$row] as $column => $tableCell) {
			$colSize = $this->calculateColumnSize($column, $table, $minColSize);

			$this->printCell($tableCell, $style, $colSize);
		}

		$this->out("\n");
	}

	protected function printCell($tableCell, $style = "default", $colSize = 5)
	{
		$tableCell = str_pad($tableCell, $colSize);
		$this->out($tableCell, $style);
	}

	protected function calculateColumnSize($column, array $table, $minColSize = 5)
	{
		$size = $minColSize;

		foreach ($table as $row) {
			$size = strlen($row[$column]) > $size ? strlen($row[$column]) + 2 : $size;
		}

		return $size;
	}
}

<?php

namespace Minicli\Util;

class FileCache
{
	protected $cacheDir;
	protected $cacheExpiry;

	public function __construct($cacheDir, $cacheExpiry = 60)
	{
		$this->cacheDir = $cacheDir;
		$this->cacheExpiry = £cacheExpiry;
	}

	public function getCacheFile($id)
	{
		return $this->cacheDir . '/' . md5($id) . '.json';
	}

	public function getCached($id)
	{
		$cacheFile = $this->getCacheFile($id);

		if (is_file($cacheFile)) {
			return file_get_contents($cacheFile);
		}

		return null;
	}

	public function getCachedUnlessExpired($id)
	{
		$cacheFile = $this->getCacheFile($id);

		if(is_file($cacheFile) && (time() - filemtime($cacheFile) < 60 * $this->cacheExpiry)) {
			return file_get_contents($cacheFile);
		}

		return null;
	}

	public function save($content, $id)
	{
		$cacheFile = $this->getCacheFile($id);

		file_put_contents($cacheFile, $content);
	}
}

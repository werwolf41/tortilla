<?php

class mseDictionaryInstallProcessor extends modProcessor {


	/** {@inheritDoc} */
	public function process() {
		$url = 'http://downloads.sourceforge.net/project/phpmorphy/phpmorphy-dictionaries/0.3.x/';
		if ($dictionary = $this->getProperty('dictionary')) {
			$file = '';
			switch (strtolower($dictionary)) {
				case 'ru_ru':
					$dictionary = 'ru_RU';
					$file = 'morphy-0.3.x-ru_RU-nojo-utf8.zip';
					break;
				case 'en_en':
					$dictionary = 'en_EN';
					$file = 'morphy-0.3.x-en_EN-windows-1250.zip';
					break;
				case 'uk_ua':
					$dictionary = 'uk_UA';
					$file = 'morphy-0.3.x-uk_UA-ispell-utf8.zip';
					break;
				case 'et_ee':
					$dictionary = 'et_EE';
					$file = 'morphy-0.3.x-et_EE-ispell-utf-8.zip';
					break;
				case 'de_de':
					$dictionary = 'de_DE';
					$file = 'morphy-0.3.x-de_DE-utf8.zip';
					break;
			}
			$url .= $dictionary . '/' . $file;
		}
		else {
			return $this->failure($this->modx->lexicon('mse2_dictionary_err_ns'));
		}

		if ($mirror = $this->getProperty('mirror')) {
			$url .= '?use_mirror=' . $mirror;
		}

		$result = $this->install($url, $dictionary, $file);
		if ($result !== true) {
			return $this->failure($result);
		}
		return $this->success();
	}


	/**
	 * Download specified file
	 *
	 * @param $src
	 * @param $dst
	 *
	 * @return bool
	 */
	public function download($src, $dst) {
		if (ini_get('allow_url_fopen')) {
			$file = @file_get_contents($src);
		}
		elseif (function_exists('curl_init')) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $src);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 180);
			$safeMode = @ini_get('safe_mode');
			$openBasedir = @ini_get('open_basedir');
			if (empty($safeMode) && empty($openBasedir)) {
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			}

			$file = curl_exec($ch);
			curl_close($ch);
		}
		else {
			return false;
		}
		file_put_contents($dst, $file);

		return file_exists($dst);
	}


	/**
	 * Install dictionary
	 *
	 * @param $url
	 * @param $dictionary
	 * @param $file
	 *
	 * @return bool|string
	 */
	public function install($url, $dictionary, $file) {
		if (!class_exists('PclZip')) {require MODX_CORE_PATH . 'xpdo/compression/pclzip.lib.php';}

		$path = $this->modx->mSearch2->config['corePath'];
		$dicts = $path . 'phpmorphy/dicts/';
		$destination = $dicts . $file;
		@mkdir($dicts);

		if (!file_exists($destination)) {
			$this->download($url, $destination);
		}

		$zip = new PclZip($destination);
		if ($zip->extract(PCLZIP_OPT_PATH, $dicts)) {
			unlink($destination);
		}
		else {
			unlink($destination);
			return 'Could not extract dictionaries from '.$destination.' to '.$dicts.'. Error: '.$zip->errorInfo();
		}

		return true;
	}

}

return 'mseDictionaryInstallProcessor';
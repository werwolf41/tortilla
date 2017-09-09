<?php

class mseDictionaryGetListProcessor extends modObjectGetListProcessor {

	/** {@inheritDoc} */
	public function process() {
		$list = array(
			array(
				'name' => 'CityLan',
				'location' => 'Moscow, Russian Federation',
				'mirror' => 'citylan',
			),
			array(
				'name' => 'German Research Network',
				'location' => 'Germany',
				'mirror' => 'dfn',
			),
			array(
				'name' => 'Japan Advanced Institute',
				'location' => 'Nomi, Japan',
				'mirror' => 'jaist',
			),
			array(
				'name' => 'kaz.kz',
				'location' => 'Almaty, Kazakhstan',
				'mirror' => 'kaz',
			),
			array(
				'name' => 'NetCologne',
				'location' => 'Köln, Germany',
				'mirror' => 'netcologne',
			),
			array(
				'name' => 'Softlayer',
				'location' => 'United States',
				'mirror' => 'softlayer-ams',
			),
			array(
				'name' => 'SWITCH',
				'location' => 'Zurich, Switzerland',
				'mirror' => 'switch',
			),
			array(
				'name' => 'TENET',
				'location' => 'Wynberg, South Africa',
				'mirror' => 'tenet',
			),
		);

		return $this->outputArray($list, count($list));
	}

}

return 'mseDictionaryGetListProcessor';
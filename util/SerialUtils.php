<?php

namespace util;

class SerialUtils {

	/**
	 * @param string $key
	 * @param mixed $mixed
	 */
	public static function save(string $key, $mixed): void {
		file_put_contents(self::getSerialPathFromKey($key), json_encode($mixed));
	}

	/**
	 * @param string $key
	 * @param $defaultValue
	 * @return mixed
	 */
	public static function get(string $key, $defaultValue = null) {
		if (!file_exists(self::getSerialPathFromKey($key))) {
			return $defaultValue;
		}
		return json_decode(file_get_contents(self::getSerialPathFromKey($key)), false);
	}

	private static function getSerialPathFromKey(string $key): string {
		return BASE_PATH . 'serial/' . bin2hex($key) . '.serial';
	}

}

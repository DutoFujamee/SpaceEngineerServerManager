<?php

namespace util;

class ArrayUtils {

	public static function arrayUnshiftWithKey(array &$array, $newKey, $newValue): void {
		$newArray = array($newKey => $newValue);
		foreach ($array as $key => $value) {
			$newArray[$key] = $value;
		}
		$array = $newArray;
	}

	/**
	 * @param array $array
	 * @param mixed $defaultValueIfEmptyArray
	 * @return mixed
	 */
	public static function arrayShift(array &$array, $defaultValueIfEmptyArray = null) {
		return count($array) !== 0
				? array_shift($array)
				: $defaultValueIfEmptyArray;
	}

	public static function getFirstOrDefault(array $array, $defaultValueIfEmptyArray = null) {
		return count($array) !== 0
				? $array[0]
				: $defaultValueIfEmptyArray;
	}

	public static function trim(array $array): array {
		foreach ($array as $key => $string) {
			$array[$key] = trim($string);
		}
		return $array;
	}

	public static function removeValues(array $array, array $values): array {
		return array_diff($array, $values);
	}

}

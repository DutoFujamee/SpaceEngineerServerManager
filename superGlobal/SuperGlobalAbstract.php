<?php

namespace superGlobal;

abstract class SuperGlobalAbstract {

	public static function getNullableString(string $key): ?string {
		$superGlobalArray = static::getSuperGlobalArray();
		return array_key_exists($key, $superGlobalArray)
				? $superGlobalArray[$key]
				: null;
	}

	abstract protected static function getSuperGlobalArray(): array;

}
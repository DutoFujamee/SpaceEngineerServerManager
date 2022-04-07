<?php

namespace util;

class Post {

	public static function getNullableString(string $key): ?string {
		return array_key_exists($key, $_POST)
				? (string) $_POST[$key]
				: null;
	}

}

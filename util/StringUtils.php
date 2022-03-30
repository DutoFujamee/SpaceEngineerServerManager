<?php

namespace util;

use InvalidArgumentException;
use RuntimeException;

class StringUtils {

	public static function removePotentialSurroundingQuotationMarks(string $string): string {
		if (strpos($string, '"') === 0) {
			$string = substr($string, 1);
		}
		if (substr($string, -1) === '"') {
			$string = substr($string, 0, -1);
		}
		return $string;
	}

	public static function getStringPart(
			string $source,
			string $prefix,
			string $suffix = ''
	): string {

		if ($prefix !== '') {
			$posPrefix = strpos($source, $prefix);
			if ($posPrefix === false) {
				throw new InvalidArgumentException('Prefix "' . $prefix . '" not found in source: "' . $source . '"');
			}
			$source = substr($source, $posPrefix + 1);
		}

		if ($suffix !== '') {
			$posSuffix = strpos($source, $suffix);
			if ($posSuffix === false) {
				throw new InvalidArgumentException('Suffix "' . $suffix . '" not found in source: "' . $source . '"');
			}
			$source = substr($source, 0, $posSuffix);
		}

		return $source;
	}

	public static function contains(string $haystack, string $needle): bool {
		return strpos($haystack, $needle) !== false;
	}

	public static function jsonEncode($value): string {
		$json = json_encode($value);
		if ($json === false) {
			throw new RuntimeException(json_last_error_msg());
		}
		return $json;
	}

}

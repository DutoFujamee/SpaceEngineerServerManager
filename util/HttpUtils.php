<?php

namespace util;

use JsonException;
use RuntimeException;

class HttpUtils {

	public const HEADER_AUTHORIZATION = 'Authorization';

	/**
	 * @param string $url
	 * @param string[] $valueByHeader
	 * @param mixed[] $patchValueByKey
	 * @return string
	 */
	public static function urlGetContent(
			string $url,
			array $valueByHeader = array(),
			array $patchValueByKey = array()
	): string {

		if (count($patchValueByKey) !== 0) {
			$valueByHeader['Content-type'] = 'application/json';
		}

		$headerText = '';
		foreach ($valueByHeader as $header => $value) {
			$headerText .= $header . ': ' . $value . "\r\n";
		}

		$options = array(
			'http' => array(
				'header' => $headerText,
			)
		);

		if (count($patchValueByKey) !== 0) {
			$options['http']['method'] = 'PATCH';
			$options['http']['content'] = json_encode($patchValueByKey, JSON_THROW_ON_ERROR);
		}

		$fileContent = file_get_contents($url, false, stream_context_create($options));

		if ($fileContent === false) {
			throw new RuntimeException('Unable to get content for url ' . $url);
		}

		return $fileContent;
	}

	/**
	 * @param string $url
	 * @param string[] $valueByHeader
	 * @param mixed[] $patchValueByKey
	 * @return string[]
	 * @throws JsonException
	 */
	public static function getJsonFromUrl(
			string $url,
			array $valueByHeader = array(),
			array $patchValueByKey = array()
	): array {
		return json_decode(self::urlGetContent($url, $valueByHeader, $patchValueByKey), true, 512, JSON_THROW_ON_ERROR);
	}

}

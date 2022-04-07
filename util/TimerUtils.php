<?php

namespace util;

class TimerUtils {

	private static $startTimestamp;

	public static function start(): void {
		self::$startTimestamp = microtime(true);
	}

	public static function stop(): int {
		$timePassed = microtime(true) - self::$startTimestamp;
		self::$startTimestamp = null;
		return $timePassed;
	}

}

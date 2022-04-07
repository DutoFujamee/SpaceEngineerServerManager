<?php

namespace util;

use LogicException;

class FileUtils {

	public static function ls(string $path, bool $fileOnly = false, ?array $extensionFilters = null): array {
		$entryPaths = array();
		foreach (array_diff(scandir($path, SCANDIR_SORT_NONE), array('..', '.')) as $entryName) {
			$entryPath = $path . DIRECTORY_SEPARATOR . $entryName;
			if ((!$fileOnly || !is_dir($entryPath)) && ($extensionFilters === null || in_array(self::getExtension($entryPath), $extensionFilters, true))) {
				$entryPaths[] = realpath($entryPath);
			}
		}
		return $entryPaths;
	}

	public static function getExtension(string $path): string {
		return pathinfo($path, PATHINFO_EXTENSION);
	}

	public static function lsRecursive(string $path, ?array $extListOnly = null): array {
		$filePathsList = array(array());
		foreach (self::ls($path) as $file) {
			$filePath = $path . DIRECTORY_SEPARATOR . $file;
			if (is_dir($filePath)) {
				$filePathsList[] = self::lsRecursive($filePath, $extListOnly);
			} else if ($extListOnly === null || in_array(pathinfo($filePath, PATHINFO_EXTENSION), $extListOnly)) {
				$filePathsList[] = array($filePath);
			}
		}
		return array_merge(...$filePathsList);
	}

	public static function getFolderPaths(string $path): array {
		$folderPaths = array();
		foreach (array_diff(scandir($path, SCANDIR_SORT_NONE), array('..', '.')) as $entryName) {
			$entryPath = $path . DIRECTORY_SEPARATOR . $entryName;
			if (is_dir($entryPath)) {
				$folderPaths[] = realpath($entryPath);
			}
		}
		return $folderPaths;
	}

	public static function getBaseNameFromFilePath(string $filePath): string {
		return pathinfo($filePath, PATHINFO_BASENAME);
	}

	/**
	 * @param array $filePaths
	 * @return string[]
	 */
	public static function indexFilePathByBaseName(array $filePaths): array {
		$filePathByFileName = array();
		foreach ($filePaths as $filePath) {
			$filePathByFileName[self::getBaseNameFromFilePath($filePath)] = $filePath;
		}
		return $filePathByFileName;
	}

	public static function getTmpFilePath(string $extension = 'tmp', string $prefix = ''): string {
		$tmpFilePath = null;
		do {
			$tmpFilePath = BASE_PATH . 'tmp/' . $prefix . uniqid('', false) . '.' . $extension;
		} while (file_exists($tmpFilePath));

		return $tmpFilePath;
	}

	public static function getTmpFolder(): string {
		return BASE_PATH . 'tmp/';
	}

	public static function humanReadableSize(int $bytes, int $precision = 2): string {
		static $units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		$step = 1024;
		$i = 0;
		while (($bytes / $step) > 0.9) {
			$bytes /= $step;
			$i++;
		}
		return round($bytes, $precision) . $units[$i];
	}

	public static function createMissingFolderForPath(string $path): void {
		$folderPath = dirname($path);
		if (is_dir($folderPath)) {
			return;
		}
		if (!mkdir($concurrentDirectory = $folderPath, 0777, true) && !is_dir($concurrentDirectory)) {
			throw new LogicException('Directory "' . $concurrentDirectory . '" was not created');
		}
	}

	public static function copy(string $from, string $to): void {
		self::createMissingFolderForPath($to);
		copy($from, $to);
	}

}

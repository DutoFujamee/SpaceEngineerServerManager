<?php

namespace util;

use DateTime;
use LogicException;
use PDO;
use pdo\PdoUtils;

class FileServerUtils {

	public static function findFirstIdForDate(PDO $pdo, string $date): int {
		$date = DateTime::createFromFormat('Y-m-d', $date)->format('Y-m-d');
		$fileId = $pdo->query("SELECT file_id FROM mta_file.file ORDER BY file_id DESC LIMIT 1")->fetchAll(PDO::FETCH_ASSOC)[0]['file_id'];

		$securityIndex = 10000;

		$step = 10000;
		while (true) {
			$fileId -= $step;
			$uploadDate = $pdo->query("SELECT upload_date FROM mta_file.file WHERE file_id = " . ((int) $fileId))->fetchAll(PDO::FETCH_ASSOC)[0]['upload_date'];
			if (!$uploadDate) {
				throw new LogicException('No date found');
			}
			if ($uploadDate < $date) {
				if ($step === 1) {
					return $fileId + 1;
				}
				$fileId += $step;
				$step /= 10;
			}

			$securityIndex--;
			if ($securityIndex < 0) {
				throw new LogicException('Infinite Loop broken');
			}
		}
	}

	public static function createFilesInFileServerFolder(PDO $pdo, string $fileContent, array $mtaFileIds): array {
		if (count($mtaFileIds) === 0) {
			return array();
		}

		$rows = PdoUtils::select($pdo, "
			SELECT
				fm.uid,
				fm.ext
			FROM mta_file.file f
				INNER JOIN mta_file.file_metadata fm ON fm.id = f.id_metadata
			WHERE f.file_id IN (" . implode(',', array_fill(0, count($mtaFileIds), '?')) . ")
		", $mtaFileIds);

		$createdPaths = array();
		foreach ($rows as $row) {
			$path = self::getFileServerFilePathFromUid($row['uid'], $row['ext']);
			$createdPaths[] = $path;
			file_put_contents($path, $fileContent);
		}

		return $createdPaths;
	}

	public static function getFileServerFilePathFromUid(string $uid, string $ext): string {
		return FILE_SERVER_ACTIVE_FOLDER_PATH
				. substr($uid, 0, 2) . DIRECTORY_SEPARATOR
				. substr($uid, 2, 2) . DIRECTORY_SEPARATOR
				. substr($uid, 4, 2) . DIRECTORY_SEPARATOR
				. $uid . '.' . $ext;
	}

}

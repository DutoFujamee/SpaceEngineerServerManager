<?php

namespace util;

use helper\builder\StringBuilder;
use InvalidArgumentException;
use RuntimeException;

class OsUtils {

	public static function exec(string $command, ?string $workDir = null): array {
		if ($workDir !== null && !is_dir($workDir)) {
			throw new InvalidArgumentException('Folder "' . $workDir . '" doesn\'t exist');
		}

		if ($workDir === null) {
			$workDir = __DIR__;
		}

		$descriptorSpec = array(
			0 => array('pipe', 'r'),
			1 => array('pipe', 'w'),
			2 => array('pipe', 'w'),
		);

		$process = proc_open($command, $descriptorSpec, $pipes, $workDir, null);

		if ($process === false) {
			throw new RuntimeException('Unable to create Process for command "' . $command . '" in "' . $workDir . '"');
		}

		$stdOut = stream_get_contents($pipes[1]);
		fclose($pipes[1]);

		$stdErr = stream_get_contents($pipes[2]);
		fclose($pipes[2]);

		$returnedCode = proc_close($process);
		if ($returnedCode !== 0 && $returnedCode !== 1) {
			$stringBuilder = new StringBuilder('<br/>');
			if ($stdErr !== '') {
				$stringBuilder->add('Error: ' . $returnedCode . ' ' . $stdErr);
				$stringBuilder->add();
			}
			if ($stdOut !== '') {
				$stringBuilder->add('Output:');
				$stringBuilder->add($stdOut);
			}
			throw new RuntimeException('Error for command: "' . $command . '":<br/>' . $stringBuilder->build());
		}

		return explode("\n", trim($stdOut));
	}

}

<?php
/**
 * SPDX-FileCopyrightText: 2017 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

$directories = [
	__DIR__ . '/../core/l10n',
];

$isDebug = in_array('--debug', $argv, true) || in_array('-d', $argv, true);

$apps = new \DirectoryIterator(__DIR__ . '/../apps');
foreach ($apps as $app) {
	if (!file_exists($app->getPathname() . '/l10n')) {
		continue;
	}

	$directories[] = $app->getPathname() . '/l10n';
}

$errors = [];
$valid = 0;
foreach ($directories as $dir) {
	if (!file_exists($dir)) {
		continue;
	}
	$directory = new \DirectoryIterator($dir);

	foreach ($directory as $file) {
		if ($file->getExtension() !== 'json') {
			continue;
		}

		$content = file_get_contents($file->getPathname());
		$json = json_decode($content, true);

		$translations = json_encode($json['translations']);
		if (strpos($translations, '|') !== false) {
			$errors[] = $file->getPathname() . "\n" . '  ' . 'Contains a | in the translations.' . "\n";
		}

		if (json_last_error() !== JSON_ERROR_NONE) {
			$errors[] = $file->getPathname() . "\n" . '  ' . json_last_error_msg() . "\n";
		} else {
			$valid++;
		}

		if ($isDebug && $file->getFilename() === 'en_GB.json') {
			$sourceStrings = json_encode(array_keys($json['translations']));

			if (strpos($sourceStrings, '\u2019') !== false) {
				$errors[] = $file->getPathname() . "\n"
					. '  ' . 'Contains a unicode single quote "’" in the english source string, please replace with normal single quotes.' . "\n"
					. '  ' . 'Please note that this only updates after a sync to transifex.' . "\n";
			}
		}
	}
}

if (count($errors) > 0) {
	echo sprintf('ERROR: There were %d errors:', count($errors)) . "\n\n";
	echo implode("\n", $errors);
	exit(1);
}

echo 'OK: ' . $valid . ' files parse' . "\n";
exit(0);

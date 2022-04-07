<?php

namespace util;

class HtmlUtils {

	public static function getAHtml(string $url, string $text, bool $inNewTab = false): string {
		return '<a' . ($inNewTab ? ' ref="noopener" target="_blank"' : '') . ' href="' . htmlentities($url) . '">' . htmlentities($text) . '</a>';
	}

}

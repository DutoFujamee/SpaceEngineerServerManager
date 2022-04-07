<?php

namespace parser\util;

use helper\xmlHelper\DomElementDecorator;

class SandBoxParserUtils {

	public static function getTextFromNullableDomElementDecorator(?DomElementDecorator $domElementDecorator, $defaultValue) {
		if ($domElementDecorator === null) {
			return $defaultValue;
		}
		return $domElementDecorator->getText();
	}

}

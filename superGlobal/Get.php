<?php

namespace superGlobal;

class Get extends SuperGlobalAbstract {

	protected static function getSuperGlobalArray(): array {
		return $_GET;
	}

}

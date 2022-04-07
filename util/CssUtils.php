<?php

namespace util;

class CssUtils {

	public static function importTableCss(): void { ?>
		<style>
			table, th, td {
				border: 1px solid black;
			}

			table {
				border-collapse: collapse;
			}
		</style>
	<?php }

}

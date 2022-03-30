<?php

namespace parser;

use helper\xmlHelper\DomElementDecorator;
use parser\bean\Structure;
use parser\bean\World;

class SandBoxParser {

	public static function getWorldFromFilePath(string $filePath): World {
		$world = new World();

		$xsiTypes = array();

		$domElementDecorator = DomElementDecorator::constructFromFilePath($filePath);
		foreach ($domElementDecorator->getUniqueChildByTagName('SessionComponents')->getChildrenByTagName('MyObjectBuilder_SessionComponent', true) as $structureXml) {
			$structure = new Structure();

			$xsiTypes[$structureXml->getStringAttribute('xsi:type')] = null;
			/*foreach ($structureXml->getChildrenByTagName() as $definitionXml) {
				echo $definitionXml->getName() . '<br/>';
			}*/

			$world->structures[] = $structure;
		}

		var_dump($xsiTypes);

		return $world;
	}

}

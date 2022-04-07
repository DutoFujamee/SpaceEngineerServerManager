<?php

namespace parser;

use helper\xmlHelper\DomElementDecorator;
use parser\bean\Block;
use parser\bean\Structure;
use parser\bean\World;
use parser\bean\XYZ;
use parser\enum\GridSizeEnum;
use parser\enum\SharedModeEnum;
use parser\util\SandBoxParserUtils;
use UnexpectedValueException;

class SandBoxParser {

	public static $errorMessagesIndexed = array();

	public static function addError(string $errorMessage): void {
		self::$errorMessagesIndexed[$errorMessage] = null;
	}

	public static function getWorldFromFilePath(string $filePath): World {
		$world = new World();

		$domElementDecorator = DomElementDecorator::constructFromFilePath($filePath);

		$structuresDomElementDecorator = $domElementDecorator->getUniqueChildFromTagName('SectorObjects')->getChildrenFromTagName('MyObjectBuilder_EntityBase', true);
		foreach ($structuresDomElementDecorator as $structureDomElementDecorator) {
			$xsiType = $structureDomElementDecorator->getXsiType();
			switch ($xsiType) {
				case 'MyObjectBuilder_Planet':
				case 'MyObjectBuilder_VoxelMap': // Asteroid
				case 'MyObjectBuilder_FloatingObject':
					break;
				case 'MyObjectBuilder_CubeGrid': // Structure
					$world->structures[] = self::getStructureFromDomElementDecorator($structureDomElementDecorator);
					break;
				default:
					self::addError('Unknow MyObjectBuilder_EntityBase: ' . $xsiType);
					break;
			}
		}

		return $world;
	}

	private static function getStructureFromDomElementDecorator(DomElementDecorator $domElementDecorator): Structure {
		$structure = new Structure();

		$structure->id = $domElementDecorator->getUniqueChildFromTagName('EntityId')->getText();
		$structure->displayName = $domElementDecorator->getUniqueChildFromTagName('DisplayName')->getText();
		$structure->gridSizeEnum = self::getGridSizeEnumFromDomElementDecorator($domElementDecorator->getUniqueChildFromTagName('GridSizeEnum'));
		$structure->position = self::getXYZFromDomElementDecorator($domElementDecorator->getUniqueChildFromTagName('PositionAndOrientation')->getUniqueChildFromTagName('Position'));
		$structure->isStation = self::getBoolFromDomElementDecorator($domElementDecorator->getNullableUniqueChildFromTagName('IsStatic'));

		foreach ($domElementDecorator->getUniqueChildFromTagName('CubeBlocks')->getChildrenFromTagName('MyObjectBuilder_CubeBlock') as $blockDomElementDecorator) {
			// Armor are detected because their have no entityId
			if ($blockDomElementDecorator->getNullableUniqueChildFromTagName('EntityId') === null) {
				$structure->totalArmor++;
				continue;
			}

			$structure->blocks[] = self::getBlockFromDomElementDecorator($blockDomElementDecorator);
		}

		return $structure;
	}

	private static function getXYZFromDomElementDecorator(DomElementDecorator $domElementDecorator): XYZ {
		$xyz = new XYZ();
		$xyz->x = $domElementDecorator->getStringAttribute('x');
		$xyz->y = $domElementDecorator->getStringAttribute('y');
		$xyz->z = $domElementDecorator->getStringAttribute('z');
		return $xyz;
	}

	private static function getGridSizeEnumFromDomElementDecorator(DomElementDecorator $domElementDecorator): int {
		$xmlText = $domElementDecorator->getText();
		switch ($xmlText) {
			case 'Small':
				return GridSizeEnum::SMALL;
			case 'Large':
				return GridSizeEnum::LARGE;
			default:
				self::addError('Unknow GridSizeEnum: ' . $xmlText);
				return GridSizeEnum::UNKNOWN;
		}
	}

	private static function getSharedModeEnumFromDomElementDecorator(?DomElementDecorator $domElementDecorator): ?int {
		if ($domElementDecorator === null) {
			return null;
		}

		$xmlText = $domElementDecorator->getText();
		switch ($xmlText) {
			case 'Faction':
				return SharedModeEnum::FACTION;
			default:
				self::addError('Unknow SharedModeEnum: ' . $xmlText);
				return SharedModeEnum::UNKNOWN;
		}
	}

	private static function getBoolFromDomElementDecorator(?DomElementDecorator $domElementDecorator): bool {
		if ($domElementDecorator === null) {
			return false;
		}
		$xmlValue = $domElementDecorator->getText();
		switch ($xmlValue) {
			case 'true':
				return true;
			case 'false':
				return false;
			default:
				self::addError('Unknow Bool Value: ' . $xmlValue);
				return false;
		}
	}

	private static function getBlockFromDomElementDecorator(DomElementDecorator $domElementDecorator): Block {
		$block = new Block();

		$block->id = $domElementDecorator->getUniqueChildFromTagName('EntityId')->getText();
		$block->ownerId = SandBoxParserUtils::getTextFromNullableDomElementDecorator($domElementDecorator->getNullableUniqueChildFromTagName('Owner'), null);
		$block->buildById = $domElementDecorator->getUniqueChildFromTagName('BuiltBy')->getText();
		$block->sharedModeEnum = self::getSharedModeEnumFromDomElementDecorator($domElementDecorator->getNullableUniqueChildFromTagName('ShareMode'));
		$block->type = $domElementDecorator->getXsiType();
		$block->isEnabled = self::getBoolFromDomElementDecorator($domElementDecorator->getNullableUniqueChildFromTagName('Enabled'));
		$block->buildPercent = (float) SandBoxParserUtils::getTextFromNullableDomElementDecorator($domElementDecorator->getNullableUniqueChildFromTagName('IntegrityPercent'), 1);
		$block->integrityPercent = (float) SandBoxParserUtils::getTextFromNullableDomElementDecorator($domElementDecorator->getNullableUniqueChildFromTagName('BuildPercent'), 1);

		return $block;
	}

}

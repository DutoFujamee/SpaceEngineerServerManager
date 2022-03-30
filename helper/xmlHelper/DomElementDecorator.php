<?php

namespace helper\xmlHelper;

use DOMDocument;
use DOMElement;
use DOMText;
use LogicException;

class DomElementDecorator {

	private DOMElement $domElement;

	public function __construct(DOMElement $domElement = null) {
		$this->domElement = $domElement;
	}

	public static function constructFromFilePath(string $filePath): DomElementDecorator {
		$domDocument = new DOMDocument();
		$domDocument->load($filePath, LIBXML_COMPACT | LIBXML_NOBLANKS);
		return new DomElementDecorator($domDocument->documentElement);
	}

	public function getName(): string {
		return $this->domElement->tagName;
	}

	public function getStringAttribute(string $attributeKey): string {
		return $this->domElement->getAttribute($attributeKey);
	}

	public function getXsiType(): string {
		return $this->getStringAttribute('xsi:type');
	}

	/**
	 * @param string|null $tagName
	 * @param bool $strict If true, then if another tagName is found, then throw an error
	 * @return DomElementDecorator[]
	 */
	public function getChildrenFromTagName(?string $tagName = null, bool $strict = false): array {
		$children = array();
		foreach ($this->domElement->childNodes as $child) {
			/** @var DOMElement $child */
			if ($child->tagName === $tagName || $tagName === null) {
				$children[] = new DomElementDecorator($child);
			} else if ($strict) {
				throw new LogicException('Another TagName found: ' . $child->tagName);
			}
		}
		return $children;
	}

	public function getUniqueChildFromTagName(string $tagName): DomElementDecorator {
		$children = $this->getNullableUniqueChildFromTagName($tagName);
		if ($children === null) {
			throw new LogicException($tagName . ' not found');
		}
		return $children;
	}

	public function getNullableUniqueChildFromTagName(string $tagName): ?DomElementDecorator {
		$children = $this->getChildrenFromTagName($tagName);
		if (count($children) === 0) {
			return null;
		}
		if (count($children) !== 1) {
			throw new LogicException(count($children) . ' children found');
		}
		return $children[0];
	}

	public function getText(): string {
		return $this->domElement->nodeValue;
	}

}

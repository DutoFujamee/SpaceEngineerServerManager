<?php

namespace parser\bean;

class Structure {

	public int $id;
	public string $displayName;
	public XYZ $position;
	public int $gridSizeEnum;
	public bool $isStation;

	public int $totalArmor = 0;

	/** @var Block[] */
	public array $blocks = array();

}

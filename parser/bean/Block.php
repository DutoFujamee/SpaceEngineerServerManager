<?php

namespace parser\bean;

class Block {

	public int $id;
	public string $type;
	public ?int $ownerId;
	public ?int $sharedModeEnum;
	public int $buildById;
	public ?bool $isEnabled;
	public float $buildPercent;
	public float $integrityPercent;

}

<?php declare(strict_types = 1);

namespace Tests\Apitte\OpenApi\Fixtures\ResponseEntity;

class SelfReferencingEntity
{

	/** @var self */
	public $selfReference;

	/** @var static */
	public $staticReference;

	/** @var SelfReferencingEntity */
	public $classNameReference;

	/** @var string */
	public $normalProperty;

}

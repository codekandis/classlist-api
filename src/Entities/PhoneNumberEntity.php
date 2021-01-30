<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Entities;

use CodeKandis\Tiphy\Entities\AbstractEntity;

class PhoneNumberEntity extends AbstractEntity
{
	/** @var string */
	public $uri = '';

	/** @var string */
	public $id = '';

	/** @var string */
	public $type = '';

	/** @var string */
	public $number = '';

	/** @var string[] */
	public $parents = [];
}

<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Entities;

use CodeKandis\Tiphy\Entities\AbstractEntity;

class PhoneNumberEntity extends AbstractEntity
{
	/** @var string */
	public string $canonicalUri = '';

	/** @var string */
	public string $id = '';

	/** @var string */
	public string $type = '';

	/** @var string */
	public string $number = '';

	/** @var string[] */
	public array $parents = [];

	/** @var string[] */
	public array $teachers = [];
}

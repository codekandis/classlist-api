<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Entities;

use CodeKandis\Tiphy\Entities\AbstractEntity;

class ChildEntity extends AbstractEntity
{
	/** @var string */
	public string $canonicalUri = '';

	/** @var string */
	public string $id = '';

	/** @var string */
	public string $gender = '';

	/** @var string */
	public string $forename = '';

	/** @var string */
	public string $lastname = '';

	/** @var string */
	public string $dateOfBirth = '';

	/** @var string[] */
	public array $parents = [];

	/** @var string[] */
	public array $teachers = [];
}

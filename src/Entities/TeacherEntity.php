<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Entities;

use CodeKandis\Tiphy\Entities\AbstractEntity;

class TeacherEntity extends AbstractEntity
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

	/** @var string[] */
	public array $addresses = [];

	/** @var string[] */
	public array $phoneNumbers = [];

	/** @var string[] */
	public array $emails = [];

	/** @var string[] */
	public array $children = [];
}

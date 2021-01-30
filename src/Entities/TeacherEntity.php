<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Entities;

use CodeKandis\Tiphy\Entities\AbstractEntity;

class TeacherEntity extends AbstractEntity
{
	/** @var string */
	public $uri = '';

	/** @var string */
	public $id = '';

	/** @var string */
	public $gender = '';

	/** @var string */
	public $forename = '';

	/** @var string */
	public $lastname = '';

	/** @var string[] */
	public $addresses = [];

	/** @var string[] */
	public $phoneNumbers = [];

	/** @var string[] */
	public $emails = [];

	/** @var string[] */
	public $children = [];
}

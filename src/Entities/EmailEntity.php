<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Entities;

use CodeKandis\Tiphy\Entities\AbstractEntity;

class EmailEntity extends AbstractEntity
{
	/** @var string */
	public $uri = '';

	/** @var string */
	public $id = '';

	/** @var string */
	public $email = '';

	/** @var string[] */
	public $parents = [];

	/** @var string[] */
	public $teachers = [];
}

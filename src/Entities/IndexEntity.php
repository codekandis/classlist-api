<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Entities;

use CodeKandis\Tiphy\Entities\AbstractEntity;

class IndexEntity extends AbstractEntity
{
	/** @var string */
	public $uri = '';

	/** @var string */
	public $childrenUri = '';

	/** @var string */
	public $parentsUri = '';

	/** @var string */
	public $teachersUri = '';

	/** @var string */
	public $addressesUri = '';

	/** @var string */
	public $phoneNumbersUri = '';

	/** @var string */
	public $emailsUri = '';
}

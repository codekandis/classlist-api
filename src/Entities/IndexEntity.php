<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Entities;

use CodeKandis\Tiphy\Entities\AbstractEntity;

class IndexEntity extends AbstractEntity
{
	/** @var string */
	public string $canonicalUri = '';

	/** @var string */
	public string $childrenUri = '';

	/** @var string */
	public string $parentsUri = '';

	/** @var string */
	public string $teachersUri = '';

	/** @var string */
	public string $addressesUri = '';

	/** @var string */
	public string $phoneNumbersUri = '';

	/** @var string */
	public string $emailsUri = '';
}

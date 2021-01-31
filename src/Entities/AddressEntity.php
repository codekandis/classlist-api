<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Entities;

use CodeKandis\Tiphy\Entities\AbstractEntity;

class AddressEntity extends AbstractEntity
{
	/** @var string */
	public string $canonicalUri = '';

	/** @var string */
	public string $id = '';

	/** @var string */
	public string $address = '';

	/** @var string */
	public string $zipCode = '';

	/** @var string */
	public string $city = '';

	/** @var ?string */
	public ?string $district = null;

	/** @var string[] */
	public array $parents = [];
}

<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Entities;

use CodeKandis\Tiphy\Entities\AbstractEntity;

class AddressEntity extends AbstractEntity
{
	/** @var string */
	public $uri = '';

	/** @var string */
	public $id = '';

	/** @var string */
	public $address = '';

	/** @var string */
	public $zipCode = '';

	/** @var string */
	public $city = '';

	/** @var string */
	public $district = '';

	/** @var string[] */
	public $parents = [];
}

<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Entities\EntityExtenders;

use CodeKandis\ClassListApi\Entities\AddressEntity;
use CodeKandis\ClassListApi\Entities\ParentEntity;
use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilderInterface;

class AddressEntityExtender extends AbstractEntityExtender
{
	/** @var AddressEntity */
	private AddressEntity $address;

	/** @var ParentEntity[] */
	private array $parents;

	/**
	 * Constructor method.
	 * @param ParentEntity[] $parents
	 */
	public function __construct( ApiUriBuilderInterface $apiUriBuilder, AddressEntity $address, array $parents )
	{
		parent::__construct( $apiUriBuilder );

		$this->address = $address;
		$this->parents = $parents;
	}

	public function extend(): void
	{
		$this->addParents();
	}

	private function addParents(): void
	{
		foreach ( $this->parents as $parent )
		{
			$this->address->parents[] = [
				'canonicalUri' => $this->apiUriBuilder->buildParentUri( $parent->id ),
				'id'           => $parent->id
			];
		}
	}
}

<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Entities\UriExtenders;

use CodeKandis\ClassListApi\Entities\AddressEntity;
use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilderInterface;

class AddressApiUriExtender extends AbstractApiUriExtender
{
	/** @var AddressEntity */
	private AddressEntity $address;

	public function __construct( ApiUriBuilderInterface $apiUriBuilder, AddressEntity $address )
	{
		parent::__construct( $apiUriBuilder );
		$this->address = $address;
	}

	public function extend(): void
	{
		$this->addCanonicalUri();
	}

	private function addCanonicalUri(): void
	{
		$this->address->canonicalUri = $this->apiUriBuilder->buildAddressUri( $this->address->id );
	}
}

<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Entities\UriExtenders;

use CodeKandis\ClassListApi\Entities\PhoneNumberEntity;
use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilderInterface;

class PhoneNumberApiUriExtender extends AbstractApiUriExtender
{
	/** @var PhoneNumberEntity */
	private PhoneNumberEntity $phoneNumber;

	public function __construct( ApiUriBuilderInterface $apiUriBuilder, PhoneNumberEntity $phoneNumber )
	{
		parent::__construct( $apiUriBuilder );
		$this->phoneNumber = $phoneNumber;
	}

	public function extend(): void
	{
		$this->addCanonicalUri();
	}

	private function addCanonicalUri(): void
	{
		$this->phoneNumber->canonicalUri = $this->apiUriBuilder->buildPhoneNumberUri( $this->phoneNumber->id );
	}
}

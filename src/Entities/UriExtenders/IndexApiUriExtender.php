<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Entities\UriExtenders;

use CodeKandis\ClassListApi\Entities\IndexEntity;
use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilderInterface;

class IndexApiUriExtender extends AbstractApiUriExtender
{
	/** @var IndexEntity */
	private IndexEntity $index;

	public function __construct( ApiUriBuilderInterface $apiUriBuilder, IndexEntity $index )
	{
		parent::__construct( $apiUriBuilder );
		$this->index = $index;
	}

	public function extend(): void
	{
		$this->addCanonicalUri();
		$this->addChildrenUri();
		$this->addParentsUri();
		$this->addTeachersUri();
		$this->addAddressesUri();
		$this->addPhoneNumbersUri();
		$this->addEmailsUri();
	}

	private function addCanonicalUri(): void
	{
		$this->index->canonicalUri = $this->apiUriBuilder->buildIndexUri();
	}

	private function addChildrenUri(): void
	{
		$this->index->childrenUri = $this->apiUriBuilder->buildChildrenUri();
	}

	private function addParentsUri(): void
	{
		$this->index->parentsUri = $this->apiUriBuilder->buildParentsUri();
	}

	private function addTeachersUri(): void
	{
		$this->index->teachersUri = $this->apiUriBuilder->buildTeachersUri();
	}

	private function addAddressesUri(): void
	{
		$this->index->addressesUri = $this->apiUriBuilder->buildAddressesUri();
	}

	private function addPhoneNumbersUri(): void
	{
		$this->index->phoneNumbersUri = $this->apiUriBuilder->buildPhoneNumbersUri();
	}

	private function addEmailsUri(): void
	{
		$this->index->emailsUri = $this->apiUriBuilder->buildEmailsUri();
	}
}

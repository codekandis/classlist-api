<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Entities\UriExtenders;

use CodeKandis\ClassListApi\Entities\ParentEntity;
use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilderInterface;

class ParentApiUriExtender extends AbstractApiUriExtender
{
	/** @var ParentEntity */
	private ParentEntity $parent;

	public function __construct( ApiUriBuilderInterface $apiUriBuilder, ParentEntity $parent )
	{
		parent::__construct( $apiUriBuilder );
		$this->parent = $parent;
	}

	public function extend(): void
	{
		$this->addCanonicalUri();
	}

	private function addCanonicalUri(): void
	{
		$this->parent->canonicalUri = $this->apiUriBuilder->buildParentUri( $this->parent->id );
	}
}

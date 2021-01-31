<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Entities\UriExtenders;

use CodeKandis\ClassListApi\Entities\ChildEntity;
use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilderInterface;

class ChildApiUriExtender extends AbstractApiUriExtender
{
	/** @var ChildEntity */
	private ChildEntity $child;

	public function __construct( ApiUriBuilderInterface $apiUriBuilder, ChildEntity $child )
	{
		parent::__construct( $apiUriBuilder );
		$this->child = $child;
	}

	public function extend(): void
	{
		$this->addCanonicalUri();
	}

	private function addCanonicalUri(): void
	{
		$this->child->canonicalUri = $this->apiUriBuilder->buildChildUri( $this->child->id );
	}
}

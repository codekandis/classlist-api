<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Entities\UriExtenders;

use CodeKandis\ClassListApi\Entities\EmailEntity;
use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilderInterface;

class EmailApiUriExtender extends AbstractApiUriExtender
{
	/** @var EmailEntity */
	private EmailEntity $email;

	public function __construct( ApiUriBuilderInterface $apiUriBuilder, EmailEntity $email )
	{
		parent::__construct( $apiUriBuilder );
		$this->email = $email;
	}

	public function extend(): void
	{
		$this->addCanonicalUri();
	}

	private function addCanonicalUri(): void
	{
		$this->email->canonicalUri = $this->apiUriBuilder->buildEmailUri( $this->email->id );
	}
}

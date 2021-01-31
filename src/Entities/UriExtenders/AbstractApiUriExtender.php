<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Entities\UriExtenders;

use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilderInterface;
use CodeKandis\Tiphy\Entities\UriExtenders\UriExtenderInterface;

abstract class AbstractApiUriExtender implements UriExtenderInterface
{
	/** @var ApiUriBuilderInterface */
	protected ApiUriBuilderInterface $apiUriBuilder;

	public function __construct( ApiUriBuilderInterface $apiUriBuilder )
	{
		$this->apiUriBuilder = $apiUriBuilder;
	}
}

<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Entities\EntityExtenders;

use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilderInterface;
use CodeKandis\Tiphy\Entities\EntityExtenders\EntityExtenderInterface;

abstract class AbstractEntityExtender implements EntityExtenderInterface
{
	/** @var ApiUriBuilderInterface */
	protected ApiUriBuilderInterface $apiUriBuilder;

	public function __construct( ApiUriBuilderInterface $apiUriBuilder )
	{
		$this->apiUriBuilder = $apiUriBuilder;
	}
}

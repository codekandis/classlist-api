<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Actions;

use CodeKandis\ClassListApi\Configurations\ConfigurationRegistry;
use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilder;
use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilderInterface;
use CodeKandis\Tiphy\Actions\AbstractAction;

abstract class AbstractWithApiUriBuilderAction extends AbstractAction
{
	/** @var ApiUriBuilderInterface */
	private ApiUriBuilderInterface $apiUriBuilder;

	protected function getApiUriBuilder(): ApiUriBuilderInterface
	{
		return $this->apiUriBuilder ??
			   $this->apiUriBuilder = new ApiUriBuilder(
				   ConfigurationRegistry::_()->getUriBuilderConfiguration()
			   );
	}
}

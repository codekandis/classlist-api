<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Actions;

use CodeKandis\ClassListApi\Configurations\ConfigurationRegistry;
use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilder;
use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilderInterface;
use CodeKandis\Tiphy\Actions\AbstractAction;
use CodeKandis\Tiphy\Persistence\MariaDb\Connector;
use CodeKandis\Tiphy\Persistence\MariaDb\ConnectorInterface;

abstract class AbstractWithDatabaseConnectorAndApiUriBuilderAction extends AbstractAction
{
	/** @var ConnectorInterface */
	private ConnectorInterface $databaseConnector;

	protected function getDatabaseConnector(): ConnectorInterface
	{
		return $this->databaseConnector ??
			   $this->databaseConnector = new Connector(
				   ConfigurationRegistry::_()->getPersistenceConfiguration()
			   );
	}

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

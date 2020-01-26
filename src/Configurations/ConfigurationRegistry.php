<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Configurations;

use CodeKandis\Tiphy\Configurations\AbstractConfigurationRegistry;
use function dirname;

class ConfigurationRegistry extends AbstractConfigurationRegistry
{
	protected function initialize(): void
	{
		$this->setRoutesConfigurationPath( dirname( __DIR__, 2 ) . '/config/routes.php' );
		$this->setPersistenceConfigurationPath( dirname( __DIR__, 2 ) . '/config/persistence.php' );
		$this->setUriBuilderConfigurationPath( dirname( __DIR__, 2 ) . '/config/uriBuilder.php' );
	}
}

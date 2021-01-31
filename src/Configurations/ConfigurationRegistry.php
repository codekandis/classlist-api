<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Configurations;

use CodeKandis\TiphySentryClientIntegration\Configurations\AbstractConfigurationRegistry;
use function dirname;

class ConfigurationRegistry extends AbstractConfigurationRegistry
{
	protected function initialize(): void
	{
		$this->setPlainSentryClientConfiguration(
			require dirname( __DIR__, 2 ) . '/config/sentryClient.php'
		);
		$this->setPlainRoutesConfiguration(
			( require dirname( __DIR__, 2 ) . '/config/routes.php' )
			+ ( require __DIR__ . '/Plain/routes.php' )
		);
		$this->setPlainPersistenceConfiguration(
			require dirname( __DIR__, 2 ) . '/config/persistence.php'
		);
		$this->setPlainUriBuilderConfiguration(
			( require dirname( __DIR__, 2 ) . '/config/uriBuilder.php' )
			+ ( require __DIR__ . '/Plain/uriBuilder.php' )
		);
	}
}

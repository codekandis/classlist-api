<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Configurations;

use CodeKandis\TiphySentryClientIntegration\Configurations\AbstractConfigurationRegistry;
use function dirname;

class ConfigurationRegistry extends AbstractConfigurationRegistry
{
	protected function initialize(): void
	{
		$this->setPlainSentryClientConfiguration(
			( require __DIR__ . '/Plain/sentryClient.php' )
			+ ( require dirname( __DIR__, 2 ) . '/config/sentryClient.php' )
		);
		$this->setPlainRoutesConfiguration(
			( require __DIR__ . '/Plain/routes.php' )
			+ ( require dirname( __DIR__, 2 ) . '/config/routes.php' )
		);
		$this->setPlainPersistenceConfiguration(
			require dirname( __DIR__, 2 ) . '/config/persistence.php'
		);
		$this->setPlainUriBuilderConfiguration(
			( require __DIR__ . '/Plain/uriBuilder.php' )
			+ ( require dirname( __DIR__, 2 ) . '/config/uriBuilder.php' )
		);
	}
}

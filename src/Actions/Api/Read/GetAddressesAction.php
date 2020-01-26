<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Actions\Api\Read;

use CodeKandis\ClassListApi\Configurations\ConfigurationRegistry;
use CodeKandis\ClassListApi\Entities\AddressEntity;
use CodeKandis\ClassListApi\Entities\ParentEntity;
use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilder;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\AddressesRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\ParentsRepository;
use CodeKandis\Tiphy\Actions\AbstractAction;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\MariaDb\Connector;
use CodeKandis\Tiphy\Persistence\MariaDb\ConnectorInterface;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use ReflectionException;

class GetAddressesAction extends AbstractAction
{
	/** @var ConnectorInterface */
	private $databaseConnector;

	/** @var ApiUriBuilder */
	private $uriBuilder;

	private function getDatabaseConnector(): ConnectorInterface
	{
		if ( null === $this->databaseConnector )
		{
			$databaseConfig          = ConfigurationRegistry::_()->getPersistenceConfiguration();
			$this->databaseConnector = new Connector( $databaseConfig );
		}

		return $this->databaseConnector;
	}

	private function getUriBuilder(): ApiUriBuilder
	{
		if ( null === $this->uriBuilder )
		{
			$uriBuilderConfiguration = ConfigurationRegistry::_()->getUriBuilderConfiguration();
			$this->uriBuilder        = new ApiUriBuilder( $uriBuilderConfiguration );
		}

		return $this->uriBuilder;
	}

	/**
	 * @throws PersistenceException
	 * @throws ReflectionException
	 */
	public function execute(): void
	{
		$addresses = $this->getAddresses();
		$this->addAddressesUris( $addresses );
		$this->addParents( $addresses );

		$responderData = [
			'addresses' => $addresses,
		];
		$responder     = new JsonResponder( StatusCodes::OK, $responderData );
		$responder->respond();
	}

	/**
	 * @param AddressEntity[] $addresses
	 */
	private function addAddressesUris( array $addresses ): void
	{
		foreach ( $addresses as $address )
		{
			$address->uri = $this->getUriBuilder()->getAddressUri( $address->id );
		}
	}

	/**
	 * @param AddressEntity[] $addresses
	 * @throws PersistenceException
	 */
	private function addParents( array $addresses ): void
	{
		foreach ( $addresses as $address )
		{
			$parents = $this->getParentsIdsOfAddress( $address );
			foreach ( $parents as $parent )
			{
				$address->parents[] = $this->getUriBuilder()->getParentUri( $parent->id );
			}
		}
	}

	/**
	 * @return AddressEntity[]
	 * @throws PersistenceException
	 */
	private function getAddresses(): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new AddressesRepository( $databaseConnector ) )
			->readAddresses();
	}

	/**
	 * @return ParentEntity[]
	 * @throws PersistenceException
	 */
	private function getParentsIdsOfAddress( AddressEntity $address ): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new ParentsRepository( $databaseConnector ) )
			->readParentsIdsOfAddress( $address );
	}
}

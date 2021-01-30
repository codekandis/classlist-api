<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Actions\Api\Read;

use CodeKandis\ClassListApi\Configurations\ConfigurationRegistry;
use CodeKandis\ClassListApi\Entities\AddressEntity;
use CodeKandis\ClassListApi\Entities\EmailEntity;
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

class GetAddressAction extends AbstractAction
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
		$inputData = $this->getInputData();

		$requestedAddress     = new AddressEntity();
		$requestedAddress->id = $inputData[ 'id' ];
		$address              = $this->getAddress( $requestedAddress );

		if ( null === $address )
		{
			$responder = new JsonResponder( StatusCodes::NOT_FOUND, null );
			$responder->respond();

			return;
		}

		$this->addAddressUri( $address );
		$this->addParents( $address );

		$responderData = [
			'address' => $address,
		];
		$responder     = new JsonResponder( StatusCodes::OK, $responderData );
		$responder->respond();
	}

	/**
	 * @return string[]
	 */
	private function getInputData(): array
	{
		return $this->arguments;
	}

	private function addAddressUri( AddressEntity $address ): void
	{
		$address->uri = $this->getUriBuilder()->getAddressUri( $address->id );
	}

	/**
	 * @throws PersistenceException
	 */
	private function addParents( AddressEntity $address ): void
	{
		$parents = $this->getParentsIdsOfAddress( $address );
		foreach ( $parents as $parent )
		{
			$address->parents[] = [
				'id'  => $parent->id,
				'uri' => $this->getUriBuilder()->getParentUri( $parent->id )
			];
		}
	}

	/**
	 * @throws PersistenceException
	 */
	private function getAddress( AddressEntity $requestedAddress ): ?AddressEntity
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new AddressesRepository( $databaseConnector ) )
			->readAddressById( $requestedAddress );
	}

	/**
	 * @return EmailEntity[]
	 * @throws PersistenceException
	 */
	private function getParentsIdsOfAddress( AddressEntity $address ): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new ParentsRepository( $databaseConnector ) )
			->readParentsIdsOfAddress( $address );
	}
}

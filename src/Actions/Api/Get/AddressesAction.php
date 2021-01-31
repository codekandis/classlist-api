<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Actions\Api\Get;

use CodeKandis\ClassListApi\Actions\AbstractWithDatabaseConnectorAndApiUriBuilderAction;
use CodeKandis\ClassListApi\Entities\AddressEntity;
use CodeKandis\ClassListApi\Entities\EntityExtenders\AddressEntityExtender;
use CodeKandis\ClassListApi\Entities\ParentEntity;
use CodeKandis\ClassListApi\Entities\UriExtenders\AddressApiUriExtender;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\AddressesRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\ParentsRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use JsonException;

class AddressesAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
{
	/**
	 * @throws PersistenceException
	 * @throws JsonException
	 */
	public function execute(): void
	{
		$addresses = $this->readAddresses();
		$this->extendUris( $addresses );
		$this->extendEntities( $addresses );

		$responderData = [
			'addresses' => $addresses,
		];
		$responder     = new JsonResponder( StatusCodes::OK, $responderData );
		$responder->respond();
	}

	/**
	 * @param AddressEntity[] $addresses
	 */
	private function extendUris( array $addresses ): void
	{
		$apiUriBuilder = $this->getApiUriBuilder();
		foreach ( $addresses as $address )
		{
			( new AddressApiUriExtender( $apiUriBuilder, $address ) )
				->extend();
		}
	}

	/**
	 * @param AddressEntity[] $addresses
	 */
	private function extendEntities( array $addresses ): void
	{
		$apiUriBuilder = $this->getApiUriBuilder();
		foreach ( $addresses as $address )
		{
			( new AddressEntityExtender(
				$apiUriBuilder,
				$address,
				$this->readParentsIdsOfAddress( $address )
			) )
				->extend();
		}
	}

	/**
	 * @return AddressEntity[]
	 * @throws PersistenceException
	 */
	private function readAddresses(): array
	{
		return ( new AddressesRepository(
			$this->getDatabaseConnector()
		) )
			->readAddresses();
	}

	/**
	 * @return ParentEntity[]
	 * @throws PersistenceException
	 */
	private function readParentsIdsOfAddress( AddressEntity $address ): array
	{
		return ( new ParentsRepository(
			$this->getDatabaseConnector()
		) )
			->readParentsIdsOfAddress( $address );
	}
}

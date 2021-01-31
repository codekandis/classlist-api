<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Actions\Api\Get;

use CodeKandis\ClassListApi\Actions\AbstractWithDatabaseConnectorAndApiUriBuilderAction;
use CodeKandis\ClassListApi\Entities\AddressEntity;
use CodeKandis\ClassListApi\Entities\EntityExtenders\AddressEntityExtender;
use CodeKandis\ClassListApi\Entities\ParentEntity;
use CodeKandis\ClassListApi\Entities\UriExtenders\AddressApiUriExtender;
use CodeKandis\ClassListApi\Errors\AddressesErrorCodes;
use CodeKandis\ClassListApi\Errors\AddressesErrorMessages;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\AddressesRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\ParentsRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use CodeKandis\Tiphy\Throwables\ErrorInformation;
use JsonException;

class AddressAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
{
	/**
	 * @throws PersistenceException
	 * @throws JsonException
	 */
	public function execute(): void
	{
		$inputData = $this->getInputData();

		$address = $this->readAddress(
			AddressEntity::fromArray( $inputData )
		);

		if ( null === $address )
		{
			$errorInformation = new ErrorInformation( AddressesErrorCodes::ADDRESS_UNKNOWN, AddressesErrorMessages::ADDRESS_UNKNOWN, $inputData );
			$responder        = new JsonResponder( StatusCodes::NOT_FOUND, null, $errorInformation );
			$responder->respond();

			return;
		}

		$this->extendUris( $address );
		$this->extendEntity( $address );

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

	private function extendUris( AddressEntity $address ): void
	{
		( new AddressApiUriExtender(
			$this->getApiUriBuilder(),
			$address
		) )
			->extend();
	}

	private function extendEntity( AddressEntity $address ): void
	{
		( new AddressEntityExtender(
			$this->getApiUriBuilder(),
			$address,
			$this->readParentsIdsOfAddress( $address )
		) )
			->extend();
	}

	/**
	 * @throws PersistenceException
	 */
	private function readAddress( AddressEntity $requestedAddress ): ?AddressEntity
	{
		return ( new AddressesRepository(
			$this->getDatabaseConnector()
		) )
			->readAddressById( $requestedAddress );
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

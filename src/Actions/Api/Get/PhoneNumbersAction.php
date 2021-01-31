<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Actions\Api\Get;

use CodeKandis\ClassListApi\Actions\AbstractWithDatabaseConnectorAndApiUriBuilderAction;
use CodeKandis\ClassListApi\Entities\EntityExtenders\PhoneNumberEntityExtender;
use CodeKandis\ClassListApi\Entities\ParentEntity;
use CodeKandis\ClassListApi\Entities\PhoneNumberEntity;
use CodeKandis\ClassListApi\Entities\TeacherEntity;
use CodeKandis\ClassListApi\Entities\UriExtenders\PhoneNumberApiUriExtender;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\ParentsRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\PhoneNumbersRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\TeachersRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use JsonException;

class PhoneNumbersAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
{
	/**
	 * @throws PersistenceException
	 * @throws JsonException
	 */
	public function execute(): void
	{
		$phoneNumbers = $this->readPhoneNumbers();
		$this->extendUris( $phoneNumbers );
		$this->extendEntities( $phoneNumbers );

		$responderData = [
			'phoneNumbers' => $phoneNumbers,
		];
		$responder     = new JsonResponder( StatusCodes::OK, $responderData );
		$responder->respond();
	}

	/**
	 * @param PhoneNumberEntity[] $phoneNumbers
	 */
	private function extendUris( array $phoneNumbers ): void
	{
		$apiUriBuilder = $this->getApiUriBuilder();
		foreach ( $phoneNumbers as $phoneNumber )
		{
			( new PhoneNumberApiUriExtender( $apiUriBuilder, $phoneNumber ) )
				->extend();
		}
	}

	/**
	 * @param PhoneNumberEntity[] $phoneNumbers
	 */
	private function extendEntities( array $phoneNumbers ): void
	{
		$apiUriBuilder = $this->getApiUriBuilder();
		foreach ( $phoneNumbers as $phoneNumber )
		{
			( new PhoneNumberEntityExtender(
				$apiUriBuilder,
				$phoneNumber,
				$this->readParentsIdsOfPhoneNumber( $phoneNumber ),
				$this->readTeachersIdsOfPhoneNumber( $phoneNumber )
			) )
				->extend();
		}
	}

	/**
	 * @return PhoneNumberEntity[]
	 * @throws PersistenceException
	 */
	private function readPhoneNumbers(): array
	{
		return ( new PhoneNumbersRepository(
			$this->getDatabaseConnector()
		) )
			->readPhoneNumbers();
	}

	/**
	 * @return ParentEntity[]
	 * @throws PersistenceException
	 */
	private function readParentsIdsOfPhoneNumber( PhoneNumberEntity $phoneNumber ): array
	{
		return ( new ParentsRepository(
			$this->getDatabaseConnector()
		) )
			->readParentsIdsOfPhoneNumber( $phoneNumber );
	}

	/**
	 * @return TeacherEntity[]
	 * @throws PersistenceException
	 */
	private function readTeachersIdsOfPhoneNumber( PhoneNumberEntity $phoneNumber ): array
	{
		return ( new TeachersRepository(
			$this->getDatabaseConnector()
		) )
			->readTeachersIdsOfPhoneNumber( $phoneNumber );
	}
}

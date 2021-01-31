<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Actions\Api\Get;

use CodeKandis\ClassListApi\Actions\AbstractWithDatabaseConnectorAndApiUriBuilderAction;
use CodeKandis\ClassListApi\Entities\EntityExtenders\PhoneNumberEntityExtender;
use CodeKandis\ClassListApi\Entities\ParentEntity;
use CodeKandis\ClassListApi\Entities\PhoneNumberEntity;
use CodeKandis\ClassListApi\Entities\TeacherEntity;
use CodeKandis\ClassListApi\Entities\UriExtenders\PhoneNumberApiUriExtender;
use CodeKandis\ClassListApi\Errors\PhoneNumbersErrorCodes;
use CodeKandis\ClassListApi\Errors\PhoneNumbersErrorMessages;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\ParentsRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\PhoneNumbersRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\TeachersRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use CodeKandis\Tiphy\Throwables\ErrorInformation;
use JsonException;

class PhoneNumberAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
{
	/**
	 * @throws PersistenceException
	 * @throws JsonException
	 */
	public function execute(): void
	{
		$inputData = $this->getInputData();

		$phoneNumber = $this->readPhoneNumber(
			PhoneNumberEntity::fromArray( $inputData )
		);

		if ( null === $phoneNumber )
		{
			$errorInformation = new ErrorInformation( PhoneNumbersErrorCodes::PHONE_NUMBER_UNKNOWN, PhoneNumbersErrorMessages::PHONE_NUMBER_UNKNOWN, $inputData );
			$responder        = new JsonResponder( StatusCodes::NOT_FOUND, null, $errorInformation );
			$responder->respond();

			return;
		}

		$this->extendUris( $phoneNumber );
		$this->extendEntity( $phoneNumber );

		$responderData = [
			'phoneNumber' => $phoneNumber,
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

	private function extendUris( PhoneNumberEntity $phoneNumber ): void
	{
		( new PhoneNumberApiUriExtender(
			$this->getApiUriBuilder(),
			$phoneNumber
		) )
			->extend();
	}

	private function extendEntity( PhoneNumberEntity $phoneNumber ): void
	{
		( new PhoneNumberEntityExtender(
			$this->getApiUriBuilder(),
			$phoneNumber,
			$this->readParentsIdsOfPhoneNumber( $phoneNumber ),
			$this->readTeachersIdsOfPhoneNumber( $phoneNumber )
		) )
			->extend();
	}

	/**
	 * @throws PersistenceException
	 */
	private function readPhoneNumber( PhoneNumberEntity $requestedPhoneNumber ): ?PhoneNumberEntity
	{
		return ( new PhoneNumbersRepository(
			$this->getDatabaseConnector()
		) )
			->readPhoneNumberById( $requestedPhoneNumber );
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

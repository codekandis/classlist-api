<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Actions\Api\Get;

use CodeKandis\ClassListApi\Actions\AbstractWithDatabaseConnectorAndApiUriBuilderAction;
use CodeKandis\ClassListApi\Entities\AddressEntity;
use CodeKandis\ClassListApi\Entities\ChildEntity;
use CodeKandis\ClassListApi\Entities\EmailEntity;
use CodeKandis\ClassListApi\Entities\EntityExtenders\TeacherEntityExtender;
use CodeKandis\ClassListApi\Entities\PhoneNumberEntity;
use CodeKandis\ClassListApi\Entities\TeacherEntity;
use CodeKandis\ClassListApi\Entities\UriExtenders\TeacherApiUriExtender;
use CodeKandis\ClassListApi\Errors\TeachersErrorCodes;
use CodeKandis\ClassListApi\Errors\TeachersErrorMessages;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\AddressesRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\ChildrenRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\EmailsRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\PhoneNumbersRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\TeachersRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use CodeKandis\Tiphy\Throwables\ErrorInformation;
use JsonException;

class TeacherAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
{
	/**
	 * @throws PersistenceException
	 * @throws JsonException
	 */
	public function execute(): void
	{
		$inputData = $this->getInputData();

		$teacher = $this->readTeacher(
			TeacherEntity::fromArray( $inputData )
		);

		if ( null === $teacher )
		{
			$errorInformation = new ErrorInformation( TeachersErrorCodes::TEACHER_UNKNOWN, TeachersErrorMessages::TEACHER_UNKNOWN, $inputData );
			$responder        = new JsonResponder( StatusCodes::NOT_FOUND, null, $errorInformation );
			$responder->respond();

			return;
		}

		$this->extendUris( $teacher );
		$this->extendEntity( $teacher );

		$responderData = [
			'teacher' => $teacher,
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

	private function extendUris( TeacherEntity $teacher ): void
	{
		( new TeacherApiUriExtender(
			$this->getApiUriBuilder(),
			$teacher
		) )
			->extend();
	}

	private function extendEntity( TeacherEntity $teacher ): void
	{
		( new TeacherEntityExtender(
			$this->getApiUriBuilder(),
			$teacher,
			$this->readAddressesIdsOfTeacher( $teacher ),
			$this->readPhoneNumbersIdsOfTeacher( $teacher ),
			$this->readEmailsIdsOfTeacher( $teacher ),
			$this->readChildrenIdsOfTeacher( $teacher )
		) )
			->extend();
	}

	/**
	 * @throws PersistenceException
	 */
	private function readTeacher( TeacherEntity $requestedTeacher ): ?TeacherEntity
	{
		return ( new TeachersRepository(
			$this->getDatabaseConnector()
		) )
			->readTeacherById( $requestedTeacher );
	}

	/**
	 * @return AddressEntity[]
	 * @throws PersistenceException
	 */
	private function readAddressesIdsOfTeacher( TeacherEntity $teacher ): array
	{
		return ( new AddressesRepository(
			$this->getDatabaseConnector()
		) )
			->readAddressesIdsOfTeacher( $teacher );
	}

	/**
	 * @return PhoneNumberEntity[]
	 * @throws PersistenceException
	 */
	private function readPhoneNumbersIdsOfTeacher( TeacherEntity $teacher ): array
	{
		return ( new PhoneNumbersRepository(
			$this->getDatabaseConnector()
		) )
			->readPhoneNumbersIdsOfTeacher( $teacher );
	}

	/**
	 * @return EmailEntity[]
	 * @throws PersistenceException
	 */
	private function readEmailsIdsOfTeacher( TeacherEntity $teacher ): array
	{
		return ( new EmailsRepository(
			$this->getDatabaseConnector()
		) )
			->readEmailsIdsOfTeacher( $teacher );
	}

	/**
	 * @return ChildEntity[]
	 * @throws PersistenceException
	 */
	private function readChildrenIdsOfTeacher( TeacherEntity $teacher ): array
	{
		return ( new ChildrenRepository(
			$this->getDatabaseConnector()
		) )
			->readChildrenIdsOfTeacher( $teacher );
	}
}

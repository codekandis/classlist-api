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
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\AddressesRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\ChildrenRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\EmailsRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\PhoneNumbersRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\TeachersRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use JsonException;

class TeachersAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
{
	/**
	 * @throws PersistenceException
	 * @throws JsonException
	 */
	public function execute(): void
	{
		$teachers = $this->readTeachers();
		$this->extendUris( $teachers );
		$this->extendEntities( $teachers );

		$responderData = [
			'teachers' => $teachers,
		];
		$responder     = new JsonResponder( StatusCodes::OK, $responderData );
		$responder->respond();
	}

	/**
	 * @param TeacherEntity[] $teachers
	 */
	private function extendUris( array $teachers ): void
	{
		$apiUriBuilder = $this->getApiUriBuilder();
		foreach ( $teachers as $teacher )
		{
			( new TeacherApiUriExtender( $apiUriBuilder, $teacher ) )
				->extend();
		}
	}

	/**
	 * @param TeacherEntity[] $teachers
	 */
	private function extendEntities( array $teachers ): void
	{
		$apiUriBuilder = $this->getApiUriBuilder();
		foreach ( $teachers as $teacher )
		{
			( new TeacherEntityExtender(
				$apiUriBuilder,
				$teacher,
				$this->readAddressesIdsOfTeacher( $teacher ),
				$this->readPhoneNumbersIdsOfTeacher( $teacher ),
				$this->readEmailsIdsOfTeacher( $teacher ),
				$this->readChildrenIdsOfTeacher( $teacher )
			) )
				->extend();
		}
	}

	/**
	 * @return TeacherEntity[]
	 * @throws PersistenceException
	 */
	private function readTeachers(): array
	{
		return ( new TeachersRepository(
			$this->getDatabaseConnector()
		) )
			->readTeachers();
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

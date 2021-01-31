<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Persistence\MariaDb\Repositories;

use CodeKandis\ClassListApi\Entities\ChildEntity;
use CodeKandis\ClassListApi\Entities\EmailEntity;
use CodeKandis\ClassListApi\Entities\PhoneNumberEntity;
use CodeKandis\ClassListApi\Entities\TeacherEntity;
use CodeKandis\Tiphy\Persistence\MariaDb\Repositories\AbstractRepository;
use CodeKandis\Tiphy\Persistence\PersistenceException;

class TeachersRepository extends AbstractRepository
{
	/**
	 * @return TeacherEntity[]
	 * @throws PersistenceException
	 */
	public function readTeachers(): array
	{
		$query = <<< END
			SELECT
				`teachers`.*
			FROM
				`teachers`
			ORDER BY
			    `teachers`.`gender` ASC,
				`teachers`.`forename` ASC;
		END;

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var TeacherEntity[] $resultSet */
			$resultSet = $this->databaseConnector->query( $query, null, TeacherEntity::class );
			$this->databaseConnector->commit();
		}
		catch ( PersistenceException $exception )
		{
			$this->databaseConnector->rollback();
			throw $exception;
		}

		return $resultSet;
	}

	/**
	 * @throws PersistenceException
	 */
	public function readTeacherById( TeacherEntity $teacher ): ?TeacherEntity
	{
		$query = <<< END
			SELECT
				`teachers`.*
			FROM
				`teachers`
			WHERE
				`teachers`.`id` = :teacherId
			LIMIT
				0, 1;
		END;

		$arguments = [
			'teacherId' => $teacher->id
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var TeacherEntity $result */
			$result = $this->databaseConnector->queryFirst( $query, $arguments, TeacherEntity::class );
			$this->databaseConnector->commit();
		}
		catch ( PersistenceException $exception )
		{
			$this->databaseConnector->rollback();
			throw $exception;
		}

		return $result;
	}

	/**
	 * @return TeacherEntity[]
	 * @throws PersistenceException
	 */
	public function readTeachersIdsOfChild( ChildEntity $child ): array
	{
		$query = <<< END
			SELECT
				`teachers`.`id`
			FROM
				`teachers`
			INNER JOIN
				`children_teachers`
				ON
				`children_teachers`.`childId` = :childId
			WHERE
				`teachers`.`id` = `children_teachers`.`teacherId`
			ORDER BY
			    `teachers`.`gender` ASC,
				`teachers`.`forename` ASC;
		END;

		$arguments = [
			'childId' => $child->id
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var TeacherEntity[] $resultSet */
			$resultSet = $this->databaseConnector->query( $query, $arguments, TeacherEntity::class );
			$this->databaseConnector->commit();
		}
		catch ( PersistenceException $exception )
		{
			$this->databaseConnector->rollback();
			throw $exception;
		}

		return $resultSet;
	}

	/**
	 * @return PhoneNumberEntity[]
	 * @throws PersistenceException
	 */
	public function readTeachersIdsOfPhoneNumber( PhoneNumberEntity $phoneNumber ): array
	{
		$query = <<< END
			SELECT
				`teachers`.`id`
			FROM
				`teachers`
			INNER JOIN
				`teachers_phoneNumbers`
				ON
				`teachers_phoneNumbers`.`phoneNumberId` = :phoneNumberId
			WHERE
				`teachers`.`id` = `teachers_phoneNumbers`.`teacherId`
			ORDER BY
			    `teachers`.`gender` ASC,
				`teachers`.`forename` ASC;
		END;

		$arguments = [
			'phoneNumberId' => $phoneNumber->id
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var TeacherEntity[] $resultSet */
			$resultSet = $this->databaseConnector->query( $query, $arguments, TeacherEntity::class );
			$this->databaseConnector->commit();
		}
		catch ( PersistenceException $exception )
		{
			$this->databaseConnector->rollback();
			throw $exception;
		}

		return $resultSet;
	}

	/**
	 * @return TeacherEntity[]
	 * @throws PersistenceException
	 */
	public function readTeachersIdsOfEmail( EmailEntity $email ): array
	{
		$query = <<< END
			SELECT
				`teachers`.`id`
			FROM
				`teachers`
			INNER JOIN
				`teachers_emails`
				ON
				`teachers_emails`.`emailId` = :emailId
			WHERE
				`teachers`.`id` = `teachers_emails`.`teacherId`
			ORDER BY
			    `teachers`.`gender` ASC,
				`teachers`.`forename` ASC;
		END;

		$arguments = [
			'emailId' => $email->id
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var TeacherEntity[] $resultSet */
			$resultSet = $this->databaseConnector->query( $query, $arguments, TeacherEntity::class );
			$this->databaseConnector->commit();
		}
		catch ( PersistenceException $exception )
		{
			$this->databaseConnector->rollback();
			throw $exception;
		}

		return $resultSet;
	}
}

<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Persistence\MariaDb\Repositories;

use CodeKandis\ClassListApi\Entities\EmailEntity;
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
	public function readTeachersIds(): array
	{
		$query = <<< END
			SELECT
				`teachers`.`id`
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

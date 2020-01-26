<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Persistence\MariaDb\Repositories;

use CodeKandis\ClassListApi\Entities\EmailEntity;
use CodeKandis\ClassListApi\Entities\ParentEntity;
use CodeKandis\ClassListApi\Entities\TeacherEntity;
use CodeKandis\Tiphy\Persistence\MariaDb\Repositories\AbstractRepository;
use CodeKandis\Tiphy\Persistence\PersistenceException;

class EmailsRepository extends AbstractRepository
{
	/**
	 * @return EmailEntity[]
	 * @throws PersistenceException
	 */
	public function readEmails(): array
	{
		$query = <<< END
			SELECT
				`emails`.*
			FROM
				`emails`
			ORDER BY
				`emails`.`email` ASC;
		END;

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var EmailEntity[] $resultSet */
			$resultSet = $this->databaseConnector->queryPrepared( $query, null, EmailEntity::class );
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
	public function readEmailById( EmailEntity $email ): ?EmailEntity
	{
		$query = <<< END
			SELECT
				`emails`.*
			FROM
				`emails`
			WHERE
				`emails`.`id` = :emailId
			LIMIT
				0, 1;
		END;

		$arguments = [
			'emailId' => $email->id
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var EmailEntity $result */
			$result = $this->databaseConnector->queryFirstPrepared( $query, $arguments, EmailEntity::class );
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
	 * @return EmailEntity[]
	 * @throws PersistenceException
	 */
	public function readEmailsIdsOfParent( ParentEntity $parent ): array
	{
		$query = <<< END
			SELECT
				`emails`.`id`
			FROM
				`emails`
			INNER JOIN
				`parents_emails`
				ON
				`parents_emails`.`parentId` = :parentId
			WHERE
				`emails`.`id` = `parents_emails`.`emailId`
			ORDER BY
				`emails`.`email` ASC;
		END;

		$arguments = [
			'parentId' => $parent->id
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var EmailEntity[] $resultSet */
			$resultSet = $this->databaseConnector->queryPrepared( $query, $arguments, EmailEntity::class );
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
	 * @return EmailEntity[]
	 * @throws PersistenceException
	 */
	public function readEmailsIdsOfTeacher( TeacherEntity $teacher ): array
	{
		$query = <<< END
			SELECT
				`emails`.`id`
			FROM
				`emails`
			INNER JOIN
				`teachers_emails`
				ON
				`teachers_emails`.`teacherId` = :teacherId
			WHERE
				`emails`.`id` = `teachers_emails`.`emailId`
			ORDER BY
				`emails`.`email` ASC;
		END;

		$arguments = [
			'teacherId' => $teacher->id
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var EmailEntity[] $resultSet */
			$resultSet = $this->databaseConnector->queryPrepared( $query, $arguments, EmailEntity::class );
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

<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Persistence\MariaDb\Repositories;

use CodeKandis\ClassListApi\Entities\ParentEntity;
use CodeKandis\ClassListApi\Entities\PhoneNumberEntity;
use CodeKandis\ClassListApi\Entities\TeacherEntity;
use CodeKandis\Tiphy\Persistence\MariaDb\Repositories\AbstractRepository;
use CodeKandis\Tiphy\Persistence\PersistenceException;

class PhoneNumbersRepository extends AbstractRepository
{
	/**
	 * @return PhoneNumberEntity[]
	 * @throws PersistenceException
	 */
	public function readPhoneNumbers(): array
	{
		$query = <<< END
			SELECT
				`phoneNumbers`.*
			FROM
				`phoneNumbers`
			ORDER BY
				`phoneNumbers`.`number` ASC;
		END;

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var PhoneNumberEntity[] $resultSet */
			$resultSet = $this->databaseConnector->queryPrepared( $query, null, PhoneNumberEntity::class );
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
	public function readPhoneNumberById( PhoneNumberEntity $phoneNumber ): ?PhoneNumberEntity
	{
		$query = <<< END
			SELECT
				`phoneNumbers`.*
			FROM
				`phoneNumbers`
			WHERE
				`phoneNumbers`.`id` = :phoneNumberId
			LIMIT
				0, 1;
		END;

		$arguments = [
			'phoneNumberId' => $phoneNumber->id
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var PhoneNumberEntity $result */
			$result = $this->databaseConnector->queryFirstPrepared( $query, $arguments, PhoneNumberEntity::class );
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
	 * @return PhoneNumberEntity[]
	 * @throws PersistenceException
	 */
	public function readPhoneNumbersIdsOfParent( ParentEntity $parent ): array
	{
		$query = <<< END
			SELECT
				`phoneNumbers`.`id`
			FROM
				`phoneNumbers`
			INNER JOIN
				`parents_phoneNumbers`
				ON
				`parents_phoneNumbers`.`parentId` = :parentId
			WHERE
				`phoneNumbers`.`id` = `parents_phoneNumbers`.`phoneNumberId`
			ORDER BY
				`phoneNumbers`.`number` ASC;
		END;

		$arguments = [
			'parentId' => $parent->id
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var PhoneNumberEntity[] $resultSet */
			$resultSet = $this->databaseConnector->queryPrepared( $query, $arguments, PhoneNumberEntity::class );
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
	public function readPhoneNumbersIdsOfTeacher( TeacherEntity $teacher ): array
	{
		$query = <<< END
			SELECT
				`phoneNumbers`.`id`
			FROM
				`phoneNumbers`
			INNER JOIN
				`teachers_phoneNumbers`
				ON
				`teachers_phoneNumbers`.`teacherId` = :teacherId
			WHERE
				`phoneNumbers`.`id` = `teachers_phoneNumbers`.`phoneNumberId`
			ORDER BY
				`phoneNumbers`.`number` ASC;
		END;

		$arguments = [
			'teacherId' => $teacher->id
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var PhoneNumberEntity[] $resultSet */
			$resultSet = $this->databaseConnector->queryPrepared( $query, $arguments, PhoneNumberEntity::class );
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

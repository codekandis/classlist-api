<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Persistence\MariaDb\Repositories;

use CodeKandis\ClassListApi\Entities\AddressEntity;
use CodeKandis\ClassListApi\Entities\ParentEntity;
use CodeKandis\ClassListApi\Entities\TeacherEntity;
use CodeKandis\Tiphy\Persistence\MariaDb\Repositories\AbstractRepository;
use CodeKandis\Tiphy\Persistence\PersistenceException;

class AddressesRepository extends AbstractRepository
{
	/**
	 * @return AddressEntity[]
	 * @throws PersistenceException
	 */
	public function readAddresses(): array
	{
		$query = <<< END
			SELECT
				`addresses`.*
			FROM
				`addresses`
			ORDER BY
				`addresses`.`address` ASC;
		END;

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var AddressEntity[] $resultSet */
			$resultSet = $this->databaseConnector->query( $query, null, AddressEntity::class );
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
	public function readAddressById( AddressEntity $address ): ?AddressEntity
	{
		$query = <<< END
			SELECT
				`addresses`.*
			FROM
				`addresses`
			WHERE
				`addresses`.`id` = :addressId
			LIMIT
				0, 1;
		END;

		$arguments = [
			'addressId' => $address->id
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var AddressEntity $result */
			$result = $this->databaseConnector->queryFirst( $query, $arguments, AddressEntity::class );
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
	 * @return AddressEntity[]
	 * @throws PersistenceException
	 */
	public function readAddressesIdsOfParent( ParentEntity $parent ): array
	{
		$query = <<< END
			SELECT
				`addresses`.`id`
			FROM
				`addresses`
			INNER JOIN
				`parents_addresses`
				ON
				`parents_addresses`.`parentId` = :parentId
			WHERE
				`addresses`.`id` = `parents_addresses`.`addressId`
			ORDER BY
				`addresses`.`address` ASC;
		END;

		$arguments = [
			'parentId' => $parent->id
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var AddressEntity[] $resultSet */
			$resultSet = $this->databaseConnector->query( $query, $arguments, AddressEntity::class );
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
	 * @return AddressEntity[]
	 * @throws PersistenceException
	 */
	public function readAddressesIdsOfTeacher( TeacherEntity $teacher ): array
	{
		$query = <<< END
			SELECT
				`addresses`.`id`
			FROM
				`addresses`
			INNER JOIN
				`teachers_addresses`
				ON
				`teachers_addresses`.`teacherId` = :teacherId
			WHERE
				`addresses`.`id` = `teachers_addresses`.`addressId`
			ORDER BY
				`addresses`.`address` ASC;
		END;

		$arguments = [
			'teacherId' => $teacher->id
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var AddressEntity[] $resultSet */
			$resultSet = $this->databaseConnector->query( $query, $arguments, AddressEntity::class );
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

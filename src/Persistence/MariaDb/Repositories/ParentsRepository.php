<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Persistence\MariaDb\Repositories;

use CodeKandis\ClassListApi\Entities\AddressEntity;
use CodeKandis\ClassListApi\Entities\ChildEntity;
use CodeKandis\ClassListApi\Entities\EmailEntity;
use CodeKandis\ClassListApi\Entities\ParentEntity;
use CodeKandis\ClassListApi\Entities\PhoneNumberEntity;
use CodeKandis\Tiphy\Persistence\MariaDb\Repositories\AbstractRepository;
use CodeKandis\Tiphy\Persistence\PersistenceException;

class ParentsRepository extends AbstractRepository
{
	/**
	 * @return ParentEntity[]
	 * @throws PersistenceException
	 */
	public function readParents(): array
	{
		$query = <<< END
			SELECT
				`parents`.*
			FROM
				`parents`
			ORDER BY
			    `parents`.`gender` ASC,
				`parents`.`forename` ASC;
		END;

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var ParentEntity[] $resultSet */
			$resultSet = $this->databaseConnector->queryPrepared( $query, null, ParentEntity::class );
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
	public function readParentById( ParentEntity $parent ): ?ParentEntity
	{
		$query = <<< END
			SELECT
				`parents`.*
			FROM
				`parents`
			WHERE
				`parents`.`id` = :parentId
			LIMIT
				0, 1;
		END;

		$arguments = [
			'parentId' => $parent->id
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var ParentEntity $result */
			$result = $this->databaseConnector->queryFirstPrepared( $query, $arguments, ParentEntity::class );
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
	 * @return ParentEntity[]
	 * @throws PersistenceException
	 */
	public function readParentsIdsOfChild( ChildEntity $child ): array
	{
		$query = <<< END
			SELECT
				`parents`.`id`
			FROM
				`parents`
			INNER JOIN
				`children_parents`
				ON
				`children_parents`.`childId` = :childId
			WHERE
				`parents`.`id` = `children_parents`.`parentId`
			ORDER BY
			    `parents`.`gender` ASC,
				`parents`.`forename` ASC;
		END;

		$arguments = [
			'childId' => $child->id
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var ParentEntity[] $resultSet */
			$resultSet = $this->databaseConnector->queryPrepared( $query, $arguments, ParentEntity::class );
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
	 * @return ParentEntity[]
	 * @throws PersistenceException
	 */
	public function readParentsIdsOfAddress( AddressEntity $address ): array
	{
		$query = <<< END
			SELECT
				`parents`.`id`
			FROM
				`parents`
			INNER JOIN
				`parents_addresses`
				ON
				`parents_addresses`.`addressId` = :addressId
			WHERE
				`parents`.`id` = `parents_addresses`.`parentId`
			ORDER BY
			    `parents`.`gender` ASC,
				`parents`.`forename` ASC;
		END;

		$arguments = [
			'addressId' => $address->id
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var ParentEntity[] $resultSet */
			$resultSet = $this->databaseConnector->queryPrepared( $query, $arguments, ParentEntity::class );
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
	 * @return ParentEntity[]
	 * @throws PersistenceException
	 */
	public function readParentsIdsOfPhoneNumber( PhoneNumberEntity $phoneNumber ): array
	{
		$query = <<< END
			SELECT
				`parents`.`id`
			FROM
				`parents`
			INNER JOIN
				`parents_phoneNumbers`
				ON
				`parents_phoneNumbers`.`phoneNumberId` = :phoneNumberId
			WHERE
				`parents`.`id` = `parents_phoneNumbers`.`parentId`
			ORDER BY
			    `parents`.`gender` ASC,
				`parents`.`forename` ASC;
		END;

		$arguments = [
			'phoneNumberId' => $phoneNumber->id
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var ParentEntity[] $resultSet */
			$resultSet = $this->databaseConnector->queryPrepared( $query, $arguments, ParentEntity::class );
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
	 * @return ParentEntity[]
	 * @throws PersistenceException
	 */
	public function readParentsIdsOfEmail( EmailEntity $email ): array
	{
		$query = <<< END
			SELECT
				`parents`.`id`
			FROM
				`parents`
			INNER JOIN
				`parents_emails`
				ON
				`parents_emails`.`emailId` = :emailId
			WHERE
				`parents`.`id` = `parents_emails`.`parentId`
			ORDER BY
			    `parents`.`gender` ASC,
				`parents`.`forename` ASC;
		END;

		$arguments = [
			'emailId' => $email->id
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var ParentEntity[] $resultSet */
			$resultSet = $this->databaseConnector->queryPrepared( $query, $arguments, ParentEntity::class );
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

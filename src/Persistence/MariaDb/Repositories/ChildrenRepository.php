<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Persistence\MariaDb\Repositories;

use CodeKandis\ClassListApi\Entities\ChildEntity;
use CodeKandis\ClassListApi\Entities\ParentEntity;
use CodeKandis\ClassListApi\Entities\TeacherEntity;
use CodeKandis\Tiphy\Persistence\MariaDb\Repositories\AbstractRepository;
use CodeKandis\Tiphy\Persistence\PersistenceException;

class ChildrenRepository extends AbstractRepository
{
	/**
	 * @return ChildEntity[]
	 * @throws PersistenceException
	 */
	public function readChildren(): array
	{
		$query = <<< END
			SELECT
				`children`.*
			FROM
				`children`
			ORDER BY
			    `children`.`gender` ASC,
				`children`.`forename` ASC;
		END;

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var ChildEntity[] $resultSet */
			$resultSet = $this->databaseConnector->queryPrepared( $query, null, ChildEntity::class );
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
	public function readChildById( ChildEntity $child ): ?ChildEntity
	{
		$query = <<< END
			SELECT
				`children`.*
			FROM
				`children`
			WHERE
				`children`.`id` = :childId
			LIMIT
				0, 1;
		END;

		$arguments = [
			'childId' => $child->id
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var ChildEntity $result */
			$result = $this->databaseConnector->queryFirstPrepared( $query, $arguments, ChildEntity::class );
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
	 * @return ChildEntity[]
	 * @throws PersistenceException
	 */
	public function readChildrenIdsOfParent( ParentEntity $parent ): array
	{
		$query = <<< END
			SELECT
				`children`.`id`
			FROM
				`children`
			INNER JOIN
				`children_parents`
				ON
				`children_parents`.`parentId` = :parentId
			WHERE
				`children`.`id` = `children_parents`.`childId`
			ORDER BY
			    `children`.`gender` ASC,
				`children`.`forename` ASC;
		END;

		$arguments = [
			'parentId' => $parent->id
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var ChildEntity[] $resultSet */
			$resultSet = $this->databaseConnector->queryPrepared( $query, $arguments, ChildEntity::class );
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
	 * @return ChildEntity[]
	 * @throws PersistenceException
	 */
	public function readChildrenIdsOfTeacher( TeacherEntity $teacher ): array
	{
		$query = <<< END
			SELECT
				`children`.`id`
			FROM
				`children`
			INNER JOIN
				`children_teachers`
				ON
				`children_teachers`.`teacherId` = :teacherId
			WHERE
				`children`.`id` = `children_teachers`.`childId`
			ORDER BY
			    `children`.`gender` ASC,
				`children`.`forename` ASC;
		END;

		$arguments = [
			'teacherId' => $teacher->id
		];

		try
		{
			$this->databaseConnector->beginTransaction();
			/** @var ChildEntity[] $resultSet */
			$resultSet = $this->databaseConnector->queryPrepared( $query, $arguments, ChildEntity::class );
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

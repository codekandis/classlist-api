<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Actions\Api\Get;

use CodeKandis\ClassListApi\Actions\AbstractWithDatabaseConnectorAndApiUriBuilderAction;
use CodeKandis\ClassListApi\Entities\ChildEntity;
use CodeKandis\ClassListApi\Entities\EntityExtenders\ChildEntityExtender;
use CodeKandis\ClassListApi\Entities\ParentEntity;
use CodeKandis\ClassListApi\Entities\TeacherEntity;
use CodeKandis\ClassListApi\Entities\UriExtenders\ChildApiUriExtender;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\ChildrenRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\ParentsRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\TeachersRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use JsonException;

class ChildrenAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
{
	/**
	 * @throws PersistenceException
	 * @throws JsonException
	 */
	public function execute(): void
	{
		$children = $this->readChildren();
		$this->extendUris( $children );
		$this->extendEntities( $children );

		$responderData = [
			'children' => $children,
		];
		$responder     = new JsonResponder( StatusCodes::OK, $responderData );
		$responder->respond();
	}

	/**
	 * @param ChildEntity[] $children
	 */
	private function extendUris( array $children ): void
	{
		$apiUriBuilder = $this->getApiUriBuilder();
		foreach ( $children as $child )
		{
			( new ChildApiUriExtender( $apiUriBuilder, $child ) )
				->extend();
		}
	}

	/**
	 * @param ChildEntity[] $children
	 */
	private function extendEntities( array $children ): void
	{
		$apiUriBuilder = $this->getApiUriBuilder();
		foreach ( $children as $child )
		{
			( new ChildEntityExtender(
				$apiUriBuilder,
				$child,
				$this->readParentsIdsOfChild( $child ),
				$this->readTeachersIdsOfChild( $child )
			) )
				->extend();
		}
	}

	/**
	 * @return ChildEntity[]
	 * @throws PersistenceException
	 */
	private function readChildren(): array
	{
		return ( new ChildrenRepository(
			$this->getDatabaseConnector()
		) )
			->readChildren();
	}

	/**
	 * @return ParentEntity[]
	 * @throws PersistenceException
	 */
	private function readParentsIdsOfChild( ChildEntity $child ): array
	{
		return ( new ParentsRepository(
			$this->getDatabaseConnector()
		) )
			->readParentsIdsOfChild( $child );
	}

	/**
	 * @return TeacherEntity[]
	 * @throws PersistenceException
	 */
	private function readTeachersIdsOfChild( ChildEntity $child ): array
	{
		return ( new TeachersRepository(
			$this->getDatabaseConnector()
		) )
			->readTeachersIdsOfChild( $child );
	}
}

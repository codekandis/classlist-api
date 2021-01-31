<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Actions\Api\Get;

use CodeKandis\ClassListApi\Actions\AbstractWithDatabaseConnectorAndApiUriBuilderAction;
use CodeKandis\ClassListApi\Entities\ChildEntity;
use CodeKandis\ClassListApi\Entities\EntityExtenders\ChildEntityExtender;
use CodeKandis\ClassListApi\Entities\ParentEntity;
use CodeKandis\ClassListApi\Entities\TeacherEntity;
use CodeKandis\ClassListApi\Entities\UriExtenders\ChildApiUriExtender;
use CodeKandis\ClassListApi\Errors\ChildrenErrorCodes;
use CodeKandis\ClassListApi\Errors\ChildrenErrorMessages;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\ChildrenRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\ParentsRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\TeachersRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use CodeKandis\Tiphy\Throwables\ErrorInformation;
use JsonException;

class ChildAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
{
	/**
	 * @throws PersistenceException
	 * @throws JsonException
	 */
	public function execute(): void
	{
		$inputData = $this->getInputData();

		$child = $this->readChild(
			ChildEntity::fromArray( $inputData )
		);

		if ( null === $child )
		{
			$errorInformation = new ErrorInformation( ChildrenErrorCodes::CHILD_UNKNOWN, ChildrenErrorMessages::CHILD_UNKNOWN, $inputData );
			$responder        = new JsonResponder( StatusCodes::NOT_FOUND, null, $errorInformation );
			$responder->respond();

			return;
		}

		$this->extendUris( $child );
		$this->extendEntity( $child );

		$responderData = [
			'child' => $child,
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

	private function extendUris( ChildEntity $child ): void
	{
		( new ChildApiUriExtender(
			$this->getApiUriBuilder(),
			$child
		) )
			->extend();
	}

	private function extendEntity( ChildEntity $child ): void
	{
		( new ChildEntityExtender(
			$this->getApiUriBuilder(),
			$child,
			$this->readParentsIdsOfChild( $child ),
			$this->readTeachersIdsOfChild( $child )
		) )
			->extend();
	}

	/**
	 * @throws PersistenceException
	 */
	private function readChild( ChildEntity $requestedChild ): ?ChildEntity
	{
		return ( new ChildrenRepository(
			$this->getDatabaseConnector()
		) )
			->readChildById( $requestedChild );
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

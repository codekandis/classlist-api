<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Actions\Api\Read;

use CodeKandis\ClassListApi\Configurations\ConfigurationRegistry;
use CodeKandis\ClassListApi\Entities\ChildEntity;
use CodeKandis\ClassListApi\Entities\ParentEntity;
use CodeKandis\ClassListApi\Entities\TeacherEntity;
use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilder;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\ChildrenRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\ParentsRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\TeachersRepository;
use CodeKandis\Tiphy\Actions\AbstractAction;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\MariaDb\Connector;
use CodeKandis\Tiphy\Persistence\MariaDb\ConnectorInterface;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use ReflectionException;

class GetChildAction extends AbstractAction
{
	/** @var ConnectorInterface */
	private $databaseConnector;

	/** @var ApiUriBuilder */
	private $uriBuilder;

	private function getDatabaseConnector(): ConnectorInterface
	{
		if ( null === $this->databaseConnector )
		{
			$databaseConfig          = ConfigurationRegistry::_()->getPersistenceConfiguration();
			$this->databaseConnector = new Connector( $databaseConfig );
		}

		return $this->databaseConnector;
	}

	private function getUriBuilder(): ApiUriBuilder
	{
		if ( null === $this->uriBuilder )
		{
			$uriBuilderConfiguration = ConfigurationRegistry::_()->getUriBuilderConfiguration();
			$this->uriBuilder        = new ApiUriBuilder( $uriBuilderConfiguration );
		}

		return $this->uriBuilder;
	}

	/**
	 * @throws PersistenceException
	 * @throws ReflectionException
	 */
	public function execute(): void
	{
		$inputData = $this->getInputData();

		$requestedChild     = new ChildEntity();
		$requestedChild->id = $inputData[ 'id' ];
		$child              = $this->readChild( $requestedChild );

		if ( null === $child )
		{
			$responder = new JsonResponder( StatusCodes::NOT_FOUND, null );
			$responder->respond();

			return;
		}

		$this->addChildUri( $child );
		$this->addParents( $child );
		$this->addTeachers( $child );

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

	private function addChildUri( ChildEntity $child ): void
	{
		$child->uri = $this->getUriBuilder()->getChildUri( $child->id );
	}

	/**
	 * @throws PersistenceException
	 */
	private function addParents( ChildEntity $child ): void
	{
		$parents = $this->readParentsIdsOfChild( $child );
		foreach ( $parents as $parent )
		{
			$child->parents[] = [
				'id'  => $parent->id,
				'uri' => $this->getUriBuilder()->getParentUri( $parent->id )
			];
		}
	}

	/**
	 * @throws PersistenceException
	 */
	private function addTeachers( ChildEntity $child ): void
	{
		$teachers = $this->readTeachersIds();
		foreach ( $teachers as $teacher )
		{
			$child->teachers[] = [
				'id'  => $teacher->id,
				'uri' => $this->getUriBuilder()->getParentUri( $teacher->id )
			];
		}
	}

	/**
	 * @throws PersistenceException
	 */
	private function readChild( ChildEntity $requestedChild ): ?ChildEntity
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new ChildrenRepository( $databaseConnector ) )
			->readChildById( $requestedChild );
	}

	/**
	 * @return ParentEntity[]
	 * @throws PersistenceException
	 */
	private function readParentsIdsOfChild( ChildEntity $child ): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new ParentsRepository( $databaseConnector ) )
			->readParentsIdsOfChild( $child );
	}

	/**
	 * @return TeacherEntity[]
	 * @throws PersistenceException
	 */
	private function readTeachersIds(): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new TeachersRepository( $databaseConnector ) )
			->readTeachersIds();
	}
}

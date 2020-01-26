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

class GetChildrenAction extends AbstractAction
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
		$children = $this->readChildren();
		$this->addChildrenUris( $children );
		$this->addParents( $children );
		$this->addTeachers( $children );

		$responderData = [
			'children' => $children,
		];
		$responder     = new JsonResponder( StatusCodes::OK, $responderData );
		$responder->respond();
	}

	/**
	 * @param ChildEntity[] $children
	 */
	private function addChildrenUris( array $children ): void
	{
		foreach ( $children as $child )
		{
			$child->uri = $this->getUriBuilder()->getChildUri( $child->id );
		}
	}

	/**
	 * @param ChildEntity[] $children
	 * @throws PersistenceException
	 */
	private function addParents( array $children ): void
	{
		foreach ( $children as $child )
		{
			$parents = $this->readParentsIdsOfChild( $child );
			foreach ( $parents as $parent )
			{
				$child->parents = [
					'id'  => $parent->id,
					'uri' => $this->getUriBuilder()->getParentUri( $parent->id )
				];
			}
		}
	}

	/**
	 * @param ChildEntity[] $children
	 * @throws PersistenceException
	 */
	private function addTeachers( array $children ): void
	{
		foreach ( $children as $child )
		{
			$teachers = $this->readTeachersIds();
			foreach ( $teachers as $teacher )
			{
				$child->teachers[] = [
					'id'  => $teacher->id,
					'uri' => $this->getUriBuilder()->getTeacherUri( $teacher->id )
				];
			}
		}
	}

	/**
	 * @return ChildEntity[]
	 * @throws PersistenceException
	 */
	private function readChildren(): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new ChildrenRepository( $databaseConnector ) )
			->readChildren();
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

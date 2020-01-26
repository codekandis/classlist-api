<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Actions\Api\Read;

use CodeKandis\ClassListApi\Configurations\ConfigurationRegistry;
use CodeKandis\ClassListApi\Entities\EmailEntity;
use CodeKandis\ClassListApi\Entities\ParentEntity;
use CodeKandis\ClassListApi\Entities\TeacherEntity;
use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilder;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\EmailsRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\ParentsRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\TeachersRepository;
use CodeKandis\Tiphy\Actions\AbstractAction;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\MariaDb\Connector;
use CodeKandis\Tiphy\Persistence\MariaDb\ConnectorInterface;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use ReflectionException;

class GetEmailsAction extends AbstractAction
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
		$emails = $this->readEmails();
		$this->addEmailsUris( $emails );
		$this->addParents( $emails );
		$this->addTeachers( $emails );

		$responderData = [
			'emails' => $emails,
		];
		$responder     = new JsonResponder( StatusCodes::OK, $responderData );
		$responder->respond();
	}

	/**
	 * @param EmailEntity[] $emails
	 */
	private function addEmailsUris( array $emails ): void
	{
		foreach ( $emails as $email )
		{
			$email->uri = $this->getUriBuilder()->getEmailUri( $email->id );
		}
	}

	/**
	 * @param EmailEntity[] $emails
	 * @throws PersistenceException
	 */
	private function addParents( array $emails ): void
	{
		foreach ( $emails as $email )
		{
			$parents = $this->readParentsIdsOfEmail( $email );
			foreach ( $parents as $parent )
			{
				$email->parents[] = [
					'id'  => $parent->id,
					'uri' => $this->getUriBuilder()->getParentUri( $parent->id )
				];
			}
		}
	}

	/**
	 * @param EmailEntity[] $emails
	 * @throws PersistenceException
	 */
	private function addTeachers( array $emails ): void
	{
		foreach ( $emails as $email )
		{
			$teachers = $this->readTeachersIdsOfEmail( $email );
			foreach ( $teachers as $teacher )
			{
				$email->teachers[] = [
					'id'  => $teacher->id,
					'uri' => $this->getUriBuilder()->getTeacherUri( $teacher->id )
				];
			}
		}
	}

	/**
	 * @return EmailEntity[]
	 * @throws PersistenceException
	 */
	private function readEmails(): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new EmailsRepository( $databaseConnector ) )
			->readEmails();
	}

	/**
	 * @return ParentEntity[]
	 * @throws PersistenceException
	 */
	private function readParentsIdsOfEmail( EmailEntity $email ): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new ParentsRepository( $databaseConnector ) )
			->readParentsIdsOfEmail( $email );
	}

	/**
	 * @return TeacherEntity[]
	 * @throws PersistenceException
	 */
	private function readTeachersIdsOfEmail( EmailEntity $email ): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new TeachersRepository( $databaseConnector ) )
			->readTeachersIdsOfEmail( $email );
	}
}

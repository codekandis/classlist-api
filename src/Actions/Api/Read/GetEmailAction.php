<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Actions\Api\Read;

use CodeKandis\ClassListApi\Configurations\ConfigurationRegistry;
use CodeKandis\ClassListApi\Entities\EmailEntity;
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

class GetEmailAction extends AbstractAction
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

		$requestedEmail     = new EmailEntity();
		$requestedEmail->id = $inputData[ 'id' ];
		$email              = $this->readEmail( $requestedEmail );

		if ( null === $email )
		{
			$responder = new JsonResponder( StatusCodes::NOT_FOUND, null );
			$responder->respond();

			return;
		}

		$this->addEmailUri( $email );
		$this->addParents( $email );
		$this->addTeachers( $email );

		$responderData = [
			'email' => $email,
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

	private function addEmailUri( EmailEntity $email ): void
	{
		$email->uri = $this->getUriBuilder()->getEmailUri( $email->id );
	}

	/**
	 * @throws PersistenceException
	 */
	private function addParents( EmailEntity $email ): void
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

	/**
	 * @throws PersistenceException
	 */
	private function addTeachers( EmailEntity $email ): void
	{
		$teachers = $this->readTeachersIdsOfEmail( $email );
		foreach ( $teachers as $teacher )
		{
			$email->teachers[] = [
				'id'  => $teacher->id,
				'uri' => $this->getUriBuilder()->getParentUri( $teacher->id )
			];
		}
	}

	/**
	 * @throws PersistenceException
	 */
	private function readEmail( EmailEntity $requestedEmail ): ?EmailEntity
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new EmailsRepository( $databaseConnector ) )
			->readEmailById( $requestedEmail );
	}

	/**
	 * @return EmailEntity[]
	 * @throws PersistenceException
	 */
	private function readParentsIdsOfEmail( EmailEntity $email ): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new ParentsRepository( $databaseConnector ) )
			->readParentsIdsOfEmail( $email );
	}

	/**
	 * @return EmailEntity[]
	 * @throws PersistenceException
	 */
	private function readTeachersIdsOfEmail( EmailEntity $email ): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new TeachersRepository( $databaseConnector ) )
			->readTeachersIdsOfEmail( $email );
	}
}

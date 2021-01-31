<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Actions\Api\Get;

use CodeKandis\ClassListApi\Actions\AbstractWithDatabaseConnectorAndApiUriBuilderAction;
use CodeKandis\ClassListApi\Entities\EmailEntity;
use CodeKandis\ClassListApi\Entities\EntityExtenders\EmailEntityExtender;
use CodeKandis\ClassListApi\Entities\ParentEntity;
use CodeKandis\ClassListApi\Entities\TeacherEntity;
use CodeKandis\ClassListApi\Entities\UriExtenders\EmailApiUriExtender;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\EmailsRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\ParentsRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\TeachersRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use JsonException;

class EmailAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
{
	/**
	 * @throws PersistenceException
	 * @throws JsonException
	 */
	public function execute(): void
	{
		$inputData = $this->getInputData();

		$email = $this->readEmail(
			EmailEntity::fromArray( $inputData )
		);

		if ( null === $email )
		{
			$responder = new JsonResponder( StatusCodes::NOT_FOUND, null );
			$responder->respond();

			return;
		}

		$this->extendUris( $email );
		$this->extendEntity( $email );

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

	private function extendUris( EmailEntity $email ): void
	{
		( new EmailApiUriExtender(
			$this->getApiUriBuilder(),
			$email
		) )
			->extend();
	}

	private function extendEntity( EmailEntity $email ): void
	{
		( new EmailEntityExtender(
			$this->getApiUriBuilder(),
			$email,
			$this->readParentsIdsOfEmail( $email ),
			$this->readTeachersIdsOfEmail( $email )
		) )
			->extend();
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

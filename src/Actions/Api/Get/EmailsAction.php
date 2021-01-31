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

class EmailsAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
{
	/**
	 * @throws PersistenceException
	 * @throws JsonException
	 */
	public function execute(): void
	{
		$emails = $this->readEmails();
		$this->extendUris( $emails );
		$this->extendEntities( $emails );

		$responderData = [
			'emails' => $emails,
		];
		$responder     = new JsonResponder( StatusCodes::OK, $responderData );
		$responder->respond();
	}

	/**
	 * @param EmailEntity[] $emails
	 */
	private function extendUris( array $emails ): void
	{
		$apiUriBuilder = $this->getApiUriBuilder();
		foreach ( $emails as $email )
		{
			( new EmailApiUriExtender( $apiUriBuilder, $email ) )
				->extend();
		}
	}

	/**
	 * @param EmailEntity[] $emails
	 */
	private function extendEntities( array $emails ): void
	{
		$apiUriBuilder = $this->getApiUriBuilder();
		foreach ( $emails as $email )
		{
			( new EmailEntityExtender(
				$apiUriBuilder,
				$email,
				$this->readParentsIdsOfEmail( $email ),
				$this->readTeachersIdsOfEmail( $email )
			) )
				->extend();
		}
	}

	/**
	 * @return EmailEntity[]
	 * @throws PersistenceException
	 */
	private function readEmails(): array
	{
		return ( new EmailsRepository(
			$this->getDatabaseConnector()
		) )
			->readEmails();
	}

	/**
	 * @return ParentEntity[]
	 * @throws PersistenceException
	 */
	private function readParentsIdsOfEmail( EmailEntity $email ): array
	{
		return ( new ParentsRepository(
			$this->getDatabaseConnector()
		) )
			->readParentsIdsOfEmail( $email );
	}

	/**
	 * @return TeacherEntity[]
	 * @throws PersistenceException
	 */
	private function readTeachersIdsOfEmail( EmailEntity $email ): array
	{
		return ( new TeachersRepository(
			$this->getDatabaseConnector()
		) )
			->readTeachersIdsOfEmail( $email );
	}
}

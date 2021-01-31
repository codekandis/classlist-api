<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Actions\Api\Get;

use CodeKandis\ClassListApi\Actions\AbstractWithDatabaseConnectorAndApiUriBuilderAction;
use CodeKandis\ClassListApi\Entities\AddressEntity;
use CodeKandis\ClassListApi\Entities\ChildEntity;
use CodeKandis\ClassListApi\Entities\EmailEntity;
use CodeKandis\ClassListApi\Entities\EntityExtenders\ParentEntityExtender;
use CodeKandis\ClassListApi\Entities\ParentEntity;
use CodeKandis\ClassListApi\Entities\PhoneNumberEntity;
use CodeKandis\ClassListApi\Entities\UriExtenders\ParentApiUriExtender;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\AddressesRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\ChildrenRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\EmailsRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\ParentsRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\PhoneNumbersRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use JsonException;

class ParentsAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
{
	/**
	 * @throws PersistenceException
	 * @throws JsonException
	 */
	public function execute(): void
	{
		$parents = $this->readParents();
		$this->extendUris( $parents );
		$this->extendEntities( $parents );

		$responderData = [
			'parents' => $parents,
		];
		$responder     = new JsonResponder( StatusCodes::OK, $responderData );
		$responder->respond();
	}

	/**
	 * @param ParentEntity[] $parents
	 */
	private function extendUris( array $parents ): void
	{
		$apiUriBuilder = $this->getApiUriBuilder();
		foreach ( $parents as $parent )
		{
			( new ParentApiUriExtender( $apiUriBuilder, $parent ) )
				->extend();
		}
	}

	/**
	 * @param ParentEntity[] $parents
	 */
	private function extendEntities( array $parents ): void
	{
		$apiUriBuilder = $this->getApiUriBuilder();
		foreach ( $parents as $parent )
		{
			( new ParentEntityExtender(
				$apiUriBuilder,
				$parent,
				$this->readAddressesIdsOfParent( $parent ),
				$this->readPhoneNumbersIdsOfParent( $parent ),
				$this->readEmailsIdsOfParent( $parent ),
				$this->readChildrenIdsOfParent( $parent )
			) )
				->extend();
		}
	}

	/**
	 * @return ParentEntity[]
	 * @throws PersistenceException
	 */
	private function readParents(): array
	{
		return ( new ParentsRepository(
			$this->getDatabaseConnector()
		) )
			->readParents();
	}

	/**
	 * @return AddressEntity[]
	 * @throws PersistenceException
	 */
	private function readAddressesIdsOfParent( ParentEntity $parent ): array
	{
		return ( new AddressesRepository(
			$this->getDatabaseConnector()
		) )
			->readAddressesIdsOfParent( $parent );
	}

	/**
	 * @return PhoneNumberEntity[]
	 * @throws PersistenceException
	 */
	private function readPhoneNumbersIdsOfParent( ParentEntity $parent ): array
	{
		return ( new PhoneNumbersRepository(
			$this->getDatabaseConnector()
		) )
			->readPhoneNumbersIdsOfParent( $parent );
	}

	/**
	 * @return EmailEntity[]
	 * @throws PersistenceException
	 */
	private function readEmailsIdsOfParent( ParentEntity $parent ): array
	{
		return ( new EmailsRepository(
			$this->getDatabaseConnector()
		) )
			->readEmailsIdsOfParent( $parent );
	}

	/**
	 * @return ChildEntity[]
	 * @throws PersistenceException
	 */
	private function readChildrenIdsOfParent( ParentEntity $parent ): array
	{
		return ( new ChildrenRepository(
			$this->getDatabaseConnector()
		) )
			->readChildrenIdsOfParent( $parent );
	}
}

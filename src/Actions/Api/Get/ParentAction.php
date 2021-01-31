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
use CodeKandis\ClassListApi\Errors\ParentsErrorCodes;
use CodeKandis\ClassListApi\Errors\ParentsErrorMessages;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\AddressesRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\ChildrenRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\EmailsRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\ParentsRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\PhoneNumbersRepository;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use CodeKandis\Tiphy\Throwables\ErrorInformation;
use JsonException;

class ParentAction extends AbstractWithDatabaseConnectorAndApiUriBuilderAction
{
	/**
	 * @throws PersistenceException
	 * @throws JsonException
	 */
	public function execute(): void
	{
		$inputData = $this->getInputData();

		$parent = $this->readParent(
			ParentEntity::fromArray( $inputData )
		);

		if ( null === $parent )
		{
			$errorInformation = new ErrorInformation( ParentsErrorCodes::PARENT_UNKNOWN, ParentsErrorMessages::PARENT_UNKNOWN, $inputData );
			$responder        = new JsonResponder( StatusCodes::NOT_FOUND, null, $errorInformation );
			$responder->respond();

			return;
		}

		$this->extendUris( $parent );
		$this->extendEntity( $parent );

		$responderData = [
			'parent' => $parent,
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

	private function extendUris( ParentEntity $parent ): void
	{
		( new ParentApiUriExtender(
			$this->getApiUriBuilder(),
			$parent
		) )
			->extend();
	}

	private function extendEntity( ParentEntity $parent ): void
	{
		( new ParentEntityExtender(
			$this->getApiUriBuilder(),
			$parent,
			$this->readAddressesIdsOfParent( $parent ),
			$this->readPhoneNumbersIdsOfParent( $parent ),
			$this->readEmailsIdsOfParent( $parent ),
			$this->readChildrenIdsOfParent( $parent )
		) )
			->extend();
	}

	/**
	 * @throws PersistenceException
	 */
	private function readParent( ParentEntity $requestedParent ): ?ParentEntity
	{
		return ( new ParentsRepository(
			$this->getDatabaseConnector()
		) )
			->readParentById( $requestedParent );
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

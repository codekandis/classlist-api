<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Actions\Api\Read;

use CodeKandis\ClassListApi\Configurations\ConfigurationRegistry;
use CodeKandis\ClassListApi\Entities\AddressEntity;
use CodeKandis\ClassListApi\Entities\ChildEntity;
use CodeKandis\ClassListApi\Entities\EmailEntity;
use CodeKandis\ClassListApi\Entities\ParentEntity;
use CodeKandis\ClassListApi\Entities\PhoneNumberEntity;
use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilder;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\AddressesRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\ChildrenRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\EmailsRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\ParentsRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\PhoneNumbersRepository;
use CodeKandis\Tiphy\Actions\AbstractAction;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\MariaDb\Connector;
use CodeKandis\Tiphy\Persistence\MariaDb\ConnectorInterface;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use ReflectionException;

class GetParentAction extends AbstractAction
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

		$requestedParent     = new ParentEntity();
		$requestedParent->id = $inputData[ 'id' ];
		$parent              = $this->readParent( $requestedParent );

		if ( null === $parent )
		{
			$responder = new JsonResponder( StatusCodes::NOT_FOUND, null );
			$responder->respond();

			return;
		}

		$this->addParentUri( $parent );
		$this->addAddresses( $parent );
		$this->addPhoneNumbers( $parent );
		$this->addEmails( $parent );
		$this->addChildren( $parent );

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

	private function addParentUri( ParentEntity $parent ): void
	{
		$parent->uri = $this->getUriBuilder()->getParentUri( $parent->id );
	}

	/**
	 * @throws PersistenceException
	 */
	private function addAddresses( ParentEntity $parent ): void
	{
		$addresses = $this->readAddressesIdsOfParent( $parent );
		foreach ( $addresses as $address )
		{
			$parent->addresses[] = [
				'id'  => $address->id,
				'uri' => $this->getUriBuilder()->getAddressUri( $address->id )
			];
		}
	}

	/**
	 * @throws PersistenceException
	 */
	private function addPhoneNumbers( ParentEntity $parent ): void
	{
		$phoneNumbers = $this->readPhoneNumbersIdsOfParent( $parent );
		foreach ( $phoneNumbers as $phoneNumber )
		{
			$parent->phoneNumbers[] = [
				'id'  => $phoneNumber->id,
				'uri' => $this->getUriBuilder()->getPhoneNumberUri( $phoneNumber->id )
			];
		}
	}

	/**
	 * @throws PersistenceException
	 */
	private function addEmails( ParentEntity $parent ): void
	{
		$emails = $this->readEmailsIdsOfParent( $parent );
		foreach ( $emails as $email )
		{
			$parent->emails[] = [
				'id'  => $email->id,
				'uri' => $this->getUriBuilder()->getEmailUri( $email->id )
			];
		}
	}

	/**
	 * @throws PersistenceException
	 */
	private function addChildren( ParentEntity $parent ): void
	{
		$children = $this->readChildrenIdsOfParent( $parent );
		foreach ( $children as $child )
		{
			$parent->children[] = [
				'id'  => $child->id,
				'uri' => $this->getUriBuilder()->getChildUri( $child->id )
			];
		}
	}

	/**
	 * @throws PersistenceException
	 */
	private function readParent( ParentEntity $requestedParent ): ?ParentEntity
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new ParentsRepository( $databaseConnector ) )
			->readParentById( $requestedParent );
	}

	/**
	 * @return AddressEntity[]
	 * @throws PersistenceException
	 */
	private function readAddressesIdsOfParent( ParentEntity $parent ): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new AddressesRepository( $databaseConnector ) )
			->readAddressesIdsOfParent( $parent );
	}

	/**
	 * @return PhoneNumberEntity[]
	 * @throws PersistenceException
	 */
	private function readPhoneNumbersIdsOfParent( ParentEntity $parent ): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new PhoneNumbersRepository( $databaseConnector ) )
			->readPhoneNumbersIdsOfParent( $parent );
	}

	/**
	 * @return EmailEntity[]
	 * @throws PersistenceException
	 */
	private function readEmailsIdsOfParent( ParentEntity $parent ): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new EmailsRepository( $databaseConnector ) )
			->readEmailsIdsOfParent( $parent );
	}

	/**
	 * @return ChildEntity[]
	 * @throws PersistenceException
	 */
	private function readChildrenIdsOfParent( ParentEntity $parent ): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new ChildrenRepository( $databaseConnector ) )
			->readChildrenIdsOfParent( $parent );
	}
}

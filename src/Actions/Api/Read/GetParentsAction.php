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

class GetParentsAction extends AbstractAction
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
		$parents = $this->readParents();
		$this->addParentsUris( $parents );
		$this->addAddresses( $parents );
		$this->addPhoneNumbers( $parents );
		$this->addEmails( $parents );
		$this->addChildren( $parents );

		$responderData = [
			'parents' => $parents,
		];
		$responder     = new JsonResponder( StatusCodes::OK, $responderData );
		$responder->respond();
	}

	/**
	 * @param ParentEntity[] $parents
	 */
	private function addParentsUris( array $parents ): void
	{
		foreach ( $parents as $parent )
		{
			$parent->uri = $this->getUriBuilder()->getParentUri( $parent->id );
		}
	}

	/**
	 * @param ParentEntity[] $parents
	 * @throws PersistenceException
	 */
	private function addAddresses( array $parents ): void
	{
		foreach ( $parents as $parent )
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
	}

	/**
	 * @param ParentEntity[] $parents
	 * @throws PersistenceException
	 */
	private function addPhoneNumbers( array $parents ): void
	{
		foreach ( $parents as $parent )
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
	}

	/**
	 * @param ParentEntity[] $parents
	 * @throws PersistenceException
	 */
	private function addEmails( array $parents ): void
	{
		foreach ( $parents as $parent )
		{
			$emails = $this->readEmailsIdsOfParent( $parent );
			foreach ( $emails as $email )
			{
				$parent->emails[] = [
					'id'  => $email->uri,
					'uri' => $this->getUriBuilder()->getEmailUri( $email->id )
				];
			}
		}
	}

	/**
	 * @param ParentEntity[] $parents
	 * @throws PersistenceException
	 */
	private function addChildren( array $parents ): void
	{
		foreach ( $parents as $parent )
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
	}

	/**
	 * @return ParentEntity[]
	 * @throws PersistenceException
	 */
	private function readParents(): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new ParentsRepository( $databaseConnector ) )
			->readParents();
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

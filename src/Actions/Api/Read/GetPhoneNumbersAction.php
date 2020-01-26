<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Actions\Api\Read;

use CodeKandis\ClassListApi\Configurations\ConfigurationRegistry;
use CodeKandis\ClassListApi\Entities\ParentEntity;
use CodeKandis\ClassListApi\Entities\PhoneNumberEntity;
use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilder;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\ParentsRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\PhoneNumbersRepository;
use CodeKandis\Tiphy\Actions\AbstractAction;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\MariaDb\Connector;
use CodeKandis\Tiphy\Persistence\MariaDb\ConnectorInterface;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use ReflectionException;

class GetPhoneNumbersAction extends AbstractAction
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
		$phoneNumbers = $this->readPhoneNumbers();
		$this->addPhoneNumbersUris( $phoneNumbers );
		$this->addParents( $phoneNumbers );

		$responderData = [
			'phoneNumbers' => $phoneNumbers,
		];
		$responder     = new JsonResponder( StatusCodes::OK, $responderData );
		$responder->respond();
	}

	/**
	 * @param PhoneNumberEntity[] $phoneNumbers
	 */
	private function addPhoneNumbersUris( array $phoneNumbers ): void
	{
		foreach ( $phoneNumbers as $phoneNumber )
		{
			$phoneNumber->uri = $this->getUriBuilder()->getPhoneNumberUri( $phoneNumber->id );
		}
	}

	/**
	 * @param PhoneNumberEntity[] $phoneNumbers
	 * @throws PersistenceException
	 */
	private function addParents( array $phoneNumbers ): void
	{
		foreach ( $phoneNumbers as $phoneNumber )
		{
			$parents = $this->readParentsIdsOfPhoneNumber( $phoneNumber );
			foreach ( $parents as $parent )
			{
				$phoneNumber->parents[] = [
					'id'  => $parent->id,
					'uri' => $this->getUriBuilder()->getParentUri( $parent->id )
				];
			}
		}
	}

	/**
	 * @return PhoneNumberEntity[]
	 * @throws PersistenceException
	 */
	private function readPhoneNumbers(): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new PhoneNumbersRepository( $databaseConnector ) )
			->readPhoneNumbers();
	}

	/**
	 * @return ParentEntity[]
	 * @throws PersistenceException
	 */
	private function readParentsIdsOfPhoneNumber( PhoneNumberEntity $phoneNumber ): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new ParentsRepository( $databaseConnector ) )
			->readParentsIdsOfPhoneNumber( $phoneNumber );
	}
}

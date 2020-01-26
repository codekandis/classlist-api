<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Actions\Api\Read;

use CodeKandis\ClassListApi\Configurations\ConfigurationRegistry;
use CodeKandis\ClassListApi\Entities\EmailEntity;
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

class GetPhoneNumberAction extends AbstractAction
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

		$requestedPhoneNumber     = new PhoneNumberEntity();
		$requestedPhoneNumber->id = $inputData[ 'id' ];
		$phoneNumber              = $this->readPhoneNumber( $requestedPhoneNumber );

		if ( null === $phoneNumber )
		{
			$responder = new JsonResponder( StatusCodes::NOT_FOUND, null );
			$responder->respond();

			return;
		}

		$this->addPhoneNumberUri( $phoneNumber );
		$this->addParents( $phoneNumber );

		$responderData = [
			'phoneNumber' => $phoneNumber,
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

	private function addPhoneNumberUri( PhoneNumberEntity $phoneNumber ): void
	{
		$phoneNumber->uri = $this->getUriBuilder()->getPhoneNumberUri( $phoneNumber->id );
	}

	/**
	 * @throws PersistenceException
	 */
	private function addParents( PhoneNumberEntity $phoneNumber ): void
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

	/**
	 * @throws PersistenceException
	 */
	private function readPhoneNumber( PhoneNumberEntity $requestedPhoneNumber ): ?PhoneNumberEntity
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new PhoneNumbersRepository( $databaseConnector ) )
			->readPhoneNumberById( $requestedPhoneNumber );
	}

	/**
	 * @return EmailEntity[]
	 * @throws PersistenceException
	 */
	private function readParentsIdsOfPhoneNumber( PhoneNumberEntity $phoneNumber ): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new ParentsRepository( $databaseConnector ) )
			->readParentsIdsOfPhoneNumber( $phoneNumber );
	}
}

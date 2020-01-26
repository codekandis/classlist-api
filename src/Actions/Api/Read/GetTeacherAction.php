<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Actions\Api\Read;

use CodeKandis\ClassListApi\Configurations\ConfigurationRegistry;
use CodeKandis\ClassListApi\Entities\AddressEntity;
use CodeKandis\ClassListApi\Entities\ChildEntity;
use CodeKandis\ClassListApi\Entities\EmailEntity;
use CodeKandis\ClassListApi\Entities\PhoneNumberEntity;
use CodeKandis\ClassListApi\Entities\TeacherEntity;
use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilder;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\AddressesRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\ChildrenRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\EmailsRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\PhoneNumbersRepository;
use CodeKandis\ClassListApi\Persistence\MariaDb\Repositories\TeachersRepository;
use CodeKandis\Tiphy\Actions\AbstractAction;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use CodeKandis\Tiphy\Persistence\MariaDb\Connector;
use CodeKandis\Tiphy\Persistence\MariaDb\ConnectorInterface;
use CodeKandis\Tiphy\Persistence\PersistenceException;
use ReflectionException;

class GetTeacherAction extends AbstractAction
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

		$requestedTeacher     = new TeacherEntity();
		$requestedTeacher->id = $inputData[ 'id' ];
		$teacher              = $this->readTeacher( $requestedTeacher );

		if ( null === $teacher )
		{
			$responder = new JsonResponder( StatusCodes::NOT_FOUND, null );
			$responder->respond();

			return;
		}

		$this->addTeacherUri( $teacher );
		$this->addAddresses( $teacher );
		$this->addPhoneNumbers( $teacher );
		$this->addEmails( $teacher );
		$this->addChildren( $teacher );

		$responderData = [
			'teacher' => $teacher,
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

	private function addTeacherUri( TeacherEntity $teacher ): void
	{
		$teacher->uri = $this->getUriBuilder()->getTeacherUri( $teacher->id );
	}

	/**
	 * @throws PersistenceException
	 */
	private function addAddresses( TeacherEntity $teacher ): void
	{
		$addresses = $this->readAddressesIdsOfTeacher( $teacher );
		foreach ( $addresses as $address )
		{
			$teacher->addresses[] = [
				'id'  => $address->id,
				'uri' => $this->getUriBuilder()->getAddressUri( $address->id )
			];
		}
	}

	/**
	 * @throws PersistenceException
	 */
	private function addPhoneNumbers( TeacherEntity $teacher ): void
	{
		$phoneNumbers = $this->readPhoneNumbersIdsOfTeacher( $teacher );
		foreach ( $phoneNumbers as $phoneNumber )
		{
			$teacher->phoneNumbers[] = [
				'id'  => $phoneNumber->id,
				'uri' => $this->getUriBuilder()->getPhoneNumberUri( $phoneNumber->id )
			];
		}
	}

	/**
	 * @throws PersistenceException
	 */
	private function addEmails( TeacherEntity $teacher ): void
	{
		$emails = $this->readEmailsIdsOfTeacher( $teacher );
		foreach ( $emails as $email )
		{
			$teacher->emails[] = [
				'id'  => $email->id,
				'uri' => $this->getUriBuilder()->getEmailUri( $email->id )
			];
		}
	}

	/**
	 * @throws PersistenceException
	 */
	private function addChildren( TeacherEntity $teacher ): void
	{
		$children = $this->readChildrenIdsOfTeacher( $teacher );
		foreach ( $children as $child )
		{
			$teacher->children[] = [
				'id'  => $child->id,
				'uri' => $this->getUriBuilder()->getChildUri( $child->id )
			];
		}
	}

	/**
	 * @throws PersistenceException
	 */
	private function readTeacher( TeacherEntity $requestedTeacher ): ?TeacherEntity
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new TeachersRepository( $databaseConnector ) )
			->readTeacherById( $requestedTeacher );
	}

	/**
	 * @return AddressEntity[]
	 * @throws PersistenceException
	 */
	private function readAddressesIdsOfTeacher( TeacherEntity $teacher ): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new AddressesRepository( $databaseConnector ) )
			->readAddressesIdsOfTeacher( $teacher );
	}

	/**
	 * @return PhoneNumberEntity[]
	 * @throws PersistenceException
	 */
	private function readPhoneNumbersIdsOfTeacher( TeacherEntity $teacher ): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new PhoneNumbersRepository( $databaseConnector ) )
			->readPhoneNumbersIdsOfTeacher( $teacher );
	}

	/**
	 * @return EmailEntity[]
	 * @throws PersistenceException
	 */
	private function readEmailsIdsOfTeacher( TeacherEntity $teacher ): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new EmailsRepository( $databaseConnector ) )
			->readEmailsIdsOfTeacher( $teacher );
	}

	/**
	 * @return ChildEntity[]
	 * @throws PersistenceException
	 */
	private function readChildrenIdsOfTeacher( TeacherEntity $teacher ): array
	{
		$databaseConnector = $this->getDatabaseConnector();

		return ( new ChildrenRepository( $databaseConnector ) )
			->readChildrenIdsOfTeacher( $teacher );
	}
}

<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Actions\Api\Read;

use CodeKandis\ClassListApi\Configurations\ConfigurationRegistry;
use CodeKandis\ClassListApi\Entities\IndexEntity;
use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilder;
use CodeKandis\Tiphy\Actions\AbstractAction;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use ReflectionException;

class GetIndexAction extends AbstractAction
{
	/** @var ApiUriBuilder */
	private $uriBuilder;

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
	 * @throws ReflectionException
	 */
	public function execute(): void
	{
		$index = new IndexEntity;
		$this->addIndexUri( $index );
		$this->addChildrenUri( $index );
		$this->addParentsUri( $index );
		$this->addTeachersUri( $index );
		$this->addAddressesUri( $index );
		$this->addPhoneNumbersUri( $index );
		$this->addEmailsUri( $index );

		$responderData = [
			'index' => $index,
		];
		$responder     = new JsonResponder( StatusCodes::OK, $responderData );
		$responder->respond();
	}

	private function addIndexUri( IndexEntity $index ): void
	{
		$index->uri = $this->getUriBuilder()->getIndexUri();
	}

	private function addChildrenUri( IndexEntity $index ): void
	{
		$index->childrenUri = $this->getUriBuilder()->getChildrenUri();
	}

	private function addParentsUri( IndexEntity $index ): void
	{
		$index->parentsUri = $this->getUriBuilder()->getParentsUri();
	}

	private function addTeachersUri( IndexEntity $index ): void
	{
		$index->teachersUri = $this->getUriBuilder()->getTeachersUri();
	}

	private function addAddressesUri( IndexEntity $index ): void
	{
		$index->addressesUri = $this->getUriBuilder()->getAddressesUri();
	}

	private function addPhoneNumbersUri( IndexEntity $index ): void
	{
		$index->phoneNumbersUri = $this->getUriBuilder()->getPhoneNumbersUri();
	}

	private function addEmailsUri( IndexEntity $index ): void
	{
		$index->emailsUri = $this->getUriBuilder()->getEmailsUri();
	}
}

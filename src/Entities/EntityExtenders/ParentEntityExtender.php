<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Entities\EntityExtenders;

use CodeKandis\ClassListApi\Entities\AddressEntity;
use CodeKandis\ClassListApi\Entities\ChildEntity;
use CodeKandis\ClassListApi\Entities\EmailEntity;
use CodeKandis\ClassListApi\Entities\ParentEntity;
use CodeKandis\ClassListApi\Entities\PhoneNumberEntity;
use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilderInterface;

class ParentEntityExtender extends AbstractEntityExtender
{
	/** @var ParentEntity */
	private ParentEntity $parent;

	/** @var AddressEntity[] */
	private array $addresses;

	/** @var PhoneNumberEntity[] */
	private array $phoneNumbers;

	/** @var EmailEntity[] */
	private array $emails;

	/** @var ChildEntity[] */
	private array $children;

	/**
	 * Constructor method.
	 * @param AddressEntity[] $addresses
	 * @param PhoneNumberEntity[] $phoneNumbers
	 * @param EmailEntity[] $emails
	 * @param ChildEntity[] $children
	 */
	public function __construct( ApiUriBuilderInterface $apiUriBuilder, ParentEntity $parent, array $addresses, array $phoneNumbers, array $emails, array $children )
	{
		parent::__construct( $apiUriBuilder );

		$this->parent       = $parent;
		$this->addresses    = $addresses;
		$this->phoneNumbers = $phoneNumbers;
		$this->emails       = $emails;
		$this->children     = $children;
	}

	public function extend(): void
	{
		$this->addAddresses();
		$this->addPhoneNumbers();
		$this->addEmails();
		$this->addChildren();
	}

	private function addAddresses(): void
	{
		foreach ( $this->addresses as $address )
		{
			$this->parent->addresses[] = [
				'canonicalUri' => $this->apiUriBuilder->buildAddressUri( $address->id ),
				'id'           => $address->id
			];
		}
	}

	private function addPhoneNumbers(): void
	{
		foreach ( $this->phoneNumbers as $phoneNumber )
		{
			$this->parent->phoneNumbers[] = [
				'canonicalUri' => $this->apiUriBuilder->buildPhoneNumberUri( $phoneNumber->id ),
				'id'           => $phoneNumber->id
			];
		}
	}

	private function addEmails(): void
	{
		foreach ( $this->emails as $email )
		{
			$this->parent->emails[] = [
				'canonicalUri' => $this->apiUriBuilder->buildEmailUri( $email->id ),
				'id'           => $email->id
			];
		}
	}

	private function addChildren(): void
	{
		foreach ( $this->children as $child )
		{
			$this->parent->children[] = [
				'canonicalUri' => $this->apiUriBuilder->buildChildUri( $child->id ),
				'id'           => $child->id
			];
		}
	}
}

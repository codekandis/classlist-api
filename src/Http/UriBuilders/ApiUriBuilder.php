<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Http\UriBuilders;

use CodeKandis\Tiphy\Http\UriBuilders\AbstractUriBuilder;

class ApiUriBuilder extends AbstractUriBuilder
{
	public function getIndexUri(): string
	{
		return $this->getUri( 'index' );
	}

	public function getChildrenUri(): string
	{
		return $this->getUri( 'children' );
	}

	public function getChildUri( string $id ): string
	{
		return $this->getUri( 'child', $id );
	}

	public function getParentsUri(): string
	{
		return $this->getUri( 'parents' );
	}

	public function getParentUri( string $id ): string
	{
		return $this->getUri( 'parent', $id );
	}

	public function getTeachersUri(): string
	{
		return $this->getUri( 'teachers' );
	}

	public function getTeacherUri( string $id ): string
	{
		return $this->getUri( 'teacher', $id );
	}

	public function getAddressesUri(): string
	{
		return $this->getUri( 'addresses' );
	}

	public function getAddressUri( string $id ): string
	{
		return $this->getUri( 'address', $id );
	}

	public function getPhoneNumbersUri(): string
	{
		return $this->getUri( 'phoneNumbers' );
	}

	public function getPhoneNumberUri( string $id ): string
	{
		return $this->getUri( 'phoneNumber', $id );
	}

	public function getEmailsUri(): string
	{
		return $this->getUri( 'emails' );
	}

	public function getEmailUri( string $id ): string
	{
		return $this->getUri( 'email', $id );
	}
}

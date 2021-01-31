<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Http\UriBuilders;

use CodeKandis\Tiphy\Http\UriBuilders\AbstractUriBuilder;

class ApiUriBuilder extends AbstractUriBuilder
{
	public function getIndexUri(): string
	{
		return $this->build( 'index' );
	}

	public function getChildrenUri(): string
	{
		return $this->build( 'children' );
	}

	public function getChildUri( string $id ): string
	{
		return $this->build( 'child', $id );
	}

	public function getParentsUri(): string
	{
		return $this->build( 'parents' );
	}

	public function getParentUri( string $id ): string
	{
		return $this->build( 'parent', $id );
	}

	public function getTeachersUri(): string
	{
		return $this->build( 'teachers' );
	}

	public function getTeacherUri( string $id ): string
	{
		return $this->build( 'teacher', $id );
	}

	public function getAddressesUri(): string
	{
		return $this->build( 'addresses' );
	}

	public function getAddressUri( string $id ): string
	{
		return $this->build( 'address', $id );
	}

	public function getPhoneNumbersUri(): string
	{
		return $this->build( 'phoneNumbers' );
	}

	public function getPhoneNumberUri( string $id ): string
	{
		return $this->build( 'phoneNumber', $id );
	}

	public function getEmailsUri(): string
	{
		return $this->build( 'emails' );
	}

	public function getEmailUri( string $id ): string
	{
		return $this->build( 'email', $id );
	}
}

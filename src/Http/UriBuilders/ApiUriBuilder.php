<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Http\UriBuilders;

use CodeKandis\Tiphy\Http\UriBuilders\AbstractUriBuilder;

class ApiUriBuilder extends AbstractUriBuilder implements ApiUriBuilderInterface
{
	public function buildIndexUri(): string
	{
		return $this->build( 'index' );
	}

	public function buildChildrenUri(): string
	{
		return $this->build( 'children' );
	}

	public function buildChildUri( string $id ): string
	{
		return $this->build( 'child', $id );
	}

	public function buildParentsUri(): string
	{
		return $this->build( 'parents' );
	}

	public function buildParentUri( string $id ): string
	{
		return $this->build( 'parent', $id );
	}

	public function buildTeachersUri(): string
	{
		return $this->build( 'teachers' );
	}

	public function buildTeacherUri( string $id ): string
	{
		return $this->build( 'teacher', $id );
	}

	public function buildAddressesUri(): string
	{
		return $this->build( 'addresses' );
	}

	public function buildAddressUri( string $id ): string
	{
		return $this->build( 'address', $id );
	}

	public function buildPhoneNumbersUri(): string
	{
		return $this->build( 'phoneNumbers' );
	}

	public function buildPhoneNumberUri( string $id ): string
	{
		return $this->build( 'phoneNumber', $id );
	}

	public function buildEmailsUri(): string
	{
		return $this->build( 'emails' );
	}

	public function buildEmailUri( string $id ): string
	{
		return $this->build( 'email', $id );
	}
}

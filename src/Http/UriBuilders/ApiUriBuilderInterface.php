<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Http\UriBuilders;

interface ApiUriBuilderInterface
{
	public function buildIndexUri(): string;

	public function buildChildrenUri(): string;

	public function buildChildUri( string $id ): string;

	public function buildParentsUri(): string;

	public function buildParentUri( string $id ): string;

	public function buildTeachersUri(): string;

	public function buildTeacherUri( string $id ): string;

	public function buildAddressesUri(): string;

	public function buildAddressUri( string $id ): string;

	public function buildPhoneNumbersUri(): string;

	public function buildPhoneNumberUri( string $id ): string;

	public function buildEmailsUri(): string;

	public function buildEmailUri( string $id ): string;
}

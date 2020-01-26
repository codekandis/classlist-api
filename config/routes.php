<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Configurations;

use CodeKandis\ClassListApi\Actions\Api;
use CodeKandis\Tiphy\Http\Requests\Methods;

return [
	'^/$'                                                                 => [
		Methods::GET => Api\Read\GetIndexAction::class
	],
	'^/children$'                                                         => [
		Methods::GET => Api\Read\GetChildrenAction::class
	],
	'^/children/(?<id>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})$'      => [
		Methods::GET => Api\Read\GetChildAction::class
	],
	'^/parents$'                                                          => [
		Methods::GET => Api\Read\GetParentsAction::class
	],
	'^/parents/(?<id>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})$'       => [
		Methods::GET => Api\Read\GetParentAction::class
	],
	'^/teachers$'                                                         => [
		Methods::GET => Api\Read\GetTeachersAction::class
	],
	'^/teachers/(?<id>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})$'      => [
		Methods::GET => Api\Read\GetTeacherAction::class
	],
	'^/addresses$'                                                        => [
		Methods::GET => Api\Read\GetAddressesAction::class
	],
	'^/addresses/(?<id>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})$'     => [
		Methods::GET => Api\Read\GetAddressAction::class
	],
	'^/phone-numbers$'                                                    => [
		Methods::GET => Api\Read\GetPhoneNumbersAction::class
	],
	'^/phone-numbers/(?<id>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})$' => [
		Methods::GET => Api\Read\GetPhoneNumberAction::class
	],
	'^/emails$'                                                           => [
		Methods::GET => Api\Read\GetEmailsAction::class
	],
	'^/emails/(?<id>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})$'        => [
		Methods::GET => Api\Read\GetEmailAction::class
	]
];

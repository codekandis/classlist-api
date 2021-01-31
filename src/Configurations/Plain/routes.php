<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Configurations\Plain;

use CodeKandis\ClassListApi\Actions\Api;
use CodeKandis\Tiphy\Http\Requests\Methods;

return [
	'routes' => [
		'^/$'                                                                 => [
			Methods::GET => Api\Get\IndexAction::class
		],
		'^/children$'                                                         => [
			Methods::GET => Api\Get\ChildrenAction::class
		],
		'^/children/(?<id>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})$'      => [
			Methods::GET => Api\Get\ChildAction::class
		],
		'^/parents$'                                                          => [
			Methods::GET => Api\Get\ParentsAction::class
		],
		'^/parents/(?<id>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})$'       => [
			Methods::GET => Api\Get\ParentAction::class
		],
		'^/teachers$'                                                         => [
			Methods::GET => Api\Get\TeachersAction::class
		],
		'^/teachers/(?<id>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})$'      => [
			Methods::GET => Api\Get\TeacherAction::class
		],
		'^/addresses$'                                                        => [
			Methods::GET => Api\Get\AddressesAction::class
		],
		'^/addresses/(?<id>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})$'     => [
			Methods::GET => Api\Get\AddressAction::class
		],
		'^/phone-numbers$'                                                    => [
			Methods::GET => Api\Get\PhoneNumbersAction::class
		],
		'^/phone-numbers/(?<id>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})$' => [
			Methods::GET => Api\Get\PhoneNumberAction::class
		],
		'^/emails$'                                                           => [
			Methods::GET => Api\Get\EmailsAction::class
		],
		'^/emails/(?<id>[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12})$'        => [
			Methods::GET => Api\Get\EmailAction::class
		]
	]
];

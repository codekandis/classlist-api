<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Configurations;

return [
	'schema'       => 'https',
	'host'         => 'api.classlist.codekandis',
	'baseUri'      => '/',
	'relativeUris' => [
		'index'        => '',
		'children'     => 'children',
		'child'        => 'children/%s',
		'parents'      => 'parents',
		'parent'       => 'parents/%s',
		'teachers'     => 'teachers',
		'teacher'      => 'teachers/%s',
		'addresses'    => 'addresses',
		'address'      => 'addresses/%s',
		'phoneNumbers' => 'phone-numbers',
		'phoneNumber'  => 'phone-numbers/%s',
		'emails'       => 'emails',
		'email'        => 'emails/%s',
	]
];

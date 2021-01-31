<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Entities\UriExtenders;

use CodeKandis\ClassListApi\Entities\TeacherEntity;
use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilderInterface;

class TeacherApiUriExtender extends AbstractApiUriExtender
{
	/** @var TeacherEntity */
	private TeacherEntity $teacher;

	public function __construct( ApiUriBuilderInterface $apiUriBuilder, TeacherEntity $teacher )
	{
		parent::__construct( $apiUriBuilder );
		$this->teacher = $teacher;
	}

	public function extend(): void
	{
		$this->addCanonicalUri();
	}

	private function addCanonicalUri(): void
	{
		$this->teacher->canonicalUri = $this->apiUriBuilder->buildTeacherUri( $this->teacher->id );
	}
}

<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Entities\EntityExtenders;

use CodeKandis\ClassListApi\Entities\EmailEntity;
use CodeKandis\ClassListApi\Entities\ParentEntity;
use CodeKandis\ClassListApi\Entities\TeacherEntity;
use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilderInterface;

class EmailEntityExtender extends AbstractEntityExtender
{
	/** @var EmailEntity */
	private EmailEntity $email;

	/** @var ParentEntity[] */
	private array $parents;

	/** @var TeacherEntity[] */
	private array $teachers;

	/**
	 * Constructor method.
	 * @param ParentEntity[] $parents
	 * @param TeacherEntity[] $teachers
	 */
	public function __construct( ApiUriBuilderInterface $apiUriBuilder, EmailEntity $email, array $parents, array $teachers )
	{
		parent::__construct( $apiUriBuilder );

		$this->email    = $email;
		$this->parents  = $parents;
		$this->teachers = $teachers;
	}

	public function extend(): void
	{
		$this->addParents();
		$this->addTeachers();
	}

	private function addParents(): void
	{
		foreach ( $this->parents as $parent )
		{
			$this->email->parents[] = [
				'canonicalUri' => $this->apiUriBuilder->buildParentUri( $parent->id ),
				'id'           => $parent->id
			];
		}
	}

	private function addTeachers(): void
	{
		foreach ( $this->teachers as $teacher )
		{
			$this->email->teachers[] = [
				'canonicalUri' => $this->apiUriBuilder->buildTeacherUri( $teacher->id ),
				'id'           => $teacher->id
			];
		}
	}
}

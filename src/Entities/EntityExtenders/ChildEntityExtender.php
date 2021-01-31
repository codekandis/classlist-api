<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Entities\EntityExtenders;

use CodeKandis\ClassListApi\Entities\ChildEntity;
use CodeKandis\ClassListApi\Entities\ParentEntity;
use CodeKandis\ClassListApi\Entities\TeacherEntity;
use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilderInterface;

class ChildEntityExtender extends AbstractEntityExtender
{
	/** @var ChildEntity */
	private ChildEntity $child;

	/** @var ParentEntity[] */
	private array $parents;

	/** @var TeacherEntity[] */
	private array $teachers;

	/**
	 * Constructor method.
	 * @param ParentEntity[] $parents
	 * @param TeacherEntity[] $teachers
	 */
	public function __construct( ApiUriBuilderInterface $apiUriBuilder, ChildEntity $child, array $parents, array $teachers )
	{
		parent::__construct( $apiUriBuilder );

		$this->child    = $child;
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
			$this->child->parents[] = [
				'canonicalUri' => $this->apiUriBuilder->buildParentUri( $parent->id ),
				'id'           => $parent->id
			];
		}
	}

	private function addTeachers(): void
	{
		foreach ( $this->teachers as $teacher )
		{
			$this->child->teachers[] = [
				'canonicalUri' => $this->apiUriBuilder->buildTeacherUri( $teacher->id ),
				'id'           => $teacher->id
			];
		}
	}
}

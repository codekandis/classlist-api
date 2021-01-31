<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Entities\EntityExtenders;

use CodeKandis\ClassListApi\Entities\ParentEntity;
use CodeKandis\ClassListApi\Entities\PhoneNumberEntity;
use CodeKandis\ClassListApi\Entities\TeacherEntity;
use CodeKandis\ClassListApi\Http\UriBuilders\ApiUriBuilderInterface;

class PhoneNumberEntityExtender extends AbstractEntityExtender
{
	/** @var PhoneNumberEntity */
	private PhoneNumberEntity $phoneNumber;

	/** @var ParentEntity[] */
	private array $parents;

	/** @var TeacherEntity[] */
	private array $teachers;

	/**
	 * Constructor method.
	 * @param ParentEntity[] $parents
	 * @param TeacherEntity[] $teachers
	 */
	public function __construct( ApiUriBuilderInterface $apiUriBuilder, PhoneNumberEntity $phoneNumber, array $parents, array $teachers )
	{
		parent::__construct( $apiUriBuilder );

		$this->phoneNumber = $phoneNumber;
		$this->parents     = $parents;
		$this->teachers    = $teachers;
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
			$this->phoneNumber->parents[] = [
				'canonicalUri' => $this->apiUriBuilder->buildParentUri( $parent->id ),
				'id'           => $parent->id
			];
		}
	}

	private function addTeachers(): void
	{
		foreach ( $this->teachers as $teacher )
		{
			$this->phoneNumber->teachers[] = [
				'canonicalUri' => $this->apiUriBuilder->buildTeacherUri( $teacher->id ),
				'id'           => $teacher->id
			];
		}
	}
}

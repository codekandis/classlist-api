<?php declare( strict_types = 1 );
namespace CodeKandis\ClassListApi\Actions\Api\Get;

use CodeKandis\ClassListApi\Actions\AbstractWithApiUriBuilderAction;
use CodeKandis\ClassListApi\Entities\IndexEntity;
use CodeKandis\ClassListApi\Entities\UriExtenders\IndexApiUriExtender;
use CodeKandis\Tiphy\Http\Responses\JsonResponder;
use CodeKandis\Tiphy\Http\Responses\StatusCodes;
use JsonException;

class IndexAction extends AbstractWithApiUriBuilderAction
{
	/**
	 * @throws JsonException
	 */
	public function execute(): void
	{
		$index = new IndexEntity;
		$this->extendUris( $index );

		$responderData = [
			'index' => $index,
		];
		$responder     = new JsonResponder( StatusCodes::OK, $responderData );
		$responder->respond();
	}

	private function extendUris( $index ): void
	{
		( new IndexApiUriExtender(
			$this->getApiUriBuilder(),
			$index
		) )
			->extend();
	}
}

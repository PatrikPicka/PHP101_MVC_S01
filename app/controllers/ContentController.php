<?php

namespace App\Controllers;

use App\Models\Content;
use App\Models\ContentCategory;
use Core\Request;
use Core\Superclasses\BaseController;

class ContentController extends BaseController
{
	public function listAction(Request $request, array $params): void
	{
		$categorySlug = $params[0];
		$category = new ContentCategory(slug: $categorySlug);
		$content = new Content();
		$contents = $content->findAllCOntentForCategoryBySlug(slug: $categorySlug);

		$this->view->render('pages/content/list', [
			'category' => $category,
			'contents' => $contents,
		]);
	}
}
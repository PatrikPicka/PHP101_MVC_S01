<?php

namespace App\Controllers;

use App\Models\Content;
use App\Models\ContentCategory;
use App\Models\User;
use Core\Request;
use Core\Router;
use Core\Session;
use Core\Superclasses\BaseController;
use Couchbase\PathNotFoundException;

class ContentController extends BaseController
{
	protected const STATUS_CREATE_USERS_LECTURE = 1;
	protected const STATUS_FINISHED_USERS_LECTURE = 2;

	/**
	 * @param Request $request
	 * @param array $params
	 * @return void
	 * @throws PathNotFoundException
	 */
	public function listAction(Request $request, array $params): void
	{
		$categorySlug = $params[0];
		$category = new ContentCategory(data: $categorySlug);
		$content = new Content();
		$contents = $content->findAllCOntentForCategoryBySlug(slug: $categorySlug);
		$user = new User(User::getLoggedInUserId());

		$this->view->render('pages/content/list', [
			'category' => $category,
			'contents' => $contents,
			'user' => $user,
		]);
	}

	/**
	 * @param Request $request
	 * @param array $params
	 * @return void
	 * @throws PathNotFoundException
	 */
	public function detailAction(Request $request, array $params): void
	{
		$contentId = $params[0];
		$content = new Content($contentId);
		$user = new User(User::getLoggedInUserId());

		if (!$user->hasAccessToContent(content: $content)) {
			Session::setAlertMessage('You have to first watch or confirm that you already have the knowledge of previous content.', ALERT_WARNING);

			Router::redirect(['controller' => 'content', 'action' => 'list', 'params' => ['slug' => $content->getCategory()->slug]]);
		}

		$this->view->render('pages/content/detail', [
			'content' => $content,
			'user' => $user,
		]);
	}

	public function ajaxUpdatesAction(Request $request): void
	{
		if ($request->getMethod() !== 'POST') {
			Router::redirect([
				'controller' => 'error',
				'action' => 'ajaxMethodNotSupported',
			]);
		} elseif (!$request->isSubmitted(name: 'userId') || !$request->isSubmitted(name: 'contentId') || !$request->isSubmitted(name: 'status')) {
			Router::redirect([
				'controller' => 'error',
				'action' => 'ajaxBadRequest',
			]);
		}

		$userId = (int) $request->get(name: 'userId');
		$contentId = (int) $request->get(name: 'contentId');
		$status = (int) $request->get(name: 'status');
		$user = new User($userId);

		if ($status === self::STATUS_CREATE_USERS_LECTURE) {
			if ($user->createLectureIfNotExists(contentId: $contentId)) {
				$this->ajaxResponse(resp: ['message' => 'Successfully started new lecture.'], code: 201);
			}
		} elseif ($status === self::STATUS_FINISHED_USERS_LECTURE) {
			if ($user->updateLectureToFinished(contentId: $contentId, finished: 1)) {
				$this->ajaxResponse(resp: ['message' => 'Successfully finished the lecture.']);
			}
		}

		$this->ajaxResponse(resp: ['message' => 'There was an error on our side.'], code: 500);
	}
}
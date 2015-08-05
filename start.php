<?php

namespace Core;

use Apps\PHPfox_KB\Model\Article,
	Apps\PHPfox_KB\Model\Category;

use Core\Auth\User;

new Route\Group(setting('kb_route'), function() {

	$form = function(Controller $controller) {
		(new User())->membersOnly();

		$edit = false;
		if ($controller->request->get('id')) {
			$edit = (new Article())->get((int) $controller->request->get('id'));
			if (!$controller->active->isAdmin() && $edit->user->id != $controller->active->id ) {
				throw error('Unable to edit this article.');
			}
		}

		$validator = new Validator();
		$validator->rule('title')->required();
		$validator->rule('content')->required();
		$validator->make();

		if ($controller->request->isPost()) {
			$val = (object) $controller->request->get('val');
			$article = ($edit ? (new Article())->put($edit->id, $val) : (new Article())->post($val));

			return $controller->url->send($article->permalink, [], _p(($edit ? 'Article successfully updated!' : 'Article successfully created!')));
		}

		$categories = [];
		foreach ((new Category())->get() as $category) {
			$categories[$category->id] = $category->value;
		}

		$controller->section(setting('kb_section_name'), '/kb');
		return $controller->render('add.html', [
			'edit' => $edit,
			'categoryOptions' => $categories
		]);
	};


	new Route('/add', $form);
	new Route('/edit', $form);

	new Route('/', function(Controller $controller) {

		$controller->section(setting('kb_section_name'), '/kb');
		return $controller->render('index.html', [
			'categories' => (new Category())->get()
		]);
	});

	(new Route('/:id/:title'))->where([':id' => '([0-9])'])->run(function(Controller $controller, $id, $title) {
		$article = (new Article())->get($id);
		if ($title != $controller->url->clean($article->title)) {
			header('HTTP/1.1 301 Moved Permanently');
			$controller->url->send($article->permalink);
		}

		$controller->h1($article->title, $article->permalink);
		$controller->section(setting('kb_section_name'), '/kb');
		$controller->title($article->title);

		return $controller->render('view.html', [
			'article' => $article
		]);
	});
});
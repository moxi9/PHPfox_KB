<?php

namespace Core;

use Apps\PHPfox_KB\Model\Article,
	Apps\PHPfox_KB\Model\Category;

use Core\Auth\User;

new Route\Group('/kb', function() {
	new Route('/add', function(Controller $controller) {
		(new User())->membersOnly();

		$validator = new Validator();
		$validator->rule('title')->required();
		$validator->rule('content')->required();
		$validator->make();

		if ($controller->request->isPost()) {
			$article = (new Article())->post((object) $controller->request->get('val'));

			return $controller->url->send($article->permalink);
		}

		$controller->asset('<script src="//cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>');

		return $controller->render('add.html');
	});

	new Route('/', function(Controller $controller) {
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
		$controller->section('KB', $controller->url->make('/kb'));
		$controller->title($article->title);

		return $controller->render('view.html', [
			'article' => $article
		]);
	});
});
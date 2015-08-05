<?php

namespace Apps\PHPfox_KB\Model;

use \Apps\PHPfox_KB\Model\Article;

class Category extends \Core\Model {
	public function get() {
		$article = new Article();
		$categories = $this->storage->all('kb_categories');
		foreach ($categories as $key => $category) {
			$articles = $this->storage->all('kb_articles_' . $category->id);
			$categories[$key]->articles = [];
			foreach ($articles as $cache) {
				$categories[$key]->articles[] = $article->get($cache->value);
			}
		}

		return $categories;
	}

	public function post($name) {
		$this->storage->set('kb_categories', $name);
	}
}

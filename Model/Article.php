<?php

namespace Apps\PHPfox_KB\Model;

class Article extends \Core\Model {
	public function get($id) {
		$feed = new \Api\Feed();

		$entry = $feed->get($id);

		$object = $entry->content;
		$object->id = $entry->id;

		return new Article\Object($object);
	}

	public function post($val) {
		(new \Core\Request())->set([
			'type_id' => 'PHPfox_KB',
			'content' => [
				'title' => $val->title,
				'content' => $val->content
			]
		]);

		$feed = (new \Api\Feed())->post();

		$this->storage->set('kb_articles_2', $feed->id);

		return $this->get($feed->id);
	}
}
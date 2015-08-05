<?php

namespace Apps\PHPfox_KB\Model\Article;

class Object extends \Core\Objectify {
	public $id;
	public $title;
	public $content;
	public $permalink;

	public function __construct($objects) {
		parent::__construct($objects);

		$url = new \Core\Url();
		$this->permalink = $url->make('/kb/' . $this->id . '/' . $url->clean($this->title));

		$this->content = (new \Parsedown())->parse($this->content);
	}
}
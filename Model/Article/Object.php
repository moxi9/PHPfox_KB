<?php

namespace Apps\PHPfox_KB\Model\Article;

class Object extends \Core\Objectify {
	public $id;
	public $title;
	public $content;
	public $content_original;
	public $permalink;
	public $user;
	public $can_edit = false;

	public function __construct($objects) {
		parent::__construct($objects);

		$url = new \Core\Url();
		$this->permalink = $url->make('/kb/' . $this->id . '/' . $url->clean($this->title));

		$this->content_original = $this->content;
		$this->content = (new \Parsedown())->parse($this->content);
		if ($this->user->id == user()->id || user()->isAdmin()) {
			$this->can_edit = true;
		}
	}
}
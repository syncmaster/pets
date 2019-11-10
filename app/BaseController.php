<?php
namespace App;

use PDO;

class BaseController
{
	protected $smarty;
	protected $db;

	public function __construct() {
		global $smarty, $db;

		$this->smarty = $smarty;
		$this->db = $db;

	}

	protected function redirectTo($route) {
		header("Location: " . Route($route, true));
		exit;
	}

	protected function redirectToUrl($url) {
		header("Location: " . $url);
		exit;
	}

}

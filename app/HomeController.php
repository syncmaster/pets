<?php
namespace App;


class HomeController extends BaseController
{
	public function index() {
		$this->smarty->assign('TITLE', 'Домашни любимци');
		$this->smarty->assign('HEADING', 'homepage');

		return $this->smarty->fetch("home.html");
	}

}
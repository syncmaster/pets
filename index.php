<?php
namespace App;
include ('boot.php');
$page = isset($_GET['route']) ? trim($_GET['route'], '/') : '';

foreach ($routes as $route) {
	if ($route['route'] === $page) {
		list($class, $method) = explode('@', $route['callback']);
		break;
	}
}

if (!isset($class)) {
	foreach ($routes as $route) {
		if (isset($route['default']) && $route['default'] === true) {
			list($class, $method) = explode('@', $route['callback']);
			break;
		}
	}

	if (!empty($page)) {
		header('Location: ' . Route($route['name'], true));
		exit;
	}
}

$class = 'App\\' . $class;
$controller = new $class();
$content = $controller->{$method}();

if (is_string($content)) {
	$smarty->assign('PAGE', $content);
	$smarty->display('index.html');
} else {
	echo json_encode($content);
}

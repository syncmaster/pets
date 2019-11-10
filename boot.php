<?php
define('ROOT', __DIR__);
define('URL', str_replace('//', '/', dirname($_SERVER['SCRIPT_NAME']) . '/' ));
define('SITE_URL', 'http' .
	((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 's' : '')
	. '://' . $_SERVER['HTTP_HOST'] . URL);
require_once(ROOT . '/vendor/autoload.php');
require_once(ROOT . '/config/settings.php');
$routes = include(ROOT . '/config/routes.php');

// set-up UTF8 codepage for all mb_* functions
mb_internal_encoding('UTF-8');
mb_regex_encoding('UTF-8');

@ini_set('session.hash_function', 1);
@ini_set('session.hash_bits_per_character', 6);


try {
    $db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8", DB_USER, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode="NO_ZERO_DATE,ONLY_FULL_GROUP_BY"'));
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Connection failed to database: ";
}

call_user_func(function() use ($db) {
	$result = $db->query('SELECT @@SESSION.sql_mode AS modes;');
	if (!$result->rowCount()) {
		return;
	}
	$modes = $result->fetch();
	$modes = array_flip(explode(',', $modes['modes']));
	$modes = array_diff_key($modes, array_flip(array(
		'NO_ZERO_DATE',
		'ONLY_FULL_GROUP_BY',
	)));
	$db->query("SET SESSION sql_mode = '" . implode(",", array_keys($modes)) . "'");
});

$smarty = new Smarty();
$smarty->setCompileDir(ROOT . '/cache');
$smarty->setTemplateDir(ROOT . '/templates');
$smarty->error_reporting = error_reporting() &~E_NOTICE;
$smarty->assign('URL', URL);
$smarty->assign('SITE_URL', SITE_URL);
$smarty->registerPlugin('block', 'route', 'GetRoute');
$smarty->registerPlugin('block', 'asset', 'AssetSmarty');

error_reporting(E_ALL);
ini_set('display_errors', 1);
function P($data)
{
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}

/**
 * Get path for given route name
 *
 * @param array $params Parameters within {route}
 * @param string $content Text between {route} and {/route}
 * @param object $smarty Smarty template
 * @param integer $repeat
 * @return null|string Route path
 */
function GetRoute($params, $content, $smarty, &$repeat) {
	return Route($content);
}

function AssetSmarty($params, $content, $smarty, &$repeat) {

	if (empty($content)) {
        return;
    }

	$file = str_replace(DIRECTORY_SEPARATOR, '/', $content);

    $file = ltrim($file, '/');

    $rootPath = realpath(ROOT . $file);
    $templPath = realpath(ROOT . DIRECTORY_SEPARATOR . $file);

    clearstatcache();

    $version = null;
    $path = '';

    if ($templPath && is_readable($templPath) && is_file($templPath)) {
        $version = substr(md5(date("YmdHi", @filemtime($templPath))), 0, 16);
    } elseif ($rootPath && is_readable($rootPath) && is_file($rootPath)) {
        $version = substr(md5(date("YmdHi", @filemtime($rootPath))), 0, 16);
    }

    return SITE_URL . $file . ($version ? "?version=" . $version : '');
}

function Route($route, $fullAddress = false) {
	global $routes;
	static $routeNames = array();

	$route = mb_strtolower(trim($route));

	if (empty($route)) {
		return;
	}

	if (count($routes) && !count($routeNames)) {
		foreach ($routes as $found) {
			$routeNames[mb_strtolower($found['name'])] = $found['route'];
		}
	}

	if (isset($routeNames[$route])) {
		return ($fullAddress ? SITE_URL : URL) . $routeNames[$route];
	} else {
		return;
	}
}



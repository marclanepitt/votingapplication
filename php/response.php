

<?php
header("Access-Control-Allow-Origin: *");
date_default_timezone_set('America/New_York');
require_once('orm/Tokens.php');
require_once('orm/Response.php');
$path_components = explode('/', $_SERVER['PATH_INFO']);

if ($_SERVER['REQUEST_METHOD'] == "GET") {
	if(count($path_components) >=2 && $path_components[1] != "") {
		$is_authorized = true;//Token::authorizeRequest($_COOKIE['uid'], $_COOKIE['uuid']);
		if($is_authorized) {
			$minAge = null;
			$maxAge = null;
			if (isset($_GET['minAge']))
				$minAge = $_GET['minAge'];
			if (isset($_GET['maxAge']))
				$maxAge = $_GET['maxAge'];
			header("Content-Type: application/json");
			print(json_encode(Response::getResponseData($path_components[1], $_GET['races'], $_GET['religions'], $_GET['countries'], $_GET['genders'], 
														$minAge, $maxAge)));
			exit();
		} else {
			header("HTTP/1.0 401 Unauthorized");
			print("oh no!");
			exit();
		}
	}
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if(count($path_components) >=2 && $path_components[1] != "") {
		$is_authorized = Token::authorizeRequest($_COOKIE['uid'], $_COOKIE['uuid']);
		if($is_authorized) {
			header("Content-Type: application/json");
			print(json_encode(Response::createResponse($_POST['user_id'], $path_components[1], $_POST['answer_id'])));
			exit();
		} else {
			header("HTTP/1.0 401 Unauthorized");
			print("oh no!");
			exit();
		}
	}
}
header("HTTP/1.0 400 Bad Request");
print("Did not understand URL");
exit();

?>

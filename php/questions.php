

<?php
header("Access-Control-Allow-Origin: *");
date_default_timezone_set('America/New_York');
require_once('orm/Tokens.php');
require_once('orm/Question.php');
$path_components = explode('/', $_SERVER['PATH_INFO']);

if ($_SERVER['REQUEST_METHOD'] == "GET") {
	if(count($path_components) >=2 && $path_components[1] != "") {
		if($path_components[1] == "recent-activity") {
			$is_authorized = Token::authorizeRequest($path_components[2],$_COOKIE['uuid']);
			if($is_authorized) {
				header("Content-Type: application/json");
				print(json_encode(Question::getRecentActivity($path_components[2])));
				exit();
			} else {
				header("HTTP/1.0 401 Unauthorized");
				print("oh no!");
				exit();
			}
		} else if($path_components[1] == "search") {
			$is_authorized = Token::authorizeRequest($_COOKIE['uid'],$_COOKIE['uuid']);
			if($is_authorized) {
				header("Content-Type: application/json");
				$str = null;
				if (isset($_GET['string'])) 
					$str = $_GET['string'];
				print(json_encode(Question::filterQuestions($_COOKIE['uid'], $str)));
				exit();
			} else {
				header("HTTP/1.0 401 Unauthorized");
				print("oh no!");
				exit();
			}			
		} else if($path_components[1] == "trending") {
			$is_authorized = Token::authorizeRequest($_COOKIE['uid'],$_COOKIE['uuid']);
			if($is_authorized) {
				header("Content-Type: application/json");
				print(json_encode(Question::getTrendingQuestion($_COOKIE['uid'])));
				exit();
			} else {
				header("HTTP/1.0 401 Unauthorized");
				print("oh no!");
				exit();
			}
		} else if($path_components[1] == "answers") {
			$is_authorized = Token::authorizeRequest($_COOKIE['uid'],$_COOKIE['uuid']);
			if($is_authorized) {
				header("Content-Type: application/json");
				print(json_encode(Question::getQuestionAndAnswers($path_components[2])));
				exit();
			} else {
				header("HTTP/1.0 401 Unauthorized");
				print("oh no!");
				exit();
			}
		} else if($path_components[1] == "viewed") {
			$is_authorized = Token::authorizeRequest($_COOKIE['uid'],$_COOKIE['uuid']);
			if($is_authorized) {
				header("Content-Type: application/json");
				print(json_encode(Question::createView($_GET['question'],$_GET['viewed_by'])));
				exit();
			} else {
				header("HTTP/1.0 401 Unauthorized");
				print("oh no!");
				exit();
			}		
		}
	} 

} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if(count($path_components) >=2 && $path_components[1] != "") {
		if($path_components[1] == "create") {
			$is_authorized = Token::authorizeRequest($_COOKIE['uid'],$_COOKIE['uuid']);
			if($is_authorized) {
				header("Content-Type: application/json");
				print(json_encode(Question::getCreateQuestion($_COOKIE['uid'],$_GET['question'],$_GET['answers'],$_GET['date'])));
				exit();
			} else {
				header("HTTP/1.0 401 Unauthorized");
				print("oh no!");
				exit();
			}
		}
	}

}
header("HTTP/1.0 400 Bad Request");
print("Did not understand URL");
exit();

?>

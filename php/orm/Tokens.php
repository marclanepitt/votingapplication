<?php
date_default_timezone_set('America/New_York');

class Token
{
  private $id;
  private $token;
  private $user;
  private $expiry;

  public static function connect() {
    return new mysqli("us-cdbr-iron-east-05.cleardb.net", 
          "b7f1450e372951", 
          "01e28eb8", 
          "heroku_5486668f57cfa79");
  }


  public static function getToken($username, $password) {
    $mysqli=Token::connect();
    $query = "SELECT username,password,id FROM Final_User where username = '".$username."'";
    $response = $mysqli->query($query);
    $user_info = $response->fetch_array();
    if($response) {
      if($user_info['password']==$password) {
        $user_id = $user_info['id'];
        $token = Token::generateToken($username,$user_id);
        return array(
          'token'=>$token,
          'user_id'=>$user_id
        );
      }
    } else {
      return false;
    }
  }
  
  public static function generateToken($username,$user_id) {
    $token = md5(uniqid($username, true));
    $expiry = date('Y-m-d H:i:s');
    $expiry = date('Y-m-d H:i:s', strtotime($expiry. ' + 10 days'));
    $mysqli = Token::connect();
    $mysqli->query("INSERT into Final_Tokens VALUES (0,'".$token."' ,".$user_id.", '".$expiry."')");
    return $token;
  }

  public static function logout($user_id) {
    $mysqli = Token::connect();
    $query = "DELETE FROM Final_Tokens where user = ".$user_id;
    $mysqli->query($query);
    return true;
  }

  public static function authorizeRequest($id, $token) {
    $mysqli = Token::connect();
    $response = $mysqli->query("SELECT * From Final_Tokens where user = ".$id." and token = '".$token."'");
    if($response) {
      return true;
    } else {
      return false;
    }
  }
}
?>
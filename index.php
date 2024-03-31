<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

require_once 'config/db.php';
require_once 'src/autoloader.php';
require_once 'vendor/autoload.php';
use Firebase\JWT\JWT;

$auth = new Auth();

switch ($_SERVER["REQUEST_METHOD"]) {
  case 'POST':
    if ($_SERVER['REQUEST_URI'] === '/register') {
      $data = json_decode(file_get_contents("php://input"));
      $response = $auth->register($data->email, $data->password);
      echo json_encode($response);
    } elseif ($_SERVER['REQUEST_URI'] === '/authorize') {
      $data = json_decode(file_get_contents("php://input"));

      $response = $auth->authorize($data->email, $data->password);
      echo json_encode($response);
    }
    break;
  case 'GET':
    if ($_SERVER['REQUEST_URI'] === '/feed') {
      $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'];

      if (!$authorizationHeader) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
      }

      $token = explode(' ', $authorizationHeader)[1];
      $userId = $auth->jwt->validateToken($token);

      if (!$userId) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
      }

      http_response_code(200);
      echo json_encode(['message' => 'Access granted']);
    }
    break;
  default:
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    break;
}

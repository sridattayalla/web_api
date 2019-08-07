<?php
//allow only if request comes from post and type is application/json
header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// required to encode json web token
include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

//database connections
include_once 'config/database.php';
include_once 'objects/project.php';
include_once 'objects/user.php';

//database connection
$database = new Database();
$db = $database->getConnection();

$user = new User($db);


// get posted data
$data = json_decode(file_get_contents("php://input"));

//get jwt
$jwt = isset($data->jwt)? $data->jwt: " ";

//function to say access denied
function accessDenied(){
     // set response code
     http_response_code(401);
 
     // show error message
     echo json_encode(array(
         "message" => "Access denied",
     ));

     exit();
}

//function can't create project
function cannotGet($msg){
    http_response_code(401);

    echo json_encode(array(
        "message" => $msg
    ));

    
}

//jwt is in encoded format so we need to decode it
if($jwt){
    try{
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        //setting user properties
        $user->name = $decoded->data->name;
        $user->id = $decoded->data->id;
        $user->email = $decoded->data->email;

        $projectsArray = $user->getProjects();

        if($projectsArray){
            echo json_encode($projectsArray);
        }else{
            cannotGet("no projects");
        }
        
    }catch(Exception $e){
        accessDenied();
    }
}else{
   accessDenied();
}


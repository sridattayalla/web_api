<?php

// required headers
header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// database connection will be here
include_once 'config/database.php';
include_once 'objects/user.php';

//database connection
$database = new Database();
$db = $database->getConnection();

$user = new User($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// set product property values
$user->name = $data->name;
$user->email = $data->email;
$user->password = $data->password;

function sendErrResponse($msg){
    // set response code
    http_response_code(400);

    // display message: $msg
    echo json_encode(array("message" => $msg));
}

//creating user
if(!empty($user->name) &&
!empty($user->email) &&
!empty($user->password) ){
    if(!$user->emailExists()){
        if($user->create()){
            // set response code
            http_response_code(200);
        
            echo json_encode(array("message"=>"user was created"));
        }else{
            sendErrResponse("Somethig went wrong, unable to create user.");
        }
    }
    //if user exist with given email
    else{
        sendErrResponse("User with email id ".$user->email." already exist"); 
    }
    
}else{
    sendErrResponse("Unable to create user.");
}
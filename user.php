<?php
header("Access-Control-Allow-Origin: *");

require('controller/controller_user.php');

if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
    $_POST = json_decode(file_get_contents("php://input"), true);
}

$funcao = $_POST['funcao'];

if( $funcao === 'login' ){
    call_user_func( $funcao,
        $_POST['login'],
        $_POST['password']);
}

if( $funcao === 'cadastro' ){
    call_user_func( $funcao, 
        $_POST['login'],
        $_POST['password'],
        $_POST['nome']
    );
}

if( $funcao === 'checkLogin' ){
    call_user_func( $funcao, 
        $_POST['token']        
    );
}

return;

function cadastro( String $login, String $password, String $nome )
{   
   $result = User::create( $login, $password, $nome );
   echo json_encode($result);
}

function login( String $login, String $password )
{   
   $result = User::login( $login, $password );
   echo json_encode($result);
}


function checkLogin( String $token )
{
    $result = User::isLoggin( $token );
    echo json_encode($result);
}
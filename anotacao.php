<?php
header("Access-Control-Allow-Origin: *");

require('controller/controller_anotacao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_POST = json_decode(file_get_contents("php://input"), true);
}

$funcao = $_POST['funcao'];

if ($funcao === 'gravar') {
    call_user_func(
        $funcao,
        $_POST['codigo'],
        $_POST['titulo'],
        $_POST['conteudo'],
        $_POST['usuario']
    );
}

if ($funcao === 'carregaAnotacao') {
    call_user_func(
        $funcao,
        $_POST['usuario']
    );
}

if ($funcao === 'excluirAnotacao') {
    call_user_func(
        $funcao,
        $_POST['codigo']
    );
}

    return;

function gravar(Int $codigo,String $titulo, String $conteudo, String $usuario)
{
    $result = Anotacao::gravar($codigo,$titulo, $conteudo,$usuario);
    echo json_encode($result);
}

function carregaAnotacao( String $token )
{
    $result = Anotacao::anotacoes($token);
    echo json_encode($result);
}

function excluirAnotacao( String $codigo )
{
    $result = Anotacao::excluir($codigo);
    echo json_encode($result);
}

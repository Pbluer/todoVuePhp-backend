<?php

require('connection.php');

class DataBase
{
    static private $servidor = SERVIDOR;
    static private $usuario = USUARIO;
    static private $senha = SENHA;
    static private $banco = BANCO;

    static private function connection(){

        $usuario = self::$usuario;
        $senha = self::$senha;
        $servidor = self::$servidor;
        $banco = self::$banco;

        try{
            
            $pdo = new PDO("mysql:host=$servidor;dbname=$banco", $usuario, $senha);
            
            $pdo->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC                        
            );
            
            $pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION                         
            ); 
        
        }catch( Exception $e ){
            echo $e->getMessage();
        }

        return $pdo;
    }

    static function runQuery( String $query ): Array
    {
        $conn = self::connection();

        try{
            $result = $conn->query( $query );
        }catch(Exception $e){
            $codigo = $e->getCode();   
            
            return [
                'status' => 'error',
                'codigo' => $codigo
            ];

        }
        
        $rows = $result->fetchAll();

        return $rows;
    }

    static private function injectionSql( $query ){
        return preg_replace( '(.*?[=|;].*)' ,'', $query );
    }

};
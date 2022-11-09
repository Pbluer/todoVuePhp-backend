<?php


require("./dataBase/dataBase.php");

class User {

    static function create( String $login, String $password, String $nome ): Array
    {
        $passwordHash = md5( $password );
        $data = date('Y-m-d H:i:s');
        $userToken = md5( uniqid( ( $data . $login ), true ) );
        
        $login = formatString( $login );
        $data = formatString( $data );
        $userToken = formatString( $userToken );
        $nome = formatString( $nome );
        $passwordHash = formatString( $passwordHash );

        $sql = "INSERT INTO user( login,password,nome,create_at, user_token )
            VALUES ( $login,$passwordHash,$nome,$data,$userToken)";

        $result = DataBase::runQuery( $sql );

        if( $result['codigo'] ){
            $codigo = ( Int ) $result['codigo'];

            $result = match( $codigo ){                 
                '23000' => [
                    'status' => 'error',
                    'mensage' => 'Usuário já cadastrado'
                ],
                default => [
                    'status' => 'error',
                    'mensagem' => $codigo
                ]
            };           

        }else{
            return [
                'status' => 'success',
                'mensage' => 'Cadastro efetuado com sucesso'
            ];
        }
        
        return $result;
    }

    static function login( String $login, String $password ): Array
    {
        $passwordHash = md5( $password );
        $data = date('Y-m-d H:i:s');

        
        $login = formatString( $login );
        $passwordHash = formatString( $passwordHash );
        
        $sql = "SELECT codigo,nome,password FROM user WHERE login = $login";
        $result = DataBase::runQuery( $sql );
        
        if( $row = $result[0] ){
            
            $codigo = (Int) $row['codigo'];
            
            
            if( md5($password) === $row['password'] ){

                $loginToken = md5( uniqid( $data, true ) );
                
                $sql = "UPDATE user SET login_token = '$loginToken' WHERE codigo = $codigo";
                $result = DataBase::runQuery( $sql );
                
                if( !count( $result ) ){
                    return [
                        'status' => 'success',
                        'mensage' => 'Acesso efetuado com sucesso',
                        'token' => "$loginToken"
                    ];
                }
            }else{
                return [
                    'status' => 'error',
                    'mensage' => 'Senha incorreta'
                ];
            }


        }else{
            return [
                'status' => 'error',
                'mensage' => 'Usuário não cadastrado',
            ];
        }



        return $result;
    }


    static function isLoggin( String $token ): Array
    {        
        $token = formatString( $token );

        $sql = "SELECT codigo,nome FROM user WHERE login_token = $token";
        $result = DataBase::runQuery( $sql );
        
        if( $result[0] ){    
            return [
                'status' => 'success'
            ];                        

        }
        
        return [
            'status' => 'error'
        ];
        
    }

}

function formatString( String $value ): String 
{
    if( $value === null ){
        return "NULL";
    }

    return '\'' . $value . '\'';
}
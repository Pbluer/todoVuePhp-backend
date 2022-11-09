<?php


require("./dataBase/dataBase.php");

class Anotacao {

    static function gravar( Int $codigo, String $titulo, String $conteudo, String $token ): Array
    {
        $data = date('Y-m-d H:i:s');
        $titulo = formatString( $titulo );
        $conteudo = formatString( $conteudo );
        $token = formatString( $token );
        $data = formatString( $data );

        $sql = "SELECT codigo FROM user	WHERE login_token = $token";
        $result = DataBase::runQuery( $sql );

        if( $row = $result[0] ){
            $usuario = (Int) $row['codigo'];
        }
        
        if( $codigo ){
            $sql = "UPDATE anotacao SET 
                titulo = $titulo,
                conteudo = $conteudo,
                usuario = $usuario,
                dataAlteracao = $data
                WHERE codigo = $codigo";
        }else{
            $sql = "INSERT INTO anotacao( titulo,conteudo,usuario,dataAlteracao)
                VALUES ( $titulo,$conteudo,$usuario,$data)";
        }

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

    static function anotacoes( String $token ): Array
    {
        $token = formatString( $token );

        $sql = "SELECT AN.codigo,AN.titulo,AN.conteudo,AN.dataAlteracao FROM todovuephp.anotacao AN
        INNER JOIN todovuephp.user US ON US.login_token = $token
        WHERE AN.usuario = US.codigo  ORDER BY AN.dataAlteracao ";
        $result = DataBase::runQuery( $sql );

        if( count( $result ) > 0 ){
            return $result;
        }
    }

    static function excluir( Int $codigo){
        
        $codigo = (Int) $codigo;

        $sql = "DELETE FROM todovuephp.anotacao WHERE codigo = $codigo";
        $result = DataBase::runQuery( $sql );

        if( count($result ) < 1 ){
            return [
                'status' => 'success',
                'mensage' => 'Anotação excluida com sucesso.'
            ];
        }
    }
}

function formatString( String $value ): String 
{
    if( $value === null ){
        return "NULL";
    }

    return '\'' . $value . '\'';
}
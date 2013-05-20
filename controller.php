<?php
var_dump($_FILES);
var_dump($_SESSION);

ini_set('display_errors',1);

require_once './Gramatica.php';

define('APPLICATION_DIRECTORY', getcwd());

//Verifica se já tem um arquivo na sessão
if (isset($_SESSION) && isset($_SESSION['file']) && file_exists($_SESSION['file'])){
    
    //Interpreta o arquivo
    $gramatica = new Gramatica();
    $gramatica->leDoArquivo($_SESSION['file']['arquivoGramatica']);
    
}
//Neste caso o usuário não enviou nada ainda, temos que processar tudo
else{
    $newFileName = APPLICATION_DIRECTORY . "/uploads/gramatica.txt";
    if (moveArquivoGramatica($newFileName)){
        
        //Interpreta o arquivo
        $gramatica = new Gramatica();
        $gramatica->leDoArquivo($newFileName);
        
        //Coloca na sessão o arquivo lido
        $_SESSION['file']['arquivoGramatica'] = $newFileName;
    }else{
        echo 'erro';
    }
}



function moveArquivoGramatica($newFileName){
    //Verifica as informações do arquivo
    if ($_FILES['file']['error'] == 0){
        if ($_FILES['file']['type'] == "text/plain"){

            //Move o arquivo para a pasta de uploads
            
            if (move_uploaded_file($_FILES['file']['tmp_name'], $newFileName)){
                return true;
            }else{
                //throw Exception
            }
        }else{
            //thor Exception
        }
    }else{
        //throw Exception
    }
    return false;
}

?>

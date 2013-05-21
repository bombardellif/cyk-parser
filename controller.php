<?php
//Bibliotecas Utilizadas
require_once './Gramatica.php';

/**
 * Tratamento do upload de arquivo, move da localização temporária para a final.
 * 
 * @param string $newFileName Nova localização e nome do arquivo movido.
 * @return void
 * @throws LeituraArquivoGramaticaException
 */
function moveArquivoGramatica($newFileName){
    //Verifica as informações do arquivo
    if ($_FILES['file']['error'] == 0){
        if ($_FILES['file']['type'] == "text/plain"){

            //Move o arquivo para a pasta de uploads
            
            if (move_uploaded_file($_FILES['file']['tmp_name'], $newFileName)){
                return;
            }else{
                throw new LeituraArquivoGramaticaException("Arquivo não pode ser movido", 30);
            }
        }else{
            throw new LeituraArquivoGramaticaException("Tipo de arquivo inválido", 20);
        }
    }else{
        if ($_FILES['file']['error'] == 4){
            throw new LeituraArquivoGramaticaException("Nenhum arquivo enviado", 10);
        }else{
            throw new LeituraArquivoGramaticaException("Ocorreu um erro ao fazer upload de arquivo. ".print_r($_FILES['file'], true), 20);
        }
    }
}


/**
 * Faz a inicialização da aplicação e excecução principal da lógica de controle
 * @return void
 */
function bootstrap(){
    
    //Neste caso o usuário não enviou nada ainda, temos que processar tudo
    if (isset($_FILES) && isset($_FILES['file']) && isset($_REQUEST['frase']) && is_string($_REQUEST['frase'])){
        
        $newFileName = APPLICATION_DIRECTORY . NOME_ARQUIVO_GRAMATICA;
        
        try{
            //Move Arquivo de Up
            moveArquivoGramatica($newFileName);

            //Interpreta o arquivo
            $gramatica = new Gramatica();
            $gramatica->leDoArquivo($newFileName);
            
            //Envia pra view o nome do arquivo
            $view['arquivo'] = $newFileName;
            
            //Envia pra view o nome do arquivo
            $view['frase'] = $_REQUEST['frase'];

            //Envia pra view a gramatica
            $view['gramatica'] = $gramatica;
            
            //Converte para Chomsky
            $gramaticaChomsky = Chomsky::getChomsky($gramatica);
            
            //
            
            //Aqui testa se a frase faz parte da linguagem (se frase pertence a ACEITA($gramatica))
            

            include 'views/veResultado.php';
            
        }catch (LeituraArquivoGramaticaException $e){
            include 'views/erro.php';
        }catch (ParseArquivoGramaticaException $e){
            include 'views/erro.php';
        }
    }
    //Aqui não enviou nada mostra tela inicial
    else{
        include 'views/index.php';
    }
}

?>

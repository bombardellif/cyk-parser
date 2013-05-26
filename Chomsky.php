<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Classe que implementa os algorítmos de tradução de qualquer gramática livre de contexto para a sua forma normal de Chomsky
 *
 * @author william
 */
class Chomsky {
    
    /**
     * Simplifica a $gramatica, retirando produções vazias (na forma A -> ³), segundo o algorítmo do livro "Linguagens Formais e Autômato" de Paulo Blauth Menezes. Note que a palavra vazia é denotada pela palavra que contem um único símbolo s, s = '³'.
     * Supõe que $gramatica é Livre do Contexto e não a modifica, ou seja, retorna uma nova gramática. Além disso, aceita que a gramática não aceita a palavra vazia (³ não pertence a GERA($gramatica))
     * 
     * @param Gramatica $gramatica A gramática a ser simplificada
     * @return Gramatica Uma nova gramática simplificada, isto é, não modifica a gramática de entrada
     */
    private static function simplificaProducoesVazias(Gramatica $gramatica){
        
        //Clona para não modifica fonte
        $gramaticaSimples = clone $gramatica;
        
        ////////Etapa 1\\\\\\\\
        $V³ = new Set();
        
        foreach ($gramaticaSimples->getProducoes()->getData() as $producao){
            //Verifica se a regra leva a ³ diretamente
            if ($producao[1] == new Palavra('³')){
                //Se sim adiciona o símbolo do lado esquerdo da regra à V³
                $V³ = $V³->union(new Set(array($producao[0])));
            }
        }
        
        //Verifica se leva a ³ indiretamente
        $qtdElementos = 0;
        while($V³->size() > $qtdElementos){
            $qtdElementos = $V³->size();
            //Ve se cada produção gera alguma palavra cujos simbolos estao em V³
            foreach ($gramaticaSimples->getProducoes()->getData() as $producao){
                if ($V³->contains(new Set(array($producao[1])))){
                    //Se sim adicona-o a V³
                    $V³ = $V³->union(new Set(array($producao[0])));
                    //var_dump($producao[0]);
                }
            }
        }
        
        ////////Etapa 2\\\\\\\\
        $P1 = new Set();
        
        foreach ($gramaticaSimples->getProducoes()->getData() as $producao){
            //Verifica se a regra não leva a ³ diretamente
            if ($producao[1] != new Palavra('³')){
                //Se sim adiciona a produção à P1
                $P1 = $P1->union(new Set(array($producao)));
            }
        }
        
        //var_dump($P1->getData());
        //Exclusão de produções vazias
        $qtdElementos = 0;
        while($P1->size() > $qtdElementos){
            $qtdElementos = $P1->size();
            //Ve se cada produção gera alguma palavra com símbolos que geram o vazio
            foreach ($P1->getData() as $p){
                foreach($V³->getData() as $x){
                    if ($p[1]->contem($x->getConteudo()[0]) && $p[1] != $x){
                        //Aqui significa que $p leva a uma palavra que contem símbolos que geram o vazio
                        $novoP = array();
                        $novoP[0] = $p[0];
                        $novoP[1] = $p[1]->remove($x->getConteudo()[0]);
                        //Adiciona a nova regra a P1 (sem X)
                        $P1 = $P1->union(new Set(array($novoP)));
                        break;
                    }
                }
            }
        }
        //var_dump($P1->getData()); exit;
        
        //Novo conjunto de produções (simplificado) da gramática
        $gramaticaSimples->setProducoes($P1);
        
        return $gramaticaSimples;
    }
    
    /**
     * Simplifica a $gramatica, retirando produções que substituem variáveis diretamente (na forma A -> B), segundo o algorítmo do livro "Linguagens Formais e Autômato" de Paulo Blauth Menezes.
     * Supõe que $gramatica é Livre do Contexto e não a modifica, ou seja, retorna uma nova gramática.
     * 
     * @param Gramatica $gramatica A gramática a ser simplificada
     * @return Gramatica A gramática simplificada
     */
    private static function simplificaProducoesSubstituemVariaveis(Gramatica $gramatica){
        
        //Clona para não modifica fonte
        $gramaticaSimples = clone $gramatica;

        //Etapa 1 - Fecho Transitivo de cada Variável
        $fecho = array();
        foreach($gramaticaSimples->getVariaveis()->getData() as $variavel){
            $fecho[$variavel] = new Set();
            
            $pilha = array();
            array_push($pilha, $variavel);
            
            //Imagine que as produções formam um grafo, onde os vértices são as palavras das produções e dois vértices V e W são adjacentes se exisste uma produção (V -> W), assim temos que fazer um caminhamento no grafo adicionando ao fecho por onde passamos.
            while ($v = array_pop($pilha)){
                foreach($gramaticaSimples->getProducoes()->getData() as $p){
                    //Avalia todas as produções que partem de $v, verifica se não leva à própria $variavel, se é do tipo X -> Y e finalmente se já não incluiu no fecho ...
                    if ($p[0] == new Palavra($v) && $p[1] != new Palavra($variavel) && $p[1]->tamanho() == 1 && $gramaticaSimples->getVariaveis()->has((String)$p[1]) && !$fecho[$variavel]->has((String)$p[1])){
                        //Se passou então adiciona ao fecho
                        $fecho[$variavel] = $fecho[$variavel]->union(new Set(array((String)$p[1])));
                        //E adiciona à pilha para ela ser processada
                        array_push($pilha, (String)$p[0]);
                    }
                }
            }   
        }
        
        //Etapa 2 - Exclusão das variáveis que substituem variáveis
        $P1 = new Set();
        foreach($gramaticaSimples->getProducoes()->getData() as $p){
            if ( !$gramaticaSimples->getVariaveis()->has((String)$p[1]) ){
                $P1 = $P1->union(new Set(array($p)));
            }
        }
        
        foreach($gramaticaSimples->getVariaveis()->getData() as $A){
            foreach($fecho[$A]->getData() as $B){
                foreach($gramaticaSimples->getProducoes()->getData() as $p){
                    if ($p[0] == new Palavra($B) && !$gramaticaSimples->getVariaveis()->has($p[1])){
                        $newP = array();
                        $newP[0] = new Palavra($A);
                        $newP[1] = $p[1];
                        $P1 = $P1->union(new Set(array($newP)));
                    }
                }
            }
        }
        
        //Novas produções, gramática está simplificada
        $gramaticaSimples->setProducoes($P1);
        
        return $gramaticaSimples;
    }
    
    /**
     * Retorna um prefixo disponível para criação de novas variáveis na dada gramática
     * dado um padrão de sufixo (em preg) e um caracter de prefixo incial.
     * @param Gramatica $gramatica
     * @param string $charIni   Caracter incial para o prefixo
     * @param string $regex     Padrão preg para o texto que deve ser livre após o prefixo
     * @return string   Prefixo para criação de novas variáveis
     */
    private static function prefixoParaVariaveis(Gramatica $gramatica, $charIni, $regex) {
        $iPre = ord($charIni);
        
        $continuar = true;
        while ($continuar) {
            $continuar = true;
            $p = chr($iPre++);
            foreach ($gramatica->getVariaveis()->getData() as $v) {
                if (preg_match('/^'.$p.$regex.'/', $v) != 1) {
                    $continuar = false;
                    break;
                }
            }
        }
        return $p;
    }
    
    /**
     * Etapa 2 da transformação de uma gramática para a Forma Normal de Chomsky.
     * Transformação do lado direito das produções de comprimento maior ou igual a dois,
     * substituíndo os terminais por variáveis.
     * @param Gramatica $gramatica
     */
    private static function substituiTerminaisPorVariaveis(Gramatica $gramatica) {
        $novaGramatica = clone $gramatica;
        
        $producoes = $novaGramatica->getProducoes();
        $variaveis = $novaGramatica->getVariaveis();
        $terminais = $novaGramatica->getTerminais();
        $prefixo = self::prefixoParaVariaveis($novaGramatica, 'C', '\d+$');
        // Esse map fornece um número inteiro para cada terminal da gramática
        $map = array_combine($terminais->getData(), range(1,$terminais->size()));
        
        // para toda produção com lado direto maior ou igual a 2, faça ...
        $novasProds = new Set();
        $prodsAlteradas = $producoes->getData();
        foreach ($prodsAlteradas as &$p) {
            if (($tam = $p[1]->tamanho()) >= 2) {
                // para todos os símbolos terminais do lado direito da produção, faça ...
                for($r=0; $r<$tam; $r++) {
                    
                    if ($terminais->has($p[1]->getSimbolo($r))) {
                        // Gera nova produção cujo lado esquerdo é uma nova variável
                        // e o lado direito é um número que representa o terminal
                        $novaProducao = array(
                            0 => new Palavra($prefixo . $map[$p[1]->getSimbolo($r)]),
                            1 => new Palavra($p[1]->getSimbolo($r))
                        );
                        // adiciona a nova variável ao conjunto de variáveis da gramática
                        $variaveis = $variaveis->union(new Set(array(
                            (string)$novaProducao[0]
                        )));
                        // Substitui o terminal atual no lado direito da produção $p pela nova variável
                        $p[1]->setSimbolo($r, (string)$novaProducao[0]);
                        // adiciona a nova produção no conjunto de produções da gramática
                        $novasProds = $novasProds->union(new Set(array(
                            $novaProducao
                        )));
                    }
                }
            }
        }
        $novaGramatica->setVariaveis($variaveis);
        $novaGramatica->setProducoes($novasProds->union(new Set($prodsAlteradas)));
        
        return $novaGramatica;
    }
    
    /**
     * Etapas 3. Transformação do lado direito das produções de comprimento maior
     * ou igual a 3, em produçẽs com exatamente duas variáveis.
     * @param Gramatica $gramatica
     */
    private static function reduzTamanhoProducoes(Gramatica $gramatica) {
        $novaGramatica = clone $gramatica;
        
        $producoes = $novaGramatica->getProducoes();
        $variaveis = $novaGramatica->getVariaveis();
        $prefixo = self::prefixoParaVariaveis($novaGramatica, 'D', '\d+$');
        
        $nVar = 1;
        foreach ($producoes->getData() as $p) {
            if (($tam = $p[1]->tamanho()) >= 3) {
                // Gera as novas variáveis necessárias para a gramática na FNC
                    // $p[0] não é nova variável, porém continuará no conjunto. Colocamos ela nessa array
                    // para entrar como primeira variável no loop da geração de novas produções
                $novasVariaveis = array(0 => (string)$p[0]); 
                for ($i=1; $i<$tam-1; $i++) {
                    $novasVariaveis[$i] = $prefixo . $nVar++;
                }
                $variaveis = $variaveis->union(new Set($novasVariaveis));
                
                // Gera novas produções a serem adicionadas à gramática
                $novasProducoes = array();
                for ($j=0; $j<$tam-2;$j++) {
                    
                    $novasProducoes[$j] = array(
                        0 => new Palavra($novasVariaveis[$j]),
                        1 => new Palavra(array(
                                $p[1]->getSimbolo($j),
                                $novasVariaveis[$j+1]
                            )
                        )
                    );
                }
                    // última produção não leva novas variáveis no lado direito (finalizando o encadeamento)
                $novasProducoes[$j] = array(
                    0 => new Palavra($novasVariaveis[$j]),
                    1 => new Palavra(array(
                        $p[1]->getSimbolo($tam-2),
                        $p[1]->getSimbolo($tam-1)
                        )
                    )
                );
                
                $producoes = $producoes->diff(new Set(array($p)))->union(new Set($novasProducoes));
            }
        }
        $novaGramatica->setVariaveis($variaveis);
        $novaGramatica->setProducoes($producoes);
        
        return $novaGramatica;
    }


    /**
     * Transforma a $gramatica em uma gramática equivalente na forma normal de Chomsky, supondo que $gramatica é livre do contexto. Não modifica o objeto recebido por parâmetro.
     * @param Gramatica $gramatica A gramática livre do contexto a ser transformada
     * @return Gramatica a gramática na forma normal de Chomsky
     */
    static function getChomsky(Gramatica $gramatica){
        
        //Simplificações
        $novaGramatica = self::simplificaProducoesVazias($gramatica);
        
        $novaGramatica = self::simplificaProducoesSubstituemVariaveis($novaGramatica);
        
        // Etapa 2 de conversão para FNC
        $novaGramatica = self::substituiTerminaisPorVariaveis($novaGramatica);
        
        // Etapa 2 de conversão para FNC
        $novaGramatica = self::reduzTamanhoProducoes($novaGramatica);
        
        return $novaGramatica;
    }
}

?>

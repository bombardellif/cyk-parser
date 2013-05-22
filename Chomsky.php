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
     * Supõe que $gramatica é Livre do Contexto e não a modifica, ou seja, retorna uma nova gramática.
     * 
     * @param Gramatica $gramatica A gramática a ser simplificada
     * @return Gramatica Uma nova gramática simplificada, isto é, não modifica a gramática de entrada
     */
    static function simplificaProducoesVazias(Gramatica $gramatica){
        $gramaticaSimples = clone $gramatica;
        ////////Etapa 1\\\\\\\\
        $V³ = new Set();
        
        foreach ($gramaticaSimples->getProducoes() as $producao){
            //Verifica se a regra leva a ³ diretamente
            if ($producao[1]->contem('³')){
                //Se sim adiciona o símbolo do lado esquerdo da regra à V³
                $V³ = $V³->union(new Set(array($producao[0])));
            }
        }
        //Verifica se leva a ³ indiretamente
        $adicionou = true;
        while($adicionou){
            $adicionou = false;
            //Ve se cada produção gera alguma palavra cujos simbolos estao em V³
            foreach ($gramaticaSimples->getProducoes() as $producao){
                if ($V³->contains(new Set($producao[1]->getConteudo()))){
                    //Se sim adicona-o a V³
                    $V³->union($producao[0]);
                    $adicionou = true;
                }
            }
        }
        
        ////////Etapa 2\\\\\\\\
        $P1 = new Set();
        
        foreach ($gramaticaSimples->getProducoes() as $producao){
            //Verifica se a regra não leva a ³ diretamente
            if ($producao[1] != new Palavra('³')){
                //Se sim adiciona a produção à P1
                $P1 = $P1->union(new Set(array($producao)));
            }
        }
        //Exclusão de produções vazias
        $adicionou = true;
        while($adicionou){
            $adicionou = false;
            //Ve se cada produção gera alguma palavra com símbolos que geram o vazio
            foreach ($P1 as $p){
                foreach($V³ as $x){
                    if ($p[1]->contem($x) && $p[1]->getConteudo()[0] != $x && $p[1]->getConteudo()[$p['1']->tamanho()-1] != $x){
                        //Aqui significa que $p leva a uma palavra que contem símbolos que geram o vazio
                        $novoP = array();
                        $novoP[0] = $p[0];
                        $novoP[0] = $p[1]->remove($x);
                        //Adiciona a nova regra a P1 (sem X)
                        $P1 = $P1->union(new Set(array($novoP[0])));
                        $adicionou = true;
                        break;
                    }
                }
                if ($adicionou){
                    break;
                }
            }
        }
        
        //Novo conjunto de produções (simplificado) da gramática
        $gramaticaSimples->setProducoes($P1);
        
        return $gramaticaSimples;
    }
    
    /**
     * Simplifica a $gramatica, retirando produções que substituem variáveis diretamento (na forma A -> B), segundo o algorítmo do livro "Linguagens Formais e Autômato" de Paulo Blauth Menezes
     * @param Gramatica $gramatica A gramática a ser simplificada
     * @return Gramatica A gramática simplificada
     */
    static function simplificaProducoesSubstituemVariaveis(Gramatica $gramatica){
        
    }
    
    /**
     * Etapa 2 da transformação de uma gramática para a Forma Normal de Chomsky.
     * Transformação do lado direito das produções de comprimento maior ou igual a dois,
     * substituíndo os terminais por variáveis.
     * @param Gramatica $gramatica
     */
    static function substituiTerminaisPorVariaveis(Gramatica $gramatica) {
        $producoes = $gramatica->getProducoes();
        $variaveis = $gramatica->getVariaveis();
        $terminais = $gramatica->getTerminais();
        $prefixo = 'C';  // TODO: DETERMINAR PREFIXO
        
        // para toda produção com lado direto maior ou igual a 2, faça ...
        $novasProds = $producoes->getData();
        foreach ($novasProds as &$p) {
            if (($tam = $p[1]->tamanho()) >= 2) {
                // para todos os símbolos terminais do lado direito da produção, faça ...
                for($r=0; $r<$tam; $r++) {
                    if ($terminais->contains($p[1]->getSimbolo($r))) {
                        // Gera nova produção cujo lado esquerdo é uma nova variável
                        // e o lado direito é o terminal
                        $novaProducao = array(
                            0 => new Palavra($prefixo . $p[1]->getSimbolo($r)),
                            1 => array(
                                new Palavra($p[1]->getSimbolo($r))
                            )
                        );
                        // adiciona a nova variável ao conjunto de variáveis da gramática
                        $variaveis = $variaveis->union(new Set(array(
                            $novaProducao[0]
                        )));
                        // Substitui o terminal atual pela nova variável no lado direito da produção $p
                        $p[1]->setSimbolo($r, $novaProducao[0]);
                        // adiciona a nova produção no conjunto de produções da gramática
                        $producoes = $producoes->union(new Set(array(
                            $novaProducao
                        )));
                    }
                }
            }
        }
        $gramatica->setVariaveis($variaveis);
        $producoes->setData($novasProds);
        $gramatica->setProducoes($producoes);
    }
    
    /**
     * Etapas 3. Transformação do lado direito das produções de comprimento maior
     * ou igual a 3, em produçẽs com exatamente duas variáveis.
     * @param Gramatica $gramatica
     */
    static function reduzTamanhoProducoes(Gramatica $gramatica) {
        $producoes = $gramatica->getProducoes();
        $variaveis = $gramatica->getVariaveis();
        $prefixo = 'D'; // TODO: Determinar Prefixo
        
        foreach ($producoes->getData() as $p) {
            if (($tam = $p[1]->tamanho()) >= 3) {
                // Gera as novas variáveis necessárias para a gramática na FNC
                    // $p[1]->getSimbolo(0) não é nova variável, porém continuará no conjunto. Colocamos ela nessa array
                    // para entrar como primeira variável no loop da geração de novas produções
                $novasVariaveis = array(0 => $p[1]->getSimbolo(0)); 
                for ($i=1; $i<$tam-1; $i++) {
                    $novasVariaveis[$i] = new Palavra($prefixo . $i);
                }
                $variaveis = $variaveis->union(new Set($novasVariaveis));
                
                // Gera novas produções a serem adicionadas à gramática
                $novasProducoes = array();
                for ($j=0; $j<$tam-2;) {
                    $novasProducoes[$j] = array(
                        0 => $novasVariaveis[$j],
                        1 => new Palavra(array(
                                $p[1]->getSimbolo($j),
                                $novasVariaveis[++$j]
                            )
                        )
                    );
                }
                    // última produção não leva novas variáveis no lado direito (finalizando o encadeamento)
                $novasProducoes[$j] = array(
                    0 => $novasVariaveis[$j],
                    1 => array(
                        $p[1]->getSimbolo($tam-2),
                        $p[1]->getSimbolo($tam-1)
                    )
                );
                $producoes = $producoes->diff(new Set(array($p)))->union(new Set($novasProducoes));
            }
        }
        $gramatica->setVariaveis($variaveis);
        $gramatica->setProducoes($producoes);
    }


    /**
     * Transforma a $gramatica em uma gramática equivalente na forma normal de Chomsky, suponde que $gramatica é livre do contexto.
     * @param Gramatica $gramatica A gramática livre do contexto a ser transformada
     * @return Gramatica a gramática na forma normal de Chomsky
     */
    static function getChomsky(Gramatica $gramatica){
        //Simplificações
        $gramatica = self::simplificaProducoesVazias($gramatica);
        
        $gramatica = self::simplificaProducoesSubstituemVariaveis($gramatica);
        
        
        //Faz toda a mágica
        return $gramatica;
    }
}

?>

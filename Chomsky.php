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
     * Supõe que $gramatica é Livre do Contexto.
     * 
     * @param Gramatica $gramatica A gramática a ser simplificada
     * @return Gramatica A gramática simplificada
     */
    static function simplificaProducoesVazias(Gramatica $gramatica){
        //Etapa 1
        $V³ = new Set();
        
        foreach ($gramatica->getProducoes() as $producao){
            //Verifica se a regra leva a ³ diretamente
            if ($producao[1]->contem('³')){
                //Se sim adiciona o símbolo do lado esquerdo da regra à V³
                $V³ = $V³->union(new Set(array($producao[0])));
            }
        }
        //Verifica se leva a ³ indiretamente
        
        while(1){
            foreach ($gramatica->getProducoes() as $producao){
                if ($V³->contains(new Set($producao[1]->getConteudo()))){
                    $V³->union();
                }
            }
        }
            $X = 
            $V³ = $V³->union(new Set(array()));
        
        
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
        foreach ($producoes->getData() as &$p) {
            if (($tam = $p[1]->tamanho()) >= 2) {
                // para todos os símbolos terminais do lado direito da produção, faça ...
                for($r=0; $r<$tam; $r++) {
                    if ($terminais->contains($p[1][$r])) {
                        // Gera nova produção cujo lado esquerdo é uma nova variável
                        // e o lado direito é o terminal
                        $novaProducao = array(
                            0 => new Palavra($prefixo . $p[1][$r]),
                            1 => array(
                                new Palavra($p[1][$r])
                            )
                        );
                        // adiciona a nova variável ao conjunto de variáveis da gramática
                        $variaveis = $variaveis->union(new Set(array(
                            $novaProducao[0]
                        )));
                        // Substitui o terminal atual pela nova variável no lado direito da produção $p
                        $p[1][$r] = $novaProducao[0];
                        // adiciona a nova produção no conjunto de produções da gramática
                        $producoes = $producoes->union(new Set(array(
                            $novaProducao
                        )));
                    }
                }
            }
        }
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
                $novasVariaveis = array();
                for ($i=1; $i<$tam-1; $i++) {
                    $novasVariaveis[] = new Palavra($prefixo . $i);
                }
                $variaveis = $variaveis.union(new Set($novasVariaveis));
                
                $novasProducoesDir = array();
                for ($j=1; $j<$tam-1; $j++){
                    
                }
                $producoes->diff(new Set(array($p)));
            }
        }
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
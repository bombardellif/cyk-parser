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
        
        while(){
            foreach ($gramatica->getProducoes() as $producao){
                if ($V³->contains(new Set($producao[1]->getConteudo()))){
                    $V³->union()
                }
            }
        }
            $X = 
            $V³ = $V³->union(new Set(array()))
        
        
    }
    
    /**
     * Simplifica a $gramatica, retirando produções que substituem variáveis diretamento (na forma A -> B), segundo o algorítmo do livro "Linguagens Formais e Autômato" de Paulo Blauth Menezes
     * @param Gramatica $gramatica A gramática a ser simplificada
     * @return Gramatica A gramática simplificada
     */
    static function simplificaProducoesSubstituemVariaveis(Gramatica $gramatica){
        
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
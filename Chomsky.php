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
     * Supõe que $gramatica é Livre do Contexto e não a modifica, ou seja, retorna uma nova gramática. Além disso, aceita 
     * 
     * @param Gramatica $gramatica A gramática a ser simplificada
     * @return Gramatica Uma nova gramática simplificada, isto é, não modifica a gramática de entrada
     */
    static function simplificaProducoesVazias(Gramatica $gramatica){
        $gramaticaSimples = clone $gramatica;
        
        ////////Etapa 1\\\\\\\\
        $V³ = new Set();
        
        //var_dump($gramaticaSimples->getProducoes()->getData()[14]); exit;
        
        foreach ($gramaticaSimples->getProducoes()->getData() as $producao){
            //Verifica se a regra leva a ³ diretamente
            if ($producao[1] == new Palavra('³')){
                //Se sim adiciona o símbolo do lado esquerdo da regra à V³
                $V³ = $V³->union(new Set(array($producao[0])));
            }
        }
        var_dump($V³->getData());
        //Verifica se leva a ³ indiretamente
        $adicionou = true;
        while($adicionou){
            $adicionou = false;
            //Ve se cada produção gera alguma palavra cujos simbolos estao em V³
            foreach ($gramaticaSimples->getProducoes()->getData() as $producao){
                if ($V³->contains(new Set($producao[1]->getConteudo()))){
                    //Se sim adicona-o a V³
                    $V³->union($producao[0]);
                    $adicionou = true;
                }
            }
        }
        var_dump($V³->getData()); exit;
        
        ////////Etapa 2\\\\\\\\
        $P1 = new Set();
        
        foreach ($gramaticaSimples->getProducoes()->getData() as $producao){
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
            foreach ($P1->getData() as $p){
                foreach($V³->getData() as $x){
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
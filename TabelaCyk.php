<?php

/**
 * <pre>
 * Classe que representa a tabela do algoritmo CYK.
 * A tabela prorpiamente dita é representada por uma array de arrays. Ex.:
 * array(
 *  1 => array(
 *      1 => CELULA (1,1),
 *      2 => CELULA (1,2),
 *      3 => CELULA (1,3)
 *  )
 *  2 => array(
 *      1 => CELULA (2,1),
 *      2 => CELULA (2,2)
 *  )
 *  3 => array(
 *      1 => CELULA (3,1)
 *  )
 * )
 *
 * Essa array representa a seguinte Tabela do CYK (para a palavra "abc"):
 *      (1,3)
 *      (1,2) (2,2)
 *      (1,1) (2,1) (3,1)
 *        a     b     c
 * </pre>
 * 
 * @author fernando
 */
class TabelaCyk {
    
    /**
     * Estrutura que armazena as informações do algoritmo (array de arrays de CelulaCyk).
     * @see CelulaCyk
     * @var array 
     */
    private $tabela;
    
    /**
     * Retorna o elemento de uma celula
     * 
     * @param int $i Índice da coluna
     * @param int $j Índice da linha
     * @return CelulaCyk Celula presente na posição i,j
     */
    public function get($i, $j){
        return isset($this->tabela[$i]) ? isset($this->tabela[$i][$j]) ? $this->tabela[$i][$j] : NULL : NULL;
    }
    
    /**
     * Altera o elemento de uma celula
     * 
     * @param int $i Índice da coluna
     * @param int $j Índice da linha
     * @param CelulaCyk Novo conteúdo na posição i,j
     * @return void
     */
    public function set($i, $j, $celula){
        if (isset($this->tabela[$i])){
            $this->tabela[$i][$j] = $celula;
        }else{
            $this->tabela[$i] = array($j => $celula);
        }
    }

}

?>

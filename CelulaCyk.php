<?php

/**
 * Classe que representa uma célula da tabela do algoritmo CYK.
 *
 * @author fernando
 */
class CelulaCyk {
    
    /**
     * Conjunto de String (variávis da gramática que estão nessa célula).
     * @var Set
     */
    private $variaveis;
    
    /**
     * <pre>
     * Conjunto de arrays. Cada array representa uma Combinação (Tipo Combinacao definido abaixo)
     * Uma combinacao é:
     * NULL, ou
     * array(
     *  0 => array(
     *      0 => Um-Simbolo-A,
     *      1 => Um-Simbolo-B
     *  )
     *  1 => Uma-Simbolo-C
     * )
     * Note que uma combinacao representa uma iteração do algoritmo, uma vez que o item 0 da combinacao
     * contém as duas variáveis do lado direito de uma produção da gramática e o item 1 
     * contém a variável do lado esquerdo da mesma produção. Assim se uma combinacao
     * X = array(
     *      0 => array(A, B),
     *      1 => C
     * ), isto implica que existe uma produção do tipo C -> AB na gramática, onde C,A e B pertencem 
     * ao conjunto de variáveis da gramática.
     * </pre>
     * @var Set
     */
    private $combinacoes;
    /**
     * Array de árvores de derivação correspondentes do parsing até essa célula.
     * @var Array de Arvore
     */
    private $arvores;
    
    function __construct(Set $variaveis = null, Set $combinacoes = null, $arvores = null) {
        $this->variaveis = ($variaveis == null) ? new Set() : $variaveis;
        $this->combinacoes = ($combinacoes == null) ? new Set() : $combinacoes;
        $this->arvores = ($arvores == null) ? null : $arvores;
    }
    
    public function getVariaveis() {
        return $this->variaveis;
    }

    public function setVariaveis(Set $variaveis) {
        $this->variaveis = $variaveis;
    }

    public function getCombinacoes() {
        return $this->combinacoes;
    }

    public function setCombinacoes(Set $combinacoes) {
        $this->combinacoes = $combinacoes;
    }
    
    public function getArvore($id) {
        if(!empty($this->arvores))
        {
            foreach($this->arvores as $a)
            {
                if($id == $a->getId())
                {
                    return $a;
                }
            }
        }
        return null;
    }
    
    public function getArrayArvores() {
        return $this->arvores;
    }

    public function setArrayArvores($arvores) {
        $this->arvores = $arvores;
    }
    
    public function nroArvores() {
        return count($this->arvores);
    }
}

?>

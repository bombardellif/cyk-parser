<?php

/**
 * Classe que representa uma célula da tabela do algoritmo CYK.
 *
 * @author fernando
 */
class CelulaCyk {
    
    /**
     * Conjunto de objetos Palavra (variávis da gramática que estão nessa célula).
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
     *      0 => Uma-Palavra-A,
     *      1 => Uma-Palavra-B
     *  )
     *  1 => Uma-Palavra-C
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
     * Contém uma árvore de derivação correspondete do parsing até essa célula.
     * @var Arvore
     */
    private $subArvore;
    
    function __construct(Set $variaveis, Set $combinacoes = null, Arvore $subArvore = null) {
        $this->variaveis = $variaveis;
        $this->combinacoes = $combinacoes;
        $this->subArvore = $subArvore;
    }

}

?>

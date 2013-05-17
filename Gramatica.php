<?php
/**
 * Classe que representa a definição formal de uma gramatica.
 *
 * @author fernando
 */
class Gramatica {
    /**
     *
     * @var Set Conjunto das variáveis da gramática
     */
    private $variaveis;
    
    /**
     *
     * @var Set Conjunto dos terminais da gramática
     */
    private $terminais;
    
    /**
     *
     * @var Set Conjunto das produções da gramática
     */
    private $producoes;
    
    /**
     *
     * @var Palavra Variável inicial da gramática
     */
    private $inicial;
    
    public function __construct(Set $variaveis, Set $terminais, Set $producoes, Palavra $inicial) {
        $this->variaveis = $variaveis;
        $this->terminais = $terminais;
        $this->producoes = $producoes;
        $this->inicial = $inicial;
    }
    
    public function getVariaveis() {
        return $this->variaveis;
    }

    public function setVariaveis(Set $variaveis) {
        $this->variaveis = $variaveis;
    }

    public function getTerminais() {
        return $this->terminais;
    }

    public function setTerminais(Set $terminais) {
        $this->terminais = $terminais;
    }

    public function getProducoes() {
        return $this->producoes;
    }

    public function setProducoes(Set $producoes) {
        $this->producoes = $producoes;
    }

    public function getInicial() {
        return $this->inicial;
    }

    public function setInicial(Palavra $inicial) {
        $this->inicial = $inicial;
    }
    
}

?>

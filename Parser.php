<?php

/**
 * Classe que executa o algoritmo de parsing CYK de uma dada palavra sobre uma dada
 * gramática.
 *
 * @author fernando
 */
class Parser {
    
    /**
     * Gramática geradora da linguagem a analizar.
     * @var Gramatica
     */
    private $gramatica;
    
    /**
     * Aramzena a tabela do algorítmo CYK
     * @var TabelaCyk Tabela do algorítmo
     * @see TabelaCyk
     */
    private $tabela;
    
    /**
     * Construtor da classe
     * @param Gramatica $gramatica
     */
    function __construct(Gramatica $gramatica) {
        $this->gramatica = $gramatica;
    }
    
    /**
     * Executa o algortimo CYK e avalia se a palavra enviada pertende a linguagem aceita 
     * pela gramática.
     * 
     * @param Palavra $palavra Palavra a ser avaliada
     * @return boolean Se a palavra for aceita retorna True, caso contrário retorna False
     */
    public function parse(Palavra $palavra){
        //Etapa 1: geração da primeira linha da tabela (variáveis que geram terminais) (folhas da árvore)
        $n = $palavra->tamanho();
        for ($i = 1; $i <= $n; $i++){
            $set = new Set();
            foreach ($this->gramatica->getTerminais()->getData() as $t){
                if (((String)$t) == $palavra->getSimbolo($i)){
                    $set = $set->union(new Set(array($t)));
                }
            }
            $this->tabela->set($i, 1, new CelulaCyk($set));
        }
        
        //Etapa 2
        
    }
    
    /**
     * Retorna todas as árovores de derivação (afora de isomorfismos) que partem 
     * do símbolo inicial e derivam toda a palavra.
     * Supõe que o método parse foi executado previamente, para que existam árvores a ser retornado
     * 
     * @return mixed Array de Árvores ou NULL caso vazia
     * @see Arvore
     */
    public function getArvoresDerivacao(){
        // TODO
    }
    
    /**
     * Retorna a tabela CYK preenchida durante a última execução do método parse.
     * Supõe que o método parse foi executado previamente, para que exista a tabela
     * 
     * @return mixed TabelaCYK ou NULL, caso de vazio
     * @see TabelaCyk
     */
    public function getTabelaCYK(){
        return $this->tabela;
    }
    

}

?>

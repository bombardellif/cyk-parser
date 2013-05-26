<?php

require_once 'Gramatica.php';
require_once 'TabelaCyk.php';

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
        $this->tabela = new TabelaCyk();
    }
    
    /**
     * Executa o algortimo CYK e avalia se a palavra enviada pertende a linguagem aceita 
     * pela gramática.
     * 
     * @param Palavra $palavra Palavra a ser avaliada
     * @return boolean Se a palavra for aceita retorna True, caso contrário retorna False
     */
    public function parse(Palavra $palavra){
        //Etapa 1: geração da primeira linha da tabela (variáveis que geram terminais)
        $n = $palavra->tamanho();
        for ($i = 1; $i <= $n; $i++){
            $set = new Set();
            foreach ($this->gramatica->getTerminais()->getData() as $t){
                if ($t == $palavra->getSimbolo($i-1)){
                    foreach ($this->gramatica->getProducoes()->getData() as $p) {
                        if (((string)$p[1]) == $t) {
                            $set = $set->union(new Set(array((string)$p[0])));
                        }
                    }
                    break;
                }
            }
            $this->tabela->set($i, 1, new CelulaCyk($set));
        }
        
        //Etapa 2
        for ($s=2; $s<=$n; $s++) {
            for ($r=1, $lim=$n-$s+1; $r<=$lim; $r++) {
                $this->tabela->set($r, $s, new CelulaCyk());
                // Repete a "roldana"
                for ($k=1; $k<=$s-1; $k++) {
                    // Itera nas variáveis de uma célula na coluna abaixo dessa célula
                    foreach ($this->tabela->get($r, $k)->getVariaveis()->getData() as $Vrk) {
                        // Itera nas variáveis de uma célula na diagonal abaixo e a esquerda dessa célula
                        foreach ($this->tabela->get($r+$k, $s-$k)->getVariaveis()->getData() as $Vrksk) {
                            // Itera nas Produções
                            foreach ($this->gramatica->getProducoes()->getData() as $p) {
                                if ($p[1] == new Palavra(array($Vrk, $Vrksk))) {
                                    $celula = $this->tabela->get($r, $s);
                                    // Une a variável do lado esquerdo dessa produção ao conjunto das variáveis
                                    // contidas na célula atual
                                    $celula->setVariaveis(
                                            $celula->getVariaveis()->union(new Set(array((String)$p[0]))));
                                    // Une no conjunto de combinações, dessa célula, uma nova combinação formada 
                                    // a partir da regra que acabamos de encontrar
                                    $celula->setCombinacoes(
                                            $celula->getCombinacoes()->union(
                                                    new Set(array(
                                                        array(
                                                            0 => array($Vrk, $Vrksk),
                                                            1 => array((String)$p[0])
                                                        )
                                                    ))
                                            )
                                    );
                                    // Une no conjunto de sub-árvores dessa célula, uma nova árvore, que contém,
                                    // como informação, a variável p[0], que deriva outras duas variáveis (Vrk e Vrksk),
                                    // representadas pelas sub-árvores filhas da esquerda e da direita, respectivamente.
                                }
                            }
                        }
                    }
                }
            }
        }
        
        //Etapa 3
        //Se  tiver o inicial na "raiz" da tabela a palavra foi aceita
        return $this->tabela->get(1, $n)->getVariaveis()->has($this->gramatica->getInicial());
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

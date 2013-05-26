<?php
/**
 * Classe para representar o tipo de dados "Conjunto".
 *
 * @author fernando
 */
class Set {
    
    /**
     *
     * @var array   Array dos dados do conjunto
     */
    private $data;
    
    /**
     * 
     * @param array $data   Array Inicial para o conjunto (não verifica se há valores duplicados)
     */
    public function __construct(Array $data = array()) {
        $this->data = $data;
    }


    /**
     * 
     * @return Array    A array com os dados
     */
    public function getData() {
        return $this->data;
    }
    
    /**
     * 
     * @param array $data A array com os dados (não verifica se há valores duplicados)
     */
    public function setData(Array $data) {
        // array_values: devolve uma array cujos índices são numéricos começando em zero.
        $this->data = array_values($data);
    }
    
    /**
     * 
     * @return int  Cardinalidade do conjunto
     */
    public function size() {
        return count($this->data);
    }
    
    /**
     * 
     * @param Set $set  Conjunto para realizar a instersecção com este
     * @return Set  Conjunto após realizada a intersecção
     */
    public function intersect(Set $set) {
        return new Set(array_uintersect($this->data, $set->getData(), "Set::compareElems"));
    }
    
    /**
     * 
     * @param Set $set  Conjunto para realizar a união com este
     * @return Set  Conjunto resultante da união deste com o do parâmetro.
     */
    public function union(Set $set) {
        return new Set(array_unique(array_merge($this->data, $set->getData()), SORT_REGULAR));
    }
    
    /**
     * <p>Função usada como callback para outras funções do php.
     * Retorna um número menor que zero se $a < $b, zero se $a == $b, e um número
     * maior que zero se $a > b.</p>
     * <p>Foi construída para poder comparar duas arrays (independente de seus tamanhos)
     * , assim como uma array com outro tipo de dado (desde que possível de  realizar 
     * casting para string).</p>
     * <p>Notar que só funciona para arrays com índices numerados a partir de 0 de
     * forma crescente, incrementados em 1.</p>
     * @param mixed $a  Elemento a comparar
     * @param mixed $b  Elemento a comparar
     * @return int      
     */
    public static function compareElems($a, $b) {
        if (is_array($a) || is_array($b)) {
            // Se ao menos uma for um array, compara ellemento a elemento recursivamente
            // realiza casting dos dois parâmetros para array, pois se um deles já não for,
            // então o casting criará um array onde o elemento 0 é o elemento "casted"
            $a = (array)$a;
            $b = (array)$b;
            $tamA = count($a); 
            $tamB = count($b);
            $limite = min(array($tamA, $tamB));
            // verifica elemento a elemento recursivamente a comparação dos arrays
            // enquanto forem iguais ou chegar ao final da menor em tamanho.
            // Se chegar ao final do menor array, então retorna a última comparação,
            // Se chegar em uma comparação de elementos diferente, então não precisa
            // continuar a comparação, retorna o valor dessa última comparação.
            $retorno = 0;
            for ($i=0; $i<$limite; $i++) {
                if (($retorno = Set::compareElems($a[$i], $b[$i])) != 0) {
                    break;
                }
            }
            return $retorno;
        } else {
            return strcmp((string)$a, (string)$b);
        }
    }
    
    /**
     * 
     * @param Set $set  Conjunto para subtrarir com este (ou seja, o operador da
     *                  direta da operação)
     * @return Set
     */
    public function diff(Set $set) {
        return new Set(array_udiff($this->data, $set->getData(), "Set::compareElems"));
    }
    
    /**
     * 
     * @param mixed $element    Elemento para verificar se pertence a este conjunto
     * @return bool True se o elemento pertence ao conjunto, False caso contrário.
     */
    public function has($element) {
        return in_array($element, $this->data);
    }
    
    /**
     * Operador de contenção de conjuntos.
     * @param Set   $subSet  Conjunto a testar se está contido neste.
     * @return bool True se este conjunto contém o conjunto enviado no parâmetro 
     * ($subSet), False caso contrário.
     */
    public function contains(Set $subSet) {
        //A contem B, se e somente se, (A e B) - B = 0
        //return array_diff(array_intersect($this->data, $subSet->getData()), $subSet->getData()) == array();
        // O codigo acima não funciona (maneira antiga)
        // =================
        // A contém B, se e somente se, B - A = 0
        return array_udiff($subSet->getData(), $this->data, "Set::compareElems") == array();
    }
    
    public function __clone() {
        foreach ($this->data as $k=>$d) {
            if (is_object($d)) {
                $this->data[$k] = clone $d;
            }
            if (is_array($d)) {
                // Isso é uma gambiarra
                $this->data[$k] = unserialize(serialize($d));
            }
        }
    }
}

?>

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
        $this->data = $data;
    }
    
    /**
     * 
     * @return int  Cardinalidade do conjunto
     */
    public function size() {
        return count($this->data);
    }
    
    /**
     * Realiza uma comparação básica entre dois objetos.
     * @param mixed $a
     * @param mixed $b
     * @return int Retorna 0 se os valores $a e $b são igual ($a == $b), 1 caso contrário
     */
    static public function compare($a, $b) {
        return ($a == $b) ? 0 : 1;
    }
    
    /**
     * 
     * @param Set $set  Conjunto para realizar a instersecção com este
     * @return Set  Conjunto após realizada a intersecção
     */
    public function intersect(Set $set) {
        
        return new Set(array_uintersect($this->data, $set->getData(), "Set::compare"));
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
     * 
     * @param Set $set  Conjunto para subtrarir com este (ou seja, o operador da
     *                  direta da operação)
     * @return Set
     */
    public function diff(Set $set) {
        return new Set(array_diff($this->data, $set->getData(), "Set::compare"));
    }
    
    /**
     * 
     * @param mixed $element    Elemento para verificar se pertence a este conjunto
     * @return bool True se o elemento pertence ao conjunto, False caso contrário.
     */
    public function belongs($element) {
        return in_array($element, $this->data);
    }
    
    /**
     * Operador de contenção de conjuntos.
     * @param Set   $subSet  Conjunto a testar se está contido neste.
     * @return bool True se este conjunto contém o conjunto enviado no parâmetro 
     * ($subSet), False caso contrário.
     */
    public function contains(Set $subSet) {
        return array_uintersect($this->data, $subSet->getData(), "Set::compare") == $subSet->getData();
    }    
}

?>

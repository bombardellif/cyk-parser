<?php
/**
 * Classe que representa uma palavra da linguagem.
 * É basicamente um array de strings, onde cada elemento do array é considerado
 * indivisível, portanto sendo considerado um símbolo do alfabeto, muito embora
 * essa string não tenha que obrigatoriamente conter somente um caracter.
 *
 * @author fernando
 */
class Palavra {
    /*
     * 
     * @var array   Conteúdo da palavra (sempre é armazenado como um array)
     */
    private $palavra;
    
    /**
     * <pre>
     * Contrutor da classe, inicializa a palavra.
     * Aceita como parâmetro tanto uma string ou um array de strings.
     * Ex.:
     *  new Palavra('the')
     *  new Palavra('A')
     *  new Palavra(array('X', 'Y'))
     * @param mixed $palavra
     */
    public function __construct($palavra) {
        $this->palavra = (array)$palavra;
    }
    
    /**
     * Representação String da Palavra.
     * @return string Símbolos da Palavra separados por vírgula
     */
    public function __toString() {
        return implode(',', $this->palavra);
    }
    
    /**
     * Retorna o conteúdo propriamente da palavra
     * @return array Array de Símbolos (Array de Strings) que representa a palavra
     */
    public function getConteudo(){
        return $this->palavra;
    }
    
    /**
     * Dado um índice, retorna o símbolo desta palavra nesse índice.
     * @param int $i    Índice da palavra
     * @return string   Símbolo no índice
     */
    public function getSimbolo($i) {
        return $this->palavra[$i];
    }
    
    /**
     * Escreve um símbolo nesta palavra no índice especificado por $i.
     * @param int $i    Índice da palavra
     * @param string $value Símbolo a ser gravado no índice
     */
    public function setSimbolo($i, $value) {
        $this->palavra[$i] = $value;
    }
    
    /**
     * Verifica se esta palavra contem $simbolo, isto é, verifica se a string $simbolo é pertencente à palavra ( ou ainda existe a ocorrência de pelo menos uma instancia de $simbolo na palavra)
     * 
     * @param string $simbolo String que representa um símbolo a ser procurado
     * @return boolean True se encontrou pelo menos um $simbolo na palavra, False caso contrário
     */
    public function contem($simbolo){
        return in_array($simbolo, $this->palavra);
    }
    
    /**
     * Remove todas as ocorrências de $simbolo na palavra e retorna a nova palavra modificada, sem salvar a alteração na palavra do objeto.
     * 
     * @param string $simbolo String que representa um símbolo a ser removido
     * @return Palavra a palavra resultado
     */
    public function remove($simbolo){
        $newArray = array_diff($this->palavra, array($simbolo));
        return new Palavra(array_combine(range(0, count($newArray)-1), $newArray));
    }
    
    /**
     * Retorna o tamanho da palavra (quantidade de Strings na Palavra).
     * @return int  Tamanho da palavra
     */
    public function tamanho() {
        return count($this->palavra);
    }
}

?>

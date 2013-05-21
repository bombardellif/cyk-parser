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
    
    
    public function getConteudo(){
        return $this->palavra;
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
}

?>

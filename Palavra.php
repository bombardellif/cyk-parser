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
}

?>

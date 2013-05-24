<?php

/**
 * Necesita de Set para implementar os conjuntos
 */
require_once './Set.php';

/**
 * Necesita de Palavra para implementar gramática
 */
require_once './Palavra.php';

/**
 * Arvores para a derivação
 */
require_once './arvore.php';

/**
 * Exceções Utilizadas
 */
require_once './LeituraArquivoGramaticaException.php';
require_once './ParseArquivoGramaticaException.php';


/**
 * Classe que representa a definição formal de uma gramatica.
 *
 * @author fernando
 */
class Gramatica {
    /**
     *
     * @var Set Conjunto das variáveis da gramática (Set de Símbolos)
     */
    private $variaveis;
    
    /**
     *
     * @var Set Conjunto dos terminais da gramática (Set de Símbolos)
     */
    private $terminais;
    
    /**
     *
     * @var Set Conjunto das produções da gramática, uma produção é Array de dois elementos do modelo: array([0] => X, [1] => Y), onde X é uma palavra de um único símbolo (Livre do contexto) e Y é uma palavra qualquer.
     * Note também que uma regra X -> A B C ... é denotada por array([0] => new Palavra('X'), [1] => new Palavra(array('A','B','C', ...)), ou seja, abstraindo teríamos: array([0] => X, [1] => ABC)
     */
    private $producoes;
    
    /**
     *
     * @var Palavra Variável inicial da gramática, palavra de um único símbolo
     */
    private $inicial;
        
    public function __construct(Set $variaveis = null, Set $terminais = null, Set $producoes = null, Palavra $inicial = null) {
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
    
    /**
     * Lê o arquivo fonte da gramática, inteepreta e configura o objeto de acordo com os dados lidos, supõe que a gramática é livre de contexto.
     * Formato esperado do arquivo, dado que Ti são símbolos que representam os terminais (cada símbolo é uma string), 
     * Vi são símbolos que representam as variáveis, S é a variável inicial, V são variáveis, W e Y são variáveis ou terminais
     * Terminais
     * { T1, T2, ..., Tn }      #O hash separa os comentários
     * Variaveis
     * { V1, V2, ..., Vn }      #Tudo que ocorre depois do # é desconsiderado
     * Inicial
     * { S }
     * Regras
     * { V > W , Y }
     * ...
     * 
     * Note que cada regra é colocada em uma linha diferente, isto é, um "\r\n" ou "\n" separa cada regra
     * Se existe uma regra X -> YZ na gramática, ela será representada por { X > Y , Z}, note que apenas um símbolo é permitido antes da seta ('>'), pois supõe que a gramática e livre do contexto. Não existe limite para símbolos após a seta.
     * 
     * @param String $nomeArquivo Caminho estático ao arquivo fonte da gramática
     * @return void
     * @throws LeituraArquivoGramaticaException, ParseArquivoGramaticaException
     */
    public function leDoArquivo($nomeArquivo){
        
        $conteudoArquivo = file($nomeArquivo);
        if ($conteudoArquivo){
            
            //Leitura dos Terminais
            if ($this->isLinhaDef("Terminais", $conteudoArquivo[0])){
                if ($this->isLinhaSet($conteudoArquivo[1])){
                    $terminais = $this->criaSet($conteudoArquivo[1]);
                    assert(!is_null($terminais) && count($terminais->getData()) > 0);
            
                    //Leitura das variáveis
                    if($this->isLinhaDef("Variaveis", $conteudoArquivo[2])){
                        if ($this->isLinhaSet($conteudoArquivo[3])){
                            $variaveis = $this->criaSet($conteudoArquivo[3]);
                            assert(!is_null($variaveis) && count($variaveis->getData()) > 0);
                            
                            //Leitura do Inicial
                            if ($this->isLinhaDef("Inicial", $conteudoArquivo[4])){
                                if ($this->isLinhaSimbolo($conteudoArquivo[5])){
                                    $inicial = $this->criaPalavra($conteudoArquivo[5]);
                                    assert(!is_null($inicial) && count($inicial->getConteudo()) == 1); //GLC
                                    
                                    //Leitura de Regras
                                    if ($this->isLinhaDef("Regras", $conteudoArquivo[6])){
                                        $i = 7;
                                        $regras = new Set();
                                        
                                        
                                        //var_dump($this->isLinhaRegra($conteudoArquivo[16])); 
                                        //var_dump ($this->criaRegra($conteudoArquivo[16])); exit;
                                        while($i < count($conteudoArquivo) && $this->isLinhaRegra($conteudoArquivo[$i])){
                                            $regras = $regras->union(new Set(array($this->criaRegra($conteudoArquivo[$i]))));
                                            $i++;
                                        }
                                        
                                        if (count($regras->getData()) <= 0){
                                            throw new ParseArquivoGramaticaException("Conjunto de regras inexistente ou vazio", 41);
                                        }
                                    }else{
                                        throw new ParseArquivoGramaticaException("Seção de regras não encontrada", 40);
                                    }
                                }else{
                                    throw new ParseArquivoGramaticaException("Símbolo inicial inexistente", 31);
                                }
                            }else{
                                throw new ParseArquivoGramaticaException("Seção de símbolo inicial não encontrada", 30);
                            }
                        }else{
                            throw new ParseArquivoGramaticaException("Conjunto de variáveis inexistente ou vazio", 21);
                        }
                    }else{
                        throw new ParseArquivoGramaticaException("Seção de variáveis não encontrada", 20);
                    }
                }else{
                    throw new ParseArquivoGramaticaException("Conjunto de terminais inexistente ou vazio", 11);
                }
            }else{
                throw new ParseArquivoGramaticaException("Seção de terminais não encontrada", 10);
            }
            $this->terminais = $terminais;
            $this->variaveis = $variaveis;
            $this->inicial = $inicial;
            $this->producoes = $regras;
            
        }else{
            throw new LeituraArquivoGramaticaException("Conteúdo do arquivo vazio ou não pode ser lido");
        }
    }
    
    /**
     * Verifica se a linha é de definição de escopo do arquivo de gramática. Por exemplo "Terminais   #Comentario" é um linha de definição.
     * 
     * @param string $stringDef String que define o escopo (p.e. "Terminais")
     * @param string $linha Linha do arquivo a ser avalidada.
     * @return boolean True se a linha é do modelo "$stringDef   # XXXXX", False caso contrário 
     */
    private function isLinhaDef($stringDef, $linha){
        $regex = "/^$stringDef\s*((\s#).*)?/";
        return preg_match($regex, $linha) == 1;
    }
    
    /**
     * Verifica se a linha é de definição de um conjunto. Por exemplo "{ A, B , C}    #Comentario" é definição de um conjunto.
     * 
     * @param string $linha Linha do arquivo a ser avalidada.
     * @return boolean True se a linha é do modelo "{A, B , C,D, ...}   # XXXXX", False caso contrário 
     */
    private function isLinhaSet($linha){
        $regex = "/^(({\s*})|({\s*((?![,{}\b\n\r\s#]).)+(\s*,\s*((?![,{}\b\n\r\s#]).)+)*\s*}))\s*((\s#).*)?$/";
        return preg_match($regex, $linha) == 1;
    }
    
    /**
     * Verifica se a linha é de definição de um conjunto com um único elemento, ou seja um único símbolo. Por exemplo "{ S }   #Comentario" é definição de um conjunto com um único símbolo.
     * 
     * @param string $linha Linha do arquivo a ser avalidada.
     * @return boolean True se a linha é do modelo "{S}   # XXXXX", False caso contrário 
     */
    private function isLinhaSimbolo($linha){
        $regex = "/^({\s*((?![,{}\b\n\r\s#]).)+\s*})\s*((\s#).*)?$/";
        return preg_match($regex,$linha) == 1;
    }
    
    /**
     * Verifica se a linha é de definição de uma regra. Por exemplo "{ S > V, W }   #Comentario" é definição de uma regra.
     * 
     * @param string $linha Linha do arquivo a ser avalidada.
     * @return boolean True se a linha é do modelo "{ S > V, W, ..., Z }  # XXXXX", False caso contrário 
     */
    private function isLinhaRegra($linha){
        $regex = "/^{\s*((?![,{}\b\n\r\s#]).)+\s*>\s*((?![,{}\b\n\r\s#]).)+(\s*,\s*((?![,{}\b\n\r\s#]).)+)*\s*}\s*((\s#).*)?$/";
        return preg_match($regex,$linha) == 1;
    }
    
    /**
     * Supõe que a linha do parâmetro passou por isLinhaSet, isto é, é uma definição de conjunto. Sabendo disso 
     * constói um Set com cada símbolo lido da linha. Note que cada símbolo é separado por vírgula e o conjunto é 
     * delimitado pelas chaves "{" e "}"
     * 
     * @param string $linha Linha do arquivo a ser avalidada.
     * @return Set Conjunto com os itens lidos da linha (Set de Símbolos)
     * @see isLinhaSet
     */
    private function criaSet($linha){
        $set = new Set();
        
        //Pega só a parte até o fecha chaves, ignorando tudo após isto (espaços e comentários)
        $linha = substr($linha, 0, strpos($linha, "}")+1);
        
        //Pega o primeiro token (símbolo) do conjunto
        $simbolo = trim(strtok($linha,",{}#"));
        //Se $linha = "{ A , B}", espera que $simbolo = "A"
        
        //Processa cada símbolo, adicionando ao conjunto final
        while (is_string($simbolo) && strlen($simbolo) > 0){
            //Cria um conjunto com o símbolo e faz união ao conjunto final
            $set = $set->union(new Set(array($simbolo)));
            
            //Próximo token, espera algo como "B", mesmo que B seja o último símbolo do cojunto
            $simbolo = trim(strtok(",{}#"));
        }
        
        return $set;
    }
    
    /**
     * Supõe que a linha do parâmetro passou por isLinhaSimbolo, isto é, é uma definição de conjunto com um único símbolo. Sabendo disso 
     * constói uma palavra com o símbolo lido (palavra de um único símbolo). Note que o conjunto é delimitado pelas chaves "{" e "}"
     * 
     * @param string $linha Linha do arquivo a ser avalidada.
     * @return Palavra Palavra criada a partir do símbolo lido
     * @see isLinhaSimbolo
     */
    private function criaPalavra($linha){
        //Pega só a parte até o fecha chaves, ignorando tudo após isto (espaços e comentários)
        $linha = substr($linha, 0, strpos($linha, "}")+1);
        
        //Pega o primeiro token (símbolo) do conjunto
        $simbolo = trim(strtok($linha,",{}#"));
        //Se $linha = "{ S }", espera que $simbolo = "S"
        
        return new Palavra($simbolo);
    }
    
    /**
     * Supõe que a linha do parâmetro passou por isLinhaRegra, isto é, é uma definição de conjunto de regras de produção. Sabendo disso 
     * constói uma regra de produção de acorodo com os símbolos da linha. Note que cada símbolo é separado por vírgula, 
     * o bracket '>' delimita o lado esquerdo do direito da produção e o conjunto é delimitado pelas chaves "{" e "}".
     * Note também que uma regra X -> A B C ... é denotada por array([0] => new Palavra('X'), [1] => new Palavra(array('A','B','C', ...)), ou seja, abstraindo teríamos: array([0] => X, [1] => ABC)
     * 
     * @param string $linha Linha do arquivo a ser avalidada.
     * @return array Array de dois elementos do modelo: array([0] => X, [1] => Y), onde X é uma palavra de um único símbolo (Livre do contexto) e Y é uma palavra qualquer, assim retorna um par ordenado que representa a regra lida da $linha
     * @see isLinhaRegra
     */
    private function criaRegra($linha){
        
        //Pega só a parte até o fecha chaves, ignorando tudo após isto (espaços e comentários)
        $linha = substr($linha, 0, strpos($linha, "}")+1);
        
        //Pega o primeiro token (lado esquerdo da produção)
        $palavraEsquerda = trim(strtok($linha,",{>"));
        //Se $linha = "{ A > B, C}", espera que $esquerdo = "A"
        
        //Pega o primeiro token (lado esquerdo da produção)
        $simbolo = trim(strtok(",{}#"));
        
        //Processa cada símbolo, adicionando os ao conjunto final
        while (is_string($simbolo) && strlen($simbolo) > 0){
            //Cria uma array com os simbolos que formam a palavra
            $palavraDireita[] = $simbolo;
            
            //Próximo token, espera algo como "B", mesmo que B seja o último símbolo do cojunto
            $simbolo = trim(strtok(",{}#"));
        }
        
        $regra[0] = new Palavra($palavraEsquerda);
        $regra[1] = new Palavra($palavraDireita);
        return $regra;
    }
    
    /**
     * Gera uma string de saída formatada a partir dos atributos da gramática
     * @return String Saída formatada
     */
    public function saidaFormatada(){
        $saida = "<span>Terminais:</span> {". implode(", ", $this->getTerminais()->getData()) ."}</br>";
        $saida .= "<span>Variáveis:</span> {". implode(", ", $this->getVariaveis()->getData()) ."}</br>";
        $saida .= "<span>Inicial:</span> ". $this->getInicial() . "</br>";
        $saida .= "<span>Regras:</span> {";
        
        foreach ($this->getProducoes()->getData() as $p){
            $saida .= $p[0] ." > ". $p[1] . "; &nbsp;&nbsp;";
        }
        $saida .= "}</br>";
        
        return $saida;
    }
    
}

?>

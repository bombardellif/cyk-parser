<?php

define("ESPACO_NODO", 64); 		// espaçamento entre nodos
define("ESPACO_NIVEL", 50); 	// espaçamento entre níveis
define("RAIO_NODO", 20);		// raio dos nodos
define("BORDA_DESENHO", 30);	// borda para o desenho nao ser cortado (na esquerda e em cima)
/**
 * Classe para representar árvores
 * @author turco
 */
class Arvore
{
    /**
     * Armazena a identificação do nó da árvore.
     * @var String 
     */
    private $_id;
    /**
     * Armazena um conjunto de sub-árvores.
     * @var Array de Arvore 
     */
    private $_arrSubArvore;        
    /**
     * Construtor da classe Arvore.
     * @param String $id
     * @param Array de Arvore $subarvore
     */
    public function __construct($id, $subarvore = null)
    {
        $this->_id = $id;
        $this->_arrSubArvore = $subarvore;
    }
    /**
     * Imprime a árvore.
     */
    public function imprimeArvore()
    {
        // calcula a largura da árvore
	$larguraArvore = $this->nroMaxFolhas() * ESPACO_NODO * $this->nroSubArvores();
	$this->imprimeNodos(0, 0, $larguraArvore);
    }
    /**
     * Retorna o total de sub-árvores que não contém sub-árvores (folhas)
     * @return int
     */
    private function nroFolhas()
    {
	if(empty($this->_arrSubArvore))
	{
            return 1;
	}
	else
	{
            $soma = 0;
            foreach($this->_arrSubArvore as $subArvore)
            {
                $soma += $subArvore->nroNodosFolha();
            }
            return $soma;
        }
    }
    /**
     * Retorna o total de sub-árvores que não contém sub-árvores (folhas) 
     * da maior sub-árvore
     * @return int
     */
    public function nroMaxFolhas()
    {
	if(empty($this->_arrSubArvore))
	{
            return 1;
	}
	else
	{
            $max = 0;
            foreach($this->_arrSubArvore as $subArvore)
            {
                if($subArvore->nroFolhas() > $max)
                {
                    $max = $subArvore->nroFolhas();
		}
            }
            return $max;
	}
    }
    /**
     * Retorna o número de sub-árvores
     * @return int
     */
    public function nroSubArvores()
    {
	return count($this->_arrSubArvore);
    }
    /**
     * Imprime na tela o círculo que representa o nó da árvore, juntamente
     * com a identificação (id) do nó.
     * @param int $cx
     * @param int $cy
     */
    public function imprimeBola($cx, $cy)
    {
        $cx += BORDA_DESENHO;
	$cy += BORDA_DESENHO;
	$r = RAIO_NODO;
	echo "<circle cx='$cx' cy='$cy' r='$r' stroke='red' stroke-width='2' fill='white' />\n";
	$cy += 7.5;
	echo "<text x='$cx' y='$cy' font-family='sans-serif' font-size='20px' text-anchor='middle' fill='black'>$this->_id</text>";
    }
    /**
     * Imprime na tela uma linha de espessura 2px com extremidades em (x0, y0)
     * e (x1, y1).
     * @param int $x0
     * @param int $y0
     * @param int $x1
     * @param int $y1
     */
    public function imprimeLinha($x0, $y0, $x1, $y1)
    {
	$x0 += BORDA_DESENHO;
	$y0 += BORDA_DESENHO;
	$x1 += BORDA_DESENHO;
	$y1 += BORDA_DESENHO;
	echo "<line x1='$x0' y1='$y0' x2='$x1' y2='$y1' style='stroke: rgb(255,0,0); stroke-width: 2;'/>\n";
    }
    /**
     * Função recursiva que imprime os nós na tela.
     * @param int $nivel
     * @param int $espaco_h
     * @param int $largura
     */
    public function imprimeNodos($nivel, $espaco_h, $largura)
    {
	$bola_x = ($largura / 2) + $espaco_h; 	// média da largura + espaço
	$bola_y = $nivel * ESPACO_NIVEL; 	// calcula y em relação ao nível
	
	if($this->nroSubArvores() != 0)
            $mediaLarguraFilho = $largura / $this->nroSubArvores();
	
	foreach($this->_arrSubArvore as $subArvore)
	{
            $this->imprimeLinha($bola_x, $bola_y, ($mediaLarguraFilho / 2) + $espaco_h, ($bola_y + ESPACO_NIVEL)); 	// desenha a linha ligando as bolas
            $subArvore->imprimeNodos($nivel + 1, $espaco_h, $mediaLarguraFilho);										// chamada recursiva
            $espaco_h += $mediaLarguraFilho;																		// adiciona espaçamento
	}
		
	$this->imprimeBola($bola_x, $bola_y); // imprime as bolas no final para aparecerem sobrepostas às linhas
    }        
}
?>
<?php

define("ESPACO_NODO", 64); 		// espaçamento entre nodos
define("ESPACO_NIVEL", 50); 	// espaçamento entre níveis
define("RAIO_NODO", 20);		// raio dos nodos
define("BORDA_DESENHO", 30);	// borda para o desenho nao ser cortado (na esquerda e em cima)

class Nodos
{
	private $_nome;
	private $_arrFilhos;
	
	/* __construct(nome, array de filhos)
		Dado um nome para o nodo e um array com filhos, inicia a classe com esses valores */
	public function __construct($nome, $arrFilhos = array())
	{
		$this->_nome = $nome;
		$this->_arrFilhos = $arrFilhos;
	}
	
	/* nroNodosFolha()
		Retorna o número de nodos folha (terminais) contidos em uma árvore */
	public function nroNodosFolha()
	{
		if(empty($this->_arrFilhos))
		{
			return 1;
		}
		else
		{
			$soma = 0;
			foreach($this->_arrFilhos as $filho)
			{
				$soma += $filho->nroNodosFolha();
			}
			return $soma;
		}
	}
	/* nroMaxNodosFolha()
		Retorna o número de nodos folha (terminais) contidos no filho que possui o maior número de nodos folha (terminais).
		Ou seja, retorna o número de nodos folha do maior filho */
	public function nroMaxNodosFolha()
	{
		if(empty($this->_arrFilhos))
		{
			return 1;
		}
		else
		{
			$max = 0;
			foreach($this->_arrFilhos as $nroFolhasFilho)
			{
				if($nroFolhasFilho->nroNodosFolha() > $max)
				{
					$max = $nroFolhasFilho->nroNodosFolha();
				}
			}
			return $max;
		}
	}
	/* nroFilhos()
		Retorna o número de filhos de determinado nodo */
	public function nroFilhos()
	{
		return count($this->_arrFilhos);
	}
	/* imprimeBola(x, y)
		Retorna uma string HTML que imprime um círculo de raio 10 com centro em (x, y) e dentro do círculo,
		imprime o nomo do respectivo nodo.*/
	public function imprimeBola($cx, $cy)
	{
		$cx += BORDA_DESENHO;
		$cy += BORDA_DESENHO;
		$r = RAIO_NODO;
		echo "<circle cx='$cx' cy='$cy' r='$r' stroke='red' stroke-width='2' fill='white' />\n";
		$cy += 7.5;
		echo "<text x='$cx' y='$cy' font-family='sans-serif' font-size='20px' text-anchor='middle' fill='black'>$this->_nome</text>";
	}
	/* imprimeLinha((x0, y0), (x1, y1))
		Imprime na tela uma linha de espessura 2px com extremidades em [x0, y0] e [x1, y1] */
	public function imprimeLinha($x0, $y0, $x1, $y1)
	{
		$x0 += BORDA_DESENHO;
		$y0 += BORDA_DESENHO;
		$x1 += BORDA_DESENHO;
		$y1 += BORDA_DESENHO;
		echo "<line x1='$x0' y1='$y0' x2='$x1' y2='$y1' style='stroke: rgb(255,0,0); stroke-width: 2;'/>\n";
	}	
	/* imprimeNodos(nivel, espaco, largura)
		Imprime o nodo em determinado nível, adicionando-se determinado espaço à esquerda e centralizando na largura dada.
		Desenha a árvore recursivamente. A primeira chamada da função recebe a largura total da árvore. A cada nível que a árvore desce,
		essa largura total vai sendo dividida igualmente (árvore simétrica) entre os filhos. Para imprimir os irmãos corretamente, é adicionado a cada nodo
		que a função imprime um espaçamento (que é a soma dos espaços que os irmãos anteriores ocuparam). */
	public function imprimeNodos($nivel, $espaco_h, $largura)
	{
		$bola_x = ($largura / 2) + $espaco_h; 	// média da largura + espaço
		$bola_y = $nivel * ESPACO_NIVEL; 		// em relação ao nível
		//$bola = 
		
		if(count($this->_arrFilhos) != 0)
			$mediaLarguraFilho = $largura / count($this->_arrFilhos);
	
		foreach($this->_arrFilhos as $filho)
		{
			$this->imprimeLinha($bola_x, $bola_y, ($mediaLarguraFilho / 2) + $espaco_h, ($bola_y + ESPACO_NIVEL)); 	// desenha a linha ligando as bolas
			$filho->imprimeNodos($nivel + 1, $espaco_h, $mediaLarguraFilho);										// chamada recursiva
			$espaco_h += $mediaLarguraFilho;																		// adiciona espaçamento
		}
		
		$this->imprimeBola($bola_x, $bola_y); // imprime as bolas no final para aparecerem sobrepostas às linhas
	}
}

class Arvore
{
	/* :D */
	private $_arrArvore;
	
	public function __construct($arvore)
	{
		// Esperar implementação do CYK para implementar pro melhor uso
		// Por enquanto o uso é:
		// $nodos = new Nodos("nodo1", $nodos2);
		// $nova_arvore = new Arvore($nodos);
		// onde:
		// $nodos2 = conjunto (array) de nodos adjacentes a "nodo1"
		// $nodos = nodo "nodo1" com filhos $nodos2
		// $nova_arvore = árvore construída a partir do nodo "nodo1"
		$this->_arrArvore = $arvore;
	}
	
	public function imprimeArvore()
	{
		// calcula a largura da árvore
		$larguraArvore = $this->_arrArvore->nroMaxNodosFolha() * ESPACO_NODO * $this->_arrArvore->nroFilhos();
		$this->_arrArvore->imprimeNodos(0, 0, $larguraArvore);
	}
}

// ÁRVORES PARA TESTE, APENAS MUDAR O ÍNDICE EM $arvore :D
$teste = new Nodos("A", array(new Nodos("B", array(new Nodos("D", array(new Nodos("A", array(new Nodos("B", array(new Nodos("D"), new Nodos("E"))), new Nodos("C", array(new Nodos("I"))), new Nodos("F", array(new Nodos("H"), new Nodos("G"))), new Nodos("J"), new nodos("K"))))), new Nodos("E"))), new Nodos("C", array(new Nodos("I"))), new Nodos("F", array(new Nodos("H"), new Nodos("G"))), new Nodos("J"), new nodos("K")));
$teste2 = new Nodos("A", 
			array(
			new Nodos("B",
				array(
					new Nodos("D", array(new Nodos("H"), new Nodos("I"))),
					new Nodos("E", array(new Nodos("H"), new Nodos("I"))))), 
			new Nodos("C",
				array(
					new Nodos("D", array(new Nodos("H"), new Nodos("I"))),
					new Nodos("E", array(new Nodos("H"), new Nodos("I")))))));
$teste3 = new Nodos("A", array(new Nodos("B"), new Nodos("C")));
$teste4 = new Nodos("A", array(new Nodos("B", array(new Nodos("E"), new Nodos("D"))), new Nodos("C")));

$teste5 = new Nodos("A", 
			array(
			new Nodos("B",
				array(
					new Nodos("D", array(new Nodos("H"), new Nodos("I"))),
					new Nodos("E"))), 
			new Nodos("C",
				array(
					new Nodos("F"),
					new Nodos("G")))));
$teste6 = new Nodos("A", array(new Nodos("B", array(new Nodos("F"), new Nodos("G"))), new Nodos("C", array(new Nodos("H"), new Nodos("I"))), new Nodos("D", array(new Nodos("J"), new Nodos("K"))), new Nodos("E", array(new Nodos("L"), new Nodos("M")))));
$teste7 = new Nodos("ABC", 
			array(
			new Nodos("BC",
				array(
					new Nodos("D", array(new Nodos("H", array(new Nodos("H"), new Nodos("I"))), new Nodos("I", array(new Nodos("H"), new Nodos("I"))))),
					new Nodos("E", array(new Nodos("H", array(new Nodos("H"), new Nodos("I"))), new Nodos("I", array(new Nodos("H"), new Nodos("I"))))))), 
			new Nodos("BD",
				array(
					new Nodos("D", array(new Nodos("H", array(new Nodos("H"), new Nodos("I"))), new Nodos("I", array(new Nodos("H"), new Nodos("I"))))),
					new Nodos("E", array(new Nodos("H", array(new Nodos("H"), new Nodos("I"))), new Nodos("I", array(new Nodos("H"), new Nodos("I"))))))), 
			new Nodos("CD",
				array(
					new Nodos("D", array(new Nodos("H", array(new Nodos("H"), new Nodos("I"))), new Nodos("I", array(new Nodos("H"), new Nodos("I"))))),
					new Nodos("E", array(new Nodos("H", array(new Nodos("H"), new Nodos("I"))), new Nodos("I", array(new Nodos("H"), new Nodos("I")))))))));
$arvore = new Arvore($teste7);
?>
<!DOCTYPE html>
 <html>
 <body>

 <svg xmlns="http://www.w3.org/2000/svg" version="1.1" height="100%">
   <?php $arvore->imprimeArvore(); ?>
 </svg>
 </body>
 </html>
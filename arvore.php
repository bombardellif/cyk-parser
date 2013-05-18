<?php
class Nodos
{
	private $_nome;
	private $_arrFilhos;
	
	public function __construct($nome, $arrFilhos = array())
	{
		$this->_nome = $nome;
		$this->_arrFilhos = $arrFilhos;
	}
	
	/* função destruct? */
	
	/* nroNodosFolha()
		Retorna o número de nodos folha */
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
	
	public function imprimeNodos($nivel = 0, $espaco_h = 0)
	{
		$nroFilhos = $this->nroNodosFolha();
		if($nroFilhos > 0)
		{
			$pos_h = (($nroFilhos * 30) + (($nroFilhos - 1) * 30)) / 2 + $espaco_h;
			$pos_v = ($nivel * 50) + 20;			
			//$novoEspaco = $espaco_h;
			foreach($this->_arrFilhos as $filho)
			{
				// Imprime a linha
				$nroFolhasFilho = $filho->nroNodosFolha();
				$pagina = '<line x1="'.$pos_h.'" y1="'.$pos_v.'" x2="'.((($nroFolhasFilho * 30) + (($nroFolhasFilho - 1) * 30)) / 2 + $espaco_h).'" y2="'.($pos_v + 50).'" style="stroke: rgb(0,0,0); stroke-width: 2;"/>';
				echo $pagina;
				$filho->imprimeNodos($nivel + 1, $espaco_h);
				//echo '$filho->imprimeNodos('.($nivel + 1).', '.$novoEspaco.');<br>';
				$espaco_h += ($nroFolhasFilho * 30) + ($nroFolhasFilho * 30);
			}
			$pagina = '<circle cx="'.$pos_h.'" cy="'.$pos_v.'" r="10" stroke="black" stroke-width="2" fill="red" />';
			echo $pagina;
		}
	}
}
// espaçamento entre filhos = 40
// largura filhos = 22 ( 2 = borda 
/*
							O
			O				O				O
	O		O		O		O			O		O
*/
class Arvore
{
	/* :D */
	private $arrNodos;
}

$teste = new Nodos("A", array(new Nodos("B", array(new Nodos("D"), new Nodos("E"))), new Nodos("C", array(new Nodos("I"))), new Nodos("F", array(new Nodos("H"), new Nodos("G")))));

/*echo $teste->nroNodosFolha();*/



/* <line x1="0" y1="0" x2="200" y2="0" style="stroke: rgb(255,0,0); stroke-width: 2;"/>
<circle cx="1000" cy="50" r="10" stroke="black" stroke-width="2" fill="red" /> */

?>
<!DOCTYPE html>
 <html>
 <body>

 <svg xmlns="http://www.w3.org/2000/svg" version="1.1" height="100%">
   <?php $teste->imprimeNodos(); ?>
 </svg>
 </body>
 </html>
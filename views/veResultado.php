<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>CYK-PARSER</title>
        <link rel="stylesheet" href="style.css" />
        <link rel="stylesheet" href="jquery-ui.css" />
        <script src="jquery-1.9.1.js"></script>
	<script src="jquery-ui.js"></script>
        <script>
	$(function() {
		$( "#tabs" ).tabs();
	});
	</script>
    </head>
    <body>
        <br />
        Usando arquivo: <?php echo $view['arquivo']; ?><br />
        Frase: <?php echo $view['frase']; ?><br />

        <br /><br />
        
        Gramática Original: <br />
        <pre><?php //var_dump($view['gramaticaOriginal']); ?></pre>
        <?php if (isset($view['gramaticaOriginal']) && $view['gramaticaOriginal'] instanceof Gramatica):?>
        <?php echo $view['gramaticaOriginal']->saidaFormatada(); ?>
        <?php endif; ?>
        
        <br /><br />
        
        Chomsky: <br />
        <pre><?php //var_dump($view['gramaticaChomsky']); ?></pre>
        <?php if (isset($view['gramaticaChomsky']) && $view['gramaticaChomsky'] instanceof Gramatica):?>
        <?php echo $view['gramaticaChomsky']->saidaFormatada(); ?>
        <?php endif; ?>
        
        <br /><br />
        
        Resultado: 
        <?php if (isset($view['aceita']) &&  $view['aceita'] === true): ?>
            <span class="green">A frase foi aceita.</span>
        <?php elseif ($view['aceita'] === null): ?>
            <span class="yellow">A frase é vazia (não aplica CYK).</span>
        <?php else: ?>
            <span class="red">A frase foi rejeitada.</span>
        <?php endif; ?>
        
        <br /><br />
        
        <?php if ($view['aceita'] !== null): ?>
            Tabela do Algoritmo CYK: <?php /*echo '<pre>';var_dump($view['tabelaCYK']);echo '</pre>';*/ ?>
            <table style='background: #fff;' align='center'>
                <?php
                    // O número de palavras é o número de folhas da árvore [GAMBIARRA]
                    $n = $view['arvores'][0]->nroFolhas();
                    for($k = $n; $k > 0; $k--)
                    {
                        echo "<tr>";
                        for($i = 1; $i <= $n; $i++)
                        {
                            $celula = $view['tabelaCYK']->get($i, $k);
                            if($celula == NULL) // não há valor na coordenada, imprime célula vazia
                            {
                                echo "<td style='min-width: 30px; height: 30px; border: 1px solid white;'>";
                            }
                            else
                            {
                                echo "<td style='min-width: 30px; height: 30px; border: 1px dashed black;' align='center'>";
                                $vars = $celula->getVariaveis();
                                if($vars->size() > 0)
                                {
                                    $arrayVars = $vars->getData();
                                    echo "$arrayVars[0]";
                                    for($j = 1; $j < count($arrayVars); $j++)
                                    {
                                        echo ", $arrayVars[$j]";
                                    }
                                }
                            }
                            echo "</td>";
                        }
                        echo "</tr>";
                    }
                    ?>
            </table>
            <br /><br />

            Árvores:
            <div id="tabs">
                <ul>
                    <?php
                        $maiorAltura = 0;
                        for($i = 1; $i <= count($view['arvores']); $i++)
                        {
                            echo "<li><a href='#tabs-$i'>Arvore $i</a></li>";
                            $altura = ($view['arvores'][$i - 1]->nroNiveis() + 1) * ESPACO_NIVEL + BORDA_DESENHO;
                            if($altura > $maiorAltura)
                                $maiorAltura = $altura;
                        }
                    ?>
                </ul>
                    <?php
                        for($i = 0; $i < count($view['arvores']); $i++)
                        {
                            $altura = ($view['arvores'][$i]->nroNiveis() + 1) * ESPACO_NIVEL + BORDA_DESENHO;
                            $largura = (pow(2, ($view['arvores'][$i]->nroNiveis() - 1)) * ESPACO_NODO);
                            echo "<div id='tabs-".($i + 1)."' height='$maiorAltura' style='overflow: scroll;'>";                            
                            echo "<svg xmlns='http://www.w3.org/2000/svg' version='1.1' height='$maiorAltura' width='".(($largura * 2) + 30)."' style='position: relative; left: 50%; margin-left: -".($largura - 30)."px;'>";
                            $view['arvores'][$i]->imprimeArvore();
                            echo "</svg>";
                            echo "</div>";
                        }
                    ?>
            </div>
        <?php endif; ?>
    </body>
</html>

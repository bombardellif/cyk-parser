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
    <body class="pagina-resultado">
        <div id="header">CYK-PARSER - Linguagens Formais e Autômatos</div>
        
        <div id="corpo">
            <div id="info-subheader" class="infobox">
                Resultado do processamento da frase
            </div>

            <div id="info-entrada" class="infobox">
                <b>Usando arquivo:</b> <?php echo $view['arquivo']; ?><br />
                <b>Frase:</b> <?php echo $view['frase']; ?><br />
            </div>

            <div class="info-gramatica infobox">
                <b>Gramática Original:</b> <br />
                <pre><?php //var_dump($view['gramaticaOriginal']); ?></pre>
                <?php if (isset($view['gramaticaOriginal']) && $view['gramaticaOriginal'] instanceof Gramatica):?>
                <?php echo $view['gramaticaOriginal']->saidaFormatada(); ?>
                <?php endif; ?>
            </div>

            <div class="info-gramatica infobox">
                <b>Chomsky:</b> <br />
                <pre><?php //var_dump($view['gramaticaChomsky']); ?></pre>
                <?php if (isset($view['gramaticaChomsky']) && $view['gramaticaChomsky'] instanceof Gramatica):?>
                <?php echo $view['gramaticaChomsky']->saidaFormatada(); ?>
                <?php endif; ?>
            </div>


            <div id="resultado" class="infobox">
                <b>Resultado: </b>
                <?php if (isset($view['aceita']) &&  $view['aceita'] === true): ?>
                    <span class="green">A frase foi aceita.</span>
                <?php elseif ($view['aceita'] === null): ?>
                    <span class="yellow">A frase é vazia (não aplica CYK).</span>
                <?php else: ?>
                    <span class="red">A frase foi rejeitada.</span>
                <?php endif; ?>
            </div>

            <?php if ($view['aceita'] !== null): ?>
            <div class="infobox"><b>Tabela do Algoritmo CYK:</b> <?php /*echo '<pre>';var_dump($view['tabelaCYK']);echo '</pre>';*/ ?></div>
                <table style='background: #fff;' align='center'>
                    <?php
                        // O número de palavras é o número de folhas da árvore [GAMBIARRA]
                        $n = $view['nroPalavrasFrase']; //$view['arvores'][0]->nroFolhas();
                        for($k = $n; $k > 0; $k--)
                        {
                            echo "<tr>";
                            for($i = 1; $i <= $n; $i++)
                            {
                                $celula = $view['tabelaCYK']->get($i, $k);
                                if($celula == NULL) // não há valor na coordenada, imprime célula vazia
                                {
                                    echo "<td class=\"celula-cyk-vazia\">";
                                }
                                else
                                {
                                    echo "<td class=\"celula-cyk\" align='center'>";
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
                                    $vars = $celula->getCombinacoes();
                                    
                                    if($vars->size() > 0)
                                    {
                                        $saida = array();
                                        foreach ($vars->getData() as $combinacao){
                                            $saida[] = $combinacao[1][0] . ' > ' . implode(",",$combinacao[0]);
                                        }
                                        echo "<div class=\"combinacoes-celula\">" .implode("; <br />",$saida). "</div>";
                                    }
                                }
                                echo "</td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                </table>
                <br /><br />
                <div class="infobox"><b>Árvores:</b></div>
                <?php if (!is_null($view['arvores']) && is_array($view['arvores']) && count($view['arvores']) > 0): ?>
                    <div id="tabs">
                        <ul>
                            <?php
                                $maiorAltura = 0;
                                for($i = 1; $i <= count($view['arvores']); $i++)
                                {
                                    echo "<li><a href='#tabs-$i'>Árvore $i</a></li>";
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
                <?php else: ?>
                    <span class="red">Nenhuma árvore gerada.</span>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <div id="rodape">
            <b>Autores:</b> Eduardo A. Turconi, Fernando B. da Silva, William B. da Silva
        </div>
    </body>
</html>

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
            Tabela do Algoritmo CYK: <?php echo '<pre>';var_dump($view['tabelaCYK']);echo '</pre>'; ?>

            <br /><br />

            Árvores:
            <div id="tabs">
                <ul>
                    <?php
                        for($i = 0; $i < count($view['arvores']); $i++)
                        {
                            echo "<li><a href='#tabs-".($i + 1)."'>Arvore $i</a></li>";
                        }
                    ?>
                </ul>
                    <?php
                        for($i = 0; $i < count($view['arvores']); $i++)
                        {
                            echo "<div id='tabs-".($i + 1)."' height='1000'>";
                            echo "<svg xmlns='http://www.w3.org/2000/svg' version='1.1' height='1000'>";
                            $view['arvores'][$i]->imprimeArvore();
                            echo "</svg>";
                            echo "</div>";
                        }
                    ?>
            </div>
        <?php endif; ?>
    </body>
</html>

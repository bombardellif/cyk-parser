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

            Árvores: <?php var_dump($view['arvores']); ?>
            <div id="tabs">
                <ul>
                        <li><a href="#tabs-1">Nunc tincidunt</a></li>
                        <li><a href="#tabs-2">Proin dolor</a></li>
                        <li><a href="#tabs-3">Aenean lacinia</a></li>
                </ul>
                <div id="tabs-1">
                        <p>Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt interdum. Phasellus ipsum. Nunc tristique tempus lectus.</p>
                </div>
                <div id="tabs-2">
                        <p>Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt interdum. Phasellus ipsum. Nunc tristique tempus lectus.</p>
                </div>
                <div id="tabs-3">
                        <p>Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt interdum. Phasellus ipsum. Nunc tristique tempus lectus.</p>
                </div>
            </div>
        <?php endif; ?>
    </body>
</html>

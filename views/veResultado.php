<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>CYK-PARSER</title>
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
        
        Resultado: <?php var_dump($view['aceita']); ?><br />
        
        <br /><br />
        
        Árvores: <?php var_dump($view['arvores']); ?>
    </body>
</html>

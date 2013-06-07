<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" href="style.css" />
        <title>CYK-PARSER</title>
    </head>
    <body class="pagina-inicial">
        <div id="header">CYK-PARSER - Linguagens Formais e Autômatos</div>
        <div class="center">
            <div id="form-header">
                Selecione o Arquivo da Gramática e Informe uma Frase
            </div>
            <form id="caixaForm" action="" method="post" enctype="multipart/form-data">
                <div class="x2">
                <label for="file">Gramática:</label>
                </div>
                <div class="x5">
                <input class="width-100" type="file" name="file" id="file" required />
                </div>
                <div class="clearBlock"></div>
                <div class="x2">
                <label for="frase">Frase:</label>
                </div>
                <div class="x5">
                <input class="width-100" type="text" name="frase" id="frase" />
                </div>
                <div class="clearBlock"></div>
                <div class="width-100 in-block">
                    <input type="submit" name="submit" value="Parse!" class="btn-brown f-right" />
                </div>
                <div class="clearBlock"></div>
            </form>
        </div>
    </body>
</html>


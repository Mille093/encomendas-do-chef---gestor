<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Central de Ajuda</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f9f9f9;
            color: #333;
            line-height: 1.6;
        }

        /* Cabeçalho */
        header {
          background-color: #f4c430;
            color: white;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        header h1 {
            color: white;
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        header h1::before {
        
            font-size: 28px;
        }

        /* Link de termos */
        .terms {
            margin: 15px auto;
            max-width: 1200px;
            font-size: 14px;
            color: #666;
            text-align: left;
            padding-left: 20px;
        }

        .terms a {
            color: #f9a825;
            text-decoration: none;
        }

        .terms a:hover {
            text-decoration: underline;
        }

        /* Conteúdo principal */
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            text-align: center;
        }

        .help-text {
            margin-bottom: 20px;
            color: #555;
            font-size: 16px;
        }

        .contact {
            color: #f9a825;
            font-weight: 600;
            text-decoration: none;
            font-size: 16px;
        }

        .contact:hover {
            text-decoration: underline;
        }
        .terms{
            text-align: center;
        }
    </style>
</head>
<body>

    
    <header>
        <h1>Central de Ajuda</h1>
    </header>


    <div class="terms">
    <a href="index.php?controller=Termos&action=index">Termos de Uso e Privacidade</a>
</div>
   
    <div class="container">
        <p class="help-text">Ainda precisa de ajuda? Fale conosco:</p>
        <p>
            E-mail: 
            <a href="mailto:pauloluizvieira.05@gmail.com" class="contact">
                pauloluizvieira.05@gmail.com
            </a>
        </p>
    </div>

</body>
</html>
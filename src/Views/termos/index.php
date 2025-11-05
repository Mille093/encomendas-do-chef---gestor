<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termos de Uso - Encomendas do Chefe</title>
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
            line-height: 1.7;
            padding: 0px 15px;
        }

        header {
            background-color: #f4c430;
            color: white;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        header h1 {
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        header h1::before {
            content: "Chef";
            font-size: 28px;
        }

        /* Conteúdo */
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .title {
            color: #c0392b;
            font-size: 22px;
            text-align: center;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .subtitle {
            text-align: center;
            font-size: 18px;
            color: #c0392b;
            margin-bottom: 20px;
        }

        .meta {
            text-align: center;
            color: #666;
            font-size: 15px;
            margin-bottom: 30px;
        }

        .meta .icon {
            display: inline-block;
            margin-right: 5px;
            font-size: 16px;
        }

        /* Ilustração SVG */
        .illustration {
            text-align: center;
            margin: 40px 0;
        }

        .illustration svg {
            width: 220px;
            height: 220px;
        }

        .content {
            font-size: 15px;
            color: #444;
            text-align: justify;
        }

        .content h2 {
            color: #c0392b;
            margin: 25px 0 12px;
            font-size: 18px;
            border-bottom: 1px solid #f9a825;
            padding-bottom: 6px;
        }

        .content ul {
            margin: 12px 0;
            padding-left: 22px;
        }

        .content ul li {
            margin-bottom: 8px;
        }

        .back {
            text-align: center;
            margin-top: 40px;
        }

        .back a {
            color: #f9a825;
            text-decoration: none;
            font-weight: 600;
        }

        .back a:hover {
            text-decoration: underline;
        }

        .update {
            text-align: right;
            font-style: italic;
            color: #888;
            font-size: 13px;
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <!-- CABEÇALHO -->
    <header>
        <h1>Termos de Uso</h1>
    </header>

    <!-- CONTEÚDO -->
    <div class="container">
        <div class="title">Termos & Condições de Uso da Plataforma</div>
        <div class="subtitle">Encomendas do Chefe para os Usuários</div>

        <div class="meta">
            <span><strong>Data de atualização:</strong> 17 de abril de 2025</span>
            <br>
            <span class="icon">Hourglass</span> <strong>Tempo de leitura:</strong> 30 minutos.
        </div>

        <!-- ILUSTRAÇÃO -->
        <div class="illustration">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <circle cx="100" cy="100" r="80" fill="#e74c3c" opacity="0.2"/>
                <circle cx="100" cy="100" r="65" fill="#e74c3c"/>
                <path d="M70 85 Q65 80 65 75 Q65 65 75 65 Q85 65 85 75 Q85 80 80 85" fill="#2c3e50"/>
                <path d="M115 85 Q120 80 120 75 Q120 65 110 65 Q100 65 100 75 Q100 80 105 85" fill="#2c3e50"/>
                <path d="M80 100 Q90 105 100 105 Q110 105 120 100 L125 95 L115 90 L105 90 L95 90 L85 90 L75 95 Z" fill="#2c3e50"/>
                <rect x="75" y="110" width="50" height="40" rx="8" fill="#1abc9c"/>
                <path d="M80 115 L85 140 L95 145 L105 140 L115 115" fill="#16a085"/>
                <path d="M78 118 L88 138" stroke="#fff" stroke-width="2"/>
                <path d="M88 118 L98 138" stroke="#fff" stroke-width="2"/>
                <path d="M98 118 L108 138" stroke="#fff" stroke-width="2"/>
                <path d="M108 118 L118 138" stroke="#fff" stroke-width="2"/>
            </svg>
        </div>

        <div class="content">
            <p><strong>Bem-vindo(a) ao Encomendas do Chefe!</strong> Estes Termos de Uso ("Termos") regem o seu acesso e uso da plataforma web <em>Encomendas do Chefe</em>, operada pelo <strong>gestor do sistema</strong>.</p>

            <h2>1. Aceitação dos Termos</h2>
            <p>Ao acessar ou utilizar a plataforma, você concorda integralmente com estes Termos. Caso não concorde, interrompa imediatamente o uso.</p>

            <h2>2. Responsabilidades do Gestor</h2>
            <p>O <strong>gestor do sistema</strong> compromete-se a:</p>
            <ul>
                <li>Manter a plataforma funcional e segura;</li>
                <li>Processar pedidos com precisão;</li>
                <li>Proteger os dados dos usuários conforme a LGPD;</li>
                <li>Fornecer suporte técnico via e-mail;</li>
                <li>Atualizar os termos com antecedência mínima de 7 dias.</li>
            </ul>

            <h2>3. Responsabilidades do Usuário</h2>
            <p>Você se compromete a:</p>
            <ul>
                <li>Fornecer informações verdadeiras;</li>
                <li>Não compartilhar sua conta;</li>
                <li>Notificar imediatamente sobre uso indevido;</li>
                <li>Respeitar os prazos de encomenda.</li>
            </ul>

            <h2>4. Privacidade e Dados</h2>
            <p>Seus dados são usados apenas para:</p>
            <ul>
                <li>Processar pedidos;</li>
                <li>Enviar confirmações;</li>
                <li>Gerar relatórios internos.</li>
            </ul>
            <p><strong>Nunca vendemos seus dados.</strong> Para mais detalhes, contate o gestor.</p>

            <h2>5. Alterações</h2>
            <p>Estes Termos podem ser atualizados. A versão mais recente estará sempre disponível nesta página.</p>

            <div class="back">
                <a href="index.php?controller=Ajuda&action=index">← Voltar para Central de Ajuda</a>
            </div>

            <p class="update">Última atualização: 17 de abril de 2025</p>
        </div>
    </div>

</body>
</html>
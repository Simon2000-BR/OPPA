<?php
session_start(); // Inicia a sessão
$nome_usuario = isset($_SESSION['usuario']['nome']) ? $_SESSION['usuario']['nome'] : '';
$email_usuario = isset($_SESSION['usuario']['email']) ? $_SESSION['usuario']['email'] : '';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O.P.P.A</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

    <style>
        body {
            color: rgb(0, 0, 0); 
            font-family: Arial, Helvetica, sans-serif;
            background-color: rgb(221, 255, 255); 
            padding: 0;
            margin: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* Menu fixo no topo */
        #menu {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 90px;
            background-color: rgb(0, 255, 76); /* Cor de fundo do menu */
            display: flex;
            align-items: center;
            box-sizing: border-box;
            padding: 0 20px;
        }

        #menu img {
            height: 60px;
            margin-right: 10px; /* Adiciona espaço entre o ícone e o título */
        }

        #menu h1 {
            margin: 0;
            font-size: 36px;
        }
        #menu .card {
    margin-left: auto; 
    margin-right: 20px;
    background-color: white;
    border-radius: 50%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 10px;
    cursor: pointer;
    position: relative;
    font-size: 20px; 
}

#menu .card:hover {
    background-color: rgb(240, 240, 240);
}

        #userCard {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 300px;
            text-align: center;
            overflow: hidden;
            padding: 20px;
            margin: 10px;
            position: absolute;
            right: 20px;
            top: 30px;
            transition: transform 0.3s ease, opacity 0.3s ease;
            opacity: 0;
            z-index: 1000;
        }

        #userCard.show {
            opacity: 1;
            transform: translateY(70px);
        }

        #userCard img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 15px;
        }

        #userCard h2 {
            margin: 10px 0;
            font-size: 1.5em;
        }

        #userCard p {
            color: #555;
            margin: 5px 0;
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px;
            box-sizing: border-box;
            margin-top: 90px;
        }

        ul {
            list-style: none;
            display: flex;
            padding: 0;
            margin: 0;
            flex: 1;
        }

        li {
            flex: 1;
            margin: 4px; 
        }

        a {
            display: block;
            width: 100%;
            height: 100%;
            color: white;
            text-align: center;
            padding: 20px;
            text-decoration: none;
            border-radius: 10px;
            font-size: 30px;
            box-sizing: border-box;
        }

        .menu1 {
            background-color: rgb(0, 123, 255); 
        }

        .menu2 {
            background-color: rgb(255, 193, 7); 
        }

        .menu3 {
            background-color: rgb(40, 167, 69); 
        }

        .menu4 {
            background-color: rgb(220, 53, 69); 
        }

        .menu5 {
            background-color: rgb(108, 117, 125); 
            height: 100%;
        }

        .menu-description {
            font-size: 24px; 
            margin-top: 10px;
            color: rgb(0, 0, 0);
            line-height: 1.4;
        }

        .last-menu-item {
            flex: 1;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header id="menu">
        <img src="favicon.ico" alt="Favicon">
        <h1>O.P.P.A</h1>
        <div class="card" onclick="toggleCard()">
            Card
        </div>
    </header>

    <div id="userCard">
        <h2><?php echo htmlspecialchars($nome_usuario); ?></h2>
        <p><?php echo htmlspecialchars($email_usuario); ?></p>
    </div>

    <script>
        function toggleCard() {
            const card = document.getElementById('userCard');
            card.classList.toggle('show');
        }
    </script>
    
    <main>
        <ul>
            <li>
                <a href="funcionarios.php" class="menu1">Funcionários
                    <div class="menu-description">Gerencie todos os seus funcionários em uma tabela simples.</div>
                </a>
            </li>
            <li>
                <a href="maquinarios.php" class="menu2">Maquinários
                    <div class="menu-description">Acompanhe e organize os seus maquinários em uma tabela.</div>
                </a>
            </li>
        </ul>
        <ul>
            <li>
                <a href="animais.php" class="menu3">Animais
                    <div class="menu-description">Mantenha um registro completo dos seus animais, como gerenciar a produção, a quantidade, etc., em uma tabela.</div>
                </a>
            </li>
            <li>
                <a href="estoque.php" class="menu4">Estoque
                    <div class="menu-description">Gerencie seu estoque de insumos agrícolas, controlando a entrada e saída dos insumos.</div>
                </a>
            </li>
        </ul>
        <div class="last-menu-item">
            <a href="anotacoes.php" class="menu5">Anotações
                <div class="menu-description">Registre e organize anotações importantes sobre sua propriedade.</div>
            </a>
        </div>
    </main>
</body>
</html>

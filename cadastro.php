<?php
session_start();  // Inicia a sessão 
require_once 'conexao.php'; 

$erro = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome']; 
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Validação da senha
    if (strlen($senha) < 8) {
        $erro = "A senha deve ter pelo menos 8 caracteres.";
    } elseif (!preg_match('/[A-Z]/', $senha)) {
        $erro = "A senha deve conter pelo menos uma letra maiúscula.";
    } elseif (!preg_match('/[a-z]/', $senha)) {
        $erro = "A senha deve conter pelo menos uma letra minúscula.";
    } elseif (!preg_match('/\d/', $senha)) {
        $erro = "A senha deve conter pelo menos um número.";
    } elseif (!preg_match('/[\W_]/', $senha)) { 
        $erro = "A senha deve conter pelo menos um caractere especial.";
    }

    if ($erro === '') {
        // Senha com MD5
        $senha_md5 = md5($senha);

        // Verificar se o e-mail já existe no banco
        $sql = "SELECT id FROM usuarios WHERE email = ?";  
        $stmt = mysqli_prepare($conn, $sql); 
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $erro = "Este e-mail já está cadastrado.";
        } else {
            // Inserir o novo usuário no banco de dados
            $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";  
            $stmt = mysqli_prepare($conn, $sql); 
            mysqli_stmt_bind_param($stmt, "sss", $nome, $email, $senha_md5);
            $executado = mysqli_stmt_execute($stmt);

            if ($executado) {
                // Redireciona para a página de login após sucesso
                header("Location: login.php");
                exit;
            } else {                         
                $erro = "Erro ao cadastrar o usuário. Tente novamente.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    // Fechar a conexão
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="loguin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Ícones -->
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>Cadastro</title>
    <style>
        /* Estilo das mensagens de erro */
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            margin-top: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">
            <h2>Cadastro</h2>
        </div>
        <!-- Formulário de cadastro -->
        <form action="cadastro.php" method="POST" autocomplete="on">
            <div class="field">
                <i class="fa fa-user"></i>
                <input type="text" name="nome" placeholder="Nome Completo" required>
            </div>
            <div class="field">
                <i class="fa fa-envelope"></i>
                <input type="email" name="email" placeholder="E-mail" required>
            </div>
            <div class="field">
                <i class="fa fa-lock"></i>
                <input type="password" name="senha" placeholder="Senha" autocomplete="new-password" required minlength="8" maxlength="20">
            </div>
            <div class="login">
                <button type="submit">Cadastrar</button>
            </div>
            <div class="signup">
                Já tem uma conta? <a href="login.php" class="button">Faça Login</a>
            </div>
        </form>

        <!-- Exibição de erros -->
        <?php if (!empty($erro)) { ?>
        <div class="error-message">
            <strong>Erro:</strong> <?php echo $erro; ?>
        </div>
        <?php } ?>
    </div>
</body>
</html>

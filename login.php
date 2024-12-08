<?php
// Incluir o arquivo de conexão
require_once 'conexao.php'; 

$erro = ''; // Variável para armazenar o erro

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Criptografar a senha usando MD5
    $senha_md5 = md5($senha);

    // Verificar se o e-mail e senha são válidos
    $sql = "SELECT id, nome, email FROM cadastro WHERE email = ? AND senha = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $email, $senha_md5);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $usuario = mysqli_fetch_assoc($result);

        // Iniciar a sessão e armazenar os dados do usuário
        session_start();
        $_SESSION['usuario'] = [
            'nome' => $usuario['nome'],
            'email' => $usuario['email']
        ];

        // Redirecionar para a página inicial (index)
        header("Location: index.php");
        exit;
    } else {
        $erro = "E-mail ou senha inválidos!";
    }
}

// Verificar se a conexão foi bem-sucedida antes de fechar
if ($conn) {
    mysqli_close($conn);  // Usar $conn para fechar a conexão
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
    <title>Login</title>
</head>
<body>
    <div class="container">
        <div class="title">
            <h2>Login</h2>
        </div>

        <!-- Formulário de login -->
        <form action="login.php" method="POST" autocomplete="on">
            <div class="field">
                <i class="fa fa-envelope"></i>
                <input type="email" name="email" placeholder="E-mail" required>
            </div>
            <div class="field">
                <i class="fa fa-lock"></i>
                <input type="password" name="senha" placeholder="Senha" required>
            </div>
            
            <div class="login">
                <button type="submit">Login</button>
            </div>
            <div class="signup">
                Não tem uma conta? <a href="cadastro.php" class="button">Cadastre-se</a>
            </div>
        </form>

        <!-- Exibição de erros -->
        <?php if(!empty($erro)) { ?>
        <div class="error-message">
            <strong>Erro:</strong> <?php echo $erro; ?>
        </div>
        <?php } ?>
    </div>
</body>
</html>

<?php
require_once 'conexao.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // MD5
    $senha_md5 = md5($senha);

    $sql = "SELECT id, nome, senha FROM cadastro WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql); 
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Verificar se o e-mail foi encontrado
    if (mysqli_num_rows($result) > 0) {
        $usuario = mysqli_fetch_assoc($result);

        // É a senha criptografada no banco
        error_log("Senha do banco: " . $usuario['senha']);
        
        // Verificar a senha com MD5
        if ($senha_md5 == $usuario['senha']) {
            session_start();
            $_SESSION['usuario'] = [
                'id' => $usuario['id'],
                'nome' => $usuario['nome']
            ];
            header("Location: index.php"); 
            exit;
        }
    }
    header("Location: login.php?erro=1");
    exit;
}

// Fechar a conexão
mysqli_close($conn); 
?>

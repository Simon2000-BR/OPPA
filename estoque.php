<?php 
// Iniciar a sessão
session_start(); 

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) { 
    die("Você precisa estar logado para cadastrar um insumo.");
}

// Incluir o arquivo de conexão
require_once 'conexao.php';

$erro = '';

// Adicionar condição para verificar a ação de cadastrar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $nome = $_POST['nome'] ?? '';
    $quantidade = $_POST['quantidade'];

    // Validar campos
    if (empty($nome) || empty($quantidade)) {
        $erro = "Todos os campos obrigatórios devem ser preenchidos.";
    } else {
        // Previne SQL Injection
        $nome = $conn->real_escape_string($nome);
        $quantidade = $conn->real_escape_string($quantidade);

        // Obtém o user_id da sessão
        $user_id = $_SESSION['usuario_id'];

        // Insere o novo insumo
        $sql = "INSERT INTO estoque (nome, quantidade, user_id) VALUES ('$nome', '$quantidade', $user_id)";

        // Verifica se a inserção foi bem-sucedida
        if ($conn->query($sql) === TRUE) {
            $erro = "Novo insumo cadastrado com sucesso!";
            header("Location: estoque.php");
            exit;
        } else {
            $erro = "Erro ao cadastrar: " . $conn->error;
        }
    }
}

// Verifica se a ação é excluir
if (isset($_GET['delete'])) {
    $estoq_id = (int) $_GET['delete'];

    // Previne SQL Injection
    $estoq_id = $conn->real_escape_string($estoq_id);

    // Verifica se o insumo pertence ao usuário logado
    $sql = "DELETE FROM estoque WHERE id = $estoq_id AND user_id = {$_SESSION['usuario_id']}";

    if ($conn->query($sql) === TRUE) {
        $erro = "Insumo excluído com sucesso!";
    } else {
        $erro = "Erro ao excluir o insumo: " . $conn->error;
    }
}

// Recupera os insumos cadastrados
$sql = "SELECT * FROM estoque WHERE user_id = {$_SESSION['usuario_id']}";
$result = $conn->query($sql);
$estoqCadastrados = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $estoqCadastrados[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Insumo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f6f8f9, #e8f7f0);
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #27ae60;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        header a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            margin: 0 10px;
            transition: color 0.3s;
        }

        header a:hover {
            color: #c3e6cd;
        }

        .back-button {
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 12px 45px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: #c0392b;
        }

        .container {
            max-width: 600px;
            margin: 80px auto 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #34495e;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #27ae60;
            box-shadow: 0 0 5px rgba(39, 174, 96, 0.5);
        }

        button {
            width: 100%;
            padding: 12px;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #219150;
        }

        .list-container {
            margin-top: 20px;
        }

        .list-item {
            background: #ecf0f1;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .list-item strong {
            color: #2c3e50;
        }

        .delete-button {
            background: #e74c3c;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            display: block;
            width: fit-content;
            margin-top: 10px;
            text-align: right;
            margin-left: auto;
        }

        .delete-button:hover {
            background: #c0392b;
        }
    </style>
</head>

<body>
    <header>
        <a href="index.php">
            <button class="back-button">← Voltar</button>
        </a>
        <nav>
            <a href="logout.php">Sair</a>
        </nav>
    </header>

    <div class="container">
        <h2>Cadastro de Insumos</h2>

        <?php if (!empty($erro)): ?>
            <p style="color: green; text-align: center;"><?php echo $erro; ?></p>
        <?php endif; ?>


        <form method="POST">
    <input type="hidden" name="action" value="add">
    <div class="form-group">
        <label for="nome">Descrição</label>
        <input type="text" id="nome" name="nome" placeholder="Digite o nome do insumo" required>
    </div>
    <div class="form-group">
        <label for="quantidade">Quantidade</label>
        <input type="number" id="quantidade" name="quantidade" placeholder="Digite a quantidade" required>
    </div>
    <button type="submit">Adicionar Insumo</button>
</form>
    <!-- Exibe os insumos cadastrados -->
    <div class="list-container">
        <?php foreach ($estoqCadastrados as $insumo): ?>
            <div class="list-item">
                <strong>Descrição:</strong> <?= $insumo['nome']; ?><br>
                <strong>Quantidade:</strong> <?= $insumo['quantidade']; ?>
                <a href="?delete=<?= $insumo['id'] ?>" class="delete-button" onclick="return confirm('Tem certeza que deseja excluir este insumo?');">Excluir</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
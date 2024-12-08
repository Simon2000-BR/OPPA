<?php
// Conecta ao banco de dados
require_once 'conexao.php';

$erro = ''; // Variável para mensagens de erro
$avesCadastradas = []; // Lista de aves cadastradas

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    // Ação de adicionar ave
    if ($action === 'add' && isset($_POST['nome'], $_POST['alimentacao'], $_POST['idade'])) {
        $nome = $_POST['nome'] ?? '';
        $alimentacao = $_POST['alimentacao'] ?? ''; 
        $idade = $_POST['idade'] ?? 0; 
        $producao = $_POST['producao'] ?? ''; 

        // Verifica se os campos obrigatórios foram preenchidos
        if (empty($nome) || empty($alimentacao) || empty($idade)) {
            $erro = "Todos os campos obrigatórios devem ser preenchidos."; // Mensagem de erro
        } else {
            // Previne SQL Injection
            $nome = $conn->real_escape_string($nome);
            $alimentacao = $conn->real_escape_string($alimentacao);
            $idade = (int)$idade;
            $producao = $conn->real_escape_string($producao);

            // Insere a nova ave no banco
            $sql = "INSERT INTO aves (nome, alimentacao, idade, producao) VALUES ('$nome', '$alimentacao', $idade, '$producao')";

            // Verifica se a inserção foi bem-sucedida
            if ($conn->query($sql) === TRUE) {
                $erro = "Nova ave cadastrada com sucesso!";
            } else {
                $erro = "Erro ao cadastrar: " . $conn->error; // Erro na inserção
            }
        }
    }
}

// Ação de exclusão de ave
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $delete_sql = "DELETE FROM aves WHERE id = $id";
    // Deleta a ave e redireciona
    if ($conn->query($delete_sql) === TRUE) {
        header("Location: aves.php");
        exit;
    } else {
        $erro = "Erro ao excluir ave: " . $conn->error; // Erro na exclusão
    }
}

// Recupera as aves cadastradas
$result = $conn->query("SELECT * FROM aves");
if ($result->num_rows > 0) {
    // Adiciona as aves à lista
    while ($row = $result->fetch_assoc()) {
        $avesCadastradas[] = $row;
    }
}

// Fecha a conexão com o banco
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro de Aves</title>
  <style>
    /* Estilos do código HTML */
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
     word-wrap: break-word;
     overflow-wrap: break-word;
     width: 100%;
     box-sizing: border-box;
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
      <a href="bovinos.php">Bovinos</a>
      <a href="suinos.php">Suínos</a>
      <a href="ovelhas.php">Ovelhas</a>
      <a href="aves.php">Aves</a>
      <a href="animais.php">Animais</a>
    </nav>
  </header>

  <div class="container">
    <h2>Cadastro de Aves</h2>
    <?php if (!empty($erro)): ?>
      <p style="color: green; text-align: center;"><?php echo $erro; ?></p>
    <?php endif; ?>
    <form method="POST">
      <input type="hidden" name="action" value="add">
      <div class="form-group">
        <label for="nome">Nome da Ave</label>
        <input type="text" id="nome" name="nome" placeholder="Digite o nome da ave" required>
      </div>
      <div class="form-group">
        <label for="alimentacao">Tipo de Alimentação</label>
        <input type="text" id="alimentacao" name="alimentacao" placeholder="Digite o tipo de alimentação" required>
      </div>
      <div class="form-group">
        <label for="idade">Idade</label>
        <input type="number" id="idade" name="idade" placeholder="Digite a idade (em anos)" required>
      </div>
      <div class="form-group">
        <label for="producao">Produção</label>
        <input type="text" id="producao" name="producao" placeholder="Digite a produção (se aplicável)">
      </div>
      <button type="submit">Adicionar Ave</button>
    </form>

    <div class="list-container">
      <?php foreach ($avesCadastradas as $ave): ?>
        <div class="list-item">
          <strong>Nome:</strong> <?php echo htmlspecialchars($ave['nome']); ?><br>
          <strong>Alimentação:</strong> <?php echo htmlspecialchars($ave['alimentacao']); ?><br>
          <strong>Idade:</strong> <?php echo htmlspecialchars($ave['idade']); ?><br>
          <strong>Produção:</strong> <?php echo htmlspecialchars($ave['producao']); ?><br>
          <a href="?delete=<?php echo $ave['id']; ?>" class="delete-button" onclick="return confirm('Você tem certeza que deseja excluir este bovino?')">Excluir</a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>

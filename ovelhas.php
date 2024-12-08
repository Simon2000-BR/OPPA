<?php
require_once "conexao.php";
// Inicializa variável de erro
$erro = '';
$ovelhasCadastradas = [];

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    // Processa a ação de adicionar
    if ($action === 'add' && isset($_POST['nome'], $_POST['alimentacao'], $_POST['idade'])) {
        $nome = $_POST['nome'] ?? '';
        $alimentacao = $_POST['alimentacao'] ?? '';
        $idade = $_POST['idade'] ?? 0;
        $producao = $_POST['producao'] ?? '';

        // Valida os campos obrigatórios
        if (empty($nome) || empty($alimentacao) || empty($idade)) {
            $erro = "Todos os campos obrigatórios devem ser preenchidos.";
        } else {
            // Previne SQL Injection
            $nome = $conn->real_escape_string($nome);
            $alimentacao = $conn->real_escape_string($alimentacao);
            $idade = (int)$idade;
            $producao = $conn->real_escape_string($producao);

            // Insere no banco de dados
            $sql = "INSERT INTO ovelhas (nome, alimentacao, idade, producao) VALUES ('$nome', '$alimentacao', $idade, '$producao')";

            if ($conn->query($sql) === TRUE) {
                $erro = "Nova ovelha cadastrada com sucesso!";
            } else {
                $erro = "Erro ao cadastrar: " . $conn->error;
            }
        }
    }
}

// Processa a exclusão de uma ovelha
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $delete_sql = "DELETE FROM ovelhas WHERE id = $id";
    if ($conn->query($delete_sql) === TRUE) {
        header("Location: ovelhas.php");
        exit;
    } else {
        $erro = "Erro ao excluir ovelha: " . $conn->error;
    }
}

// Recupera as ovelhas cadastradas para exibição
$result = $conn->query("SELECT * FROM ovelhas");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ovelhasCadastradas[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro de Ovelhas</title>
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
    <h2>Cadastro de Ovelhas</h2>
    <?php if (!empty($erro)): ?>
      <p style="color: green; text-align: center;"><?php echo $erro; ?></p>
    <?php endif; ?>
    <form method="POST">
      <input type="hidden" name="action" value="add">
      <div class="form-group">
        <label for="nome">Nome da Ovelha</label>
        <input type="text" id="nome" name="nome" placeholder="Digite o nome da ovelha" required>
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
      <button type="submit">Adicionar Ovelha</button>
    </form>

    <!-- Exibe as ovelhas cadastradas -->
    <div class="list-container">
      <?php if (count($ovelhasCadastradas) > 0): ?>
        <?php foreach ($ovelhasCadastradas as $ovelha): ?>
          <div class="list-item">
            <strong>Nome:</strong> <?php echo $ovelha['nome']; ?><br>
            <strong>Alimentação:</strong> <?php echo $ovelha['alimentacao']; ?><br>
            <strong>Idade:</strong> <?php echo $ovelha['idade']; ?> anos<br>
            <strong>Produção:</strong> <?php echo $ovelha['producao']; ?><br>
            <a href="?delete=<?php echo $ovelha['id']; ?>" class="delete-button" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Não há ovelhas cadastradas.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>

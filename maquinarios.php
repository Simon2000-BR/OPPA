<?php
// Conecta ao banco de dados
require_once 'conexao.php';

$erro = ''; // Variável para mensagens de erro
$maquinariosCadastrados = []; // Lista de maquinários cadastrados

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    // Ação de adicionar maquinário
    if ($action === 'add' && isset($_POST['nome'], $_POST['funcionando'], $_POST['local'])) {
        $nome = $_POST['nome'] ?? '';
        $funcionando = $_POST['funcionando'] ?? '';
        $local = $_POST['local'] ?? ''; 

        // Verifica se os campos obrigatórios foram preenchidos
        if (empty($nome) || empty($funcionando) || empty($local)) {
            $erro = "Todos os campos obrigatórios devem ser preenchidos."; // Mensagem de erro
        } else {
            // Previne SQL Injection
            $nome = $conn->real_escape_string($nome);
            $funcionando = $conn->real_escape_string($funcionando);
            $local = $conn->real_escape_string($local);

            // Insere o novo maquinário no banco
            $sql = "INSERT INTO maquinarios (nome, funcionando, local) VALUES ('$nome', '$funcionando', '$local')";

            // Verifica se a inserção foi bem-sucedida
            if ($conn->query($sql) === TRUE) {
                $erro = "Novo maquinário cadastrado com sucesso!";
            } else {
                $erro = "Erro ao cadastrar: " . $conn->error; // Erro na inserção
            }
        }
    }
}

// Ação de exclusão de maquinário
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $delete_sql = "DELETE FROM maquinarios WHERE id = $id";
    // Deleta o maquinário e redireciona
    if ($conn->query($delete_sql) === TRUE) {
        header("Location: maquinarios.php");
        exit;
    } else {
        $erro = "Erro ao excluir maquinário: " . $conn->error; // Erro na exclusão
    }
}

// Recupera os maquinários cadastrados
$result = $conn->query("SELECT * FROM maquinarios");
if ($result->num_rows > 0) {
    // Adiciona os maquinários à lista
    while ($row = $result->fetch_assoc()) {
        $maquinariosCadastrados[] = $row;
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
  <title>Cadastro de Maquinários</title>
  <style>
    /* Estilos do corpo da página */
    body {
        font-family: Arial, sans-serif;
        background: linear-gradient(135deg, #f6f8f9, #e8f7f0);
        margin: 0;
        padding: 0;
    }

    /* Estilos do cabeçalho */
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

    /* Botão de voltar */
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

    /* Container principal */
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

    /* Formulário */
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

    /* Container da lista */
    .list-container {
        margin-top: 20px;
    }

    /* Estilos para cada item da lista */
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

    /* Botão de excluir */
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
  </header>

  <div class="container">
    <h2>Cadastro de Maquinários</h2>
    <?php
    if (!empty($erro)) {
        echo "<p style='color: green; text-align: center;'>$erro</p>";
    }
    ?>
    <form method="POST">
      <input type="hidden" name="action" value="add">
      <div class="form-group">
        <label for="nome">Nome do Maquinário</label>
        <input type="text" id="nome" name="nome" placeholder="Digite o nome do maquinário" required>
      </div>
      <div class="form-group">
        <label for="funcionando">Funcionando?</label>
        <input type="text" id="funcionando" name="funcionando" placeholder="Digite o estado de funcionamento" required>
      </div>
      <div class="form-group">
        <label for="local">Local</label>
        <input type="text" id="local" name="local" placeholder="Digite o local do maquinário" required>
      </div>
      <button type="submit">Adicionar Maquinário</button>
    </form>

    <!-- Exibe os maquinários cadastrados -->
    <div class="list-container">
      <?php
      if (count($maquinariosCadastrados) > 0) {
          foreach ($maquinariosCadastrados as $maquinario) {
      ?>
        <div class="list-item">
          <strong>Nome:</strong> <?php echo htmlspecialchars($maquinario['nome']); ?><br>
          <strong>Funcionando:</strong> <?php echo htmlspecialchars($maquinario['funcionando']); ?><br>
          <strong>Local:</strong> <?php echo htmlspecialchars($maquinario['local']); ?><br>
          <a href="?delete=<?php echo $maquinario['id']; ?>" class="delete-button"onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
        </div>
      <?php
          }
      }
      ?>
    </div>
  </div>
</body>
</html>

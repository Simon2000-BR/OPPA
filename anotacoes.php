<?php
require_once "conexao.php";
$erro = '';
$notacoesCadastradas = [];

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    // Processa a ação de adicionar
    if ($action === 'add' && isset($_POST['titulo'], $_POST['descricao'])) {
        $titulo = $_POST['titulo'] ?? '';
        $descricao = $_POST['descricao'] ?? '';

        // Valida os campos obrigatórios
        if (empty($titulo) || empty($descricao)) {
            $erro = "Todos os campos obrigatórios devem ser preenchidos.";
        } else {
            // Previne SQL Injection
            $titulo = $conn->real_escape_string($titulo);
            $descricao = $conn->real_escape_string($descricao);

            // Insere no banco de dados
            $sql = "INSERT INTO anotacoes (titulo, descricao) VALUES ('$titulo', '$descricao')";

            if ($conn->query($sql) === TRUE) {
                $erro = "Nova anotação cadastrada com sucesso!";
            } else {
                $erro = "Erro ao cadastrar: " . $conn->error;
            }
        }
    }
}

// Recupera as anotações cadastradas para exibição
$result = $conn->query("SELECT * FROM anotacoes");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notacoesCadastradas[] = $row;
    }
}
if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  $delete_sql = "DELETE FROM anotacoes WHERE id = $id";
  if ($conn->query($delete_sql) === TRUE) {
      header("Location: anotacoes.php");
      exit;
  } else {
      $erro = "Erro ao excluir as anotações: " . $conn->error;
  }
}

// Fecha a conexão
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro de Anotações</title>
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

  input, textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
  }

  input:focus, textarea:focus {
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

  .list-item strong {
    color: #2c3e50;
  }

/* Botão de Excluir */
.delete-button {
  background: #e74c3c;
  color: white;
  padding: 8px 12px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
  display: block; /* Garante que o botão apareça em uma nova linha abaixo do conteúdo */
  width: fit-content; /* Ajusta o tamanho do botão ao seu conteúdo */
  margin-top: 10px; /* Adiciona um pequeno espaçamento acima do botão */
  text-align: right; /* Alinha o botão à direita */
  margin-left: auto; /* Garante que o botão vá para o lado direito */
}

.delete-button:hover {
  background: #c0392b;
}


  textarea {
    min-height: 100px;
    resize: vertical;
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
    <h2>Cadastro de Anotações</h2>
    <?php if (!empty($erro)): ?>
      <p style="color: green; text-align: center;"><?php echo $erro; ?></p>
    <?php endif; ?>
    <form method="POST">
      <input type="hidden" name="action" value="add">
      <div class="form-group">
        <label for="titulo">Título</label>
        <input type="text" id="titulo" name="titulo" placeholder="Digite o título da anotação" required>
      </div>
      <div class="form-group">
        <label for="descricao">Descrição</label>
        <textarea id="descricao" name="descricao" placeholder="Digite a descrição" required></textarea>
      </div>
      <button type="submit">Adicionar Anotação</button>
    </form>

    <!-- Exibe as anotações cadastradas -->
    <div class="list-container">
      <?php if (count($notacoesCadastradas) > 0): ?>
        <?php foreach ($notacoesCadastradas as $notacao): ?>
          <div class="list-item">
            <strong>Título:</strong> <?php echo $notacao['titulo']; ?><br>
            <strong>Descrição:</strong> <?php echo $notacao['descricao']; ?><br>
            <a href="?delete=<?php echo $notacao['id']; ?>" class="delete-button"onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Nenhuma anotação cadastrada.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>

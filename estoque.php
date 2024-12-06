<?php
require_once "conexao.php";
$erro = '';
$insumosCadastrados = [];

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    // Processa a ação de adicionar
    if ($action === 'add' && isset($_POST['nome'], $_POST['quantidade'])) {
        $nome = $_POST['nome'] ?? '';
        $quantidade = $_POST['quantidade'] ?? '';

        // Valida os campos obrigatórios
        if (empty($nome) || empty($quantidade)) {
            $erro = "Todos os campos obrigatórios devem ser preenchidos.";
        } else {
            // Previne SQL Injection
            $nome = $conn->real_escape_string($nome);
            $quantidade = $conn->real_escape_string($quantidade);

            // Insere no banco de dados
            $sql = "INSERT INTO insumos (nome, quantidade) VALUES ('$nome', '$quantidade')";

            if ($conn->query($sql) === TRUE) {
                $erro = "Novo insumo cadastrado com sucesso!";
            } else {
                $erro = "Erro ao cadastrar: " . $conn->error;
            }
        }
    }
}

// Processa a exclusão de um insumo
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $delete_sql = "DELETE FROM insumos WHERE id = $id";
    if ($conn->query($delete_sql) === TRUE) {
        header("Location: estoque.php");
        exit;
    } else {
        $erro = "Erro ao excluir insumo: " . $conn->error;
    }
}

// Recupera os insumos cadastrados para exibição
$result = $conn->query("SELECT * FROM insumos");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $insumosCadastrados[] = $row;
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
  <title>Estoque de Insumo</title>
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

/* Ajuste para o conteúdo da lista para não quebrar e envolver */
.list-item {
  background: #ecf0f1;
  padding: 15px;
  border-radius: 10px;
  margin-bottom: 20px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  position: relative;
  word-wrap: break-word; /* Quebra as palavras longas */
  word-break: break-word; /* Força a quebra da linha para palavras longas */
}

/* Adiciona um estilo mais limpo aos campos da lista */
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
  display: block;
  width: fit-content; /* Ajusta o tamanho do botão ao seu conteúdo */
  margin-top: 10px; /* Adiciona um pequeno espaçamento acima do botão */
  text-align: right; /* Alinha o botão à direita */
  margin-left: auto; /* Garante que o botão vá para o lado direito */
}

.delete-button:hover {
  background: #c0392b; /* Cor de fundo ao passar o mouse */
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

    <div class="list-container">
      <?php if (count($insumosCadastrados) > 0): ?>
        <?php foreach ($insumosCadastrados as $insumo): ?>
          <div class="list-item">
            <div class="item-info" style="flex-grow: 1;">
              <strong>Descrição:</strong> <?php echo $insumo['nome']; ?><br>
              <strong>Quantidade:</strong> <?php echo $insumo['quantidade']; ?>
            </div>
            <div class="item-actions">
              <a href="?delete=<?php echo $insumo['id']; ?>" class="delete-button" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Nenhum insumo cadastrado.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>

<?php
require_once "conexao.php";
$erro = '';
$funcionariosCadastrados = [];

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nome'], $_POST['cargo'], $_POST['salario'])) {
        // insere no banco de dados
        $nome = $_POST['nome'] ?? '';
        $cargo = $_POST['cargo'] ?? '';
        $salario = $_POST['salario'] ?? 0;

        // Valida os campos obrigatórios
        if (empty($nome) || empty($cargo) || empty($salario)) {
            $erro = "Todos os campos obrigatórios devem ser preenchidos.";
        } else {
            $nome = $conn->real_escape_string($nome);
            $cargo = $conn->real_escape_string($cargo);
            $salario = (float)$salario;

            // Insere no banco de dados
            $sql = "INSERT INTO funcionarios (nome, cargo, salario) VALUES ('$nome', '$cargo', $salario)";

            if ($conn->query($sql) === TRUE) {
                $erro = "Novo funcionário cadastrado com sucesso!";
            } else {
                $erro = "Erro ao cadastrar: " . $conn->error;
            }
        }
    }
}

// Processa a exclusão de um funcionário
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $delete_sql = "DELETE FROM funcionarios WHERE id = $id";
    if ($conn->query($delete_sql) === TRUE) {
        header("Location: funcionarios.php");
        exit;
    } else {
        $erro = "Erro ao excluir funcionário: " . $conn->error;
    }
}

// Recupera os funcionários cadastrados para exibição
$result = $conn->query("SELECT * FROM funcionarios");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $funcionariosCadastrados[] = $row;
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
  <title>Cadastro de Funcionários</title>
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
      background-color: #219150;
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
    .employee-list {
      margin-top: 20px;
    }

    .employee-item {
      background: #ecf0f1;
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 15px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .employee-item strong {
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
  width: fit-content; /* Ajusta a largura do botão para o tamanho do conteúdo */
  margin-top: 10px; /* Adiciona um pequeno espaçamento acima */
  text-align: right; /* Alinha o botão à direita */
  margin-left: auto; /* Garante que o botão vá para o lado direito */
}

.delete-button:hover {
  background: #c0392b; /* Cor de fundo ao passar o mouse */
}


    .employee-item div {
      flex-grow: 1;
    }

    .employee-item a {
      display: flex;
      align-items: center;
      justify-content: center;
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
    <h2>Cadastro de Funcionários</h2>
    <form method="POST" action="">
      <div class="form-group">
        <label for="nome">Nome do Funcionário</label>
        <input type="text" id="nome" name="nome" placeholder="Digite o nome do funcionário" required>
      </div>
      <div class="form-group">
        <label for="cargo">Cargo</label>
        <input type="text" id="cargo" name="cargo" placeholder="Digite o cargo" required>
      </div>
      <div class="form-group">
        <label for="salario">Salário</label>
        <input type="number" id="salario" name="salario" placeholder="Digite o salário" required>
      </div>
      <button type="submit">Adicionar Funcionário</button>
    </form>
    
    <div class="employee-list" id="employeeList">
      <?php foreach ($funcionariosCadastrados as $funcionario): ?>
        <div class="employee-item">
          <div>
            <strong>Nome:</strong> <?php echo $funcionario['nome']; ?><br>
            <strong>Cargo:</strong> <?php echo $funcionario['cargo']; ?><br>
            <strong>Salário:</strong> R$ <?php echo number_format($funcionario['salario'], 2, ',', '.'); ?>
          </div>
          <a href="?delete=<?php echo $funcionario['id']; ?>" class="delete-button" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <script>
    <?php if ($erro == "Novo funcionário cadastrado com sucesso!"): ?>
      document.getElementById("nome").value = "";
      document.getElementById("cargo").value = "";
      document.getElementById("salario").value = "";
    <?php endif; ?>
  </script>
</body>
</html>

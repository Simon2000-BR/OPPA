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
      font-family: Arial, sans-serif; /* Define a fonte do corpo da página */
      background: linear-gradient(135deg, #f6f8f9, #e8f7f0); /* Fundo gradiente */
      margin: 0; /* Remove margens externas */
      padding: 0; /* Remove o preenchimento da página */
    }
    header {
      background-color: #27ae60; /* Cor de fundo do cabeçalho */
      padding: 10px 20px; /* Preenchimento dentro do cabeçalho */
      display: flex; /* Flexbox para disposição do conteúdo */
      justify-content: space-between; /* Espaçamento entre os itens */
      align-items: center; /* Alinha os itens verticalmente */
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Sombra no cabeçalho */
    }
    header a {
      color: white; /* Cor do texto dos links */
      text-decoration: none; /* Remove o sublinhado dos links */
      font-size: 16px; /* Tamanho da fonte */
      margin: 0 10px; /* Espaçamento entre os links */
      transition: color 0.3s; /* Transição suave para a cor */
    }
    header a:hover {
      color: #c3e6cd; /* Cor ao passar o mouse sobre o link */
    }
    .back-button {
      background-color: #e74c3c;  /* Cor de fundo do botão */
      color: white; /* Cor do texto do botão */
      border: none; /* Remove borda do botão */
      border-radius: 5px; /* Bordas arredondadas */
      padding: 12px 45px; /* Preenchimento do botão */
      font-size: 14px; /* Tamanho da fonte */
      cursor: pointer; /* Cursor de mão ao passar o mouse */
      transition: background-color 0.3s; /* Transição suave para a cor de fundo */
    }
    .back-button:hover {
      background-color: #219150; /* Cor ao passar o mouse sobre o botão */
    }
    .container {
      max-width: 600px; /* Largura máxima do conteúdo */
      margin: 80px auto 20px; /* Margens superior e inferior e centraliza */
      background: white; /* Fundo branco */
      border-radius: 10px; /* Bordas arredondadas */
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1); /* Sombra do conteúdo */
      padding: 20px; /* Preenchimento interno */
    }
    h2 {
      text-align: center; /* Alinha o título no centro */
      color: #2c3e50; /* Cor do título */
    }
    .form-group {
      margin-bottom: 15px; /* Espaçamento inferior entre os campos */
    }
    label {
      display: block; /* Faz o label ocupar toda a largura */
      font-weight: bold; /* Deixa o texto do label em negrito */
      margin-bottom: 5px; /* Espaçamento inferior do label */
      color: #34495e; /* Cor do texto do label */
    }
    input {
      width: 100%; /* Faz o campo de entrada ocupar toda a largura */
      padding: 10px; /* Preenchimento do campo */
      border: 1px solid #ccc; /* Borda do campo */
      border-radius: 5px; /* Bordas arredondadas */
      font-size: 14px; /* Tamanho da fonte */
    }
    input:focus {
      outline: none; /* Remove o contorno do campo */
      border-color: #27ae60; /* Cor da borda ao focar */
      box-shadow: 0 0 5px rgba(39, 174, 96, 0.5); /* Sombra ao focar */
    }
    button {
      width: 100%; /* Botão ocupa toda a largura */
      padding: 12px; /* Preenchimento do botão */
      background: #27ae60; /* Cor de fundo do botão */
      color: white; /* Cor do texto do botão */
      border: none; /* Remove a borda do botão */
      border-radius: 5px; /* Bordas arredondadas */
      font-size: 16px; /* Tamanho da fonte */
      cursor: pointer; /* Cursor de mão ao passar o mouse */
      transition: 0.3s; /* Transição suave para a cor de fundo */
    }
    button:hover {
      background: #219150; /* Cor de fundo ao passar o mouse */
    }
    .employee-list {
      margin-top: 20px; /* Espaçamento superior na lista de funcionários */
    }

    .employee-item {
      background: #ecf0f1; /* Cor de fundo do item da lista */
      padding: 15px; /* Preenchimento do item */
      border-radius: 10px; /* Bordas arredondadas */
      margin-bottom: 15px; /* Espaçamento inferior entre os itens */
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Sombra do item */
      display: flex; /* Flexbox para disposição do conteúdo */
      justify-content: space-between; /* Espaço entre os itens */
      align-items: center; /* Alinha os itens no centro */
    }

    .employee-item strong {
      color: #2c3e50; /* Cor do texto em negrito */
    }

    .delete-button {
      background: #e74c3c; /* Cor de fundo do botão de excluir */
      color: white; /* Cor do texto do botão */
      padding: 8px 12px; /* Preenchimento do botão */
      border: none; /* Remove a borda do botão */
      border-radius: 5px; /* Bordas arredondadas */
      cursor: pointer; /* Cursor de mão ao passar o mouse */
      transition: 0.3s; /* Transição suave para a cor de fundo */
      display: block; /* Faz o botão ser exibido em bloco */
      width: fit-content;  /* Ajusta a largura do botão para o conteúdo */
      margin-top: 10px;  /* Espaçamento superior */
      text-align: right;  /* Alinha o botão à direita */
      margin-left: auto;  /* Garante que o botão fique à direita */
    }

    .delete-button:hover {
      background: #c0392b; /* Cor de fundo ao passar o mouse sobre o botão de excluir */
    }

    .employee-item div {
      flex-grow: 1; /* Faz o conteúdo dentro do item crescer para preencher o espaço disponível */
    }

    .employee-item a {
      display: flex; /* Flexbox para alinhar o link */
      align-items: center; /* Alinha os itens no centro */
      justify-content: center; /* Centraliza o conteúdo do link */
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
  <!-- Título do formulário de cadastro de funcionários -->
  <h2>Cadastro de Funcionários</h2>

  <!-- Formulário para adicionar um novo funcionário -->
  <form method="POST" action="">
    
    <!-- Campo para inserir o nome do funcionário -->
    <div class="form-group">
      <label for="nome">Nome do Funcionário</label>
      <input type="text" id="nome" name="nome" placeholder="Digite o nome do funcionário" required>
    </div>
    
    <!-- Campo para inserir o cargo do funcionário -->
    <div class="form-group">
      <label for="cargo">Cargo</label>
      <input type="text" id="cargo" name="cargo" placeholder="Digite o cargo" required>
    </div>

    <!-- Campo para inserir o salário do funcionário -->
    <div class="form-group">
      <label for="salario">Salário</label>
      <input type="number" id="salario" name="salario" placeholder="Digite o salário" required>
    </div>

    <!-- Botão para enviar o formulário e adicionar o funcionário -->
    <button type="submit">Adicionar Funcionário</button>
  </form>
  
  <!-- Lista dos funcionários cadastrados -->
  <div class="employee-list" id="employeeList">
    <!-- Exibe cada funcionário cadastrado -->
    <?php foreach ($funcionariosCadastrados as $funcionario): ?>
      <div class="employee-item">
        <div>
          <strong>Nome:</strong> <?php echo $funcionario['nome']; ?><br>
          <strong>Cargo:</strong> <?php echo $funcionario['cargo']; ?><br>
          <strong>Salário:</strong> R$ <?php echo number_format($funcionario['salario'], 2, ',', '.'); ?>
        </div>
        <!-- Link para excluir o funcionário com confirmação -->
        <a href="?delete=<?php echo $funcionario['id']; ?>" class="delete-button" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Script para limpar os campos do formulário após o cadastro bem-sucedido -->
<script>
  <?php if ($erro == "Novo funcionário cadastrado com sucesso!"): ?>
    document.getElementById("nome").value = "";    // Limpa o campo "Nome"
    document.getElementById("cargo").value = "";   // Limpa o campo "Cargo"
    document.getElementById("salario").value = ""; // Limpa o campo "Salário"
  <?php endif; ?>
</script>

</body>
</html>

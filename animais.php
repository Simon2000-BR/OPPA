<?php
// Iniciar a sessão para garantir que o ID do usuário está disponível
session_start();

// Verificar se o usuário está logado (se o user_id está na sessão)
if (!isset($_SESSION['usuario_id'])) {
    die("Você precisa estar logado para cadastrar um animal.");
}

// Incluir o arquivo de conexão
require_once 'conexao.php';

$erro = ''; // Variável para armazenar o erro

// Adicionar condição para verificar a ação de cadastrar antes de inserir no banco
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    // Coletar os dados do formulário
    $nome = $_POST['nome'];
    $alimentacao = $_POST['alimentacao'];
    $idade = $_POST['idade'];
    $producao = $_POST['producao'];

    // Verifica se os campos obrigatórios foram preenchidos
    if (empty($nome) || empty($alimentacao) || empty($idade)) {
        $erro = "Todos os campos obrigatórios devem ser preenchidos."; // Mensagem de erro
    } else {
        // Previne SQL Injection
        $nome = $conn->real_escape_string($nome);
        $alimentacao = $conn->real_escape_string($alimentacao);
        $idade = (int)$idade;
        $producao = $conn->real_escape_string($producao);

        // Obtém o user_id da sessão
        $user_id = $_SESSION['usuario_id'];

        // Insere o novo animal no banco, incluindo o user_id
        $sql = "INSERT INTO animais (nome, alimentacao, idade, producao, user_id) 
                VALUES ('$nome', '$alimentacao', $idade, '$producao', $user_id)";

        // Verifica se a inserção foi bem-sucedida
        if ($conn->query($sql) === TRUE) {
            $erro = "Novo animal cadastrado com sucesso!";
            // Redireciona para a própria página para evitar reenvio do formulário
            header("Location: animais.php");
            exit; // Garante que o código após o redirecionamento não será executado
        } else {
            $erro = "Erro ao cadastrar: " . $conn->error; // Erro na inserção
        }
    }
}

// Verifica se a ação é excluir um animal
if (isset($_GET['delete'])) {
    $animal_id = (int) $_GET['delete']; // Obtém o ID do animal para exclusão

    // Previne SQL Injection
    $animal_id = $conn->real_escape_string($animal_id);

    // Verifica se o animal pertence ao usuário logado
    $sql = "DELETE FROM animais WHERE id = $animal_id AND user_id = {$_SESSION['usuario_id']}";

    if ($conn->query($sql) === TRUE) {
        $erro = "Animal excluído com sucesso!";
    } else {
        $erro = "Erro ao excluir o animal: " . $conn->error;
    }
}

// Recupera os animais cadastrados
$sql = "SELECT * FROM animais WHERE user_id = {$_SESSION['usuario_id']}";
$result = $conn->query($sql);

$animaisCadastrados = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $animaisCadastrados[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro de Animais</title>
  <style>
    
    /* Estilo para o corpo da página */
    body {
      font-family: Arial, sans-serif; /* Define a fonte do corpo como Arial */
      background: linear-gradient(135deg, #f6f8f9, #e8f7f0); /* Gradiente de fundo suave */
      margin: 0; /* Remove margens padrão */
      padding: 0; /* Remove o padding padrão */
    }

    /* Estilo para o cabeçalho da página */
    header {
      background-color: #27ae60; /* Cor de fundo do cabeçalho */
      padding: 10px 20px; /* Espaçamento interno */
      display: flex; /* Usa o Flexbox para layout */
      justify-content: space-between; /* Espaço entre os itens */
      align-items: center; /* Alinha os itens verticalmente */
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Sombra suave ao redor */
    }

    /* Estilo para os links dentro do cabeçalho */
    header a {
      color: white; /* Cor do texto dos links */
      text-decoration: none; /* Remove o sublinhado */
      font-size: 16px; /* Tamanho da fonte */
      margin: 0 10px; /* Espaçamento entre os links */
      transition: color 0.3s; /* Efeito de transição na cor do link */
    }

    /* Estilo para quando o link é hover (quando passa o mouse sobre ele) */
    header a:hover {
      color: #c3e6cd; /* Cor do texto ao passar o mouse */
    }

    /* Estilo para o botão de "Voltar" */
    .back-button {
      background-color: #e74c3c; /* Cor de fundo */
      color: white; /* Cor do texto */
      border: none; /* Remove a borda */
      border-radius: 5px; /* Bordas arredondadas */
      padding: 12px 45px; /* Espaçamento interno */
      font-size: 14px; /* Tamanho da fonte */
      cursor: pointer; /* Cursor de mãozinha ao passar o mouse */
      transition: background-color 0.3s; /* Efeito de transição na cor de fundo */
    }

    /* Estilo para quando o botão de "Voltar" é hover */
    .back-button:hover {
      background-color: #c0392b; /* Cor do botão ao passar o mouse */
    }

    /* Estilo para o container principal da página */
    .container {
      max-width: 600px; /* Largura máxima */
      margin: 80px auto 20px; /* Espaçamento vertical e centralização */
      background: white; /* Cor de fundo branca */
      border-radius: 10px; /* Bordas arredondadas */
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1); /* Sombra suave */
      padding: 20px; /* Espaçamento interno */
    }

    /* Estilo para os títulos */
    h2 {
      text-align: center; /* Alinha o texto ao centro */
      color: #2c3e50; /* Cor do texto */
    }

    /* Estilo para os grupos de formulário */
    .form-group {
      margin-bottom: 15px; /* Espaçamento inferior entre os grupos de campos */
    }

    /* Estilo para os rótulos dos campos do formulário */
    label {
      display: block; /* Exibe o rótulo como bloco, ocupando a linha inteira */
      font-weight: bold; /* Deixa o texto em negrito */
      margin-bottom: 5px; /* Espaçamento inferior */
      color: #34495e; /* Cor do texto */
    }

    /* Estilo para os campos de entrada do formulário */
    input {
      width: 100%; /* Largura total do campo */
      padding: 10px; /* Espaçamento interno */
      border: 1px solid #ccc; /* Borda cinza */
      border-radius: 5px; /* Bordas arredondadas */
      font-size: 14px; /* Tamanho da fonte */
    }

    /* Estilo para o campo de entrada quando está em foco (clicado) */
    input:focus {
      outline: none; /* Remove o contorno padrão */
      border-color: #27ae60; /* Cor da borda quando em foco */
      box-shadow: 0 0 5px rgba(39, 174, 96, 0.5); /* Sombra ao redor do campo */
    }

    /* Estilo para os botões */
    button {
      width: 100%; /* Largura total do botão */
      padding: 12px; /* Espaçamento interno */
      background: #27ae60; /* Cor de fundo */
      color: white; /* Cor do texto */
      border: none; /* Remove a borda */
      border-radius: 5px; /* Bordas arredondadas */
      font-size: 16px; /* Tamanho da fonte */
      cursor: pointer; /* Cursor de mãozinha ao passar o mouse */
      transition: 0.3s; /* Transição suave */
    }

    /* Estilo para quando o botão é hover */
    button:hover {
      background: #219150; /* Cor de fundo ao passar o mouse */
    }

    /* Estilo para o container da lista de itens */
    .list-container {
      margin-top: 20px; /* Espaçamento superior */
    }

    /* Estilo para cada item da lista */
    .list-item {
      background: #ecf0f1; /* Cor de fundo */
      padding: 15px; /* Espaçamento interno */
      border-radius: 10px; /* Bordas arredondadas */
      margin-bottom: 20px; /* Espaçamento inferior */
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Sombra suave */
      position: relative; /* Posicionamento relativo */
      word-wrap: break-word; /* Quebra de palavra longa */
      overflow-wrap: break-word; /* Quebra de palavra longa */
      width: 100%; /* Largura total */
      box-sizing: border-box; /* Inclui o padding e a borda no tamanho total */
    }

    /* Estilo para o botão de excluir */
    .delete-button {
      background: #e74c3c; /* Cor de fundo */
      color: white; /* Cor do texto */
      padding: 8px 12px; /* Espaçamento interno */
      border: none; /* Remove a borda */
      border-radius: 5px; /* Bordas arredondadas */
      cursor: pointer; /* Cursor de mãozinha ao passar o mouse */
      transition: 0.3s; /* Transição suave */
      display: block; /* Exibe o botão como bloco */
      width: fit-content; /* Largura ajustada ao conteúdo */
      margin-top: 10px; /* Espaçamento superior */
      text-align: right; /* Alinha o texto à direita */
      margin-left: auto; /* Centraliza o botão */
    }

    /* Estilo para quando o botão de excluir é hover */
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
    <nav>
      <a href="bovinos.php">Bovinos</a>
      <a href="suinos.php">Suínos</a>
      <a href="ovelhas.php">Ovelhas</a>
      <a href="aves.php">Aves</a>
      <a href="animais.php">Animais</a>
      <a href="logout.php">Sair</a> 
    </nav>

  </header>

  <div class="container">
    <h2>Cadastro de Animais</h2>
    <?php if (!empty($erro)): ?>
      <p style="color: green; text-align: center;"><?php echo $erro; ?></p>
    <?php endif; ?>
    <form method="POST">
      <input type="hidden" name="action" value="add">
      <div class="form-group">
        <label for="nome">Nome do Animal</label>
        <input type="text" id="nome" name="nome" placeholder="Digite o nome do animal" required>
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
      <button type="submit">Adicionar Animal</button>
    </form>

    <!-- Exibe os animais cadastrados -->
    <div class="list-container">
      <?php foreach ($animaisCadastrados as $animal): ?>
        <div class="list-item">
          <strong>Nome:</strong> <?php echo $animal['nome']; ?><br>
          <strong>Alimentação:</strong> <?php echo $animal['alimentacao']; ?><br>
          <strong>Idade:</strong> <?php echo $animal['idade']; ?><br>
          <strong>Produção:</strong> <?php echo $animal['producao']; ?><br>
          <a href="?delete=<?php echo $animal['id']; ?>" class="delete-button" onclick="return confirm('Tem certeza que deseja excluir este animal?');">Excluir</a>
          </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>

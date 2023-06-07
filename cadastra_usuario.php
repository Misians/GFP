<?php
    session_start();
    ob_start();

    if(isset($_SESSION['nomeUsuario']) == false) {
        $_SESSION['mensagem'] = "<p class='msgErro'>Necessário fazer o login!</p>";
        header('Location: index.php');
    }

    include 'frontend/cabecalho.html';
    include 'DB/conexao.php';

    $consulta = new Consulta();

    if(isset($_POST["cadastrar"])){
        $nome = $_POST["nome"];
        $funcao = $_POST["funcao"];
        $campus = $_POST['campus'];
        $condicao = $_POST["condicao"];
        $email = $_POST["email"];
        $telefone = $_POST["telefone"];

        $cadastro = $consulta->cadastrarUsuario($nome, $funcao, $campus, $condicao, $email, $telefone);

        if($cadastro->rowCount() > 0)
            $_SESSION['mensagem'] = "<p class='msgSucesso'>Usuário cadastrado com sucesso!</p>";
        else
            $_SESSION['mensagem'] = "<p class='msgErro'>Não foi possível cadastrar o usuário!</p>";
    }
?>

<h2>Adicionar Usuário</h2>
<hr>

<?php
    if(isset($_SESSION['mensagem'])) {
        echo $_SESSION['mensagem'];
        unset($_SESSION['mensagem']);
    }
?>

<form method="POST" autocomplete="off">
    <label>Nome:</label>
    <input class="nomeEmail" type="text" name="nome" placeholder="Digite o Nome do Usuário..." pattern="[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]+$" required>
    <br><br>
    <label>Função:</label>
    <select name="funcao" required>
        <option value="" selected>Selecionar...</option>
        <option value="Servidor">Servidor</option>
        <option value="Terceirizado">Terceirizado</option>
        <option value="Estagiario">Estagiario</option>
    </select>
    <br><br>
    <label>Campus:</label>
    <select name="campus">
        <option value="" selected>Selecionar...</option>
        <option value="Mossoró">Mossoró</option>
        <option value="Natal">Natal</option>
    </select>
    <br><br>
    <label>Condição Atual:</label>
    <select name="condicao">
        <option value="" selected>Selecionar...</option>
        <option value="Trabalhando">Trabalhando</option>
        <option value="Ferias">Férias</option>
        <option value="Afastado">Afastado</option>
    </select>
    <br><br>
    <label>Email:</label>
    <input class="nomeEmail" type="text" name="email" placeholder="Digite o Email do Usuário...">
    <br><br>
    <label>Telefone:</label>
    <input type="tel" name="telefone" id="telefone" maxlength="15" placeholder="Digite o Telefone do Usuário...">
    <br><br>
    <input type="submit" name="cadastrar" value="Cadastrar Usuário">
</form>

<script>$("#telefone").mask("(99) 99999-9999");</script>

<?php include 'frontend/rodape.html'; ?>

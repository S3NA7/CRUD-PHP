<?php

include 'conexao.php';

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    $sql = "INSERT INTO usuarios (nome, email) VALUES (?, ?)";

    if ($stmt = mysqli_prepare($conexao, $sql)) {
        mysqli_stmt_bind_param($stmt, "ss", $nome, $email);
        if (mysqli_stmt_execute($stmt)) {
            $mensagem = "Usuário cadastrado com sucesso!";
        } else {
            $mensagem = "Erro ao cadastrar usuário: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $mensagem = "Erro na preparação da consulta: " . mysqli_error($conexao);
    }
}

$sql_select = "SELECT id, nome, email FROM usuarios";
$resultado_select = mysqli_query($conexao, $sql_select);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de usuários</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; }
        .container { max-width: 800px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2 { text-align: center; }
        form { margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #ccc; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"] { width: 98%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; }
        button { background-color: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #218838; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
        .mensagem.sucesso { padding: 10px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 15px; }
        .mensagem.erro { padding: 10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 15px; }
    </style>
</head>
   <body>
    <div class="container">
        <h2>Cadastro de Usuários</h2>

        <?php if (!empty($mensagem)) { ?>
            <div class="mensagem <?php echo (strpos($mensagem, 'Erro') === false) ? 'sucesso' : 'erro'; ?>">
                <?php echo $mensagem; ?>
            </div>
        <?php } ?>

        <form action="index.php" method="post">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <button type="submit">Cadastrar Usuário</button>
        </form>

        <h2>Lista de Usuários</h2>
        <table>
            <thead>
               <tr>
                   <th>ID</th>
                   <th>Nome</th>
                   <th>Email</th>
               </tr>
            </thead>
            <tbody>
            <?php
            if ($resultado_select && mysqli_num_rows($resultado_select) > 0) {
                while($linha = mysqli_fetch_assoc($resultado_select)) {
                    echo "<tr>";
                    echo "<td>" . $linha['id'] . "</td>";
                    echo "<td>" . $linha['nome'] . "</td>";
                    echo "<td>" . $linha['email'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Nenhum usuário cadastrado.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

   </body>
</html>
<?php mysqli_close($conexao); ?>

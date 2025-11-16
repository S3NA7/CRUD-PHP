<?php
session_start(); 

include 'conexao.php';

$mensagem = "";
$status = "";

if (isset($_SESSION['mensagem'])) {
    $mensagem = $_SESSION['mensagem'];
    $status = $_SESSION['status'];
    
    unset($_SESSION['mensagem']);
    unset($_SESSION['status']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['nome'], $_POST['email'])) {
        $nome = $_POST['nome'];
        $email = $_POST['email'];

        $sql = "INSERT INTO usuarios (nome, email) VALUES (?, ?)";

        if ($stmt = mysqli_prepare($conexao, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $nome, $email);
            if (mysqli_stmt_execute($stmt)) {
                $mensagem = "Usuário cadastrado com sucesso!";
                $status = "sucesso";
            } else {
                $mensagem = "Erro ao cadastrar usuário: " . mysqli_stmt_error($stmt);
                $status = "erro";
            }
            mysqli_stmt_close($stmt);
        } else {
            $mensagem = "Erro na preparação da consulta: " . mysqli_error($conexao);
            $status = "erro";
        }
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
        button { padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; color: white; }
        button[type="submit"] { background-color: #28a745; }
        button[type="submit"]:hover { background-color: #218838; }
        .btn-excluir { background-color: #dc3545; padding: 5px 10px; font-size: 12px; }
        .btn-excluir:hover { background-color: #c82333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
        td.acoes { text-align: center; } 
        .mensagem.sucesso { padding: 10px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 15px; }
        .mensagem.erro { padding: 10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 15px; }
    </style>
</head>
    <body>
    <div class="container">
        <h2>Cadastro de Usuários</h2>

        <?php if (!empty($mensagem)) { ?>
            <div class="mensagem <?php echo $status; ?>">
                <?php echo htmlspecialchars($mensagem); ?>
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
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($resultado_select && mysqli_num_rows($resultado_select) > 0) {
                while($linha = mysqli_fetch_assoc($resultado_select)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($linha['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($linha['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($linha['email']) . "</td>";
                    
                    echo "<td class='acoes'>";
                    echo '<form action="delete.php" method="post" style="display: inline-block;" onsubmit="return confirm(\'Tem certeza que deseja excluir este usuário?\');">';
                        echo '<input type="hidden" name="id_excluir" value="' . $linha['id'] . '">';
                        echo '<button type="submit" class="btn-excluir">Excluir</button>';
                    echo '</form>';
                    echo "</td>";
                    
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Nenhum usuário cadastrado.</td></tr>"; 
            }
            ?>
            </tbody>
        </table>
    </div>

    </body>
</html>
<?php mysqli_close($conexao); ?>
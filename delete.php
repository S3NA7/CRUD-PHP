<?php
session_start();

include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_excluir'])) {
    
    $id_excluir = $_POST['id_excluir'];

    $sql_delete = "DELETE FROM usuarios WHERE id = ?";

    if ($stmt = mysqli_prepare($conexao, $sql_delete)) {
        
        mysqli_stmt_bind_param($stmt, "i", $id_excluir); 
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['mensagem'] = "Usuário excluído com sucesso!";
            $_SESSION['status'] = "sucesso";
        } else {
            $_SESSION['mensagem'] = "Erro ao excluir usuário: " . mysqli_stmt_error($stmt);
            $_SESSION['status'] = "erro";
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['mensagem'] = "Erro na preparação da exclusão: " . mysqli_error($conexao);
        $_SESSION['status'] = "erro";
    }

    mysqli_close($conexao);

} else {
    $_SESSION['mensagem'] = "Ação inválida.";
    $_SESSION['status'] = "erro";
}

header("Location: index.php");
exit;
?>
<?php
require_once('class/config.php');
require_once('autoload.php');

// Verificar se existe o post com todos os dados
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['repeat_password'])) {
    // Receber valores vindos do Post e Clear
    $name = clearPost($_POST['name']);
    $email = clearPost($_POST['email']);
    $password = clearPost($_POST['password']);
    $repeat_password = clearPost($_POST['repeat_password']);

    // Verificar se valores vindos do Post não estão vazios
    if(empty($name) or empty($email) or empty($password) or empty($repeat_password) or empty($_POST['terms'])) {
        $err_geral = "Todos os campos são obrigatórios!";
    } else {
        // Instanciar a classe User
        $user = new User($name, $email, $password);

        // Setar a repetição de senha
        $user->set_repetition($repeat_password);

        // Validar o cadastro
        $user->validate_register();

        // Se não tiver nenhum erro - Está vazio erros
        if(empty($user->err)) {
            // Inserir
            if($user->insert()) {
                header('location: index.php');
            } else {
                // Deu errado - Erro geral
                $err_geral = $user->err["err_geral"];
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <title>Cadastro</title>
</head>

<body>
    <form method="POST">

        <h1>Cadastrar</h1>

        <?php if(isset($err_geral)) {?>
        <div class="err-geral animate__animated animate__rubberBand">
            <?php echo $err_geral; ?>
        </div>
        <?php } ?>

        <div class="input-group">
            <img class="input-icon" src="img/card.png">
            <input <?php if (isset ($user->err["err_name"]) or isset($err_geral)) {
                echo 'class="err-input"'; 
            }?> name="name" type="text"
            <?php if(isset($_POST['name'])) {
                echo 'value="'.$_POST['name'].'"';
            }?> placeholder="Nome Completo" required>
            <div class="err">
                <?php if(isset($user->err["err_name"])) {
                    echo $user->err["err_name"];
                }?> </div>
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/user.png">
            <input <?php if (isset ($user->err["err_email"]) or isset($err_geral)) {
                echo 'class="err-input"'; 
            }?> type="email" name="email"
            <?php if(isset($_POST['email'])) {
                echo 'value="'.$_POST['email'].'"';
            }?> placeholder="Seu melhor email" required>
            <div class="err">
                <?php if(isset($user->err["err_email"])) {
                    echo $user->err["err_email"];
                }?> </div>
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/lock.png">
            <input <?php if (isset ($user->err["err_password"]) or isset($err_geral)) {
                echo 'class="err-input"'; 
            }?> type="password" name="password"
            <?php if(isset($_POST['password'])) {
                echo 'value="'.$_POST['password'].'"';
            }?> placeholder="Senha mínimo 6 Dígitos" required>
            <div class="err">
                <?php if(isset($user->err["err_password"])) {
                    echo $user->err["err_password"];
                }?> </div>
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/lock-open.png">
            <input <?php if (isset ($user->err["err_repeat"]) or isset($err_geral)) {
                echo 'class="err-input"';
            }?> type="password" name="repeat_password"
            <?php if(isset($_POST['repeat_password'])) {
                echo 'value="'.$_POST['repeat_password'].'"';
            }?> placeholder="Repita a senha criada" required>
            <div class="err">
                <?php if(isset($user->err["err_repeat"])) {
                    echo $user->err["err_repeat"];
                }?> </div>
        </div>

        <div <?php if(isset($err_geral) && $err_geral=="Todos os campos são obrigatórios!") {
            echo 'class="input-group err-input"' ;
        } else {
            echo 'class="input-group"';
        }?>>
            <input type="checkbox" id="terms" name="terms" value="ok" required>
            <label for="terms">Ao se cadastrar você concorda com a nossa <a class="link" href="#">Política de
                    Privacidade</a> e os <a class="link" href="#">Termos de uso</a></label>
        </div>

        <button class="btn-blue" type="submit">Cadastrar</button>
        <a href="index.php">Já tenho uma conta</a>
    </form>
</body>

</html>
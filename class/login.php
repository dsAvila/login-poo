<?php
require_once('DB.php');

class Login {
    protected string $table = 'users';
    public string $email;
    private string $password;
    public string $name;
    private string $token;
    public array $err=[];

    public function auth($email, $password) {
        // Criptografar a senha
        $password_crypto = sha1($password);

        // Verificar se tem esse usuário cadastrado
        $sql = "SELECT * FROM $this->table WHERE email=? AND password=? LIMIT 1";
        $sql = DB::prepare($sql);
        $sql->execute(array($email, $password_crypto));
        $user = $sql->fetch(PDO::FETCH_ASSOC);
        if($user) {
            // Criar um token
            $this->token = sha1(uniqid().date('d-m-Y-H-i-s'));

            // Atualizar este token no banco
            $sql = "UPDATE $this->table SET token=? WHERE email=? AND password=? LIMIT 1";
            $sql = DB::prepare($sql);
            if($sql->execute(array($this->token, $email, $password_crypto))) {
                // Colocar o token na sessão
                $_SESSION['TOKEN'] = $this->token;
                // Redirecionamos nosso usuário para uma área restrita
                header('location: restricted/index.php');
            } else {
                $this->err["err_geral"] = "Falha ao se comunicar com servidor!";
            }
        } else {
            $this->err["err_geral"] = "Usuário ou senha incorretos.";
        }
    }

    public function isAuth($token) {
        $sql = "SELECT * FROM $this->table WHERE token=? LIMIT 1";
        $sql = DB::prepare($sql);
        $sql->execute(array($token));
        $user = $sql->fetch(PDO::FETCH_ASSOC);
        if($user) {
            $this->name = $user["name"];
            $this->email = $user["email"];
        } else {
            header('location: ../index.php');
        }
    }
}
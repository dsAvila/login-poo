<?php
require_once('crud.php');

class User extends Crud{
    protected string $table = 'users';

    function __construct(
        public string $name,
        private string $email,
        private string $password,
        private string $repeat_password="",
        private string $recover_password="",
        private string $token="",
        private string $confirmation_code="",
        private string $status="",
        public array $err=[]
    ){}

    public function set_repetition($repeat_password){
        $this->repeat_password = $repeat_password;
    }

    public function validate_register(){
        // Validação do nome
        if (!preg_match("/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ'\s]+$/",$this->name)) {
           $this->err["err_name"] = "Por favor informe um nome válido!";
        }

        // Verificar se email é válido
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            $this->err["err_email"] = "Formato de e-mail inválido!";
        }

        // Verificar se senha tem mais de 6 dígitos
        if(strlen($this->password) < 6){
            $this->err["err_password"] = "Senha deve ter 6 caracteres ou mais!";
        }

        if($this->password !== $this->repeat_password){
            $this->err["err_repeat"] = "Senha e repetição de senha diferentes!";
        }

    }

    public function insert() {
        // Verificar se este email já está cadastrado no banco
        $sql = "SELECT * FROM users WHERE email=? LIMIT 1";
        $sql = DB::prepare($sql);
        $sql->execute(array($this->email));
        $user = $sql->fetch();
        // Se não existir o usuário - Adicionar no banco
        if (!$user) {
            $registration_date = date('d/m/Y');
            $password_crypto = sha1($this->password);
            $sql = "INSERT INTO $this->table VALUES (null,?,?,?,?,?,?,?,?)";
            $sql = DB::prepare($sql);

            return $sql->execute(array($this->name, $this->email, $password_crypto, $this->recover_password, 
            $this->token, $this->confirmation_code, $this->status, $registration_date));
        } else {
            $this->err["err_geral"] = "Usuário já cadastrado!";
        }
    }

    public function update($id) {
        $sql = "UPDATE $this->table SET token=? WHERE id=?";
        $sql = DB::prepare($sql);

        return $sql->execute(array($token,$id));
    }
}
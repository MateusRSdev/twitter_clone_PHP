<?php

namespace App\Models;

use MF\Model\Model;

class usuario extends Model
{
    private $id;
    private $nome;
    private $email;
    private $senha;

    public function __get($attr)
    {
        return $this->$attr;
    }

    public function __set($attr, $value)
    {
        $this->$attr = $value;
    }

    //salvar
    public function Salvar()
    {
        $query = "INSERT INTO usuarios(nome,email,senha) VALUES(:nome,:email,:senha)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":nome", $this->__get("nome"));
        $stmt->bindValue(":email", $this->__get("email"));
        $stmt->bindValue(":senha", $this->__get("senha")); //md5 = hash 32 caracteres
        $stmt->execute();
    }

    // validar se um cadastro pode ser feito

    public function validarCadastro()
    {
        $valido = true;

        if (strlen($this->__get("nome")) < 3) {
            $valido = false;
        }

        if (strlen($this->__get("email")) < 3) {
            $valido = false;
        }

        if (strlen($this->__get("senha")) < 3) {
            $valido = false;
        }


        return $valido;
    }

    // recuperar um usuario por email

    public function GetUsuarioPorEmail()
    {
        $query = "SELECT nome,email FROM usuarios WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":email", $this->__get("email"));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function autenticar()
    {
        $query = "SELECT id, nome, email FROM usuarios where email = :email AND senha = :senha";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":email", $this->__get("email"));
        $stmt->bindValue(":senha", $this->__get("senha"));
        $stmt->execute();

        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($usuario["id"] != "" && $usuario["nome"] != "") {
            $this->__set("id", $usuario["id"]);
            $this->__set("nome", $usuario["nome"]);
        }

        return $this;

    }

    public function getAll()
    {
        $query = "SELECT
         u.id,
          u.nome,
           u.email,
           (select count(*) from usuarios_seguidores as us where us.id_usuario = :id_usuario and id_usuario_seguindo = u.id)as seguindo_sn 
           FROM usuarios as u 
           where u.nome like :nome and u.id != :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":nome", "%" . $this->__get("nome") . "%");
        $stmt->bindValue(":id_usuario", $this->__get("id"));
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function seguirUsuario($id_usuario_seguindo)
    {
        echo "seguir usuario";
        $query = "INSERT INTO usuarios_seguidores(id_usuario,id_usuario_seguindo)VALUES(:id_usuario,:id_usuario_seguindo)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id_usuario", $this->__get("id"));
        $stmt->bindValue(":id_usuario_seguindo", $id_usuario_seguindo);
        $stmt->execute();
        return true;
    }
    public function deixarSeguirUsuario($id_usuario_seguindo)
    {
        $query = "DELETE FROM usuarios_seguidores where id_usuario = :id_usuario and id_usuario_seguindo = :id_usuario_seguindo";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id_usuario", $this->__get("id"));
        $stmt->bindValue(":id_usuario_seguindo", $id_usuario_seguindo);
        $stmt->execute();
        return true;
    }

    // recuperar as informacoes
    public function getInfoUsuario(){
        $query = "SELECT nome from usuarios where id = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id_usuario", $this->__get("id"));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // total de tweets
    public function getTotalTweets(){
        $query = "SELECT count(*) as total_tweet from tweets where id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id_usuario", $this->__get("id"));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // total de usuarios que estamos seguindo
    public function getTotalSeguindo(){
        $query = "SELECT count(*) as total_seguindo from usuarios_seguidores where id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id_usuario", $this->__get("id"));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // total de seguidores
    public function getTotalSeguidores(){
        $query = "SELECT count(*) as total_seguidores from usuarios_seguidores where id_usuario_seguindo = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id_usuario", $this->__get("id"));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function removerTweet(){
        $query = "delete from tweets where id = :id_deletar";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id_deletar", $this->__get("id_tweet_selecionado"));
        $stmt->execute();

    }
}

?>
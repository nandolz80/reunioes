<?php

class Usuario {

    function get_listAll($parameter) {
        $sql = "SELECT id, login, lastLogin
                FROM usuarios
                 ";

        if ($parameter) {
            $sql .= " WHERE
                            nome     LIKE :filtro ";
        }

        $stmt = DB::prepare($sql);

        if ($parameter) {
            $filtro = "%" . $parameter . "%";
            $stmt->bindParam("filtro", $filtro);
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }

    function get_list($id) {
        $sql = "SELECT id, login, senha FROM usuarios WHERE id = :id";
        $stmt = DB::prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    function post_save($usuario) {

        if ($usuario->id) {
            $sql = "
				UPDATE usuarios SET
					senha            = :senha,
					login            = :login
				WHERE
					id               = :id";
        } else {
            $verificaLogin = $this->verificaLogin($usuario->usuario);
            if ($verificaLogin == 0) {
                $sql = "INSERT INTO usuarios (senha, login) "
                        . " VALUES (:senha, :login)";
            } else {
                return "Login já cadastrado";
            }
        }

        $stmt = DB::prepare($sql);

        $stmt->bindParam("senha", $usuario->senha);
        $stmt->bindParam("login", $usuario->usuario);

        if ($usuario->id) {
            $stmt->bindParam("id", $usuario->id);
        }

        $stmt->execute();

        if (!$usuario->id) {
            $usuario->id = DB::lastInsertId();
        }

        return $usuario;
    }

    function post_delete($usuario) {
        $stmt = DB::prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt->bindParam("id", $usuario->id);
        $stmt->execute();
        return true;
    }

    public function post_login($usuarios) {
        if ((empty($usuarios->login)) or ( empty($usuarios->senha))) {
            throw new Exception("Login ou senha precisam ser preenchidos");
        }

        $sql = "SELECT
                *              
                FROM usuarios
                WHERE (login=:login and senha=:senha)";
        $stmt = DB::prepare($sql);
        $stmt->bindParam("login", $usuarios->login);
        $stmt->bindParam("senha", $usuarios->senha);
        $stmt->execute();

        $usuario = $stmt->fetch();

        if ($usuario != null) {
            $this->doLogin($usuario);
            unset($usuario->senha);
            return $usuario;
        } else {
            throw new Exception("Erro ao efetuar login. Usuário/Senha incorretos");
        }
    }

    public function get_isLogged() {
        return isset($_SESSION["login_id"]);
    }

    public function get_logout() {
        setcookie("usuario", "", time() - 3600);
        session_destroy();
        $_SESSION["login_id"] = null;
        $_SESSION["login_nome"] = null;
        $_SESSION["login_tipo"] = null;
        return true;
    }

    protected function doLogin($usuario) {
        /* Adiciona a data/ip do login */
        $sql = "UPDATE usuarios SET lastLogin=now(),lastIp=:lastIp WHERE id=:id";
        $stmt = DB::prepare($sql);
        $stmt->bindParam("lastIp", $_SERVER['REMOTE_ADDR']);
        $stmt->bindParam("id", $usuario->id);
        $stmt->execute();

        $_SESSION["login_id"] = $usuario->id;
        $_SESSION["login_nome"] = $usuario->login;
        $_SESSION["login_tipo"] = $usuario->id_usuario_tipo;
    }

    function verificaLogin($usuario) {
        $stmt = DB::prepare("SELECT COUNT(*) FROM usuarios WHERE login = :login");
        $stmt->bindParam("login", $usuario);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

}

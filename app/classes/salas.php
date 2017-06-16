<?php

class Salas {

    function get_listAll($parameter) {
        $sql = "SELECT *
                FROM salas ";

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
        $sql = "select * from salas WHERE id = :id";
        $stmt = DB::prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    function post_save($salas) {

        $usuario = ($_SESSION['login_nome']);

        if ($salas->id) {
            $sql = "
                    UPDATE salas SET                            
                            nome       = :nome,
                            descricao  = :descricao,
                            capacidade = :capacidade                                                          
                    WHERE
                            id         = :id";
        } else {
            $sql = "INSERT INTO salas (nome, descricao, capacidade)
                            VALUES (:nome, :descricao, :capacidade)";
        }

        try {
            DB::beginTransaction();

            $stmt = DB::prepare($sql);
            $stmt->bindParam("nome", $salas->nome);
            $stmt->bindParam("descricao", $salas->descricao);
            $stmt->bindParam("capacidade", $salas->capacidade);

            if ($salas->id) {
                $stmt->bindParam("id", $salas->id);
            }

            $stmt->execute();

            if (!$salas->id) {
                $salas->id = DB::lastInsertId();
            }

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            throw new Exception($ex->getMessage());
        }
        return $salas;
    }

    function post_delete($salas) {

        $stmt = DB::prepare("DELETE FROM salas WHERE id = :id");
        $stmt->bindParam("id", $salas->id);
        $stmt->execute();
        return true;
    }

}

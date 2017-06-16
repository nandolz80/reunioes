<?php

class Agendamentos {

    function get_listAll($parameter) {
        $usuario = ($_SESSION['login_nome']);
        $sql = "SELECT agenda.id, agenda.date, agenda.description, agenda.usuario, agenda.color, salas.nome FROM agenda
                join salas on salas.id = agenda.id_sala
                where agenda.usuario = '".$usuario."'
                ORDER BY agenda.date ASC";

        $stmt = DB::prepare($sql);

        $stmt->execute();
        
        $result = $stmt->fetchAll();
	return $result;
    }

    function get_list($id) {
        $sql = "select * from agenda WHERE id = :id";
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

    function post_delete($agendamento) {
        $stmt = DB::prepare("DELETE FROM agenda WHERE id = :id");
        $stmt->bindParam("id", $agendamento->id);
        $stmt->execute();
        return true;
    }

}

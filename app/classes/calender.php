<?php

class Calender {

    function get_listAll($parameter) {

        $sql = "SELECT agenda.id, agenda.date, agenda.description, agenda.usuario, agenda.color, agenda.id_sala, salas.nome FROM agenda
                join salas on salas.id = agenda.id_sala
                ORDER BY agenda.date ASC";

        $stmt = DB::prepare($sql);

        $stmt->execute();

        $result = $stmt->fetchAll();
        return $result;
    }

    function post_save($agenda) {
        $usuario = ($_SESSION['login_nome']);
        $result = $this->validaHorario($agenda);
        if ( $result <= 0) {
            $sql = "INSERT INTO agenda (description, id_sala, color, date, usuario)
                            VALUES (:description, :id_sala, :color, :date, :usuario)";

            $stmt = DB::prepare($sql);
            $stmt->bindParam("description", $agenda->description);
            $stmt->bindParam("id_sala", $agenda->id_sala);
            $stmt->bindParam("color", $agenda->color);
            $stmt->bindParam("date", $agenda->date);
            $stmt->bindParam("usuario", $usuario);
            $stmt->execute();

            return $agenda;
        } else {
            return 'usado';
        }
    }

    function post_upDate($agenda) {

        $sql = "
                    UPDATE agenda SET
                            date         = :date,
                            description  = :description,
                            id_sala      = :id_sala,
                            color        = :color
                    WHERE
                            id         = :id";
        $stmt = DB::prepare($sql);
        $stmt->bindParam("id", $agenda->id);
        $stmt->bindParam("date", $agenda->date);
        $stmt->bindParam("description", $agenda->description);
        $stmt->bindParam("color", $agenda->color);
        $stmt->execute();

        return $agenda;
    }

    function validaHorario($agenda) {
        $usuario = ($_SESSION['login_nome']);
        $stmt = DB::prepare("SELECT COUNT(*) FROM agenda WHERE date = :data and usuario = :usuario");
        $stmt->bindParam("data", $agenda->date);
        $stmt->bindParam("usuario", $usuario);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

}

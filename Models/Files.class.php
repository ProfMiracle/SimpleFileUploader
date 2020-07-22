<?php
namespace Models;
use dbConnection;

class Files extends \dbConnection
{
    protected function saveUpdate($update, $set, $to, $where, $equals)
    {
        $stmt = $con->prepare("UPDATE $update SET $set = ? WHERE $where = ?");
        $stmt->bind_param("si", $to, $equals);
        $stmt->execute();
        if($stmt->affected_rows === 0) exit('No rows updated');
        $stmt->close();

        return true;
    }

    protected function saveInsert($table, $column, $data)
    {
        $stmt = $con->prepare("INSERT INTO $table($column) VALUES(?)");
        $stmt->bind_param("s",$data);
        $stmt->execute();
        $stmt->close();

        return true;
    }
}

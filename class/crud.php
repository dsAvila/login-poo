<?php
require_once('DB.php');

abstract class Crud extends DB{
    protected string $table;

    abstract public function insert();
    abstract public function update($id);

    public function find($id) {
        $sql = "SELECT * FROM $this->table WHERE id=?";
        $sql = DB::prepare($sql);
        $sql->execute(array($id));
        $value = $sql->fetch();

        return $value;
    }

    public function findAll() {
        $sql = "SELECT * FROM $this->table";
        $sql = DB::prepare($sql);
        $sql->execute();
        $value = $sql->fetchAll();

        return $value;
    }

    public function delete($id) {
        $sql = "DELETE FROM $this->table WHERE id=?";
        $sql = DB::prepare($sql);
        
        return $sql->execute(array($id));
    }

}
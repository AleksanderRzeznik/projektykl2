<?php
abstract class Database {
    protected $connection;
    
    public function connect() {
        $this->connection = mysqli_connect("localhost", "root", "", "test");
        return $this->connection;
        abstract public function createDatabase($namezwa);
        abstract public function createTable($sql);
        abstract public function readTable($tablela);
        abstract public function updateTable($sql, $params);
        abstract public function deleteTable($tablela);
}
}
class Mysqldb extends Database implements Sqlinterface{
    public function createDatabase($namezwa) {
        return mysqli_query($this->connection, "CREATE DATABASE IF NOT EXISTS $namezwa");
    }
    public function createTable($sql) {
        return mysqli_query($this->connection, $sql);
    }
    public function readTable($tablela) {
        return mysqli_query($this->connection, "SELECT * FROM $tablela");
    }
    public function updateTable($sql, $params) {
        $stmt = mysqli_prepare($this->connection, $sql);
        mysqli_stmt_bind_param($stmt, str_repeat("s", count($params)), ...$params);
        return mysqli_stmt_execute($stmt);
    }
    public function deleteTable($tablela) {
        return mysqli_query($this->connection, "DROP TABLE $tablela");
    }
    public function FindAll($tablela) {
        return $this->readTable($tablela);
    }
    public function Find($tablela, $id) {
        $stmt = mysqli_prepare($this->connection, "SELECT * FROM $tablela WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }
    public function ReadOne($tablela, $id) {
        return mysqli_fetch_assoc($this->Find($table, $id));
    }
    public function ReadFirstTen($tablela) {
        return mysqli_query($this->connection, "SELECT * FROM $table LIMIT 10");
    }
    
}
class TestDatabase {
    public function test($result, $namezwa) {
        echo $result ? "[OK] $namezwa<br>" : "[BLAD] $namezwa<br>";
    }
}
$db = new Mysqldb();
$test = new TestDatabase();

$db->connect();
$test->test($db->connect(), "Polaczenie");
$db->createDatabase("test");
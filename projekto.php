
<?php

interface sqlinterface {
    public function FindAll($tablela);
    public function Find($tablela, $id);
    public function ReadOne($tablela, $id);
    public function ReadFirstTen($tablela);
}

abstract class database {
    protected $connection;

    public function connect() {
        $this->connection = mysqli_connect("localhost", "root", "", "teto");
        return $this->connection;
    }

    abstract public function createDatabase($namezwa);
    abstract public function createTable($sql);
    abstract public function readTable($tablela);
    abstract public function updateTable($sql, $params);
    abstract public function deleteTable($tablela);
}

class mysqldb extends database implements sqlinterface {

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
        return mysqli_fetch_assoc($this->Find($tablela, $id));
    }

    public function ReadFirstTen($tablela) {
        return mysqli_query($this->connection, "SELECT * FROM $tablela LIMIT 10");
    }
}

class TestDatabase {
    public function test($result, $namezwa) {
        echo $result ? "[OK] $namezwa<br>" : "[BLAD] $namezwa<br>";
    }
}

$db = new mysqldb();
$test = new TestDatabase();

$db->connect();
$test->test($db->connect(), "Polaczenie");

$db->createDatabase("teto");

mysqli_select_db($db->connect(), "teto");

$db->createTable("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100)
)");

$test->test($db->updateTable(
    "INSERT INTO users (name, email) VALUES (?, ?)",
    ["JOHN", "john@doe.pl"]
), "Dodawanie");

$test->test($db->FindAll("users"), "FindAll");
$test->test($db->Find("users", 1), "Find");
$test->test($db->ReadOne("users", 1), "ReadOne");
$test->test($db->ReadFirstTen("users"), "ReadFirstTen");
?>

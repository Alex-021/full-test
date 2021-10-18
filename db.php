<?php

class Database {
  private $connection;

  public function __construct() {
    global $config;
    $this->connection = new mysqli($config['host'], $config['user'], $config['pass'], $config['name'], $config['port']);
    if ($this->connection->connect_error) {
      echo "Connection failed: " . $this->connection->connect_error;
      exit;
    }
    $this->query("SET NAMES 'ut8'");
  }

  public function query($sql) {
    return $this->connection->query($sql);
  }

  public function insertNewUser($chatId) {
    global $config;
    $sql = "INSERT INTO " . $config['table'] . " VALUES ('" . $chatId . "', '0')";
    $this->query($sql);
  }

  public function getUserCounter($chatId) {
    global $config;
    $result = $this->query("SELECT countMsg FROM " . $config['table'] . " WHERE userId LIKE '" . $chatId . "'");
    $countMsg = $result->fetch_array()['countMsg'] + 1;
    $this->query("UPDATE " . $config['table'] . " SET countMsg = '" . $countMsg . "' WHERE userId = '" . $chatId . "'");
    return $countMsg;
  }
}
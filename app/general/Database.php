<?php

namespace general;

class Database {
  private \PDO $pdo;

  public function __construct($config) {
    try {
      // Using PDO over Mysqli since PDO works with more than just mysql databases
      // These same aspects can we rearranged for mysqli instances
      $this->pdo = new \PDO(
        "mysql:host=" . $config['host'] . ";dbname=" . $config['name'],
        $config['user'],
        $config['pass'],
        [
          \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, // Error mode to use
          \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC // Use associative array instead of object
        ]
      );
    } catch (\Exception $e) {
      die($e->getMessage());
    }
  }

  public function prepare($sql) {
    return $this->pdo->prepare($sql);
  }

  public function __destruct() {
    //Remove pdo instance (close db connection)
    if ($this->pdo !== null) {
      $this->pdo = null;
    }
  }
}

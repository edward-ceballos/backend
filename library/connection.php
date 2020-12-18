<?php

require_once('config.php');

class Connection{

  private static $objConn = null;  
  private static $instance = null;

  public static function getInstance(){
    if (self::$instance == null) {
      self::$instance = new Connection();

      $dsn = DB_TYPE.":dbname=".DB_NAME.";host=".DB_HOST;
      $attr  = array(
        PDO::MYSQL_ATTR_FOUND_ROWS   => TRUE,
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      );

      try {
          $conn = new PDO($dsn, DB_USER, DB_PASS, $attr);

          $table = "CREATE TABLE IF NOT EXISTS `contacts` (
             `id` INT(11) NOT NULL AUTO_INCREMENT,
             `name` VARCHAR(50),
             `lastname` VARCHAR(50),
             `email` VARCHAR(30),
             `phone` TEXT,
             PRIMARY KEY (`id`)
          ) CHARACTER SET utf8 COLLATE utf8_general_ci";

          $conn->query($table);
          self::$objConn = $conn;

      } catch (PDOException $e) {
          echo 'Error Connection: ' . $e->getMessage();
          exit();
      }

    }

    return self::$objConn;
  }
}
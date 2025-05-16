<?php
try {
  // Pide las variables para conectarse a la base de datos.
  require('data.php');

  // Se crea la instancia de PDO
  $db = new PDO("pgsql:host=$host;dbname=$dbname;port=$port;user=$user;password=$password");
  $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  return $db;
} catch (PDOException $e) {
  echo "Error de conexión:" . $e -> getMessage();
  exit();
}
?>
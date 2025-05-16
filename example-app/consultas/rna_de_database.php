<?php include('../templates/header.html'); ?>

<body>
  <div class="container mt-5">
    <?php
    // Verifica que existe el parámetro dbid
    if (!isset($_GET['dbid'])) {
      echo "<div class='alert alert-danger'>Parámetro de base de datos no proporcionado</div>";
      echo "<a href='rna_por_database.php' class='btn btn-primary'>Volver</a>";
      exit;
    }

    $dbid = htmlspecialchars($_GET['dbid']);

    // Llama a conexión, crea el objeto PDO y obtiene la variable $db
    require("../config/conexion.php");

    // Primero obtenemos el nombre de la base de datos para el título
    $query_db = "SELECT display_name FROM rnc_database WHERE id = :dbid";
    $stmt_db = $db->prepare($query_db);
    $stmt_db->bindParam(':dbid', $dbid);
    $stmt_db->execute();
    $db_name = $stmt_db->fetch(PDO::FETCH_ASSOC)['display_name'];
    ?>
    
    <h2 class="text-center mb-4">RNAs en la base de datos: <?php echo $db_name; ?></h2>
    
    <?php
    // Se construye la consulta para obtener RNAs de esta base de datos
    $query = "SELECT r.upi, r.seq_short, r.len, x.ac as accession, pc.description
              FROM xref x
              JOIN rna r ON x.upi = r.upi
              JOIN rnc_rna_precomputed pc ON r.upi = pc.upi
              WHERE x.dbid = :dbid
              LIMIT 50;";

    // Se prepara y ejecuta la consulta
    $stmt = $db->prepare($query);
    $stmt->bindParam(':dbid', $dbid);
    $stmt->execute();
    $results = $stmt->fetchAll();
    
    if (count($results) > 0) {
    ?>
    
    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead class="thead-dark">
          <tr>
            <th>UPI</th>
            <th>Accesión</th>
            <th>Longitud</th>
            <th>Descripción</th>
            <th>Secuencia (abreviada)</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($results as $r) {
            echo "<tr>";
            echo "<td><a href='buscar_rna.php?upi={$r['upi']}'>{$r['upi']}</a></td>";
            echo "<td>{$r['accession']}</td>";
            echo "<td>{$r['len']}</td>";
            echo "<td>{$r['description']}</td>";
            echo "<td><code>" . substr($r['seq_short'], 0, 30) . "...</code></td>";
            echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
    
    <?php
    } else {
      echo "<div class='alert alert-info'>No se encontraron RNAs para esta base de datos</div>";
    }
    ?>

    <div class="text-center mt-4">
      <a href="rna_por_database.php" class="btn btn-secondary">Volver a la lista de bases de datos</a>
      <a href="../index.php" class="btn btn-primary">Volver al inicio</a>
    </div>
  </div>
</body>

<?php include('../templates/footer.html'); ?>
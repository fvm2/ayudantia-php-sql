<?php include('../templates/header.html'); ?>

<body>
  <div class="container mt-5">
    <h2 class="text-center mb-4">RNA por Base de Datos</h2>
    
    <?php
    // Llama a conexión, crea el objeto PDO y obtiene la variable $db
    require("../config/conexion.php");

    // Se construye la consulta
    $query = "SELECT db.id, db.display_name, db.description, COUNT(x.upi) as rna_count
              FROM rnc_database db
              JOIN xref x ON db.id = x.dbid
              GROUP BY db.id, db.display_name, db.description
              ORDER BY rna_count DESC;";

    // Se prepara y ejecuta la consulta
    $stmt = $db->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll();
    ?>

    <table class="table table-striped table-hover">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Cantidad de RNAs</th>
          <th>Acción</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($results as $r) {
          echo "<tr>";
          echo "<td>$r[0]</td>";
          echo "<td>$r[1]</td>";
          echo "<td>$r[2]</td>";
          echo "<td>$r[3]</td>";
          echo "<td><a href='rna_de_database.php?dbid=$r[0]' class='btn btn-sm btn-info'>Ver RNAs</a></td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>

    <div class="text-center mt-4">
      <a href="../index.php" class="btn btn-primary">Volver al inicio</a>
    </div>
  </div>
</body>

<?php include('../templates/footer.html'); ?>
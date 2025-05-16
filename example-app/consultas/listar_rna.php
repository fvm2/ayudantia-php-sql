<?php include('../templates/header.html'); ?>

<body>
  <div class="container mt-5">
    <h2 class="text-center mb-4">Listado de Secuencias RNA</h2>
    
    <?php
    // Llama a conexión, crea el objeto PDO y obtiene la variable $db
    require("../config/conexion.php");

    // Se construye la consulta
    $query = "SELECT r.upi, r.seq_short, r.len, pc.description 
              FROM rna r
              JOIN rnc_rna_precomputed pc ON r.upi = pc.upi
              LIMIT 50;";

    // Se prepara y ejecuta la consulta
    $stmt = $db->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll();
    ?>

    <table class="table table-striped table-hover">
      <thead class="thead-dark">
        <tr>
          <th>UPI</th>
          <th>Secuencia (abreviada)</th>
          <th>Longitud</th>
          <th>Descripción</th>
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
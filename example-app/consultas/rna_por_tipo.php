<?php include('../templates/header.html'); ?>

<body>
  <div class="container mt-5">
    <h2 class="text-center mb-4">RNA por Tipo</h2>
    
    <?php
    // Llama a conexión, crea el objeto PDO y obtiene la variable $db
    require("../config/conexion.php");

    // Se construye la consulta
    $query = "SELECT pc.rna_type, 
                     COUNT(*) as cantidad, 
                     ROUND(AVG(r.len), 2) as longitud_promedio
              FROM rna r
              JOIN rnc_rna_precomputed pc ON r.upi = pc.upi
              WHERE pc.rna_type IS NOT NULL
              GROUP BY pc.rna_type
              HAVING COUNT(*) > 100
              ORDER BY cantidad DESC
              LIMIT 20;";

    // Se prepara y ejecuta la consulta
    $stmt = $db->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll();
    ?>

    <table class="table table-striped table-hover">
      <thead class="thead-dark">
        <tr>
          <th>Tipo de RNA</th>
          <th>Cantidad</th>
          <th>Longitud Promedio</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($results as $r) {
          echo "<tr>";
          echo "<td>" . ($r[0] ? $r[0] : 'No especificado') . "</td>";
          echo "<td>$r[1]</td>";
          echo "<td>$r[2]</td>";
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
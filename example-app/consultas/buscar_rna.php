<?php include('../templates/header.html'); ?>

<body>
  <div class="container mt-5">
    <h2 class="text-center mb-4">Buscar RNA por UPI</h2>
    
    <div class="card mb-4">
      <div class="card-body">
        <form method="post" action="buscar_rna.php">
          <div class="form-group">
            <label for="upi">UPI del RNA (ej: URS000000001F):</label>
            <input type="text" class="form-control" id="upi" name="upi" required>
          </div>
          <button type="submit" class="btn btn-primary mt-2">Buscar</button>
        </form>
      </div>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upi'])) {
      // Llama a conexión, crea el objeto PDO y obtiene la variable $db
      require("../config/conexion.php");

      // Obtener y sanitizar el UPI
      $upi = htmlspecialchars($_POST['upi']);

      // Se construye la consulta
      $query = "SELECT r.upi, r.seq_short, r.len, pc.description, pc.rna_type 
                FROM rna r
                JOIN rnc_rna_precomputed pc ON r.upi = pc.upi
                WHERE r.upi = :upi;";

      // Se prepara y ejecuta la consulta
      $stmt = $db->prepare($query);
      $stmt->bindParam(':upi', $upi);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($result) {
        // Consulta adicional para obtener referencias cruzadas
        $query_xref = "SELECT x.ac, db.display_name 
                       FROM xref x
                       JOIN rnc_database db ON x.dbid = db.id
                       WHERE x.upi = :upi
                       LIMIT 10;";
        $stmt_xref = $db->prepare($query_xref);
        $stmt_xref->bindParam(':upi', $upi);
        $stmt_xref->execute();
        $xrefs = $stmt_xref->fetchAll();

        echo "<div class='card mt-4'>";
        echo "<div class='card-header bg-success text-white'>RNA encontrado</div>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'>UPI: " . $result['upi'] . "</h5>";
        echo "<p><strong>Descripción:</strong> " . $result['description'] . "</p>";
        echo "<p><strong>Tipo de RNA:</strong> " . $result['rna_type'] . "</p>";
        echo "<p><strong>Longitud:</strong> " . $result['len'] . " nucleótidos</p>";
        
        echo "<div class='card mt-3'>";
        echo "<div class='card-header'>Secuencia (abreviada)</div>";
        echo "<div class='card-body'>";
        echo "<pre>" . $result['seq_short'] . "...</pre>";
        echo "</div></div>";
        
        if (count($xrefs) > 0) {
          echo "<div class='card mt-3'>";
          echo "<div class='card-header'>Referencias cruzadas</div>";
          echo "<div class='card-body'>";
          echo "<ul class='list-group'>";
          foreach ($xrefs as $xref) {
            echo "<li class='list-group-item'><strong>" . $xref['display_name'] . ":</strong> " . $xref['ac'] . "</li>";
          }
          echo "</ul>";
          echo "</div></div>";
        }
        
        echo "</div></div>";
      } else {
        echo "<div class='alert alert-warning'>No se encontró ningún RNA con el UPI: $upi</div>";
      }
    }
    ?>

    <div class="text-center mt-4">
      <a href="../index.php" class="btn btn-primary">Volver al inicio</a>
    </div>
  </div>
</body>

<?php include('../templates/footer.html'); ?>
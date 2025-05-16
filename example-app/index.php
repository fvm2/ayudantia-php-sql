<?php include('templates/header.html'); ?>

<body>
  <div class="container mt-5">
    <h1 class="text-center mb-4">Consultas a Base de Datos RNAcentral</h1>
    
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title">Consultas disponibles</h5>
        <ul class="list-group">
          <li class="list-group-item">
            <a href="consultas/listar_rna.php">Listar secuencias de RNA</a> - Muestra secuencias de RNA disponibles
          </li>
          <li class="list-group-item">
            <a href="consultas/buscar_rna.php">Buscar RNA por URS</a> - Permite buscar una secuencia específica
          </li>
          <li class="list-group-item">
            <a href="consultas/rna_por_database.php">RNA por base de datos</a> - Muestra RNA agrupado por base de datos experta
          </li>
        </ul>
      </div>
    </div>
    
    <p class="text-center text-muted">Ayudantía PHP-SQL - Conexión con PostgreSQL</p>
  </div>
</body>

<?php include('templates/footer.html'); ?>
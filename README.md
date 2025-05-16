# Apoyo

## Conexión con base de datos localmente

1. ### Instalación del servidor local de PHP

Si aún no tienes un servidor local de PHP instalado, puedes hacerlo de la siguiente manera:

- Para **Ubuntu/Debian**:
  ```bash
  sudo apt install php php-pgsql
  ```
- Para **macOS (usando Homebrew)**:
  ```bash
  brew install php
  ```

2. ### Instalar PostgreSQL

También deben instalar PostgreSQL para gestionar la base de datos de manera local. Los comandos varían según el sistema operativo:

- Para **Ubuntu/Debian**:
  ```bash
  sudo apt install postgresql postgresql-contrib
  ```
- Para **macOS (usando Homebrew)**:
  ```bash
  brew install postgresql
  ```
- Para **windows**:
  ```
  https://www.postgresql.org/download/windows/
  ```

### 3. Iniciar PostgreSQL

Una vez instalado, es necesario iniciar el servidor de PostgreSQL. Los comandos varían según el sistema operativo:

- Para **Ubuntu/Debian**:

  ```bash
  sudo service postgresql start
  ```

- Para **macOS (usando Homebrew)**:

  ```bash
  brew services start postgresql
  ```

- Para **windows**:
  Se inicia automaticamente

### 4. Acceder a la consola de PostgreSQL

Para ingresar a la consola de PostgreSQL, utiliza los siguientes comandos:

- **Ubuntu/Debian:**
  ```bash
  sudo -u postgres psql
  ```
- Para **macOS**:
  ```bash
  psql postgres
  ```
- Para **windows**:
  ```bash
  psql
  ```

### 5. Poblar tablas

Una vez conectado a la base de datos y con las tablas creadas, puedes hacer la carga de datos en las tablas correspondientes con el código definido en el archivo poblar_tablas.sql (debes hacerlo tú)

```sql
CREATE TEMP TABLE casa_tmp (
    id INTEGER,
    nombre_condominio TEXT,
    tipo TEXT
);
\copy casa_tmp FROM 'csv/casa.csv' DELIMITER ',' CSV HEADER;

INSERT INTO Casa (id, nombre_condominio, tipo)
SELECT DISTINCT ON (id, nombre_condominio)
    id, nombre_condominio, tipo
FROM habitaciones_tmp
WHERE id IS NOT NULL
  AND nombre_condominio IS NOT NULL;
```

Lo que hace el código anterior es insertar todas las tuplas que cumplen con las restricciones a la tabla real desde una tabla temporal.

# 5.1 Manejo de Datos Inválidos (Tip)

```sql
\copy (SELECT * FROM casas_tmp WHERE id IS NULL OR nombre_condominio IS NULL) TO 'descartados/casas_descartados.csv' CSV HEADER;
```

Los registros inválidos no se pierden: se exportan a carpetas de descartados/.
Lo que hace este código es llevar al archivo 'casas_descartados.csv' todas las tuplas que no cumplan con las restricciones de integridad.

## Interactuar con la consola de PostgreSQL

Cuando se conecten al servidor por medio de `ssh tu_usuario@bdd1.ing.puc.cl`, deben ejecutar el siguiente comando para ingresar a su base de datos:

```bash
psql
```

Una vez dentro pueden hacer consultas con SQL dentro del terminal. Solo como modo de **ejemplo** se pueden ejecutar las siguientes consultas:

**Crear tabla:**

```sql
CREATE TABLE Cliente (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100),
    email VARCHAR(100),
    telefono VARCHAR(20)
);
```

**Insertar datos:** (Solo como ejemplo, no hacerlo en la E2)

```sql
INSERT INTO Cliente (nombre, email, telefono)
VALUES
('María Pérez', 'maria@gmail.com', '912345678'),
('Juan Soto', 'juan@gmail.com', '911223344');
```

**Ver datos:**

```sql
SELECT * FROM Cliente;
```

**Ver estructura de la tabla**

```sql
\d Cliente
```

**Salir de psql**

```sql
\q
```

## Ejecutar archivos

Debido a que para la entrega deben tener archivos crear_tablas.sql, poblar_tablas.sql y todas las consultas, hay una forma de ejecutar estos archivos de manera sencilla desde la consola.

```
psql -h <host> -U <usuario> -d <bdd> -f crear_tablas.sql
```

Donde <host> es bdd1.ing.puc.cl, <usuario> su usuario y <bdd> es el nombre de su base de datos (en este caso su nombre de usuario).

## ⚠️ Errores Comunes al Poblar la Base de Datos

Durante el poblamiento pueden surgir errores típicos. Aquí te dejamos una tabla con los más frecuentes, su causa y cómo resolverlos:

| Error                                                               | Causa                                                                                              | Solución                                                                                         |
| ------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------ |
| `ERROR: null value in column "correo" violates not-null constraint` | El CSV tiene una fila con datos faltantes (por ejemplo, sin correo)                                | Verifica el contenido del archivo `.csv` o revisa el archivo en la carpeta `descartados/`        |
| `ERROR: insert or update on table violates foreign key constraint`  | Estás intentando insertar datos que hacen referencia a otras tablas que aún no tienen esos valores | Asegúrate de poblar las tablas en el orden correcto y que existan las claves foráneas necesarias |

Para más información, recomiendo revisar la [documentación oficial de PostgreSQL](https://www.postgresql.org/docs/current/index.html).

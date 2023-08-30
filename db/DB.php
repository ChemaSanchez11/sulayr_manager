<?php

namespace db;
use mysqli;
use stdClass;

/**
 * @property false|mysqli $db
 * @property true $status
 * @property string|null $error
 */
class DB
{

    public function __construct($host = '', $user = '', $pass = '', $port = '', $database = '')
    {

        if (empty($this->db)) {
            $this->db = @mysqli_connect($host . ':' . $port, $user, $pass, $database);
            $_SESSION['db'] = $this->db;
        }

        if ($this->db) $this->status = true;
        else $this->error = mysqli_connect_error();

        return $this->db;
    }

    public function get_records($query)
    {

        $DB = $this->db;

        $result = $DB->query($query);
        $data = [];

        if (empty(mysqli_error($DB)) && $result) {

            while ($row = $result->fetch_assoc()) {
                $data[] = (object)$row; // Agregar el objeto al array
            }

            return $data;
        }

    }

    /**
     * Obtener datos de una tabla en base a esa query
     * @param $query
     * @return stdClass|void|null
     */
    public function get_record($query)
    {
        $DB = $this->db;

        $result = $DB->query($query);
        if (!empty($result)) {
            $std = new stdClass();

            if ($result->num_rows == 1 && $result->num_rows != 0) {

                $row = $result->fetch_assoc();
                foreach ($row as $key => $object) {
                    $std->$key = $row["$key"];
                }
            } else if ($result->num_rows == 0) {
                $std = null;
            }
            return $std;
        }
    }

    /**
     * Ejecutamos una query en las tablas internas
     * @param $query
     * @param $params
     * @return bool
     */
    public function execute($query, $params = []): bool
    {
        $DB = $this->db;

        // Creamos la sentencia preparada
        $statement = mysqli_prepare($DB, $query);

        if ($statement) {
            // Vinculamos los parámetros a la query
            if (!empty($params)) {

                //Para seleccionar el tipo de parametro que es
                $types = '';
                foreach ($params as $param) {
                    if (is_int($param)) {
                        $types .= 'i'; // Tipo entero (integer)
                    } elseif (is_float($param)) {
                        $types .= 'd'; // Tipo de punto flotante (double)
                    } elseif (is_null($param)) {
                        $types .= 's'; // Tipo NULL
                    } else {
                        $types .= 's'; // Tipo cadena (string) por defecto
                    }
                }

                // Agregar la cadena de definición de tipo como primer elemento en el arreglo de parámetros
                array_unshift($params, $types);

                mysqli_stmt_bind_param($statement, ...$params);
            }

            // Ejecutamos la sentencia preparada
            mysqli_stmt_execute($statement);
            return true;
        } else {
            // Si falla devolvemos false
            return false;
        }
    }

    /**
     * Ejecutamos una query en las tablas internas
     * @param $query
     * @return int
     */
    public function insert($query): int
    {
        $DB = $this->db;
        return $DB->query($query) === true ? $DB->insert_id : false;
    }

    /**
     * Ejecutamos una query en las tablas internas
     * @param $query
     * @return bool
     */
    public function update($query): bool
    {
        $DB = $this->db;
        return $DB->query($query) === TRUE;
    }
}
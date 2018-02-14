<?php
require '../db_connection.php';

$nombre_nuevo_proveedor = $_POST['nombre_proveedor'];
$direccion = $_POST['direccion_proveedor'];
$celular = $_POST['celular_proveedor'];
$estado = $_POST['estado_proveedor'];
$municipio = $_POST['municipio_proveedor'];
$email = $_POST['email_proveedor'];
$cuenta = $_POST['cuenta'];
try {
    $db4 = getDB();
    $stmt4 = $db4->prepare("INSERT INTO proveedor (nombre_proveedor,direccion,celular,estado,municipio,email,NUMERO_cuenta) VALUES(:field1,:field2,:field3,:field4,:field5,:field6,:field7)");
    $stmt4->execute(array(':field1' => $nombre_nuevo_proveedor,':field2' => $direccion,':field3' => $celular,':field4' => $estado,':field5' => $municipio,':field6' => $email, ':field7' => $cuenta));
} catch (PDOException $e) {
    echo '{"error":{"text":' . $e->getMessage() . '}}';
}

try {
        $db = getDB();
        $stmt = $db->prepare("SELECT id,nombre_proveedor from proveedor");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $proveedores = json_encode($results);
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
    echo $proveedores;
 ?>

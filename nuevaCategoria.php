<?php
require '../db_connection.php';
$nombre_nueva_categoria = $_POST['nombre'];

try {
    $db4 = getDB();
    $stmt4 = $db4->prepare("INSERT INTO categorias (nombre_categoria) VALUES(:field1)");
    $stmt4->execute(array(':field1' => $nombre_nueva_categoria));
} catch (PDOException $e) {
    echo '{"error":{"text":' . $e->getMessage() . '}}';
}

try {
        $db = getDB();
        $stmt = $db->prepare("SELECT id,nombre_categoria from categorias");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $categos = json_encode($results);
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
    echo $categos;
 ?>

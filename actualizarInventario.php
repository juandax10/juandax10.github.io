<?php
require '../db_connection.php';

$id = $_POST['id'];
$data_nombre = $_POST['data_nombre'];
$valor_nuevo = $_POST['valor'];

try {
        $db = getDB();
        $stmt = $db->prepare("UPDATE productos set ".$data_nombre."=? WHERE id=?");
        $stmt->execute(array($valor_nuevo, $id));
        $affected_rows = $stmt->rowCount();
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
    if ($affected_rows > 0) {
      echo "1"; //OK
    }else {
      echo "0";
    }

 ?>

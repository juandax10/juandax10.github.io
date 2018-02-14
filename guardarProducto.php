<?php
session_start();
require '../db_connection.php';
if (isset($_POST['guardar'])) {

    $nombre_producto = $_POST['nombre_producto'];
    $categoria_id = $_POST['categoria_id'];
    $precio_compra_unidad = $_POST['precio_compra_unidad'];
    $precio_venta_unidad = $_POST['precio_venta_unidad'];
    $precio_venta_unidad_tienda = $_POST['precio_venta_unidad_tienda'];
    $precio_mayoreo_unidad = $_POST['precio_mayoreo_unidad'];
    $cantidad_total_casa = $_POST['cantidad_total'];
    $cantidad_total_tienda = $_POST['cantidad_total_tienda'];
    $img_path  = $_POST['imagen_producto'];
    $ubicacion = $_POST['ubicacion'];
    $proveedor_id = $_POST['proveedor_id'];
    $pz_caja_paquete = $_POST['pz_caja_paquete'];
    $talla = $_POST['talla'];


    if (empty($nombre_producto) || empty($categoria_id) || empty($precio_compra_unidad) || empty($precio_venta_unidad) || empty($cantidad_total_casa)
    || empty($ubicacion) || empty($cantidad_total_tienda) || empty($proveedor_id) || empty($pz_caja_paquete) || empty($precio_venta_unidad_tienda) ) {
      $_SESSION['mensaje'] = 'Por favor ingresa todos los datos para el producto.';
      header("Location:inventario.php");
      exit();
    }else{
    try {
        $db4 = getDB();
        $stmt4 = $db4->prepare("INSERT INTO productos (nombre_producto,categoria_id,
          precio_compra_unidad,precio_venta_unidad,precio_mayoreo_unidad,cantidad_total,
          cantidad_total_tienda,img_path,ubicacion,proveedor_id,pz_caja_paquete,talla,precio_venta_unidad_tienda)
          VALUES(:field1,:field2,:field3,:field4,:field5,:field6,:field7,:field8,:field9,:field10,:field11,:field12,:field13)");

        $stmt4->execute(array(':field1' => $nombre_producto, ':field2' => $categoria_id, ':field3' => $precio_compra_unidad,
         ':field4' => $precio_venta_unidad, ':field5' => $precio_mayoreo_unidad, ':field6' => $cantidad_total_casa, ':field7' => $cantidad_total_tienda,
          ':field8' => $img_path, ':field9' => $ubicacion, ':field10' => $proveedor_id, ':field11' => $pz_caja_paquete, ':field12' => $talla, ':field13' => $precio_venta_unidad_tienda));

    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
    header("Location:inventario.php");
    exit();
  }
}
 ?>

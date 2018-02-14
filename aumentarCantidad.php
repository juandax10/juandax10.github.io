<?php
require './Cart.php';
session_start();
require './db_connection.php';

$id_ajax = $_POST['id']; //ID producto a aumentar
$cantidad_ajax = $_POST['cantidad']; // cantidad a aumentar
try {
    $db = getDB();
    $stmt5 = $db->prepare("SELECT * from productos WHERE id=:id");
    $stmt5->execute(array(':id' => $id_ajax));
    $product = $stmt5->fetch(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    echo '{"error":{"text":' . $e->getMessage() . '}}';
}
if (!empty($_SESSION['cart'])){
     $oldCart =  $_SESSION['cart'];
  }else{
     $oldCart =  null;
  }
  $cart = new Cart($oldCart);
  $cart->add2($product, $product->id, $cantidad_ajax);
  $_SESSION['cart'] = $cart;
  if (!empty($_SESSION['cart'])){
       $nuevo_total =  $_SESSION['cart']->totalPrice;
    }else{
       $nuevo_total =  '';
    }
  $nuevo_subtotal = $cantidad_ajax*$product->precio_venta_unidad; // nuevo subtotal cantidad ingresada por precio venta

  $db = null;
  echo json_encode(array('nuevo_total' => $nuevo_total, 'nuevo_subtotal' => $nuevo_subtotal)); // regresar nuevo subtotal y total 
 ?>

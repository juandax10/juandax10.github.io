<?php
require '../Cart.php';
session_start();
require '../db_connection.php';

/* ===================
    TODOS LOS NOMBRES DE PRODUCTOS PARA EL AUTOCOMPLETE
  ======================
**/
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT nombre_producto from productos");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
    $resultado = count($results);
    $arreglo_nombres_productos = array();
    for ($i=0; $i < $resultado ; $i++) {
      array_push($arreglo_nombres_productos,$results[$i]['nombre_producto']);
    }
    /* ===================
        DATOS DE CATEGORIAS PARA OBTENER NOMBRE EN TABLA
      ======================
    **/
    try {
            $stmt2 = $db->prepare("SELECT * from categorias");
            $stmt2->execute();
        } catch (PDOException $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
        $resultado_categorias = $stmt2->fetchAll(PDO::FETCH_OBJ);

    $db = null;

    $meses = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");
    $fechaactual = getdate();//obtener fecha actual
    $year = $fechaactual['year'];
    $days = date("j");
    $mes = $meses[date('n') - 1];
    $mes_num = date("n");
    $fechaFinal = $year . "/" . $mes_num . "/" . $days;


    $oldCart =  $_SESSION['cart'];
    $carro = new Cart($oldCart);

    /* ===================
        BOTON AGREGAR PRODUCTO A CARRO
      ======================
    **/
    if (isset($_POST['agrega'])) {
      if (!empty($_POST['nombre_producto'])) {
      $nombre_producto = $_POST['nombre_producto'];
      try {
          $db = getDB();
          $stmt3 = $db->prepare("SELECT * from productos WHERE nombre_producto=:nombre_producto");
          $stmt3->execute(array(':nombre_producto' => $nombre_producto));
          $product = $stmt3->fetch(PDO::FETCH_OBJ);
      } catch (PDOException $e) {
          echo '{"error":{"text":' . $e->getMessage() . '}}';
      }
      if (!empty($_SESSION['cart'])){
           $oldCart =  $_SESSION['cart'];
        }else{
           $oldCart =  null;
        }
      $cart = new Cart($oldCart);
      $cart->add($product, $product->id);
      $_SESSION['cart'] = $cart;
      $db = null;
      header("Location:pre_venta.php");
      exit();
    }
  }
    /* ===================
       BORRAR PRODUCTO DE CARRO
      ======================
    **/
    if (isset($_GET['id'])) {
      $id = $_GET['id'];
      if (!empty($_SESSION['cart'])){
           $oldCart =  $_SESSION['cart'];
        }else{
           $oldCart =  null;
        }
      $cart = new Cart($oldCart);
      $cart->removeItem($id);
        if (count($cart->items) > 0) {
            $_SESSION['cart'] = $cart;
        } else {
          session_start();
          session_unset();
          session_destroy();
        }
        header("Location:pre_venta.php");
        exit();
    }
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>VENTA</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css">
  <script defer src="https://use.fontawesome.com/releases/v5.0.0/js/all.js"></script>
  <link rel="stylesheet" href="../css/venta.css">
  <script src="https://code.jquery.com/jquery-3.1.0.min.js"
          integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body>
  <nav class="navbar is-black">
  <div class="navbar-brand">
    <a class="navbar-item" href="#">
      <!-- <img src="https://bulma.io/images/bulma-logo.png" alt="Bulma: a modern CSS framework based on Flexbox" width="112" height="28"> -->
    </a>
    <div class="navbar-burger burger" data-target="navbarExampleTransparentExample">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>

  <div id="navbarExampleTransparentExample" class="navbar-menu">
    <div class="navbar-start">
      <a class="navbar-item" href="../index.php">
        Principal
      </a>
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link" href="#">
          Menu
        </a>
        <div class="navbar-dropdown is-boxed">
          <a class="navbar-item" href="../Inventario/inventario.php">
            Inventario
          </a>
          <a class="navbar-item  is-active" href="pre_venta.php">
            Ventas
          </a>
          <a class="navbar-item" href="Clientes/clientes.php">
            Clientes
          </a>
          <a class="navbar-item" href="Envio/envios.php">
            Envios
        </a>
          <a class="navbar-item" href="">
            Form
          </a>
          <hr class="navbar-divider">
          <a class="navbar-item" href="">
            Elements
          </a>
          <a class="navbar-item" href="">
            Components
          </a>
        </div>
      </div>
    </div>

    <div class="navbar-end">
      <div class="navbar-item">
        <div class="field is-grouped">
          <p class="control">
            <a class="bd-tw-button button" data-social-network="Twitter" data-social-action="tweet" data-social-target="http://localhost:4000" target="_blank" href="https://twitter.com/intent/tweet?text=Bulma: a modern CSS framework based on Flexbox&amp;hashtags=bulmaio&amp;url=http://localhost:4000&amp;via=jgthms">
              <span class="icon">
                <i class="fab fa-twitter"></i>
              </span>
              <span>
                Tweet
              </span>
            </a>
          </p>
          <p class="control">
            <a class="button is-primary" href="#">
              <span class="icon">
                <i class="fas fa-download"></i>
              </span>
              <span>Download</span>
            </a>
          </p>
        </div>
      </div>
    </div>
  </div>
</nav>

  <section class="section">
    <div class="container">
      <div class="card">
        <div class="card-content">
          <div class="content">
            <div class="columns is-mobile">
              <div class="column is-2">
                <div class="field">
                  <p class="control has-icons-left">
                    <input class="input is-info" type="text" value="<?php echo $fechaFinal; ?>" title="<?php echo $fechaFinal; ?>" readonly>
                    <span class="icon is-small is-left">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </p>
                </div>
              </div>
              <div class="column is-10">
                  <div class="field">
                    <form action="pre_venta.php" method="post">
                      <p class="control has-icons-left">
                          <input class="input is-info " type="text" placeholder="Nombre producto" name="nombre_producto" id="tags">
                          <input type="submit" name="agrega" value="ok" id="agregarProducto" hidden>
                        <span class="icon is-small is-left">
                          <i class="fas fa-search"></i>
                        </span>
                      </p>
                    </form>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php if (empty($_SESSION['cart'])){ ?>
        <h1 class="total">TOTAL: $0.00</h1>
      <?php }else {?>
      <a href="cerrar.php"> <i class="fas fa-trash"></i> Eliminar Carro</a>
      <table class="table is-bordered is-hoverable is-fullwidth">
        <thead>
          <tr>
            <th><abbr title="ID">#</abbr></th>
            <th><abbr title="Nombre">Nombre Producto</abbr></th>
            <th><abbr title="Categoria">Categoría</abbr></th>
            <th><abbr title="Precio Venta">Precio Pza</abbr></th>
            <th><abbr title="Cantidad Total">Cantidad</abbr></th>
            <th><abbr title="Sub Total">Sub Total</abbr></th>
            <th><abbr title="Eliminar">Eliminar</abbr></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $ban = 1;
          foreach ($carro->items as $cart) {
            foreach ($resultado_categorias as $r_catego) {
              if ($cart['item']->categoria_id == $r_catego->id) {
                    ?>
                    <div>
                    <tr class="lista-productos">
                      <th><?php echo $ban; ?></th>
                      <td><?php echo $cart['item']->nombre_producto; ?></td>
                      <td><?php echo $r_catego->nombre_categoria; ?></td>
                      <td>$<?php echo $cart['item']->precio_venta_unidad; ?></td>
                      <td><input type="text" name="<?php echo $cart['item']->id; ?>" id="cantidad_venta" class="cantidad-venta" value="<?php echo  $cart['qty']; ?>"></td>
                      <td class="id" hidden><span class="id"><?php echo $cart['item']->id; ?></span></td>
                      <td>$<?php echo $cart['price']; ?></td>
                      <td>
                        <p class="field">
                          <a class="button is-danger is-outlined" href="pre_venta.php?id=<?php echo $cart['item']->id; ?>">
                            <span>Eliminar</span>
                            <span class="icon is-small">
                              <i class="fas fa-times"></i>
                            </span>
                          </a>
                        </p>
                      </td>
                    </tr>
                    </div>
                    <?php
                    $ban++;
                  }
                  }
                }
            ?>
        </tbody>
      </table>

      <div class="columns is-mobile">
        <div class="column is-2 is-offset-5">
          <label for="tipo_venta">Tipo de Venta:</label>
          <div class="control">
            <div class="select">
              <select name="tipo_venta" id="tipo_venta">
                <option value="Envio">Envío</option>
                <option value="Fisica">Fisica</option>
              </select>
            </div>
          </div>
        </div>
        <div class="column is-3">
            <div class="field">
              <h1 class="total">TOTAL: <span id="precioTotal">$<?php if (!empty($_SESSION['cart'])){ echo $_SESSION['cart']->totalPrice;}else{echo '';} ?></span></h1>
            </div>
        </div>
        <div class="column is-2">
          <p class="field">
            <button class="button is-success is-outlined" id="continuar">
              <span>CONTINUAR</span>
              <span class="icon is-small">
                <i class="fas fa-check"></i>
              </span>
            </button>
          </p>
        </div>
      </div>
    <?php } ?>
    <div style="color: #1eb6a7;font-weight: bold;font-size: 14px;" hidden id="error_msg">INGRESE
        CATEGORIA
    </div>

    </div>
  </section>
  <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script type="text/javascript">
  jQuery.ui.autocomplete.prototype._resizeMenu = function () {
              var ul = this.menu.element;
              ul.outerWidth(this.element.outerWidth());
          };
          function monkeyPatchAutocomplete() {
              $.ui.autocomplete.prototype._renderItem = function (ul, item) {
                  item.label = item.label.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + $.ui.autocomplete.escapeRegex(this.term) + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<span style='font-weight:bold;color:Blue;'>$1</span>");
                  return $("<li></li>")
                          .data("item.autocomplete", item)
                          .append("<a style='text-decoration: none;border: none;'>" + item.label + "</a>")
                          .appendTo(ul);
              };
          }
  $(document).ready(function () {
    monkeyPatchAutocomplete();
    var nombres_productos;
    nombres_productos = <?php echo json_encode($arreglo_nombres_productos); ?>;
      $("#tags").autocomplete({
        source: nombres_productos
      });

    $("#tags").autocomplete({
      select: function (event, ui) {
            var valor_producto = ui.item.value;
            $('#tags').val(valor_producto);
            $("#agregarProducto").trigger("click");
            return false;
          },
    });
    $("#continuar").click(function () {
      var tipo_venta = $("#tipo_venta").val();
      if (tipo_venta == 'Envio') {
        window.location.href = "venta.php";
      }else{

      }
    });
    $(".cantidad-venta").keyup(function (event) {
                if (event.key === "Enter") {
                    $varThis = $(this);
                    var cantidad = $(this).val();
                    var id = $(this).attr("name");
                    if (cantidad >= 1) {
                        $.ajax({
                                    method: 'POST',
                                    url: 'aumentarCantidad.php',
                                    data: {id: id, cantidad: cantidad, es_ajax: 1},
                                    error: function (data) {
                                        var errorsHtml = '';
                                        var errors = data.responseJSON;
                                        $.each(errors, function (key, value) {
                                            errorsHtml += value;
                                        });
                                        $(this).addClass('borderClass').delay(4000)
                                                .queue(function () {
                                                    $(this).removeClass("borderClass");
                                                    $(this).dequeue();
                                                });
                                        $("#error_msg").text(errorsHtml).show().fadeOut(4000);
                                    }
                                })
                                .done(function (msg) {
                                  var nuevo_total, nuevo_subtotal, aux;
                                  aux = JSON.parse(msg);
                                  nuevo_total = aux['nuevo_total'];
                                  nuevo_subtotal = aux['nuevo_subtotal'];
                                  $("#precioTotal").text('$'+nuevo_total);
                                  $(".lista-productos").parents('div').find(".id").each(function () {
                                        var values = $(this).html();
                                        var id2 = $(this).text();
                                        if (id2 == id) {
                                            $(this).next().html('$'+nuevo_subtotal);
                                        }
                                    });
                                });
                    } else {
                        $(this).addClass('borderClass').delay(4000)
                                .queue(function () {
                                    $(this).removeClass("borderClass");
                                    $(this).dequeue();
                                });
                        $("#error_msg").show().fadeOut(4000);
                    }
                }
            });
            $(".cantidad-venta").keydown(function (e) { // permitir solo numeros
                // permite: backspace, delete, tab, escape, enter y .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                            // permite: Ctrl+A
                        (e.keyCode == 65 && e.ctrlKey === true) ||
                            // permite: Ctrl+C
                        (e.keyCode == 67 && e.ctrlKey === true) ||
                            // permite: Ctrl+X
                        (e.keyCode == 88 && e.ctrlKey === true) ||
                            // permite: home, end, left, right
                        (e.keyCode >= 35 && e.keyCode <= 39)) {

                    return;
                }
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
  });
  </script>
</body>
</html>

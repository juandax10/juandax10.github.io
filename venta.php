<?php
    require '../Cart.php';
    session_start();
    require '../db_connection.php';
    /* ===================
        DATOS DE CATEGORIAS PARA OBTENER NOMBRE EN TABLA
      ======================
    **/
    // try {
    //         $db = getDB();
    //         $stmt = $db->prepare("SELECT * from categorias");
    //         $stmt->execute();
    //     } catch (PDOException $e) {
    //         echo '{"error":{"text":' . $e->getMessage() . '}}';
    //     }
    // $resultado_categorias = $stmt->fetchAll(PDO::FETCH_OBJ);
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
</head>
<body>
  <nav class="navbar is-black">
  <div class="navbar-brand">
    <a class="navbar-item" href="https://bulma.io">
      <img src="https://bulma.io/images/bulma-logo.png" alt="Bulma: a modern CSS framework based on Flexbox" width="112" height="28">
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
          <a class="navbar-item" href="pre_venta.php">
            Ventas
          </a>
          <a class="navbar-item" href="../Clientes/clientes.php">
            Clientes
          </a>
          <a class="navbar-item" href="../Envio/envios.php">
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
<div class="modal" id="myModal">
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Venta</p>
      <button class="delete" aria-label="close"></button>
    </header>
    <section class="modal-card-body">
      <table class="table is-bordered is-hoverable is-fullwidth">
        <thead>
          <tr>
            <th><abbr title="ID">#</abbr></th>
            <th><abbr title="Nombre">Nombre Producto</abbr></th>
            <th><abbr title="Precio Venta">Precio Pza</abbr></th>
            <th><abbr title="Cantidad Total">Cantidad</abbr></th>
            <th><abbr title="Sub Total">Sub Total</abbr></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $ban = 1;
          foreach ($carro->items as $cart) {
                    ?>
                    <div>
                    <tr class="lista-productos">
                      <th><?php echo $ban; ?></th>
                      <td><?php echo $cart['item']->nombre_producto; ?></td>
                      <td>$<?php echo $cart['item']->precio_venta_unidad; ?></td>
                      <td><?php echo  $cart['qty']; ?></td>
                      <td class="id" hidden><span class="id"><?php echo $cart['item']->id; ?></span></td>
                      <td>$<?php echo $cart['price']; ?></td>
                    </tr>
                    </div>
                    <?php
                    $ban++;
                }
            ?>
        </tbody>
      </table>
      <p class="is-pulled-right"style="font-size: 26px;font-weight: bold; color: rgba(15, 69, 134, 0.91);">Total: $<?php echo $_SESSION['cart']->totalPrice; ?></p>
    </section>
    <footer class="modal-card-foot">
      <button class="button is-success" id="cancelar">Ok</button>
    </footer>
  </div>
</div>
  <section class="section">
    <div class="container">
      <div class="card">
        <div class="card-content">
          <div class="content">
            <div class="columns is-mobile">

              <div class="column is-2">
                <label for="">Fecha:</label>
                <div class="field">
                  <p class="control has-icons-left">
                    <input class="input is-info" type="text" value="<?php echo $fechaFinal; ?>" title="<?php echo $fechaFinal; ?>" readonly>
                    <span class="icon is-small is-left">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </p>
                </div>
              </div>

              <div class="column is-2">
                <label for="">Número de Venta:</label>
                <div class="field">
                  <p class="control has-icons-left">
                    <input class="input is-info" type="text" value="<?php echo $id_venta; ?>"  title="Número de Venta" readonly>
                    <span class="icon is-small is-left">
                      <i class="fas fa-hashtag"></i>
                    </span>
                  </p>
                </div>
              </div>

              <div class="column is-4">
                <label for="nombre_cliente">Nombre Cliente:</label>
                  <div class="field">
                    <p class="control has-icons-left">
                      <input class="input is-info " type="text" placeholder="Nombre cliente" name="nombre_cliente" id="nombre_cliente">
                      <span class="icon is-small is-left">
                        <i class="fas fa-user"></i>
                      </span>
                    </p>
                  </div>
              </div>

              <div class="column is-2">
                <label for="empacar">Empacar en:</label>
                <div class="control">
                  <div class="select">
                    <select name="empacar" id="empacar">
                      <option value="Cuartel">Cuartel</option>
                      <option value="Tienda">Tienda</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="column is-2">
                <label for="total_venta">Total de Venta:</label>
                  <div class="field">
                    <p class="control has-icons-left">
                      <input class="input is-info " type="text" placeholder="Total de Venta" name="total_venta" id="total_venta" value="<?php echo $_SESSION['cart']->totalPrice; ?>" readonly>
                      <span class="icon is-small is-left">
                        <i class="fas fa-dollar-sign"></i>
                      </span>
                    </p>
                  </div>
              </div>
            </div>

            <div class="columns is-mobile">

              <div class="column is-3">
                <label for="estado">Estado:</label>
                <div class="control">
                  <div class="select">
                    <select name="estado" id="estado">
                      <option value="Aguascalientes">Aguascalientes</option>
                      <option value="Baja California">Baja California</option>
                      <option value="Baja California Sur">Baja California Sur</option>
                      <option value="Campeche">Campeche</option>
                      <option value="Coahuila de Zaragoza">Coahuila de Zaragoza</option>
                      <option value="Colima">Colima</option>
                      <option value="Chiapas">Chiapas</option>
                      <option value="Chihuahua">Chihuahua</option>
                      <option value="Distrito Federal">Distrito Federal</option>
                      <option value="Durango">Durango</option>
                      <option value="Guanajuato">Guanajuato</option>
                      <option value="Guerrero">Guerrero</option>
                      <option value="Hidalgo">Hidalgo</option>
                      <option value="Jalisco">Jalisco</option>
                      <option value="México">México</option>
                      <option value="Michoacán de Ocampo">Michoacán de Ocampo</option>
                      <option value="Morelos">Morelos</option>
                      <option value="Nayarit">Nayarit</option>
                      <option value="Nuevo León">Nuevo León</option>
                      <option value="Oaxaca">Oaxaca</option>
                      <option value="Puebla">Puebla</option>
                      <option value="Querétaro">Querétaro</option>
                      <option value="Quintana Roo">Quintana Roo</option>
                      <option value="San Luis Potosí">San Luis Potosí</option>
                      <option value="Sinaloa">Sinaloa</option>
                      <option value="Sonora">Sonora</option>
                      <option value="Tabasco">Tabasco</option>
                      <option value="Tamaulipas">Tamaulipas</option>
                      <option value="Tlaxcala">Tlaxcala</option>
                      <option value="Veracruz de Ignacio de la Llave">Veracruz de Ignacio de la Llave</option>
                      <option value="Yucatán">Yucatán</option>
                      <option value="Zacatecas">Zacatecas</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="column is-2">
                <label for="municipio">Municipio:</label>
                  <div class="field">
                    <p class="control has-icons-left">
                      <input class="input is-info " type="text" placeholder="Municipio" name="municipio" id="municipio">
                      <span class="icon is-small is-left">
                        <i class="fas fa-globe"></i>
                      </span>
                    </p>
                  </div>
              </div>

              <div class="column is-2">
                <label for="telefono">Teléfono:</label>
                  <div class="field">
                    <p class="control has-icons-left">
                      <input class="input is-info " type="text" placeholder="Teléfono" name="telefono" id="telefono">
                      <span class="icon is-small is-left">
                        <i class="fas fa-phone"></i>
                      </span>
                    </p>
                  </div>
              </div>

              <!-- <div class="column is-2">
                <label for="tipo_venta">Tipo de Venta:</label>
                <div class="control">
                  <div class="select">
                    <select name="tipo_venta" id="tipo_venta">
                      <option value="Mercado Libre">Mercado Libre</option>
                      <option value="Pagina Web">Pagina Web</option>
                      <option value="Facebook">Facebook</option>
                    </select>
                  </div>
                </div>
              </div> -->

              <div class="column is-3">
                <label for="negocio">Negocio:</label>
                  <div class="field">
                    <p class="control has-icons-left">
                      <input class="input is-info " type="text" placeholder="Negocio" name="negocio" id="negocio">
                      <span class="icon is-small is-left">
                        <i class="fas fa-handshake"></i>
                      </span>
                    </p>
                  </div>
              </div>

            </div>

            <div class="columns is-mobile">

              <div class="column is-4">
                <label for="direccion">Dirección:</label>
                  <div class="field">
                    <p class="control has-icons-left">
                      <input class="input is-info " type="text" placeholder="Dirección" name="direccion" id="direccion">
                      <span class="icon is-small is-left">
                        <i class="fas fa-globe"></i>
                      </span>
                    </p>
                  </div>
              </div>

              <div class="column is-2">
                <label for="codigoPostal">Código Postal:</label>
                  <div class="field">
                    <p class="control has-icons-left">
                      <input class="input is-info " type="text" placeholder="Código Postal" name="codigoPostal" id="codigoPostal">
                      <span class="icon is-small is-left">
                        <i class="fas fa-globe"></i>
                      </span>
                    </p>
                  </div>
              </div>

              <div class="column is-2">
                <label for="numero_guia">Número de Guía:</label>
                  <div class="field">
                    <p class="control has-icons-left">
                      <input class="input is-info " type="text" placeholder="Número de guía" name="numero_guia" id="numero_guia">
                      <span class="icon is-small is-left">
                        <i class="fas fa-envelope"></i>
                      </span>
                    </p>
                  </div>
              </div>

              <div class="column is-2">
                <label for="paqueteria">Paquetería:</label>
                <div class="control">
                  <div class="select">
                    <select name="paqueteria" id="paqueteria">
                      <option value="Fedex">Fedex</option>
                      <option value="DHL">DHL</option>
                      <option value="Castores">Castores</option>
                      <option value="Otro">Otro</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="column is-2">
                <label for="precio_envio">Precio de Envío:</label>
                  <div class="field">
                    <p class="control has-icons-left">
                      <input class="input is-info " type="text" placeholder="Precio de Envío" name="precio_envio" id="precio_envio">
                      <span class="icon is-small is-left">
                        <i class="fas fa-dollar-sign"></i>
                      </span>
                    </p>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <br>

      <div class="columns is-mobile">
        <div class="column is-2  is-offset-7">
          <p class="field">
            <button class="button is-danger is-outlined" id="showModal">
              <span>Ver carro</span>
              <span class="icon is-small">
                <i class="fas fa-shopping-cart"></i>
              </span>
            </button>
          </p>
        </div>
        <div class="column is-2">
          <p class="field">
            <a class="button is-success is-outlined" href="">
              <span>CONFIRMAR</span>
              <span class="icon is-small">
                <i class="fas fa-check"></i>
              </span>
            </a>
          </p>
        </div>
      </div>
    </div>
  </section>
  <script type="text/javascript">
  $(document).ready(function () {
    /** --------> // MOSTRAR MODAL // <-------- **/
    $("#showModal").click(function() {
      $("#myModal").addClass("is-active");
    });
  /** --------> // OCULTAR MODAL // <-------- **/
    $(".modal-close, .modal-background, .delete, #cancelar").click(function() {
       $(".modal").removeClass("is-active");
    });
  });
  </script>
</body>
</html>

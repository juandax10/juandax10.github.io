<?php
session_start();
require '../db_connection.php'; //CONEXION
/* ===================
 DATOS DE PRODUCTOS PARA IMPRIMIR EN TABLA
  ======================
**/
try {
        $db = getDB();
        $stmt = $db->prepare("SELECT * from productos");
        $stmt->execute();
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
    $res_productos = $stmt->fetchAll(PDO::FETCH_OBJ);
    $db = null;
    /* ===================
        DATOS CATEGORIAS (ID, NOMBRE)
      ======================
    **/
 try {
         $db = getDB();
         $stmt2 = $db->prepare("SELECT * from categorias");
         $stmt2->execute();
     } catch (PDOException $e) {
         echo '{"error":{"text":' . $e->getMessage() . '}}';
     }
     $resultado_categorias = $stmt2->fetchAll(PDO::FETCH_OBJ);
     $db = null;
    /* ===================
        DATOS DE PROVEEDORES PARA OBTENER NOMBRE EN TABLA
      ======================
    **/
    try {
            $db = getDB();
            $stmt3 = $db->prepare("SELECT * from proveedor");
            $stmt3->execute();
        } catch (PDOException $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
        $resultado_proveedor = $stmt3->fetchAll(PDO::FETCH_OBJ);

    $db = null;
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Inventario</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css">
  <script defer src="https://use.fontawesome.com/releases/v5.0.0/js/all.js"></script>
  <link rel="stylesheet" href="../css/inventario.css">
  <script src="https://code.jquery.com/jquery-3.1.0.min.js"
          integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>
</head>
<body>
  <nav class="navbar is-black">
  <div class="navbar-brand">
    <a class="navbar-item" href="#">
      <!-- <img src="#" width="112" height="28"> -->
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
          <a class="navbar-item is-active" href="inventario.php">
            Inventario
          </a>
          <a class="navbar-item" href="../Ventas/pre_venta.php">
            Ventas
          </a>
          <a class="navbar-item" href="../Clientes/clientes.php">
            Clientes
          </a>
          <a class="navbar-item" href="../Envio/envios.php">
            Envios
          </a>
          <!-- <a class="navbar-item" href="">
            Form
          </a>
          <hr class="navbar-divider">
          <a class="navbar-item" href="">
            Elements
          </a>
          <a class="navbar-item" href="">
            Components
          </a> -->
        </div>
      </div>
    </div>

    <div class="navbar-end">
      <div class="navbar-item">
        <div class="field is-grouped">
          <p class="control">
            <!-- <a class="bd-tw-button button" data-social-network="Twitter" data-social-action="tweet" data-social-target="#" target="_blank" href="https://twitter.com/intent/tweet?text=Bulma: a modern CSS framework based on Flexbox&amp;hashtags=bulmaio&amp;url=http://localhost:4000&amp;via=jgthms"> -->
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
<!-- // MODAL AGREGAR NUEVA GATEGORIA A BD (AJAX) , SI NO EXISTE Y UTILIZARLA EN LLENADO DE DATOS DE PRODUCTO// -->
<div class="modal" id="myModal">
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Agregar Nueva Categoría</p>
      <button class="delete" aria-label="close"></button>
    </header>
    <section class="modal-card-body">
    <input type="text" class="input is-medium" name="nueva_categoria" id="nueva_categoria" placeholder="Nombre Nueva Categoria" required>
    <span id="categoria_span" hidden style="color:rgb(233, 26, 26);"></span>
    </section>
    <footer class="modal-card-foot">
      <button class="button is-success" id="btn_nueva_categoria">Agregar Categoria</button>
      <button class="button" id="cancelar">Cancelar</button>
    </footer>
  </div>
  <button class="modal-close is-large" aria-label="close"></button>
</div>
<!-- // MODAL AGREGAR NUEVA PROVEEDOR A BD (AJAX) , SI NO EXISTE Y UTILIZARLA EN LLENADO DE DATOS DE PRODUCTO// -->

<div class="modal" id="myModal2">
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Agregar Nuevo Proveedor</p>
      <button class="delete" aria-label="close"></button>
    </header>
    <section class="modal-card-body">
      <input type="text" class="input is-medium" name="nuevo_proveedor" id="nuevo_proveedor" placeholder="Nombre Nuevo Proveedor">
    </section>
    <section class="modal-card-body">
      <input type="text" class="input is-medium" name="proveedor_direccion" id="proveedor_direccion" placeholder="Dirección">
    </section>
    <section class="modal-card-body">
      <input type="text" class="input is-medium" name="proveedor_celular" id="proveedor_celular" placeholder="Celular">
    </section>
    <section class="modal-card-body">
      <input type="text" class="input is-medium" name="proveedor_cuenta" id="proveedor_cuenta" placeholder="Cuenta Banco">
    </section>
    <section class="modal-card-body">
      <input type="email" class="input is-medium" name="proveedor_email" id="proveedor_email" placeholder="Email">
    </section>
    <section class="modal-card-body">
      <div class="control">
        <div class="select is-small">
          <select name="estados" id="estado_mexico">
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
    </section>
    <section class="modal-card-body">
      <input type="text" class="input is-medium" name="municipio" id="municipio" placeholder="Municipio">
    </section>
    <footer class="modal-card-foot">
      <button class="button is-success" id="btn_nuevo_proveedor">Agregar Proveedor</button>
      <button class="button" id="cancelar">Cancelar</button>
    </footer>
  </div>
  <button class="modal-close is-large" aria-label="close"></button>
</div>
  <section class="section">
    <div class="container">
      <!-- // MENSAJE ALERTA CUALQUIER ERROR// -->
      <?php
      if (!empty($_SESSION['mensaje'])) {?>
        <article class="message is-danger" id="div_mensajeAlerta">
          <div class="message-header">
            <p>ALERTA!</p>
            <button class="delete" aria-label="delete" id="mensaje_alerta"></button>
          </div>
          <div class="message-body">
            <?php echo $_SESSION['mensaje']; ?>
          </div>
        </article>
        <?php
        $_SESSION['mensaje'] = '';
      }
       ?>
       <!-- // ACCORDION - ABRE FORM PARA NUEVO PRODUCTO// -->

      <div class="accordion">
            <dl>
              <dt>
                <a href="#accordion1" aria-expanded="false" aria-controls="accordion1" class="accordion-title accordionTitle js-accordionTrigger">Agregar Producto</a>
              </dt>
              <dd class="accordion-content accordionItem is-collapsed" id="accordion1" aria-hidden="true">
                <form class="" action="guardarProducto.php" method="post" enctype="multipart/form-data">
                  <div class="field is-horizontal">
                    <div class="field-label is-small">
                      <label class="label" for="nombre_producto">**Nombre</label>
                    </div>
                    <div class="field-body">
                      <div class="field">
                        <p class="control is-expanded has-icons-left">
                          <input class="input is-small" type="text" id="nombre_producto" placeholder="Nombre" name="nombre_producto" autocomplete="off">
                          <span class="icon is-small is-left">
                            <i class="fas fa-user"></i>
                          </span>
                        </p>
                      </div>
                    </div>
                    <div class="field-label is-small">
                      <label class="label" for="categoria">Categoria</label>
                    </div>
                    <div class="field-body">
                      <div class="field is-narrow">
                        <div class="control">
                          <div class="select is-small">
                            <select id="categoria" name="categoria_id">
                              <?php
                              foreach ($resultado_categorias as $row2) {
                               ?>
                              <option value="<?php echo $row2->id; ?>"><?php echo $row2->nombre_categoria; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <p id="showModal"><i class="fas fa-plus-square"></i></p>
                    </div>

                    <div class="field-label is-small">
                      <label class="label" for="cantidad">**Cantidad Casa</label>
                    </div>
                    <div class="field-body">
                      <div class="field is-expanded">
                        <div class="field has-addons">
                          <p class="control is-expanded">
                            <input class="input is-small" type="text" name="cantidad_total" value="0"  id="cantidad" placeholder="Cantidad Casa" autocomplete="off">
                          </p>
                        </div>
                      </div>
                    </div>

                    <div class="field-label is-small">
                      <label class="label" for="cantidad_tienda">**Cantidad Tienda</label>
                    </div>
                    <div class="field-body">
                      <div class="field is-expanded">
                        <div class="field has-addons">
                          <p class="control is-expanded">
                            <input class="input is-small" type="text" name="cantidad_total_tienda" value="0" id="cantidad_tienda" placeholder="Cantidad Tienda" autocomplete="off">
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="field is-horizontal">
                    <div class="field-label is-small">
                      <label class="label" for="compra_unidad">**P. Compra Unidad</label>
                    </div>
                    <div class="field-body">
                      <div class="field">
                        <p class="control is-expanded has-icons-left">
                          <input class="input is-small" type="text" id="compra_unidad" name="precio_compra_unidad" placeholder="Precio Compra Unidad" autocomplete="off">
                          <span class="icon is-small is-left">
                            <i class="fas fa-dollar-sign "></i>
                          </span>
                        </p>
                      </div>
                    </div>
                    <div class="field-label is-small">
                      <label class="label" for="venta_unidad">**P. Venta ML</label>
                    </div>
                    <div class="field-body">
                      <div class="field is-expanded">
                        <div class="field has-addons">
                          <p class="control is-expanded has-icons-left">
                            <input class="input is-small" type="text" id="venta_unidad" name="precio_venta_unidad" placeholder="Precio Venta Unidad" autocomplete="off">
                            <span class="icon is-small is-left">
                              <i class="fas fa-dollar-sign "></i>
                            </span>
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="field-label is-small">
                      <label class="label" for="venta_unidad">**P. Venta Tienda</label>
                    </div>
                    <div class="field-body">
                      <div class="field is-expanded">
                        <div class="field has-addons">
                          <p class="control is-expanded has-icons-left">
                            <input class="input is-small" type="text" id="venta_unidad_tienda" name="precio_venta_unidad_tienda" placeholder="Precio Venta Unidad en TIENDA" autocomplete="off">
                            <span class="icon is-small is-left">
                              <i class="fas fa-dollar-sign "></i>
                            </span>
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="field-label is-small">
                      <label class="label" for="precio_mayoreo">P. Mayo.</label>
                    </div>
                    <div class="field-body">
                      <div class="field is-expanded">
                        <div class="field has-addons">
                          <p class="control is-expanded has-icons-left">
                            <input class="input is-small" type="text" id="precio_mayoreo" name="precio_mayoreo_unidad" placeholder="Precio Mayoreo Unidad" autocomplete="off">
                            <span class="icon is-small is-left">
                              <i class="fas fa-dollar-sign "></i>
                            </span>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="field is-horizontal">

                    <div class="field-label is-small">
                      <label class="label" for="pzs_por_caja">**Pz Caja</label>
                    </div>
                    <div class="field-body">
                      <div class="field is-expanded">
                        <div class="field has-addons">
                          <p class="control is-expanded">
                            <input class="input is-small" type="text" id="pzs_por_caja" name="pz_caja_paquete" placeholder="Piezas totales por caja o paquete" autocomplete="off">
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="field-label is-small">
                      <label class="label" for="talla">Talla</label>
                    </div>
                    <div class="field-body">
                      <div class="field is-expanded">
                        <div class="field has-addons">
                          <p class="control is-expanded">
                            <input class="input is-small" type="text" id="talla" name="talla" placeholder="Talla o medida" autocomplete="off">
                          </p>
                        </div>
                      </div>
                    </div>

                    <div class="field-label is-small">
                      <label class="label" for="imagen_producto">Imagen:</label>
                    </div>
                    <div class="field-body">
                      <div class="field">
                        <div class="field has-addons">
                          <p class="control">
                            <input class="input is-small" type="file" id="imagen_producto" name="imagen_producto" accept="image/jpeg,image/png,image/jpg" autocomplete="off">
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="field is-horizontal">

                    <div class="field-label is-small">
                      <label class="label" for="ubicacion">Ubicación</label>
                    </div>
                    <div class="field-body">
                      <div class="field is-narrow">
                        <div class="control">
                          <div class="select is-small">
                            <select  id="ubicacion" name="ubicacion">
                              <option value="Tienda N.I">Tienda N.I</option>
                              <option value="Cuartel">Cuartel</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="field-label is-small">
                      <label class="label" for="proveedor">Proveedor</label>
                    </div>
                    <div class="field-body">
                      <div class="field is-narrow">
                        <div class="control">
                          <div class="select is-small">
                            <select id="proveedor" name="proveedor_id">
                              <?php
                              foreach ($resultado_proveedor as $row4) {
                               ?>
                              <option value="<?php echo $row4->id; ?>"><?php echo $row4->nombre_proveedor; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <p id="showModal2"><i class="fas fa-plus-square"></i></p>
                    </div>
                    <div class="field-body">
                      <div class="field">
                        <div class="control">
                          <input type="submit" name="guardar" id="guardarProducto" hidden>
                          <button type="button" class="button is-primary" name="button" id="guardarProducto2">Guardar Producto</button>
                        </div>
                      </div>
                    </div>
                    <div class="field-body"></div>
                </div>
                </form>
              </dd>
            </dl>
        </div>
        <br>
        <h2 class="editar-mensaje">doble click para editar campo</h2>
        <table class="table is-bordered is-hoverable">
          <thead>
            <tr>
              <th><abbr title="ID">#</abbr></th>
              <th>Nombre</th>
              <th><abbr title="Categoria">Categoría</abbr></th>
              <th><abbr title="Precio Compra">P. Compra</abbr></th>
              <th><abbr title="Precio Venta ML">P. Venta ML</abbr></th>
              <th><abbr title="Precio Venta Tienda">P. Venta Tienda</abbr></th>
              <th><abbr title="Precio Mayoreo">P. Mayoreo</abbr></th>
              <th><abbr title="Cantidad Total CASA">Cantidad Casa</abbr></th>
              <th><abbr title="Cantidad Total TIENDA">Cantidad Tienda</abbr></th>
              <th><abbr title="Piezas por caja">Pz x Caja</abbr></th>
              <th><abbr title="Ubicación">Ubicación</abbr></th>
              <th><abbr title="Proveedor">Proveedor</abbr></th>
              <th><abbr title="Talla o Medida">Talla</th>
            </tr>
          </thead>
          <tbody>
            <?php
              foreach ($res_productos as $row) {
                foreach($resultado_categorias as $r_cate){
                  foreach ($resultado_proveedor as $r_prove) {
                    if ($row->proveedor_id == $r_prove->id && $r_cate->id == $row->categoria_id) {
             ?>
            <tr>
              <th><?php echo $row->id; ?></th>
              <td class="trampa" data-nombre="nombre_producto" data-id="<?php echo $row->id; ?>"><?php echo $row->nombre_producto; ?></td>
              <td class="trampa" data-nombre="nombre_categoria" data-id="<?php echo $row->id; ?>"><?php echo $r_cate->nombre_categoria; ?></td>
              <td class="trampa" data-nombre="precio_compra_unidad" data-id="<?php echo $row->id; ?>">$<?php echo $row->precio_compra_unidad; ?></td>
              <td class="trampa" data-nombre="precio_venta_unidad" data-id="<?php echo $row->id; ?>">$<?php echo $row->precio_venta_unidad; ?></td>
              <td class="trampa" data-nombre="precio_venta_unidad_tienda" data-id="<?php echo $row->id; ?>">$<?php echo $row->precio_venta_unidad_tienda; ?></td>
              <td class="trampa" data-nombre="precio_mayoreo_unidad" data-id="<?php echo $row->id; ?>">$<?php echo $row->precio_mayoreo_unidad; ?></td>
              <td class="trampa" data-nombre="cantidad_total" data-id="<?php echo $row->id; ?>"><?php echo $row->cantidad_total; ?></td>
              <td class="trampa" data-nombre="cantidad_total_tienda" data-id="<?php echo $row->id; ?>"><?php echo $row->cantidad_total_tienda; ?></td>
              <td class="trampa" data-nombre="pz_caja_paquete" data-id="<?php echo $row->id; ?>"><?php echo $row->pz_caja_paquete; ?></td>
              <td class="trampa" data-nombre="ubicacion" data-id="<?php echo $row->id; ?>"><?php echo $row->ubicacion; ?></td>
              <td class="trampa" data-nombre="nombre_proveedor" data-id="<?php echo $row->id; ?>"><?php echo $r_prove->nombre_proveedor; ?></td>
              <td class="trampa" data-nombre="talla" data-id="<?php echo $row->id; ?>"><?php echo $row->talla; ?></td>
            </tr>
            <?php
                }
                }
              }
            }
             ?>
          </tbody>
        </table>
    </div>
  </section>
<script type="text/javascript">
$(document).ready(function () {
  /** --------> // MOSTRAR MODAL // <-------- **/
  $("#showModal").click(function() {
    $("#myModal").addClass("is-active");
  });
  $("#showModal2").click(function() {
    $("#myModal2").addClass("is-active");
  });
/** --------> // OCULTAR MODAL // <-------- **/
  $(".modal-close, .modal-background, .delete, #cancelar").click(function() {
     $(".modal").removeClass("is-active");
  });
  /** --------> // GUARDAR PRODUCTO // <-------- **/
  $("#guardarProducto2").click(function () {
    var nombre_producto, cantidad_casa, cantidad_tienda, precio_compra_unidad, precio_venta_mercado, precio_venta_tienda, pz_caja;
    nombre_producto = $("#nombre_producto").val();
    cantidad_casa = $("#cantidad").val();
    cantidad_tienda = $("#cantidad_tienda").val();
    precio_compra_unidad = $("#compra_unidad").val();
    precio_venta_mercado = $("#venta_unidad").val();
    precio_venta_tienda = $("#venta_unidad_tienda").val();
    pz_caja = $("#pzs_por_caja").val();

    if ($.trim(nombre_producto) != '' && $.trim(cantidad_casa) != '' && $.trim(cantidad_tienda) != '' && $.trim(precio_compra_unidad) != ''
     && $.trim(precio_venta_mercado) != '' && $.trim(precio_venta_tienda) != '' && $.trim(pz_caja) != '') {
      $("#guardarProducto").trigger("click");
    }else{
      if ($.trim(nombre_producto) == '') {
        $("#nombre_producto").addClass('borderClass').delay(3000)
                .queue(function () {
                    $(this).removeClass("borderClass");
                    $(this).dequeue();
                });
      }
    if ($.trim(cantidad_casa) == '') {
      $("#cantidad").addClass('borderClass').delay(3000)
              .queue(function () {
                  $(this).removeClass("borderClass");
                  $(this).dequeue();
              });
    }
    if ($.trim(cantidad_tienda) == '') {
      $("#cantidad_tienda").addClass('borderClass').delay(3000)
              .queue(function () {
                  $(this).removeClass("borderClass");
                  $(this).dequeue();
              });
    }
    if ($.trim(precio_compra_unidad) == '') {
      $("#compra_unidad").addClass('borderClass').delay(3000)
              .queue(function () {
                  $(this).removeClass("borderClass");
                  $(this).dequeue();
              });
    }
    if ($.trim(precio_venta_mercado) == '') {
      $("#venta_unidad").addClass('borderClass').delay(3000)
              .queue(function () {
                  $(this).removeClass("borderClass");
                  $(this).dequeue();
              });
    }
    if ($.trim(precio_venta_tienda) == '') {
      $("#venta_unidad_tienda").addClass('borderClass').delay(3000)
              .queue(function () {
                  $(this).removeClass("borderClass");
                  $(this).dequeue();
              });
    }
    if ($.trim(pz_caja) == '') {
      $("#pzs_por_caja").addClass('borderClass').delay(3000)
              .queue(function () {
                  $(this).removeClass("borderClass");
                  $(this).dequeue();
              });
    }
  }
  });
    /** --------> // FIN GUARDAR PRODUCTO // <-------- **/
  /** -------> // EDITAR CAMPO CON DOBLE CLICK // <-------- **/
  $(".trampa").dblclick(function(){
    var $this = $(this);
    var id= $this.attr('data-id');
    var data_nombre = $this.attr('data-nombre');
    var valor = $this.text();
    console.log("id ===>"+id);
    console.log("valor ===>"+valor);
    console.log("valor ===>"+data_nombre);
    var $input = $('<input>', {
        value: $this.text(),
        type: 'text',
        blur: function() {
           $this.text(this.value);
           var valor_nuevo = this.value;
           $.ajax({
                       method: 'POST',
                       url: 'actualizarInventario.php',
                       data: {id: id, data_nombre:data_nombre, valor:valor_nuevo},
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
                     console.log(msg);
                   });
        },
        keyup: function(e) {
           if (e.which === 13) $input.blur();
        }
    }).appendTo( $this.empty() ).focus();
    //var nuevo_dato = prompt("Ingresa el nuevo valor para", "Harry Potter");
});
  /** --------> // Agregar nueva categoria // <-------- **/
  $("#btn_nueva_categoria").click(function () {
      var new_cate = $("#nueva_categoria").val();
      if (new_cate) {
        $.ajax({
                method: 'POST',
                url: 'nuevaCategoria.php',
                data: { nombre: new_cate},
              })
                            .done(function (msg) {
                              var aux = JSON.parse(msg);
                              $('#categoria').empty();
                              for (var variable in aux) {
                                if (aux.hasOwnProperty(variable)) {
                                      $('#categoria').append('<option value=' + aux[variable]['id'] + '>' + aux[variable]['nombre_categoria'] + '</option>');
                                }
                              }
                              $("#nueva_categoria").val('');
                            });
                }else{
                    $("#categoria_span").text('Ingresa el nombre.').show();
                  $("#nueva_categoria").addClass('borderClass').delay(3000)
                          .queue(function () {
                              $(this).removeClass("borderClass");
                              $(this).dequeue();
                              $("#categoria_span").fadeOut();
                          });
                }
      });

    /** --------> // Fin agregar nueva categoria // <-------- **/
    $("#mensaje_alerta").click(function () {
      $("#div_mensajeAlerta").fadeOut();
    });
    /** --------> // Agregar nuevo proveedor // <-------- **/
    $("#btn_nuevo_proveedor").click(function () {
        var direccion, celular , estado, municipio, email, nuevo_proveedor, cuenta;
        nuevo_proveedor = $("#nuevo_proveedor").val();
        direccion = $("#proveedor_direccion").val();
        celular = $("#proveedor_celular").val();
        estado = $("#estado_mexico").val();
        municipio = $("#municipio").val();
        email = $("#proveedor_email").val();
        cuenta = $("#proveedor_cuenta").val();
        if (nuevo_proveedor && celular && direccion && cuenta && municipio) {
          $.ajax({
                  method: 'POST',
                  url: 'nuevoProveedor.php',
                  data: { cuenta: cuenta, nombre_proveedor: nuevo_proveedor, direccion_proveedor: direccion, celular_proveedor: celular, estado_proveedor: estado, municipio_proveedor: municipio, email_proveedor: email},
                })
                              .done(function (msg) {
                                var aux = JSON.parse(msg);
                                $('#proveedor').empty();
                                for (var variable in aux) {
                                  if (aux.hasOwnProperty(variable)) {
                                        $('#proveedor').append('<option value=' + aux[variable]['id'] + '>' + aux[variable]['nombre_proveedor'] + '</option>');
                                  }
                                }
                                $("#nuevo_proveedor").val('');
                                $("#proveedor_direccion").val('');
                                $("#proveedor_celular").val('');
                                $("#municipio").val('');
                                $("#proveedor_email").val('');
                                $("#proveedor_cuenta").val('');
                                $('#estado_mexico').prop('selectedIndex',0);
                              });
                  }else{
                    if ($.trim(nuevo_proveedor) == '') {
                      $("#nuevo_proveedor").addClass('borderClass').delay(3000)
                              .queue(function () {
                                  $(this).removeClass("borderClass");
                                  $(this).dequeue();
                              });
                    }
                    if ($.trim(direccion) == '') {
                      $("#proveedor_direccion").addClass('borderClass').delay(3000)
                              .queue(function () {
                                  $(this).removeClass("borderClass");
                                  $(this).dequeue();
                              });
                    }
                    if ($.trim(celular) == '') {
                      $("#proveedor_celular").addClass('borderClass').delay(3000)
                              .queue(function () {
                                  $(this).removeClass("borderClass");
                                  $(this).dequeue();
                              });
                    }
                    if ($.trim(cuenta) == '') {
                      $("#proveedor_cuenta").addClass('borderClass').delay(3000)
                              .queue(function () {
                                  $(this).removeClass("borderClass");
                                  $(this).dequeue();
                              });
                    }
                    if ($.trim(municipio) == '') {
                      $("#municipio").addClass('borderClass').delay(3000)
                              .queue(function () {
                                  $(this).removeClass("borderClass");
                                  $(this).dequeue();
                              });
                    }

                  }
        });

      /** --------> // Fin agregar nuevo proveedor // <-------- **/
      $("#cantidad , #compra_unidad , #venta_unidad , #precio_mayoreo , #pzs_por_caja, #cantidad_tienda").keydown(function (e) { // permitir solo numeros
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
    /** --------> // Inicio accordion // <-------- **/
    var acc = document.getElementsByClassName("accordion");
    var i;
    for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
      this.classList.toggle("active");
      var panel = this.nextElementSibling;
      if (panel.style.maxHeight){
        panel.style.maxHeight = null;
      } else {
        panel.style.maxHeight = panel.scrollHeight + "px";
      }

      /** --------> // Fin accordion // <-------- **/
      });
  }

  //uses classList, setAttribute, and querySelectorAll
//if you want this to work in IE8/9 youll need to polyfill these
(function(){
	var d = document,
	accordionToggles = d.querySelectorAll('.js-accordionTrigger'),
	setAria,
	setAccordionAria,
	switchAccordion,
  touchSupported = ('ontouchstart' in window),
  pointerSupported = ('pointerdown' in window);

  skipClickDelay = function(e){
    e.preventDefault();
    e.target.click();
  }

		setAriaAttr = function(el, ariaType, newProperty){
		el.setAttribute(ariaType, newProperty);
	};
	setAccordionAria = function(el1, el2, expanded){
		switch(expanded) {
      case "true":
      	setAriaAttr(el1, 'aria-expanded', 'true');
      	setAriaAttr(el2, 'aria-hidden', 'false');
      	break;
      case "false":
      	setAriaAttr(el1, 'aria-expanded', 'false');
      	setAriaAttr(el2, 'aria-hidden', 'true');
      	break;
      default:
				break;
		}
	};
//function
switchAccordion = function(e) {
	e.preventDefault();
	var thisAnswer = e.target.parentNode.nextElementSibling;
	var thisQuestion = e.target;
	if(thisAnswer.classList.contains('is-collapsed')) {
		setAccordionAria(thisQuestion, thisAnswer, 'true');
	} else {
		setAccordionAria(thisQuestion, thisAnswer, 'false');
	}
  	thisQuestion.classList.toggle('is-collapsed');
  	thisQuestion.classList.toggle('is-expanded');
		thisAnswer.classList.toggle('is-collapsed');
		thisAnswer.classList.toggle('is-expanded');

  	thisAnswer.classList.toggle('animateIn');
	};
	for (var i=0,len=accordionToggles.length; i<len; i++) {
		if(touchSupported) {
      accordionToggles[i].addEventListener('touchstart', skipClickDelay, false);
    }
    if(pointerSupported){
      accordionToggles[i].addEventListener('pointerdown', skipClickDelay, false);
    }
    accordionToggles[i].addEventListener('click', switchAccordion, false);
  }
})();
});
</script>
</body>
</html>

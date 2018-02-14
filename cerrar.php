<?php
/**
 * Created by PhpStorm.
 * User: Juanda
 * Date: 12/12/2017
 * Time: 09:24 PM
 */
session_start();
session_unset();
session_destroy();
header("Location:pre_venta.php");
exit();

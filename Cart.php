<?php
class Cart
{
    public $items = null; //grupo de productos
    public $totalQty = 0;
    public $totalPrice = 0;

    public function __construct($oldCart)
    {
        if ($oldCart) {
            $this->items = $oldCart->items;
            $this->totalQty = $oldCart->totalQty;
            $this->totalPrice = $oldCart->totalPrice;
        }
    }

    public function add($item, $id)
    {
        $storedItem = ['qty' => 0, 'price' => $item->precio_venta_unidad, 'price2' => $item->precio_venta_unidad, 'item' => $item];
        if ($this->items) {
            if (array_key_exists($id, $this->items)) {
                $storedItem = $this->items[$id];
            }
        }
        $storedItem['qty']++;
        $storedItem['price'] = $item->precio_venta_unidad * $storedItem['qty'];
        $this->items[$id] = $storedItem;
        $this->totalQty++;
        $this->totalPrice += $item->precio_venta_unidad;
    }

    public function add2($item, $id, $cantidad)
    {
        $storedItem = ['qty' => 0, 'price' => $item->precio_venta_unidad, 'price2' => $item->precio_venta_unidad, 'item' => $item];
        if ($this->items) {
            if (array_key_exists($id, $this->items)) {
                $storedItem = $this->items[$id]; // guardar los datos para ese y usar sus propiedades id de producto
            }
        }
        $oldQty = $storedItem['qty']; //cantidad vieja
        $resta = $oldQty * $item->precio_venta_unidad;
        $storedItem['qty'] = $cantidad; // nueva cantidad
        $storedItem['price'] = $item->precio_venta_unidad * $storedItem['qty']; // nuevo precio
        $this->items[$id] = $storedItem; // actualizar items
        //$this->totalQty++;
        $this->totalPrice += $storedItem['price']-$resta; //actualizar precio total de venta de todos productos ( se resta lo que habia antes de el producto que se modifica)
    }

}

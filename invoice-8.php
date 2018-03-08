<?php


if (is_user_logged_in()) {
  // echo " Hmm you are Logged in : Good Job <br>";
}
$status = (isset($_GET['status']) ? $_GET['status'] : false); // status
$id =  (isset($_GET['id']) ? $_GET['id'] : false); // id
$order = wc_get_order( $id  );
$currency_symbol = get_woocommerce_currency_symbol( $order->currency );

$products = array() ;

foreach($order->get_items() as $item_id => $item_values){

  $items = array();

  $product_id = $item_values['product_id'];
  $product_name = $item_values['name'];
  $product_quantity = $item_values['quantity'];
  $product_subtotal_price = $item_values['subtotal'];
  $product_total_price = $item_values['total'];

  echo $product_id  ." ". $product_name  ." ".   $product_quantity  ." ".   $product_subtotal_price   ." ".  $product_total_price  ;

  // $products[$product_id ]['product_id'] = $product_id  ;
  // $products[$product_id ]['name'] =       $product_name  ;
  // $products[$product_id ]['quantity'] =   $product_quantity  ;
  // $products[$product_id ]['subtotal'] =   $product_subtotal_price  ;
  // $products[$product_id ]['total'] =      $product_total_price   ;

  // $products[$product_id ]['product_id'] = $item_values['product_id'];
  // $products[$product_id ]['name'] =       $item_values['name'];
  // $products[$product_id ]['quantity'] =   $item_values['quantity'];
  // $products[$product_id ]['subtotal'] =   $item_values['subtotal'];
  // $products[$product_id ]['total'] =      $item_values['total'];


  $items['product_id'] = $item_values['product_id'];
  $items['name'] =       $item_values['name'];
  $items['quantity'] =   $item_values['quantity'];
  $items['subtotal'] =   $item_values['subtotal'];
  $items['total'] =      $item_values['total'];
  
  $products[] = $items; 

  // echo "<br>";

}

echo "<br>";
// print_r($order->get_items()  )
echo "<pre>";
print_r($products );
echo "</pre>";



?>
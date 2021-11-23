<?php
require '../webhook/product_data.php';
$results = array();
foreach( $products as $key => $value ) {

    $pair = array();
    $pair[$value] = $key;
    array_push($results, $pair);

}
echo json_encode($results);
?>

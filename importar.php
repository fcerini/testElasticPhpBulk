<?php
include_once "config.php";
include_once "./shared/elastic.php";

// OJO convertir las fechas con convert(varchar, campoFecha, 127) as campoFecha

$json = array();

//configurar la conexion en Config.php
$db = sqlsrv_connect(DB_HOST, array('Database'=>DB_DATABASE, 'UID'=>DB_USERNAME, 'PWD'=>DB_PASSWORD, 'CharacterSet' => 'UTF-8'));
if( $db === false )
    die( print_r( sqlsrv_errors(), true));

//SQL principal
$sql1 = 'SELECT pedidoId as ID, convert(varchar, fecha, 127) as fecha, c.clienteid, c.nombre
        FROM pedido p inner join cliente c on c.clienteid = p.clienteid';
$q1 = sqlsrv_query($db, $sql1);
if($q1 === false)
    die( print_r( sqlsrv_errors(), true));

//Recorro las filas y cargo los detalles en arrays hijos
while ($row = sqlsrv_fetch_array($q1, SQLSRV_FETCH_ASSOC)) {

    $sql2 = "SELECT numero from telefono where clienteid=? " ;
    $q2 = sqlsrv_query($db, $sql2, array($row['clienteid']));
    while ($detail = sqlsrv_fetch_array($q2, SQLSRV_FETCH_ASSOC)) {
        $row['telefonos'][] = $detail;
    }

    $sql3 = "select p.descripcion as producto, d.cantidad, d.subtotal
            from detalle d inner join producto p on p.productoId = d.detalleId
            where d.pedidoId =? " ;
    $q3 = sqlsrv_query($db, $sql3, array($row['ID']));
    while ($detail = sqlsrv_fetch_array($q3, SQLSRV_FETCH_ASSOC)) {
        $row['detalle'][] = $detail;
    }
    
    $json[] =  $row;
}


// mando al elastic. configurar los parametros en Config.php
Elastic::bulk($json);

sqlsrv_close($db);

?>
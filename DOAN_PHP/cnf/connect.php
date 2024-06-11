<?php

$pdo = new PDO( 'mysql:host=localhost; dbname=ddsv', 'root', '' );
$stm = $pdo->query( 'select * from _user' );
$data = $stm -> fetchAll( PDO::FETCH_ASSOC );

if ( $pdo ) {

} else {
    echo 'fail';
}

?>
<link href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' rel = 'stylesheet' integrity = 'sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC' crossorigin = 'anonymous'>
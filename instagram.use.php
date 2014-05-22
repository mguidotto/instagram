<?php

require './instagram.class.php';

$instagram = new Instagram('tags', array('snow'), array(''));
$instagram->setClientId('YOURCLIENTID');
$instagram->setClientSecret('YOURCLIENTSECRET');
$instagram->getAllImages( true );

foreach ( $instagram->list as $item ){
	var_dump($item);
}
die;
?>

<?php
date_default_timezone_set('Asia/Jakarta');
if ($_POST) {
    $data = array();
	$pos = $_POST['pos'];
	$id = $_POST['id'];
	$date = $_POST['id'];
    if ($id) {
        fwrite(fopen("box/$id.txt", "w"), $pos."|".date('d/m/Y - H:i:s'));
    }
}
?>
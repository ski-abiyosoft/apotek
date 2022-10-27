<?php
    
	$q = $_REQUEST["q"];
	$tgl1 = $_REQUEST["tgl1"];
	$tgl2 = $_REQUEST["tgl2"];
	$nobukti = $_REQUEST["nobukti"];
	$uid = $_REQUEST["uid"];
	
	$param = 'akuntansi_laporan_1_pdf.php?tgl1='.$tgl1.'&tgl2='.$tgl2.'&nobukti='.$nobukti.'&uid='.$uid;
	
	echo "<object data='$param' type='application/pdf' width='100%' height='400px'></object>"
	
?>


	
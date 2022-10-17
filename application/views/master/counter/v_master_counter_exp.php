<?php
 header("Content-type: application/vnd-ms-excel");
 header("Content-Disposition: attachment; filename=master_cabang.xls");
 header("Pragma: no-cache");
 header("Expires: 0");
?>
<h4>DAFTAR CABANG</H4>
<table border="1" >
	<thead>
		 <tr>
			 <th style="text-align: center">Kode</th>
			 <th style="text-align: center">Nama Cabang</th>
			 <th style="text-align: center">Pimpinan</th>
			 <th style="text-align: center">Alamat</th>
			 <th style="text-align: center">Telpon</th>
			
		 </tr>
	 </thead>
	 <tbody>
	 <?php
	   foreach($master->result_array() as $db) { ?>
		 <tr>
			 <td><?php echo $db['kode'];?></td>
			 <td><?php echo $db['nama'];?></td>
			 <td><?php echo $db['pimpinan'];?></td>
			 <td><?php echo $db['alamat'];?></td>
			 <td><?php echo $db['telpon'];?></td>
			                                       
		 </tr>
	 <?php } ?>
	 </tbody>
</table>





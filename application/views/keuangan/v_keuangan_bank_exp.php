<?php
 header("Content-type: application/vnd-ms-excel");
 header("Content-Disposition: attachment; filename=master_bank.xls");
 header("Pragma: no-cache");
 header("Expires: 0");
?>
<table border="1" >
	<thead>
		 <tr>
			 <th style="text-align: center">Kode</th>
			 <th style="text-align: center">Nama</th>
			 <th style="text-align: center">Jenis</th>
			 <th style="text-align: center">Kode Akun</th>
			 <th style="text-align: center">No. Rekening</th>
			 <th style="text-align: center">Unit</th>
			

		 </tr>
	 </thead>
	 <tbody>
	 <?php
	   foreach($master_bank->result_array() as $db) { ?>
		 <tr class="show1" id="row_<?php echo $db['bank_kode'];?>">
			 <td><?php echo $db['bank_kode'];?></td>
			 <td><?php echo $db['bank_nama'];?></td>
			 <td><?php echo $db['bank_jenis'];?></td>
			 <td><?php echo $db['bank_kodeakun'];?></td>
			 <td><?php echo $db['bank_norek'];?></td>
			 <td><?php echo $db['bank_pasar'];?></td>                                         
		 </tr>
	 <?php } ?>
	 </tbody>
</table>





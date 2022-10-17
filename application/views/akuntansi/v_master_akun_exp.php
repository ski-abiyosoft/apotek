<?php
 header("Content-type: application/vnd-ms-excel");
 header("Content-Disposition: attachment; filename=master_akun.xls");
 header("Pragma: no-cache");
 header("Expires: 0");
?> 

<table border="1" >
	<thead>
		 <tr>
			 <th style="text-align: center">Kode Perkiraan</th>
			 <th style="text-align: center">Nama</th>
			 <th style="text-align: center">Tipe Akun</th>
			 <th style="text-align: center">Level</th>
		 </tr>
	 </thead>
	 <tbody>
	 <?php

	   foreach($master_akun as $db) { ?>
		 <tr>
			 <td><?php echo $db->accountno;?></td>
			 <td><?php echo $db->acname;?></td>
			 <td><?php echo $db->typename;?></td>
			 <td><?php echo $db->aclevel;?></td>
		 </tr>
	 <?php } ?>
	 </tbody>
</table>





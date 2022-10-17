<?php
 header("Content-type: application/vnd-ms-excel");
 header("Content-Disposition: attachment; filename=keuangan_transfer.xls");
 header("Pragma: no-cache");
 header("Expires: 0");
?> 

<table border="1" >
	<thead>
		 <tr>
			 <th style="text-align: center">Cab.</th>
			 <th style="text-align: center">User ID</th>
			 <th style="text-align: center">Nomor</th>
			 <th style="text-align: center">Tanggal</th>
			 <th style="text-align: center">Bank (Keluar)</th>
			 <th style="text-align: center">Bank (Masuk)</th>
			 <th style="text-align: center">Keterangan</th>
			 <th style="text-align: center">Total</th>
		 </tr>
	 </thead>
	 <tbody>
	 <?php
	   foreach($data as $row) { ?>
		 <tr>
            <td><center><?php echo $row->koders;?></center></td>
            <td><center><?php echo $row->username;?></center></td> 
            <td align="center"><?php echo $row->nomutasi;?></td>
            <td align="center"><?php echo date('d-m-Y',strtotime($row->tglmutasi));?></td>
            <td><?php echo $row->acdari;?></td>
            <td><?php echo $row->acke;?></td>
            <td><?php echo $row->keterangan;?></td>
            <!-- <td align="right"><?php echo number_format($row->mutasirp,0,'.',',');?></td> -->
            <td align="right"><?php echo number_format($row->mutasirp,0,',','.');?></td>
		 </tr>
	 <?php } ?>
	 </tbody>
</table>





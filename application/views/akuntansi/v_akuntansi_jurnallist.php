
</br>
<div class="portlet-body">							
  <table class="table table-striped table-hover table-bordered" id="akuntansi-jurnal-list">
     <thead>
      <tr>
         <th>Tanggal</th>        
		 <th>Nomor Bukti</th>
         <th>Kode Akun</th>
		 <th>Nama Akun</th>
         <th>Uraian</th>
         <th>Debet</th>
         <th>Kredit</th>
		 <th>UserID</th>
         <th style="width:20px;">&nbsp</th>
         <th style="width:20px;">&nbsp</th>
     </tr>
     </thead>
     <tbody>
	         <?php
             $totd=0;
             $totk=0;
             foreach ($jurnal->result() as $row)
             {
                 $totd=$totd+$row->debet;
                 $totk=$totk+$row->kredit;
             ?>

             <tr class="" id="row_<?php echo $row->nomor;?>">
             
			     <td><?php echo date('d-m-Y',strtotime($row->tanggal));?></td>
				 <td><?php echo $row->novoucher;?></td>
                 <td><?php echo $row->kodeakun;?></td>
				 <td><?php echo $row->namaakun;?></td>
                 <td><?php echo $row->keterangan;?></td>
                 <td align="right"><?php echo number_format($row->debet,2,',','.');?></td>
                 <td align="right"><?php echo number_format($row->kredit,2,',','.');?></td>
				 <td><?php echo $row->userid;?></td>
                 <?php
                   //if($row[5]=='0')
                   //{ ?>
                     <td><a class="editx" href="<?php echo base_url()?>akuntansi_jurnal/edit/<?php echo $row->novoucher;?>">Edit</a></td>
                     <td><a href="" class="delete" onclick="delete_data('<?php echo $row->novoucher;?>')">Hapus</a></td>
                   <?php //} else { ?>
                     <!--td colspan="2" align="center"><font color="red">Posted</font></td-->

                   <?php //} ?>

             </tr>
             <?php } ?>
      </tbody>
         <tfoot>
           <tr>
			<td colspan="6" align="right"><b>TOTAL</b></td>
			<td align="right"><b><?php echo number_format($totd,2,',','.');?></b></span></td>
			<td align="right"><b><?php echo number_format($totk,2,',','.');?></b></span></td>
			<td></td>
			<td></td>
           </tr>
         </tfoot>
    </table>
</div>




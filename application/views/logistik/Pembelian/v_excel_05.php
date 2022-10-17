<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=" . $judul . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php foreach ($vdr as $v) : ?>
     <table class="table table-striped table-borderd table-hover" style="border-collapse:collapse;font-family: Times New Roman; font-size:12px" width="100%" align="center" border="0" cellspacing="1" cellpadding="3">
          <tr>
               <td>Nama Vendor</td>
               <td>:</td>
               <td><?= $v->vendor_name ?></td>
          </tr>
          <tr>
               <td>Dari tanggal</td>
               <td>:</td>
               <td><?= date('d-m-Y', strtotime($tanggal1)) ?></td>
          </tr>
          <tr>
               <td>Sampai tanggal</td>
               <td>:</td>
               <td><?= date('d-m-Y', strtotime($tanggal2)) ?></td>
          </tr>
     </table>
     <?php
     $query = $this->db->query("
     SELECT b.kodebarang, 
     (select namabarang from tbl_logbarang where kodebarang = b.kodebarang) as namabarang, 
     d.vendor_id, 
     b.satuan, 
     b.qty_terima, 
     (b.totalrp / b.qty_terima ) AS ratarata, 
     b.koders, 
     c.vendor_id, 
     c.vendor_name 
     FROM tbl_apodterimalog AS b
     JOIN tbl_apohterimalog AS d on b.terima_no = d.terima_no 
     JOIN tbl_vendor AS c ON d.vendor_id = c.vendor_id 
     WHERE d.vendor_id = '$v->vendor_id' and b.koders = '$unit' 
     and d.terima_date between '$tanggal1' and '$tanggal2'")->result();
     ?>
     <table class="table table-striped table-borderd table-hover" style="border-collapse:collapse;font-family: Times New Roman; font-size:12px" width="100%" align="center" border="1" cellspacing="1" cellpadding="3">
          <thead>
               <tr>
                    <th bgcolor="#cccccc" align="center" width="1%">No</th>
                    <th bgcolor="#cccccc" align="center">Kode Barang</th>
                    <th bgcolor="#cccccc" align="center">Nama Barang</th>
                    <th bgcolor="#cccccc" align="center">Satuan</th>
                    <th bgcolor="#cccccc" align="center">Qty</th>
                    <th bgcolor="#cccccc" align="center">Harga Rata-rata</th>
                    <th bgcolor="#cccccc" align="center">Total</th>
               </tr>
          </thead>
          <tbody>
               <?php $no = 1;
               foreach ($query as $q) : ?>
                    <?php $total = $q->qty_terima * $q->ratarata; ?>
                    <tr>
                         <td><?= $no++; ?></td>
                         <td><?= $q->kodebarang; ?></td>
                         <td><?= $q->namabarang; ?></td>
                         <td><?= $q->satuan; ?></td>
                         <td><?= round($q->qty_terima, 0); ?></td>
                         <td><?= round($q->ratarata, 0); ?></td>
                         <td><?= round($total, 0); ?></td>
                    </tr>
               <?php endforeach; ?>
          </tbody>
     </table>
     <br>
     <br>
<?php endforeach; ?>
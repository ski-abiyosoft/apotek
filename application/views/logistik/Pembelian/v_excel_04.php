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
     $query = $this->db->query("SELECT a.vatrp, a.materai, b.discountrp, b.qty_terima , b.discountrp, b.totalrp, (b.totalrp / b.qty_terima ) AS ratarata, c.vendor_name , c.vendor_id FROM tbl_apohterimalog as a JOIN tbl_apodterimalog AS b ON a.terima_no = b.terima_no JOIN tbl_vendor AS c ON a.vendor_id = c.vendor_id WHERE a.vendor_id = '$v->vendor_id' and a.koders = '$unit' and a.terima_date between '$tanggal1' and '$tanggal2'")->result();
     ?>
     <table class="table table-striped table-borderd table-hover" style="border-collapse:collapse;font-family: Times New Roman; font-size:12px" width="100%" align="center" border="1" cellspacing="1" cellpadding="3">
          <thead>
               <tr>
                    <th bgcolor="#cccccc" align="center" width="1%">No</th>
                    <th bgcolor="#cccccc" align="center">Supplier</th>
                    <th bgcolor="#cccccc" align="center">Qty</th>
                    <th bgcolor="#cccccc" align="center">Total</th>
                    <th bgcolor="#cccccc" align="center">Diskon</th>
                    <th bgcolor="#cccccc" align="center">Vat Rp</th>
                    <th bgcolor="#cccccc" align="center">Materai</th>
                    <th bgcolor="#cccccc" align="center">Total Net</th>
               </tr>
          </thead>
          <tbody>
               <?php $no = 1;
               foreach ($query as $q) : ?>
                    <tr>
                         <td><?= $no++; ?></td>
                         <td><?= $q->vendor_name; ?></td>
                         <td><?= round($q->qty_terima, 0); ?></td>
                         <td><?= round($q->totalrp, 0); ?></td>
                         <td><?= round($q->discountrp, 0); ?></td>
                         <td><?= round($q->vatrp, 0); ?></td>
                         <td><?= round($q->materai, 0); ?></td>
                         <td><?= round($q->totalrp, 0); ?></td>
                    </tr>
               <?php endforeach; ?>
          </tbody>
     </table>
     <br>
     <br>
<?php endforeach; ?>
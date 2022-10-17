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
     $query = $this->db->query("SELECT a.terima_date , a.sj_no, a.vatrp , a.materai , a.koders, a.terima_no, (sum(c.discountrp)) as diskontotal, b.vendor_name, b.vendor_id, (sum(c.qty_terima * c.price)) as totalrp, c.vat, a.vatrp, (sum(c.totalrp) + a.vatrp + a.materai) as totalnet FROM tbl_apohterimalog AS a JOIN tbl_vendor AS b ON a.vendor_id = b.vendor_id JOIN tbl_apodterimalog AS c ON a.terima_no = c.terima_no WHERE a.vendor_id = '$v->vendor_id' and a.koders = '$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2' group by a.terima_no")->result();
     ?>
     <table class="table table-striped table-borderd table-hover" style="border-collapse:collapse;font-family: Times New Roman; font-size:12px" width="100%" align="center" border="1" cellspacing="1" cellpadding="3">
          <thead>
               <tr>
                    <th bgcolor="#cccccc" align="center" width="1%">No</th>
                    <th bgcolor="#cccccc" align="center">Supplier</th>
                    <th bgcolor="#cccccc" align="center">No BAPB</th>
                    <th bgcolor="#cccccc" align="center">Tanggal</th>
                    <th bgcolor="#cccccc" align="center">No Invoice/SJ</th>
                    <th bgcolor="#cccccc" align="center">Total</th>
                    <th bgcolor="#cccccc" align="center">Diskon</th>
                    <th bgcolor="#cccccc" align="center">Vat</th>
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
                         <td><?= $q->terima_no; ?></td>
                         <td><?= $q->terima_date; ?></td>
                         <td><?= $q->sj_no; ?></td>
                         <td><?= round($q->totalrp, 0); ?></td>
                         <td><?= round($q->diskontotal, 0); ?></td>
                         <td><?= round($q->vatrp, 0); ?></td>
                         <td><?= round($q->materai, 0); ?></td>
                         <td><?= round($q->totalnet, 0); ?></td>
                    </tr>
               <?php endforeach; ?>
          </tbody>
     </table>
     <br>
     <br>
<?php endforeach; ?>
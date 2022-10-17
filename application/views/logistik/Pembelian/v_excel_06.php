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
     $query = $this->db->query("SELECT a.terima_date , a.koders,  b.price, b.satuan , b.qty_terima, b.po_no, c.vendor_id , c.vendor_name, d.kodebarang ,d.namabarang FROM tbl_apohterimalog AS a JOIN  tbl_apodterimalog AS b ON a.terima_no = b.terima_no JOIN tbl_vendor AS c ON a.vendor_id = c.vendor_id JOIN tbl_logbarang AS d ON b.kodebarang = d.kodebarang WHERE a.vendor_id = '$v->vendor_id' and a.koders = '$unit' and a.terima_date between '$tanggal1' and '$tanggal2'")->result();
     ?>
     <table class="table table-striped table-borderd table-hover" style="border-collapse:collapse;font-family: Times New Roman; font-size:12px" width="100%" align="center" border="1" cellspacing="1" cellpadding="3">
          <thead>
               <tr>
                    <th bgcolor="#cccccc" align="center" width="1%">No</th>
                    <th bgcolor="#cccccc" align="center">PO No</th>
                    <th bgcolor="#cccccc" align="center">Tanggal</th>
                    <th bgcolor="#cccccc" align="center">Kode Barang</th>
                    <th bgcolor="#cccccc" align="center">Nama Barang</th>
                    <th bgcolor="#cccccc" align="center">Qty</th>
                    <th bgcolor="#cccccc" align="center">Satuan</th>
                    <th bgcolor="#cccccc" align="center">Harga Sat</th>
                    <th bgcolor="#cccccc" align="center">Total</th>
               </tr>
          </thead>
          <tbody>
               <?php $no = 1;
               foreach ($query as $q) : ?>
                    <?php $total = $q->price * $q->qty_terima; ?>
                    <tr>
                         <td><?= $no++; ?></td>
                         <td><?= $q->po_no; ?></td>
                         <td><?= $q->terima_date; ?></td>
                         <td><?= $q->kodebarang; ?></td>
                         <td><?= $q->namabarang; ?></td>
                         <td><?= round($q->qty_terima, 0); ?></td>
                         <td><?= $q->satuan; ?></td>
                         <td><?= round($q->price, 0); ?></td>
                         <td><?= round($total, 0); ?></td>
                    </tr>
               <?php endforeach; ?>
          </tbody>
     </table>
     <br>
     <br>
<?php endforeach; ?>
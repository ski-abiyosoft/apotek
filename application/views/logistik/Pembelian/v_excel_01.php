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
     $query = $this->db->query("SELECT a.terima_no,a.terima_date,a.invoice_no,a.sj_no,b.kodebarang, a.materai, b.discountrp, c.namabarang, b.qty_terima, b.satuan, b.price, (b.totalrp + b.vatrp + a.materai) as totalnet, (b.qty_terima * b.price) as totalrp, b.discount, b.vat, b.vatrp As vatrp1, b.po_no FROM tbl_apohterimalog a JOIN tbl_apodterimalog b ON b.terima_no = a.terima_no JOIN tbl_logbarang c ON c.kodebarang = b.kodebarang JOIN tbl_vendor d ON d.vendor_id = a.vendor_id WHERE a.vendor_id = '$v->vendor_id' and a.koders='$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY a.terima_date, a.terima_no")->result();
     ?>
     <table class="table table-striped table-borderd table-hover" style="border-collapse:collapse;font-family: Times New Roman; font-size:12px" width="100%" align="center" border="1" cellspacing="1" cellpadding="3">
          <thead>
               <tr>
                    <th bgcolor="#cccccc" align="center" width="1%">No</th>
                    <th bgcolor="#cccccc" align="center">BAPB</th>
                    <th bgcolor="#cccccc" align="center">Tanggal</th>
                    <th bgcolor="#cccccc" align="center">No.Invoice/SJ</th>
                    <th bgcolor="#cccccc" align="center">Kode Barang</th>
                    <th bgcolor="#cccccc" align="center">Nama Barang</th>
                    <th bgcolor="#cccccc" align="center">Qty</th>
                    <th bgcolor="#cccccc" align="center">Satuan</th>
                    <th bgcolor="#cccccc" align="center">Harga Sat</th>
                    <th bgcolor="#cccccc" align="center">Total</th>
                    <th bgcolor="#cccccc" align="center">Diskon</th>
                    <th bgcolor="#cccccc" align="center">Vat</th>
                    <th bgcolor="#cccccc" align="center">Total Net</th>
                    <th bgcolor="#cccccc" align="center">PO No.</th>
               </tr>
          </thead>
          <tbody>
               <?php $no = 1;
               foreach ($query as $q) : ?>
                    <tr>
                         <td><?= $no++; ?></td>
                         <td><?= $q->terima_no; ?></td>
                         <td><?= $q->terima_date; ?></td>
                         <td><?= $q->invoice_no . '/' . $q->sj_no; ?></td>
                         <td><?= $q->kodebarang; ?></td>
                         <td><?= $q->namabarang; ?></td>
                         <td><?= round($q->qty_terima, 0); ?></td>
                         <td><?= $q->satuan; ?></td>
                         <td><?= round($q->price, 0); ?></td>
                         <td><?= round($q->totalrp, 0); ?></td>
                         <td><?= round($q->discountrp, 0); ?></td>
                         <td><?= round($q->vatrp1, 0); ?></td>
                         <td><?= round(($q->totalnet + $q->materai), 0); ?></td>
                         <td><?= $q->po_no; ?></td>
                    </tr>
               <?php endforeach; ?>
          </tbody>
     </table>
     <br>
     <br>
<?php endforeach; ?>
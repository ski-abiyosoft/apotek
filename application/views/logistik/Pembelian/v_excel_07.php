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
     $query = $this->db->query("select a.retur_no, a.retur_date, a.terima_no, b.kodebarang, (select namabarang from tbl_logbarang where kodebarang = b.kodebarang) as namabarang, b.qty_retur, b.satuan, b.price, b.totalrp, c.po_no from tbl_apohreturbelilog a join tbl_apodreturbelilog b on a.retur_no=b.retur_no JOIN tbl_apodterimalog c ON c.terima_no = a.terima_no join tbl_apohterimalog d on c.terima_no = d.terima_no where a.koders = '$unit' and a.vendor_id = '$v->vendor_id' and d.terima_date between '$tanggal1' and '$tanggal2' group by b.kodebarang")->result();
     ?>
     <table class="table table-striped table-borderd table-hover" style="border-collapse:collapse;font-family: Times New Roman; font-size:12px" width="100%" align="center" border="1" cellspacing="1" cellpadding="3">
          <thead>
               <tr>
                    <th bgcolor="#cccccc" align="center" width="1%">No</th>
                    <th bgcolor="#cccccc" align="center">Retur No</th>
                    <th bgcolor="#cccccc" align="center">Tanggal</th>
                    <th bgcolor="#cccccc" align="center">No BAPB</th>
                    <th bgcolor="#cccccc" align="center">Kode Barang</th>
                    <th bgcolor="#cccccc" align="center">Nama Barang</th>
                    <th bgcolor="#cccccc" align="center">Qty</th>
                    <th bgcolor="#cccccc" align="center">Satuan</th>
                    <th bgcolor="#cccccc" align="center">Harga Sat</th>
                    <th bgcolor="#cccccc" align="center">Total</th>
                    <th bgcolor="#cccccc" align="center">PO No</th>
               </tr>
          </thead>
          <tbody>
               <?php $no = 1;
               foreach ($query as $q) : ?>
                    <tr>
                         <td><?= $no++; ?></td>
                         <td><?= $q->retur_no; ?></td>
                         <td><?= $q->retur_date; ?></td>
                         <td><?= $q->terima_no; ?></td>
                         <td><?= $q->kodebarang; ?></td>
                         <td><?= $q->namabarang; ?></td>
                         <td><?= round($q->qty_retur, 0); ?></td>
                         <td><?= $q->satuan; ?></td>
                         <td><?= round($q->price, 0); ?></td>
                         <td><?= round($q->totalrp, 0); ?></td>
                         <td><?= $q->po_no; ?></td>
                    </tr>
               <?php endforeach; ?>
          </tbody>
     </table>
     <br>
     <br>
<?php endforeach; ?>
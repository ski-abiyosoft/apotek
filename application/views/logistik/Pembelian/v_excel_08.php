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
     SELECT 
          (SELECT vendor_name FROM tbl_vendor WHERE vendor_id = tbl_apohpolog.vendor_id) AS rekanan,
          tbl_apohpolog.ref_no AS no_faktur,
          0 AS obat,
          0 AS alkes_rutin,
          0 AS alk_investasi,
          0 AS bahan_kimia,
          0 AS gas_medik,
          0 AS pemeliharaan,
          0 AS sewa,
          0 AS pelengkapan,
          0 AS materai,
          tbl_apodpolog.vatrp AS lain2,
          tbl_apodpolog.total AS jumlah
     FROM tbl_apohpolog
     JOIN tbl_apodpolog ON tbl_apohpolog.po_no=tbl_apodpolog.po_no
     WHERE tbl_apohpolog.vendor_id = '$v->vendor_id' and po_date BETWEEN '$tanggal1' AND '$tanggal2'

     UNION ALL

     SELECT
          (SELECT vendor_name FROM tbl_vendor WHERE vendor_id = tbl_apohterimalog.vendor_id) AS rekanan,
          invoice_no AS no_faktur,
          0 AS obat,
          0 AS alkes_rutin,
          0 AS alk_investasi,
          0 AS bahan_kimia,
          0 AS gas_medik,
          0 AS pemeliharaan,
          0 AS sewa,
          0 AS pelengkapan,
          materai,
          tbl_apodterimalog.vatrp AS lain2,
          tbl_apodterimalog.totalrp AS jumlah
     FROM tbl_apohterimalog
     JOIN tbl_apodterimalog ON tbl_apohterimalog.terima_no=tbl_apodterimalog.terima_no
     WHERE tbl_apohterimalog.vendor_id = '$v->vendor_id' and terima_date BETWEEN '$tanggal1' AND '$tanggal2'

     UNION ALL

     SELECT
          (SELECT vendor_name FROM tbl_vendor WHERE vendor_id = tbl_apohreturbelilog.vendor_id) AS rekanan,
          invoice_no AS no_faktur,
          0 AS obat,
          0 AS alkes_rutin,
          0 AS alk_investasi,
          0 AS bahan_kimia,
          0 AS gas_medik,
          0 AS pemeliharaan,
          0 AS sewa,
          0 AS pelengkapan,
          0 AS materai,
          tbl_apodreturbelilog.taxrp AS lain2,
          tbl_apodreturbelilog.totalrp AS jumlah
     FROM tbl_apohreturbelilog
     JOIN tbl_apodreturbelilog ON tbl_apohreturbelilog.retur_no=tbl_apodreturbelilog.retur_no
     WHERE tbl_apohreturbelilog.vendor_id = '$v->vendor_id' and retur_date BETWEEN '$tanggal1' AND '$tanggal2'
     ")->result();
     ?>
     <table class="table table-striped table-borderd table-hover" style="border-collapse:collapse;font-family: Times New Roman; font-size:12px" width="100%" align="center" border="1" cellspacing="1" cellpadding="3">
          <thead>
               <tr>
                    <th bgcolor="#cccccc" align="center" width="1%">No</th>
                    <th bgcolor="#cccccc" align="center">Rekanan</th>
                    <th bgcolor="#cccccc" align="center">No Faktur</th>
                    <th bgcolor="#cccccc" align="center">Obat</th>
                    <th bgcolor="#cccccc" align="center">Alkes Rutin</th>
                    <th bgcolor="#cccccc" align="center">Alkes Inves</th>
                    <th bgcolor="#cccccc" align="center">Bahan Kimia</th>
                    <th bgcolor="#cccccc" align="center">Gas Medik</th>
                    <th bgcolor="#cccccc" align="center">Pemeliharaan</th>
                    <th bgcolor="#cccccc" align="center">Sewa</th>
                    <th bgcolor="#cccccc" align="center">Pelengkap</th>
                    <th bgcolor="#cccccc" align="center">Lain-lain</th>
                    <th bgcolor="#cccccc" align="center">Materai</th>
                    <th bgcolor="#cccccc" align="center">Jumlah</th>
               </tr>
          </thead>
          <tbody>
               <?php $no = 1;
               foreach ($query as $q) : ?>
                    <tr>
                         <td><?= $no++; ?></td>
                         <td><?= $q->rekanan; ?></td>
                         <td><?= $q->no_faktur; ?></td>
                         <td><?= $q->obat; ?></td>
                         <td><?= round($q->alkes_rutin, 0); ?></td>
                         <td><?= round($q->alk_investasi, 0); ?></td>
                         <td><?= round($q->bahan_kimia, 0); ?></td>
                         <td><?= round($q->gas_medik, 0); ?></td>
                         <td><?= round($q->pemeliharaan, 0); ?></td>
                         <td><?= round($q->sewa, 0); ?></td>
                         <td><?= round($q->pelengkapan, 0); ?></td>
                         <td><?= round($q->lain2, 0); ?></td>
                         <td><?= round($q->materai, 0); ?></td>
                         <td><?= round($q->jumlah, 0); ?></td>
                    </tr>
               <?php endforeach; ?>
          </tbody>
     </table>
     <br>
     <br>
<?php endforeach; ?>
<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=" . $judul . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php foreach ($queryx as $qx) : ?>
     <?php
     $gudang = $this->db->query("SELECT depocode, keterangan FROM tbl_depo WHERE depocode = '$qx->depocode'")->row();
     if ($depo != '') {
          $kondisi = " AND a.gudang = '$depo'";
          $gdx = $gudang->keterangan;
     } else {
          $kondisi = "";
          $gdx = 'SEMUA GUDANG';
     }
     ?>

<?php endforeach; ?>
<table border="0" width="100%" class="mt-5">
     <tr>
          <td align="center"><b><?= strtoupper($judul); ?> PADA GUDANG <?= $gdx; ?></b></td>
     </tr>
</table>
<table class="table table-striped table-bordered table-hover" style="border-collapse:collapse;font-family: Times New Roman; font-size:12px" width="30%" align="center" border="0" cellspacing="1" cellpadding="3">
     <thead>
          <tr>
               <th align="right">Dari : <?= date('d-m-Y', strtotime($dari)); ?></th>
               <th align="left">Sampai : <?= date('d-m-Y', strtotime($sampai)); ?></th>
          </tr>
     </thead>
</table>
<?php
$y = "SELECT 
     a.*,
     (SELECT namabarang FROM tbl_logbarang WHERE kodebarang = a.kodebarang) AS namabarang,

     (SELECT satuan1 FROM tbl_logbarang WHERE kodebarang = a.kodebarang) AS satuan,

     IFNULL((SELECT qty_terima FROM
          (SELECT c.koders,c.kodebarang, SUM(c.qty_terima)qty_terima,gudang 
          FROM tbl_apohterimalog b JOIN tbl_apodterimalog c ON b.terima_no = c.terima_no
          GROUP BY c.koders,c.kodebarang,gudang
          ORDER BY koders,kodebarang)AS terima 
          WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
     ),0) AS pembelian,

     IFNULL((SELECT qtymove FROM 
          (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,ke 
          FROM tbl_apohmovelog d JOIN tbl_apodmovelog e ON d.moveno = e.moveno
          GROUP BY e.koders,e.kodebarang,ke
          ORDER BY koders,kodebarang)AS terima 
          WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.ke=a.gudang
     ),0) AS mutasi_in,

     IFNULL((SELECT hasilso FROM
          (SELECT koders, kodeobat AS kodebarang, SUM(sesuai)hasilso, gudang
          FROM tbl_aposesuailog GROUP BY koders,kodeobat,gudang
          ORDER BY koders, kodeobat
          ) AS terima
          WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
     ), 0) AS so,

     (
          (IFNULL((SELECT qty_terima FROM
               (SELECT c.koders,c.kodebarang, SUM(c.qty_terima)qty_terima,gudang 
               FROM tbl_apohterimalog b JOIN tbl_apodterimalog c ON b.terima_no = c.terima_no
               GROUP BY c.koders,c.kodebarang,gudang
               ORDER BY koders,kodebarang)AS terima 
               WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
          ),0)) +
          (IFNULL((SELECT qtymove FROM 
               (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,ke 
               FROM tbl_apohmovelog d JOIN tbl_apodmovelog e ON d.moveno = e.moveno
               GROUP BY e.koders,e.kodebarang,ke
               ORDER BY koders,kodebarang)AS terima 
               WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.ke=a.gudang
          ),0)) +
          (IFNULL((SELECT hasilso FROM
               (SELECT koders, kodeobat AS kodebarang, SUM(sesuai)hasilso, gudang
               FROM tbl_aposesuailog GROUP BY koders,kodeobat,gudang
               ORDER BY koders, kodeobat
               ) AS terima
               WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
          ), 0))
     ) AS total_masuk,

     IFNULL((SELECT qtyjual FROM 
          (SELECT c.koders,c.kodebarang, SUM(c.qty) qtyjual,b.gudang 
          FROM tbl_apohresep b JOIN tbl_apodresep c ON b.resepno = c.resepno
          JOIN tbl_apoposting ps ON ps.resepno=b.resepno
          GROUP BY c.koders,c.kodebarang,b.gudang
          ORDER BY koders,kodebarang)AS terima 
          WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
     ),0) AS jual,

     IFNULL((SELECT qtymove FROM 
          (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,dari 
          FROM tbl_apohmovelog d JOIN tbl_apodmovelog e ON d.moveno = e.moveno
          GROUP BY e.koders,e.kodebarang,dari
          ORDER BY koders,kodebarang)AS terima 
          WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.dari=a.gudang
     ),0) AS mutasi_out,
     0 AS bhp,

     (
          (IFNULL((SELECT qtyjual FROM 
               (SELECT c.koders,c.kodebarang, SUM(c.qty) qtyjual,b.gudang 
               FROM tbl_apohresep b JOIN tbl_apodresep c ON b.resepno = c.resepno
               JOIN tbl_apoposting ps ON ps.resepno=b.resepno
               GROUP BY c.koders,c.kodebarang,b.gudang
               ORDER BY koders,kodebarang)AS terima 
               WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
          ),0)) +
          (IFNULL((SELECT qtymove FROM 
               (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,dari 
               FROM tbl_apohmovelog d JOIN tbl_apodmovelog e ON d.moveno = e.moveno
               GROUP BY e.koders,e.kodebarang,dari
               ORDER BY koders,kodebarang)AS terima 
               WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.dari=a.gudang
          ),0)) +
          0
     ) AS total_keluar,

     (SELECT hpp FROM tbl_logbarang WHERE kodebarang = a.kodebarang) AS hpp,

     (
          (
               (
                    (IFNULL((SELECT qty_terima FROM
                         (SELECT c.koders,c.kodebarang, SUM(c.qty_terima)qty_terima,gudang 
                         FROM tbl_apohterimalog b JOIN tbl_apodterimalog c ON b.terima_no = c.terima_no
                         GROUP BY c.koders,c.kodebarang,gudang
                         ORDER BY koders,kodebarang)AS terima 
                         WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                         ),0)) +
                    (IFNULL((SELECT qtymove FROM 
                         (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,ke 
                         FROM tbl_apohmovelog d JOIN tbl_apodmovelog e ON d.moveno = e.moveno
                         GROUP BY e.koders,e.kodebarang,ke
                         ORDER BY koders,kodebarang)AS terima 
                         WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.ke=a.gudang
                    ),0)) +
                    (IFNULL((SELECT hasilso FROM
                         (SELECT koders, kodeobat AS kodebarang, SUM(sesuai)hasilso, gudang
                         FROM tbl_aposesuailog GROUP BY koders,kodeobat,gudang
                         ORDER BY koders, kodeobat
                         ) AS terima
                         WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
                    ), 0)
               )
          )
     ) - 
     (
          (
               (IFNULL((SELECT qtyjual FROM 
                    (SELECT c.koders,c.kodebarang, SUM(c.qty) qtyjual,b.gudang 
                    FROM tbl_apohresep b JOIN tbl_apodresep c ON b.resepno = c.resepno
                    JOIN tbl_apoposting ps ON ps.resepno=b.resepno
                    GROUP BY c.koders,c.kodebarang,b.gudang
                    ORDER BY koders,kodebarang)AS terima 
                    WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
               ),0)) +
               (IFNULL((SELECT qtymove FROM 
                    (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,dari 
                    FROM tbl_apohmovelog d JOIN tbl_apodmovelog e ON d.moveno = e.moveno
                    GROUP BY e.koders,e.kodebarang,dari
                    ORDER BY koders,kodebarang)AS terima 
                    WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.dari=a.gudang
               ),0)) +
               0
               )
          )
     ) AS saldo_akhir,

     (
          (
          (
          (IFNULL((SELECT qty_terima FROM
               (SELECT c.koders,c.kodebarang, SUM(c.qty_terima)qty_terima,gudang 
               FROM tbl_apohterimalog b JOIN tbl_apodterimalog c ON b.terima_no = c.terima_no
               GROUP BY c.koders,c.kodebarang,gudang
               ORDER BY koders,kodebarang)AS terima 
               WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
               ),0)) +
          (IFNULL((SELECT qtymove FROM 
               (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,ke 
               FROM tbl_apohmovelog d JOIN tbl_apodmovelog e ON d.moveno = e.moveno
               GROUP BY e.koders,e.kodebarang,ke
               ORDER BY koders,kodebarang)AS terima 
               WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.ke=a.gudang
          ),0)) +
          (IFNULL((SELECT hasilso FROM
               (SELECT koders, kodeobat AS kodebarang, SUM(sesuai)hasilso, gudang
               FROM tbl_aposesuailog GROUP BY koders,kodeobat,gudang
               ORDER BY koders, kodeobat
               ) AS terima
               WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
          ), 0))
     ) -
     (
          (
          (IFNULL((SELECT qtyjual FROM 
               (SELECT c.koders,c.kodebarang, SUM(c.qty) qtyjual,b.gudang 
               FROM tbl_apohresep b JOIN tbl_apodresep c ON b.resepno = c.resepno
               JOIN tbl_apoposting ps ON ps.resepno=b.resepno
               GROUP BY c.koders,c.kodebarang,b.gudang
               ORDER BY koders,kodebarang)AS terima 
               WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
          ),0)) +
          (IFNULL((SELECT qtymove FROM 
               (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,dari 
               FROM tbl_apohmovelog d JOIN tbl_apodmovelog e ON d.moveno = e.moveno
               GROUP BY e.koders,e.kodebarang,dari
               ORDER BY koders,kodebarang)AS terima 
               WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.dari=a.gudang
          ),0)) +
          0
          )
          )
          *
          (SELECT hpp FROM tbl_logbarang WHERE kodebarang = a.kodebarang)
     )
     ) as total_persediaan_rp

     FROM (
          SELECT koders,kodebarang,gudang, tglso, saldoakhir
          FROM tbl_apostocklog a
          GROUP BY koders,kodebarang,gudang
     ) a
     WHERE a.koders = '$unit' AND a.tglso BETWEEN '$dari' AND '$sampai' $kondisi ORDER BY a.tglso ASC";
$query = $this->db->query($y)->result();
?>
<table class="table table-striped table-borderd table-hover" style="border-collapse:collapse;font-family: Times New Roman; font-size:12px" width="100%" align="center" border="1" cellspacing="1" cellpadding="3">
     <thead>
          <tr>
               <th bgcolor="#cccccc" align="center" width="1%" rowspan="2">No</th>
               <th bgcolor="#cccccc" align="center" rowspan="2">Kode Barang</th>
               <th bgcolor="#cccccc" align="center" rowspan="2">Nama Barang</th>
               <th bgcolor="#cccccc" align="center" rowspan="2">Satuan</th>
               <th bgcolor="#cccccc" align="center" colspan="4">Persediaan Masuk</th>
               <th bgcolor="#cccccc" align="center" colspan="4">Persediaan Keluar</th>
               <td bgcolor="#cccccc" align="center" rowspan="2">Saldo Akhir</td>
               <td bgcolor="#cccccc" align="center" rowspan="2">HPP Average</td>
               <td bgcolor="#cccccc" align="center" rowspan="2">Total Persediaan Rp</td>
          </tr>
          <tr>
               <td bgcolor="#cccccc" align="center">Pembelian</td>
               <td bgcolor="#cccccc" align="center">Mutasi In</td>
               <td bgcolor="#cccccc" align="center">Stock Opname Adjustment</td>
               <td bgcolor="#cccccc" align="center">Total Masuk</td>
               <td bgcolor="#cccccc" align="center">Jual</td>
               <td bgcolor="#cccccc" align="center">Mutasi Out</td>
               <td bgcolor="#cccccc" align="center">BHP</td>
               <td bgcolor="#cccccc" align="center">Total Keluar</td>
          </tr>
     </thead>
     <tbody>
          <?php $no = 1;
          foreach ($query as $q) : ?>
               <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $q->kodebarang; ?></td>
                    <td><?= $q->namabarang; ?></td>
                    <td><?= $q->satuan; ?></td>
                    <td align="right"><?= round($q->pembelian, 0); ?></td>
                    <td align="right"><?= round($q->mutasi_in, 0); ?></td>
                    <td align="right"><?= round($q->so, 0); ?></td>
                    <td align="right"><?= round($q->total_masuk, 0); ?></td>
                    <td align="right"><?= round($q->jual, 0); ?></td>
                    <td align="right"><?= round($q->mutasi_out, 0); ?></td>
                    <td align="right"><?= round($q->bhp, 0); ?></td>
                    <td align="right"><?= round($q->total_keluar, 0); ?></td>
                    <td align="right"><?= round($q->saldoakhir, 0); ?></td>
                    <td align="right"><?= round($q->hpp, 0); ?></td>
                    <td align="right"><?= round($q->total_persediaan_rp, 0); ?></td>
               </tr>
          <?php endforeach; ?>
     </tbody>
     <tfoot>
          <tr>
               <th align="center" colspan="14">TOTAL</th>
               <?php $sql = $this->db->query("SELECT sum(a.saldoakhir*(SELECT hpp FROM tbl_barang WHERE kodebarang = a.kodebarang)) as total from tbl_barangstock a limit 1")->result(); ?>
               <?php foreach ($sql as $s) : ?>
                    <td align="right"><?= round($s->total, 0); ?></td>
               <?php endforeach; ?>
          </tr>
     </tfoot>
</table>
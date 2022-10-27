<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=" . $judul . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php foreach ($queryx as $qx) : ?>
     <?php $gudang = $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$qx->gudang'")->row(); ?>
     <table border="0" width="100%" class="mt-5">
          <tr>
               <td align="center"><b><?= strtoupper($judul); ?> PADA GUDANG <?= strtoupper($gudang->keterangan); ?></b></td>
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
     $y = "
SELECT 
     a.*,

     (select namabarang from tbl_barang where kodebarang = a.kodebarang) as namabarang,

     (select satuan1 from tbl_barang where kodebarang = a.kodebarang) as satuan,

     IFNULL((select qty_terima from 
               (select c.koders,c.kodebarang, sum(c.qty_terima)qty_terima,gudang 
               from tbl_baranghterima b join tbl_barangdterima c on b.terima_no = c.terima_no
               group by c.koders,c.kodebarang,gudang
               order by koders,kodebarang)as terima 
          where terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
     ),0) as pembelian,

     IFNULL((SELECT qtymove FROM 
               (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,ke 
               FROM tbl_apohmove d JOIN tbl_apodmove e ON d.moveno = e.moveno
               GROUP BY e.koders,e.kodebarang,ke
               ORDER BY koders,kodebarang)AS terima 
          WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.ke=a.gudang
     ),0) AS mutasi_in,

     IFNULL((SELECT qtyjadi FROM 
               (SELECT koders,kodebarang, SUM(qtyjadi)qtyjadi,gudang 
               FROM tbl_apohproduksi d
               GROUP BY koders,kodebarang,gudang
               ORDER BY koders,kodebarang)AS terima 
          WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
     ),0) AS produksi,

     IFNULL((select hasilso from
               (select koders, kodebarang, sum(hasilso)hasilso, gudang
               from tbl_aposesuai group by koders,kodebarang,gudang
               order by koders, kodebarang
               ) as terima
          where terima.kodebarang=a.kodebarang and terima.koders=a.koders and terima.gudang=a.gudang
     ), 0) as so,

     IFNULL((SELECT qtyretur FROM 
               (SELECT e.koders,e.kodebarang, SUM(e.qtyretur)qtyretur,gudang 
               FROM tbl_apohreturjual d JOIN tbl_apodreturjual e ON d.returno = e.returno
               GROUP BY e.koders,e.kodebarang,gudang
               ORDER BY koders,kodebarang)AS terima 
          WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
     ),0) AS retur_jual,

     (
          (IFNULL((SELECT qty_terima FROM 
                    (SELECT c.koders,c.kodebarang, SUM(c.qty_terima)qty_terima,gudang 
                    FROM tbl_baranghterima b JOIN tbl_barangdterima c ON b.terima_no = c.terima_no
                    GROUP BY c.koders,c.kodebarang,gudang
                    ORDER BY koders,kodebarang)AS terima 
               WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
          ),0))
          +
          (IFNULL((SELECT qtymove FROM 
                    (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,ke 
                    FROM tbl_apohmove d JOIN tbl_apodmove e ON d.moveno = e.moveno
                    GROUP BY e.koders,e.kodebarang,ke
                    ORDER BY koders,kodebarang)AS terima 
               WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.ke=a.gudang
          ),0))
          +
          (IFNULL((SELECT qtyjadi FROM 
                    (SELECT koders,kodebarang, SUM(qtyjadi)qtyjadi,gudang 
                    FROM tbl_apohproduksi d
                    GROUP BY koders,kodebarang,gudang
                    ORDER BY koders,kodebarang)AS terima 
               WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
          ),0))
          +
          (IFNULL((SELECT hasilso FROM
                    (SELECT koders, kodebarang, SUM(hasilso)hasilso, gudang
                    FROM tbl_aposesuai GROUP BY koders,kodebarang,gudang
                    ORDER BY koders, kodebarang
                    ) AS terima
               WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
          ), 0))
          +
          (IFNULL((SELECT qtyretur FROM 
                    (SELECT e.koders,e.kodebarang, SUM(e.qtyretur)qtyretur,gudang 
                    FROM tbl_apohreturjual d JOIN tbl_apodreturjual e ON d.returno = e.returno
                    GROUP BY e.koders,e.kodebarang,gudang
                    ORDER BY koders,kodebarang)AS terima 
               WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
          ),0))
     ) AS total_masuk,

     IFNULL((SELECT qtyjual FROM 
               (SELECT c.koders,c.kodebarang, SUM(c.qty+d.qty)qtyjual,b.gudang 
               FROM tbl_apohresep b JOIN tbl_apodresep c ON b.resepno = c.resepno join tbl_apodetresep d on c.resepno=d.resepno
               GROUP BY c.koders,c.kodebarang,b.gudang
               ORDER BY koders,kodebarang)AS terima 
          WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
     ),0) AS jual,

     IFNULL((SELECT qtymove FROM 
               (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,dari 
               FROM tbl_apohmove d JOIN tbl_apodmove e ON d.moveno = e.moveno
               GROUP BY e.koders,e.kodebarang,dari
               ORDER BY koders,kodebarang)AS terima 
          WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.dari=a.gudang
     ),0) AS mutasi_out,

     IFNULL((SELECT qty_retur FROM 
               (SELECT e.koders,e.kodebarang, SUM(e.qty_retur)qty_retur,gudang 
               FROM tbl_baranghreturbeli d JOIN tbl_barangdreturbeli e ON d.retur_no = e.retur_no
               GROUP BY e.koders,e.kodebarang,gudang
               ORDER BY koders,kodebarang)AS terima 
          WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
     ),0) AS retur_beli,

     IFNULL((SELECT qty FROM 
               (SELECT e.koders,e.kodebarang, SUM(e.qty)qty,gudang 
               FROM tbl_apohproduksi d JOIN tbl_apodproduksi e ON d.prdno = e.prdno
               GROUP BY e.koders,e.kodebarang,gudang
               ORDER BY koders,kodebarang)AS terima 
          WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
     ),0) AS produksi_out, 

     0 as bhp,

     IFNULL((SELECT qty FROM 
               (SELECT e.koders,e.kodebarang, SUM(e.qty)qty,gudang 
               FROM tbl_apohex d JOIN tbl_apodex e ON d.ed_no = e.ed_no
               GROUP BY e.koders,e.kodebarang,gudang
               ORDER BY koders,kodebarang)AS terima 
          WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
     ),0) AS expired,
     (
          (IFNULL((SELECT qtyjual FROM 
                    (SELECT c.koders,c.kodebarang, SUM(c.qty+d.qty)qtyjual,b.gudang 
                    FROM tbl_apohresep b JOIN tbl_apodresep c ON b.resepno = c.resepno join tbl_apodetresep d on c.resepno=d.resepno
                    GROUP BY c.koders,c.kodebarang,b.gudang
                    ORDER BY koders,kodebarang)AS terima 
               WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
          ),0))
          +
          (IFNULL((SELECT qtymove FROM 
                    (SELECT e.koders,e.kodebarang, SUM(e.qtymove)qtymove,dari 
                    FROM tbl_apohmove d JOIN tbl_apodmove e ON d.moveno = e.moveno
                    GROUP BY e.koders,e.kodebarang,dari
                    ORDER BY koders,kodebarang)AS terima 
               WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.dari=a.gudang
          ),0))
          +
          (IFNULL((SELECT qty_retur FROM 
                    (SELECT e.koders,e.kodebarang, SUM(e.qty_retur)qty_retur,gudang 
                    FROM tbl_baranghreturbeli d JOIN tbl_barangdreturbeli e ON d.retur_no = e.retur_no
                    GROUP BY e.koders,e.kodebarang,gudang
                    ORDER BY koders,kodebarang)AS terima 
               WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
          ),0))
          +
          (IFNULL((SELECT qty FROM 
                    (SELECT e.koders,e.kodebarang, SUM(e.qty)qty,gudang 
                    FROM tbl_apohproduksi d JOIN tbl_apodproduksi e ON d.prdno = e.prdno
                    GROUP BY e.koders,e.kodebarang,gudang
                    ORDER BY koders,kodebarang)AS terima 
               WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
          ),0))
          +
          0
          +
          (IFNULL((SELECT qty FROM 
                    (SELECT e.koders,e.kodebarang, SUM(e.qty)qty,gudang 
                    FROM tbl_apohex d JOIN tbl_apodex e ON d.ed_no = e.ed_no
                    GROUP BY e.koders,e.kodebarang,gudang
                    ORDER BY koders,kodebarang)AS terima 
               WHERE terima.kodebarang=a.kodebarang AND terima.koders=a.koders AND terima.gudang=a.gudang
          ),0))
     ) as total_keluar,

     (select hpp from tbl_barang where kodebarang = a.kodebarang) as hpp,

     (a.saldoakhir*(SELECT hpp FROM tbl_barang WHERE kodebarang = a.kodebarang)) as total_persediaan_rp

FROM
     (
          select koders,kodebarang,gudang, tglso, saldoakhir
          from tbl_barangstock a
          group by koders,kodebarang,gudang
     )a
WHERE a.koders = '$unit' and a.gudang='$qx->gudang' and a.tglso between '$dari' and '$sampai'
";
     $query = $this->db->query($y)->result();
     ?>
     <table class="table table-striped table-borderd table-hover" style="border-collapse:collapse;font-family: Times New Roman; font-size:12px" width="100%" align="center" border="1" cellspacing="1" cellpadding="3">
          <thead>
               <tr>
                    <th bgcolor="#cccccc" align="center" width="1%" rowspan="2">No</th>
                    <th bgcolor="#cccccc" align="center" rowspan="2">Kode Barang</th>
                    <th bgcolor="#cccccc" align="center" rowspan="2">Nama Barang</th>
                    <th bgcolor="#cccccc" align="center" rowspan="2">Satuan</th>
                    <th bgcolor="#cccccc" align="center" colspan="6">Persediaan Masuk</th>
                    <th bgcolor="#cccccc" align="center" colspan="7">Persediaan Keluar</th>
                    <td bgcolor="#cccccc" align="center" rowspan="2">Saldo Akhir</td>
                    <td bgcolor="#cccccc" align="center" rowspan="2">HPP Average</td>
                    <td bgcolor="#cccccc" align="center" rowspan="2">Total Persediaan Rp</td>
               </tr>
               <tr>
                    <td bgcolor="#cccccc" align="center">Pembelian</td>
                    <td bgcolor="#cccccc" align="center">Mutasi In</td>
                    <td bgcolor="#cccccc" align="center">Produksi</td>
                    <td bgcolor="#cccccc" align="center">Stock Opname Adjustment</td>
                    <td bgcolor="#cccccc" align="center">Retur Penjualan</td>
                    <td bgcolor="#cccccc" align="center">Total Masuk</td>
                    <td bgcolor="#cccccc" align="center">Jual</td>
                    <td bgcolor="#cccccc" align="center">Mutasi Out</td>
                    <td bgcolor="#cccccc" align="center">Retur Pembelian</td>
                    <td bgcolor="#cccccc" align="center">Bahan Produksi</td>
                    <td bgcolor="#cccccc" align="center">BHP</td>
                    <td bgcolor="#cccccc" align="center">Barang Expired</td>
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
                         <td align="right"><?= round($q->produksi, 0); ?></td>
                         <td align="right"><?= round($q->so, 0); ?></td>
                         <td align="right"><?= round($q->retur_jual, 0); ?></td>
                         <td align="right"><?= round($q->total_masuk, 0); ?></td>
                         <td align="right"><?= round($q->jual, 0); ?></td>
                         <td align="right"><?= round($q->mutasi_out, 0); ?></td>
                         <td align="right"><?= round($q->retur_beli, 0); ?></td>
                         <td align="right"><?= round($q->produksi_out, 0); ?></td>
                         <td align="right"><?= round($q->bhp, 0); ?></td>
                         <td align="right"><?= round($q->expired, 0); ?></td>
                         <td align="right"><?= round($q->total_keluar, 0); ?></td>
                         <td align="right"><?= round($q->saldoakhir, 0); ?></td>
                         <td align="right"><?= round($q->hpp, 0); ?></td>
                         <td align="right"><?= round($q->total_persediaan_rp, 0); ?></td>
                    </tr>
               <?php endforeach; ?>
          </tbody>
          <tfoot>
               <tr>
                    <th align="center" colspan="19">TOTAL</th>
                    <?php $sql = $this->db->query("SELECT sum(a.saldoakhir*(SELECT hpp FROM tbl_barang WHERE kodebarang = a.kodebarang)) as total from tbl_barangstock a limit 1")->result(); ?>
                    <?php foreach ($sql as $s) : ?>
                         <td align="right"><?= round($s->total, 0); ?></td>
                    <?php endforeach; ?>
               </tr>
          </tfoot>
     </table>
     <br>
     <br>
     <hr>
<?php endforeach; ?>
<div class="table-responsive">
     <table style="border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;" width="100%"  border="0" cellspacing="0" cellpadding="0">
          <thead>
          <tr>
               <td colspan="2" rowspan="6" align="center">
                    <img src="<?= base_url('assets/img/logo.png') ?>"  width="100" height="100" /></td>
               <td colspan="18"><b>
                    <tr><td style="font-size:14px;border-bottom: none;"><b><?= $namars; ?></b></td></tr>
                    <tr><td style="font-size:13px;"><?= $alamat; ?></td></tr>
                    <tr><td style="font-size:13px;"><?= $alamat2; ?></td></tr>
                    <tr><td style="font-size:13px;">Wa :<?= $whatsapp; ?>    Telp :<?= $phone; ?> </td></tr>
                    <tr><td style="font-size:13px;">No. NPWP : <?= $npwp; ?></td></tr>
               </td>
          </tr>
     </table>
</div>
<div class="table-responsive">
     <table style="border-collapse:collapse;font-family: tahoma; font-size:12px" width="100%" align="center" border="0" cellspacing="1" cellpadding="3">
          <thead>
               <tr>
                    <td colspan="14" style="text-align:center;font-size:22px;border-bottom: none;color:#120292;"><b><br></b></td>
               </tr> 
               <tr>
                    <td colspan="14" style="text-align:center;font-size:22px;border-bottom: none;color:#120292;"><b><?= $judul; ?></b></td>
               </tr>
               <tr>
                    <td colspan="14" style="text-align:center;font-size:15px;border-top: none;"><?= $_peri; ?></td>
               </tr>
          </thead>
     </table>
</div>
<div class="table-responsive">
     <table class="table table-striped table-borderd table-hover"style="border-collapse:collapse;font-family: Times New Roman; font-size:12px" width="100%" align="center" border="1" cellspacing="1" cellpadding="3">
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
               <?php $no = 1; foreach($query as $q) : ?>
                    <tr>
                         <td><?= $no++; ?></td>
                         <td><?= $q->rekanan; ?></td>
                         <td><?= $q->no_faktur; ?></td>
                         <td><?= $q->obat; ?></td>
                         <td><?= number_format($q->alkes_rutin, 2); ?></td>
                         <td><?= number_format($q->alk_investasi, 2); ?></td>
                         <td><?= number_format($q->bahan_kimia, 2); ?></td>
                         <td><?= number_format($q->gas_medik, 2); ?></td>
                         <td><?= number_format($q->pemeliharaan, 2); ?></td>
                         <td><?= number_format($q->sewa, 2); ?></td>
                         <td><?= number_format($q->pelengkapan, 2); ?></td>
                         <td><?= number_format($q->lain2, 2); ?></td>
                         <td><?= number_format($q->materai, 2); ?></td>
                         <td><?= number_format($q->jumlah, 2); ?></td>
                    </tr>
               <?php endforeach; ?>
          </tbody>
     </table>
</div>
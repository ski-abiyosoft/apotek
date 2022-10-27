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
               <?php $no = 1; foreach($query as $q) : ?>
                    <tr>
                         <td><?= $no++; ?></td>
                         <td><?= $q->retur_no; ?></td>
                         <td><?= $q->retur_date; ?></td>
                         <td><?= $q->terima_no; ?></td>
                         <td><?= $q->kodebarang; ?></td>
                         <td><?= $q->namabarang; ?></td>
                         <td><?= number_format($q->qty_retur, 2); ?></td>
                         <td><?= $q->satuan; ?></td>
                         <td><?= number_format($q->price, 2); ?></td>
                         <td><?= number_format($q->totalrp, 2); ?></td>
                         <td><?= $q->po_no; ?></td>
                    </tr>
               <?php endforeach; ?>
          </tbody>
     </table>
</div>
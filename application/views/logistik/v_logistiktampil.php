<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KARTU STOCK LOGISTIK</title>
</head>

<body>
    <table style="border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;" width="100%"
        border="0" cellspacing="0" cellpadding="0" width="80%">
        <thead>
            <tr>
                <td width="10%">
                    <img src="<?=base_url('/assets/img/logo.png')?>" width="100" height="100">
                </td>
                <td>
                    <table border="0">
                        <tr>
                            <td style="font-size:14px;border-bottom: none;"><b><?=$kop['namars'];?></b></td>
                        </tr>
                        <tr>
                            <td style="font-size:13px;"><b><?=$kop['alamat']?></b></td>
                        </tr>
                        <tr>
                            <td style="font-size:13px;"><b>WA:<?=$kop['whatsapp'];?> Telp :<?=$kop['phone'];?></b> </td>
                        </tr>
                        <tr>
                            <td style="font-size:13px;"><b>No. NPWP <?= $kop['npwp']?></b></td>
                        </tr>

                    </table>
                </td>
            </tr>
        </thead>
    </table>
    <table style="border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;" width="100%"
        border="0" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <td colspan="2" style="text-align:center; font-size:22px; border-bottom: none;color:#120292;">
                    <b><br></b>
                </td>
            </tr>
            <tr>
                <td colspan="21" style="text-align:center; font-size:22px; border-bottom: none;color:#120292;">
                    <b>KARTU STOCK</b><br><br>
                </td>
            </tr>
            <!-- <tr>
                <td colspan="10" style="text-align:center; font-size:10px; border-bottom: none;color:#120292;">
                    <b><?=$peri1?></b>
                </td>
            </tr> -->
        </thead>
    </table>
    <div class="data">

        <td>Cabang:<?php echo str_replace("KLINIK ESTETIKA dr. Affandi ", "", $query_cab->namars ) ?></td><br>
        <td>Gudang: <?= $gudang->keterangan ?></td><br>
        <td>Kode Barang:<?=$barang?> </td><br>
        <td>Nama Barang: <?=$nama_barang?></td><br>
    </div><br>
    <table style="border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;" width="100%"
        border="1" cellspacing="0" cellpadding="0">

        <thead>
            <tr>
                <td style="border:0" align="center"><br> </td>
            </tr>
            <tr>
                <td bgcolor="#cccccc" align="center">No. Bukti</td>
                <td bgcolor="#cccccc" align="center">Tanggal </td>
                <td bgcolor="#cccccc" align="center">Keterangan </td>
                <td bgcolor="#cccccc" align="center">Rekanan </td>
                <td bgcolor="#cccccc" align="center">Nilai Pembelian </td>
                <td bgcolor="#cccccc" align="center">Terima </td>
                <td bgcolor="#cccccc" align="center">Keluar </td>
                <td bgcolor="#cccccc" align="center">Saldo Akhir </td>
                <td bgcolor="#cccccc" align="center">Total Nilai Persediaan</td>
                <td bgcolor="#cccccc" align="center"> Nilai Persediaan</td>
            </tr>
        </thead>
        <tbody>
            <?php         
		        $tanggal1 = $this->input->get('tanggal1');
		        $tanggal2 = $this->input->get('tanggal2');
		        $cabang = $this->input->get('cabang');
		        $gudang = $this->input->get('gudang');
		        $kodebarang = $this->input->get('kodebarang');
                
        		$_tgl1          = date('Y-m-d',strtotime($tanggal1));
                $_tgl2          = date('Y-m-d',strtotime($tanggal2));
                $_peri          = 'Dari '.date('d-m-Y',strtotime($tanggal1)).' s/d '.date('d-m-Y',strtotime($tanggal2));
                $_peri1         = 'Per Tgl. '.date('d',strtotime($tanggal2)).' '.$this->M_global->_namabulan(date('n',strtotime($tanggal2))).' '.date('Y',strtotime($tanggal2));
        
	            $_tanggalawal = $_tgl1 ;


                // $query          = "SELECT * FROM tbl_apostocklog
                // WHERE koders = '$cabang' 
                // AND kodebarang = '$barang' 
                // AND gudang = '$gudang'
       
                // AND tglso > '$_tgl1'";
			
                $query_saldo = "SELECT * from tbl_apostocklog where koders = '$cabang' and kodebarang='$barang' and gudang='$gudang' OR gudang = 'LOKAL' AND tglso >'$_tgl1' ";
        
	
		$lap = $this->db->query($query_saldo)->row();
        $_tanggalawal = $tanggal1;
        
        if($lap){
            $_tanggalawal = $lap->tglso;
            $saldoz = $lap->saldoawal;
            if($saldoz){
                $saldo = $lap->saldoawal;
            } else {
                $saldo = 0;
            }
        } else {
            $_tanggalawal = '';	
            $saldo = 0;
        }
    
        ?>

            <tr>
                <td align="center">SALDO</td>
                <td align="center"><?=date('d-m-y', strtotime( $_tanggalawal))?></td>
                <td align="center">SALDO AWAL <?= date('d-m-y' , strtotime( $_tanggalawal))?></td>
                <td align="center"></td>
                <td align="center"><?= number_format(0,0,'.',',') ?></td>
                <td align="center"><?= number_format(0,0,'.',',') ?></td>
                <td align="center"><?= number_format(0,0,'.',',') ?></td>
                <td align="center"><?= number_format($saldo,0,'.',',')?></td>
                <td align="center"><?= number_format(0,0,'.',',') ?></td>
                <td align="center"><?= number_format(0,0,'.',',') ?></td>
            </tr>

            <?php 
                foreach($data as $db):   
                
                $saldo = $saldo + $db->terima - $db->keluar ;

				$nilai = $db->qty*$db->harga;
                ?> <tr>

                <td align="center"><?=$db->nomor?></td>
                <td align="center"><?= date('d-m-Y',strtotime($db->tanggal))?></td>
                <td align="center"><?= $db->keterangan ?></td>
                <td align="center"><?= $db->rekanan ?></td>
                <td align="center"><?= number_format($nilai,0,'.',',')?></td>
                <td align="center"><?= number_format($db->terima,0,'.',',')?></td>
                <td align="center"><?= number_format($db->keluar,0,'.',',')?></td>
                <td align="center"><?= number_format($saldo,0,'.',',')?></td>
                <td align="center"><?= number_format(0,0,'.',',')?></td>
                <td align="center"><?= number_format(0,0,'.',',')?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>
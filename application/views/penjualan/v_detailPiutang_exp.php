<?php
 header('Content-type: application/vnd-ms-excel');
 header('Content-Disposition: attachment; filename=piutang.xls');
 header('Pragma: no-cache');
 header('Expires: 0');
?>

<table border='1'>
    <thead>
        <tr>
            <th style='text-align: center'>No. Faktur</th>
            <th style='text-align: center'>No. Reg</th>
            <th style='text-align: center'>Rekmed</th>
            <th style='text-align: center'>Tgl AR</th>
            <th style='text-align: center'>No Kartu</th>
            <th style='text-align: center'>No Sep</th>
            <th style='text-align: center'>Nama Pasien</th>
            <th style='text-align: center'>Asal</th>
            <th style='text-align: center'>Tujuan</th>
            <th style='text-align: center'>Jumlah Piutang</th>
            <th style='text-align: center'>Inacbg Grouper</th>
            <th style='text-align: center'>Dibayar</th>
            <th style='text-align: center'>Saldo Piutang</th>
            <th style='text-align: center'>Invoice</th>
            <!-- <th>&nbsp;</th>
                            <th>&nbsp;</th> -->
        </tr>
    </thead>
    <tbody id='dataPiutang'>
        <?php                                      
            $nomor = 1;
            $total_jumlahhutang = 0;
            $total_inacbg = 0;
            $total_jumlahbayar = 0;
            $total_sisa = 0;
            foreach($keu as $row)
            {                       
                $total_jumlahhutang += $row->jumlahhutang;
                $total_inacbg += $row->inacbg;
                $total_jumlahbayar += $row->jumlahbayar;
                $total_sisa += ($row->jumlahhutang - $row->jumlahbayar);
        ?>
        <tr class='show1' id='row_<?php echo $row->noreg;?>'>
            <td align='center'><?php echo $row->fakturno;?></td>
            <td align='center'><?php echo $row->noreg;?></td>
            <td align='center'><?php echo $row->rekmed;?></td>
            <td align='center'><?php echo date('d-m-Y',strtotime($row->tglposting));?></td>
            <td><?php echo $row->nocard;?></td>
            <td><?php echo $row->nosep;?></td>
            <td><?php echo $row->namapas;?></td>
            <td><?php echo $row->asal == 'POLI' ? 'RAJAL' : 'RANAP'; ?></td>
            <td><?php echo $row->asal == 'POLI' ? $row->namapost : 'RAWAT INAP'; ?></td>
            <td align='right'><?php echo number_format($row->jumlahhutang,2,'.',','); ?></td>
            <td align='right'><?php echo number_format($row->inacbg,2,'.',','); ?></td>
            <td align='right'><?php echo number_format($row->jumlahbayar,2,'.',','); ?></td>
            <td align='right'>
                <?php echo number_format(($row->jumlahhutang - $row->jumlahbayar),2,'.',','); ?></td>
            <td><?php echo $row->invoiceno;?></td>
        </tr>
        <?php
                $nomor++;
            } 
        ?>


    </tbody>
    <tfoot>
        <td colspan='9' style='text-align:right'>Total:</td>
        <td style='text-align:right'><?php echo number_format($total_jumlahhutang,2,'.',','); ?></td>
        <td style='text-align:right'><?php echo number_format($total_inacbg,2,'.',','); ?></td>
        <td style='text-align:right'><?php echo number_format($total_jumlahbayar,2,'.',','); ?></td>
        <td style='text-align:right'><?php echo number_format($total_sisa,2,'.',','); ?></td>
        <td></td>


    </tfoot>

</table>
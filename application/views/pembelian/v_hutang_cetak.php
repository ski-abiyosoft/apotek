
<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='1' cellspacing='1' cellpadding='3'>

        <tr class='show1'>
            <td align='left'  width='30%'>Telah Terima Dari</td>
            <td align='center'  width='5%'>:</td>
            <td align='left'  width='60%'><?php echo $data->vendor_name; ?></td>
        </tr>
         
        <tr class='show1'>
            <td align='left' width='30%'>No. Kwitansi</td>
            <td align='center'  width='5%'>:</td>
            <td align='left'  width='60%'><?php echo $data->notukar; ?></td>
        </tr>
        
        <tr class='show1'>
            <td align='left' width='30%'>Tagihan Sebesar</td>
            <td align='center'  width='5%'>:</td>
            <td align='left'  width='60%'><?php echo $data->totaltagihan; ?></td>
        </tr>

        <tr class='show1'>
            <td align='left' width='30%'>Terbilang</td>
            <td align='center'  width='5%'>:</td>
            <td align='left'  width='60%'>Data</td>
        </tr>
        
        <tr class='show1'>
            <td align='left' width='30%'></td>
            <td align='center'  width='5%'></td>
            <td align='left'  width='60%'>
                <table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='1' cellspacing='1' cellpadding='3'>
                    
        <tr class='show1'>
                <td align='right' width='5%'>1</td>
                    <td width='60%'><?php echo $data->notukar; ?></td>
                    <td align='right' width='30%'><?php echo $data->totaltagihan; ?></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class='show1'>
            <td align='left' width='30%'>Guna Pembayaran</td>
            <td align='center'  width='5%'>:</td>
            <td align='left'  width='60%'><?php echo $data->keterangan; ?></td>
        </tr>

        <tr class='show1'>
            <td align='left' width='30%'>Dapat diambil Tangal</td>
            <td align='center'  width='5%'>:</td>
            <td align='left'  width='60%'><?php echo $data->diambil; ?></td>
        </tr>
        
    </tbody>
</table>
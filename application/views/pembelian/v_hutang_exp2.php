<?php
 header("Content-type: application/vnd-ms-excel");
 header("Content-Disposition: attachment; filename=".$subtitle.".xls");
 header("Pragma: no-cache");
 header("Expires: 0");
?> 

<table style="border-collapse:collapse;font-family: tahoma; font-size:12px" width="100%" align="center" border="0" cellspacing="1" cellpadding="3">
    <tr>
        <td colspan="10" style="text-align:center;font-size:22px;border-bottom: none;color:#120292;"><b><?php echo $subtitle; ?></b></td>
    </tr> 
    <tr>
        <td colspan="10" style="text-align:center;font-size:15px;border-top: none;"><?php echo $periode; ?></td>
    </tr>
    <tr>
        <td colspan="10" style="text-align:center;font-size:15px;border-top: none;"><?php echo $vendor; ?></td>
    </tr>    
</table>
<br>
<table style="border-collapse:collapse;font-family: tahoma; font-size:12px" width="100%" align="center" border="1" cellspacing="1" cellpadding="3">
    <thead class="breadcrumb">
        <tr>
            <th style="text-align: center">Cab.</th>
            <th style="text-align: center">User ID</th>
            <th style="text-align: center">AP No.</th>
            <th style="text-align: center">Tanggal</th>
            <th style="text-align: center">Vendor</th>
            <th style="text-align: center">Keterangan</th>                                         
            <th style="text-align: center">Jatuh Tempo</th>
            <th style="text-align: center">Total Tagihan</th>
            <th style="text-align: center">Total Bayar</th>
            <th style="text-align: center">Status</th>                                  
        </tr>
    </thead>
    
    <tbody>
    <?php
    $nomor = 1;
    $grand_totaltagihan = 0;
    $grand_totalbayar = 0;
    foreach ($keu as $row)
    {   
        
        $grand_totaltagihan = $grand_totaltagihan + $row->totaltagihan;
        $grand_totalbayar = $grand_totalbayar + $row->totalbayar;
        ?>

        <tr class="show1" id="row_<?php echo $row->terima_no;?>">
            <td align="center"><?php echo $row->koders;?></td>
            <td align="center"><?php echo $row->username;?></td>
            <td align="center"><?php echo $row->terima_no;?></td>
            <td align="center"><?php echo date('d-m-Y',strtotime($row->tglinvoice));?></td>										 
            <td><?php echo $row->vendor_name;?></td>
            <td><?php echo $row->keterangan;?></td>
            
        
        
            <td align="center"><?php echo date('d-m-Y',strtotime($row->duedate));?></td>	
            <td align="right"><?php echo number_format($row->totaltagihan,0,'.',',');?></td>
            <td align="right"><?php echo number_format($row->totalbayar,0,'.',',');?></td>
            <td style="text-align: center"><?php
                    if ($row->lunas=='0')
                    { ?>
                    <span class="label label-sm label-warning">
                        Belum Lunas
                    </span>
                    <?php
                    }else
                    if ($row->lunas=='1')
                    { ?>
                    <span class="label label-sm label-success">
                        Lunas
                    </span>

                    <?php
                    } ?> 
                    
            </td>

        </tr>
        
    <?php
        $nomor++;
    }        
    ?>
    
    <tr class="show1">
        <td align="right" colspan="7">Total</td>
        <td align="right"><?php echo number_format($grand_totaltagihan,0,'.',','); ?></td>
        <td align="right"><?php echo number_format($grand_totalbayar,0,'.',','); ?></td>
        <td></td>
    </tr>
    </tbody>
</table>
<?php 
        $this->load->view('template/header');
        $this->load->view('template/body');    	  
    ?>	

    <?php echo form_open('',('id="'.$form_id.'"')); ?>
    <div class="row">
        <div class="col-md-12">
            <h3 class="page-title">
            <span class="title-unit">
                    &nbsp;<?php echo $this->session->userdata('username'); ?> 
                </span>
                - 
                <span class="title-web"><?= $form_title ?>
            </h3>
            <ul class="page-breadcrumb breadcrumb">

                
            </ul>
        </div>
    </div>
    
    <table border='0' width="100%" cellpadding="2" cellspacing="3" style="font-size: 20px;">
        <tr>
            <td width="20%" >&nbsp; <b>Nama Karyawan</b></td>
            <td width="50%" >
                <input type="hidden" style="border:none;" name="nama_kary" value="<?= $karyawan->nik ?>">
                <label for="">&nbsp;<?= $karyawan->namakary ?></label>
                
            <td width="30%" >&nbsp;</td>
            </td>
        </tr>
    </table>
    <table border='0' width="100%" cellpadding="2" cellspacing="3" style="font-size: 20px;">
        <tr>
            <td width="20%" >&nbsp; <b>Status</b></td>
            <td width="20%" >
                <select name="status_absen" id="status_absen" class="form-control">
                    <?php  
                    foreach($status as $rows){
                    ?>
                    <option value="<?= $rows->kodeset ?>"><?= $rows->keterangan ?></option>
                    <?php } ?>
                </select>
            <td width="60%" >&nbsp;</td>
            </td>
        </tr>
        
    </table>
    <table border='0' width="100%" cellpadding="2" cellspacing="3">
        
        <tr>
            <td colspan="4" align="center" style="font-size: 150px;color:dodgerblue;">
            <span name="jam" id="jam24">
            </td>
        </tr>
        <tr>
            <td align="center" colspan="4" style="font-size: 50px;">
            <b>
                
                <label style="font-size: 50px;" class="control-label" for="">&nbsp;<b><?= $cek ?></b></label>
                <input type="hidden" id="tgl2" name="tgl2" class="form-control input-medium"  value="<?= date('Y-m-d');?>" />
            </td>
        </tr>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td width="25%">&nbsp;</td>

            <td width="25%" align="center" >
                <a class="btn blue" style="font-size: 30px;"  onclick="absen(1)" >
                <i class="fa fa-sign-in" style="font-size: 30px;"></i>
                <b> MASUK </b></a>
            </td>
            <td width="25%" align="center" >
                <a class="btn red" style="font-size: 30px;" onclick="absen(2)" >
                <i style="font-size: 30px;" class="fa fa-sign-out"></i>
                <b> PULANG </b></a>
            </td>
            <td width="25%">&nbsp;</td>

        </tr>
    </table>

    <?php echo form_close(); ?>
    <?php
    $this->load->view('template/footer_tb');
    $this->load->view('template/v_report');
    ?>


    <script type="text/javascript">

    // window.onload = function(){
    //     shownama('19960303.004020822.02');              
    // };

    window.onload = function()   {
     show3();         
    }

    function shownama(nik){
        
        url = "<?php echo site_url('Master_absen/karyawan/')?>"+nik;
        $.ajax({
            url : url,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                $('[name="nama_kary"]').val(data.nama);

            },
            error:function(data){
                alert("error");
                swal('Data Absen','Gagal disimpan ...','');   	
            }
        });

    }

    function absen(param)
    {            
        var v_nama_kary       = $('[name="nama_kary"]').val();
        var nm                = '<?= $karyawan->namakary ?>';
        var v_status_absen    = $('[name="status_absen"]').val();

        if (v_nama_kary=='' || v_nama_kary== null){
        swal({
                title: "KARYAWAN",
                html: "<p>KOSONG, SILAHKAN LOGIN ULANG</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 

        if (v_status_absen=='' || v_status_absen== null){
        swal({
                title: "Status Absen",
                html: "<p>HARUS DI PILIH !</p>",
                type: "error",
                confirmButtonText: "OK" 
            });    
        return;
        } 
        if(param == '1') {
            url = "<?php echo site_url('Master_absen/ajax_add/1')?>";
        } else {
            url = "<?php echo site_url('Master_absen/ajax_add/2')?>";
        }

        // ajax adding data to database
        $.ajax({
            url : url,
            type: "POST",
            data: $('#<?=$form_id?>').serialize(),
            dataType: "JSON",
            success: function(data)
            {
                if(data.status_masuk==1){
                    var stat='MASUK';
                }else{
                    var stat='PULANG';
                }
                if(data.status=='1'){   
                    swal({
                        title: "<b>DATA ABSENSI</b>",
                        html: 
                            "<p> Nama   : <b>"+nm+" </b></p>"+
                            "<p><b>( "+data.tgll+" )</b></p>"+
                            "<p><b> "+stat+" </b></p>"+
                            "Berhasil di Simpan...",
                        type: "success",
                        confirmButtonText: "OK" 
                    });	
                    
                }else{
                    swal({
                        title: "DATA ABSENSI",
                        html: 
                            "<p> Nama   : <b>"+nm+" </b></p>"+
                            "<p><b>( "+data.tgll+" )</b></p>"+
                            "<p><b> "+stat+" </b></p>"+
                            "Sudah Ada Di Database...",
                            type: "error",
                        confirmButtonText: "OK" 
                    });	
                }

            },
            error:function(data){
                alert("error");
                swal('Data Absen','Gagal disimpan ...','');   	
            }
        });
    }

    </script>

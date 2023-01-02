    <?php 
        $this->load->view('template/header');
        $this->load->view('template/body');    	  
    ?>	

    <?php echo form_open('',('id="'.$form_id.'"')); ?>
    <div class="row">
        <div class="col-md-12">
            <h3 class="page-title">
            <span class="title-unit">
                    &nbsp;<?php echo $this->session->userdata('unit'); ?> 
                </span>
                - 
                <span class="title-web"><?= $form_title ?>
            </h3>
            <ul class="page-breadcrumb breadcrumb">

                
            </ul>
        </div>
    </div>
    
    <table border='1' width="100%" cellpadding="1" cellspacing="3" style="font-size: 20px;">
            <tr>
                <td width="10%" align="center" bgcolor="#cccccc" ><b>No</b></td>
                <td width="30%" align="center" bgcolor="#cccccc" ><b>Nik</b></td>
                <td width="30%" align="center" bgcolor="#cccccc" ><b>Nama</b></td>
                <td width="20%" align="center" bgcolor="#cccccc" ><b>User</b></td>
                <td width="10%" align="center" bgcolor="#cccccc" ><b>Lokasi</b></td>
                </td>
            </tr>
        <?php
            $no   = 1;
            $pjkx = $this->db->get('tbl_pajak')->result();
            foreach ($karyawan as $rows) {
                ?>
            <tr>
                <td align="center"> <b><?= $no ?></b></td>
                <td><?= $rows->nik; ?></td>
                <td><?= $rows->namakary; ?></td>
                <td align="center"><?= $rows->user; ?></td>
                <td align="center"><?= $rows->pos; ?></td>
            </tr>
            
        <?php 
        $no ++;
        }
        ?>
        
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

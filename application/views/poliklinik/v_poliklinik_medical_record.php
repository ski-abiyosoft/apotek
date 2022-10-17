
<?php 
$this->load->view('template/header');
$this->load->view('template/body');    
date_default_timezone_set("Asia/Jakarta");	
?>	

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
        <span class="title-unit">
                &nbsp;<?= $this->session->userdata('unit'); ?> 
            </span>
            - 
            <span class="title-web">e-HMS <small>Poliklinik</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i style="color:white;" class="fa fa-home"></i>
                <a class="title-white" href="<?= base_url();?>dashboard">
                    Awal
                </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="<?= base_url('poliklinik');?>">
                    Poliklinik
                                            </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>							
                <a class="title-white" href="">
                    Pemeriksaan Odontogram
                </a>
            </li>
        </ul>
    </div>
</div>
<form id="form_odontogram" class="form-horizontal" method="post">
<div class="row"><!-- row -->
    <div class="col-md-12"> <!-- col-md-12 -->
        <div class="portlet box blue"> <!-- portlet box blue -->
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-reorder"></i><b>Penambahan Data Odontogram | Gigi : <?= $gigi ?></b>
                </div>
            </div>
        
            <div class="portlet-body"> 

                <div class="caption">
                    <h4 class="box-title"><b>DETAIL PASIEN :  <?= $data_pas->rekmed ?> &nbsp; ( <?= $data_pas->namapas ?> ) &nbsp; </b></h4>
                </div>
                <!-- mulai odontogram -->
                <table width="100%" border="0">
                    <input type="hidden" id="id_pasien" name="id_pasien" value="<?= $data_pas->rekmed ?>">
                    <input type="hidden" id="id_odontogram" name="id_odontogram" value="<?= $gigi ?>">
                    <input type="hidden" id="noreg" name="noreg" value="<?= $noreg ?>">
                    
                    <tr>
                        <td width="15%"><label for="">Tanggal</label> </td>
                        <td width="20%">
                            <input id="tgl" name="tgl" class="form-control" type="date" value="<?= date('Y-m-d'); ?>">
                        </td>
                        <td width="10%"><input id="jam" name="jam" class="form-control" type="time" value="<?= date("H:i"); ?>"></td>
                        <td width="55%">&nbsp;</td>

                    </tr>
                    <tr>
                        <td><label for="">Jenis Penyakit</label> </td>
                        <td colspan="2">
                            <select class="form-control select2_jenis_penyakit" style="width:100%;" id="j_pen" name="j_pen">
                            </select>
                        </td>
                        <td>&nbsp;</td>

                    </tr>
                    <tr>
                        <td><label for="">Remark</label> </td>
                        <td colspan="2"><textarea class='form-control input-sm' id='remark'  name='remark' required></textarea>
                        <td>&nbsp;</td>

                    </tr>
                    <tr>
                        <td colspan="3" >&nbsp;</td>

                    </tr>
                </table>
                <table width="100%" border="0">
                    <tr>
                        <td width="10%">&nbsp; </td>
                        <td width="40%">
                            <a class="btn blue btn-sm" style="color:white" onclick="save();"><i class="fa fa-save"></i>&nbsp;<b>SIMPAN</b></a>

                            <a class="btn red btn-sm" href="<?= base_url('poliklinik/pemeriksaan_odontogram/?noreg='.$noreg.'&rekmed='.$data_pas->rekmed)?>"><i class="fa fa-undo"></i>&nbsp;<b>KEMBALI </b></a>
                            </td>
                        <td width="50%">&nbsp;</td>

                    </tr>
                </table>
                <!-- akhir odontogram -->

                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">History Pemeriksaan</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body table-responsive">
                                <table id="table" id="table" id="datatable"
                        class="table table-hover table-striped table-bordered table-condensed table-scrollable">
                                <thead class="breadcrumb">
                                <tr>
                                    <th style="text-align:center; color:white;" >ID</th>
                                    <th style="text-align:center; color:white;" >Tanggal</th>
                                    <th style="text-align:center; color:white;" >Pasien</th>
                                    <th style="text-align:center; color:white;" >Gigi</th>
                                    <th style="text-align:center; color:white;" >Penyakit</th>
                                    <th style="text-align:center; color:white;" >Diagnosa</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 	 
                                $queryMedic = $this->db->query("SELECT * FROM medical_record a 
                                JOIN tbl_pasien b ON a.id_pasien=b.rekmed 
                                JOIN kategori_warna c ON a.id_kategori_warna=c.id_kategori_warna 
                                WHERE a.id_pasien='$data_pas->rekmed' and a.id_odontogram='$gigi' ") ->result();

                                foreach($queryMedic as $m){
                                $myDateTime = $this->M_cetak->tanggal_format_indonesia( $m->tanggal).' | '.$m->jam; 
                                // $newDateString = $myDateTime->format('m/d/Y');
                                ?>

                                <tr>
                                    <td><?= $m->id_medical_record?> </td>
                                    <td><?= $myDateTime ?></td>
                                    <td><?= $m->namapas?> </td>
                                    <td><?= $m->id_odontogram?> </td>
                                    <td align='center' 
                                    style="background-color:<?= $m->nama_kategori_warna ?>; color:white">
                                    <label>&nbsp;<?= $m->jenis_penyakit?>&nbsp;</label></td>
                                    <td><?= $m->remark?> </td>
                                </tr>
                                        
                                <?php } ?>
                                </tbody>
                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>
                </div>
            </div>		
        </div> <!-- portlet box blue -->
    </div> <!-- col-md-12 -->
</div>   <!-- row -->
</form> 
		  


<?php
$this->load->view('template/footer');  
?>


<script type="text/javascript">
	
	$(document).ready(function () {
	});

    function save()
    {	        
        var tgl             = $('[name="tgl"]').val();
        var j_pen           = $('[name="j_pen"]').val();
        var remark          = $('[name="remark"]').val();
        var id_pasien       = $('[name="id_pasien"]').val();
        var id_odontogram   = $('[name="id_odontogram"]').val();
        
        if (tgl == "" || tgl == null) {
            swal({
                title   : "Tanggal",
                html    : " Tidak Boleh Kosong .!!!",
                type    : "error",
                confirmButtonText   : "OK"
            });
            return;
        }

        if (j_pen == "" || j_pen == null) {
            swal({
                title   : "Jenis Penyakit",
                html    : " Tidak Boleh Kosong .!!!",
                type    : "error",
                confirmButtonText   : "OK"
            });
            return;
        }

        if (remark == "" || remark == null) {
            swal({
                title   : "Remark",
                html    : " Tidak Boleh Kosong .!!!",
                type    : "error",
                confirmButtonText   : "OK"
            });
            return;
        }

        $.ajax({				
            url         : "<?php echo site_url('Poliklinik/ajax_add_odon')?>",
            data        : $('#form_odontogram').serialize(),
            type        : 'POST',
            dataType    : 'json',
            success:function(data){ 
            
                if(data.status=='1'){
                    swal({
                        title: "DATA ODONTORAM",
                        html: "<p> Id   : <b>"+id_pasien+"</b> </p>"+ 
                        "<p> Kode Gigi :  " + id_odontogram+"</p>"+
                        "<br> Berhasil Tersimpan ..",
                        type: "success",
                        confirmButtonText: "OK"
                        }).then((value) => {
                            reload_table(); 
                    });	
                    return;
                }else if(data.status=='2'){
                    swal({
                        title: "DATA ODONTORAM",
                        html: "<p> Id   : <b>"+id_pasien+"</b> </p>"+ 
                        "Kode Gigi :  " + id_odontogram+
                        "<br> <br>Data Sudah Ada",
                        type: "error",
                        confirmButtonText: "OK" 
                    });	
                    return;
                }else{
                    swal({
                        title: "DATA ODONTORAM",
                        html: "<p> Id   : <b>"+id_pasien+"</b> </p>"+ 
                        "<p>Kode Gigi :  " + id_odontogram+"</p>"+
                        "<br> Gagal Tersimpan",
                        type: "error",
                        confirmButtonText: "OK" 
                    });	
                    return;
                }
            
            
            },
            error:function(data){
                swal('','Gagal simpan data','');					
            }
            });
    }
    
    function reload_table()
    {
        window.location.reload();
    }
	
</script>

</body>
</html> 
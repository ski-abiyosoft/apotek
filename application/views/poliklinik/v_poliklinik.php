    <?php 
    $this->load->view('template/header');
    $this->load->view('template/body');    	  
    //   initailizeSelect2_jnsicd();
    ?>

    
            <div class="row">
                <div class="col-md-12">
                    <h3 class="page-title">
                    <span class="title-unit">
                            &nbsp;<?php echo $this->session->userdata('unit'); ?> 
                        </span>
                    - 
                    <span class="title-web">e-HMS <small>Poliklinik</small>
                    </h3>
                    <ul class="page-breadcrumb breadcrumb">

                        <li>
                            <i style="color:white;" class="fa fa-home"></i>
                            <a class="title-white" href="<?php echo base_url();?>home">
                            Awal
                            </a>
                            <i style="color:white;" class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <a class="title-white" href="#">
                            e-HMS
                            </a>
                            <i style="color:white;" class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <a class="title-white" href="#">
                            Poliklinik
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet">
                        <div class="portlet-title">
                            <div class="caption">
                                Daftar Pasien Poli -
                                <span>
                                    <b>
                                        <?= $periode;
                                        ?>

                                    </b>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="dashboard-stat green">
                                    <div class="visual">
                                        <i class="fa fa-barcodex"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <?php foreach($total_pasien as $total_pasienn): ?>
                                            <?= $total_pasienn->jum;?>
                                            <?php endforeach; ?>
                                        </div>

                                        <div class="desc">
                                            TOTAL PASIEN POLI HARI INI
                                        </div>
                                    </div>

                                    <!-- <a data-toggle="modal" class="more" href="">
                                        Lihat Rinci<i class="m-icon-swapright m-icon-white"></i>
                                    </a> -->
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="dashboard-stat green">
                                    <div class="visual">
                                        <i class="fa fa-printx"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <?= $diperiksa_perawat == 0 ? "0" : $diperiksa_perawat ?>
                                        </div>
                                        <div class="desc">
                                            PASIEN BELUM DIPERIKSA
                                        </div>
                                    </div>

                                    <!-- <a data-toggle="modal" class="more" href="">
                                        Lihat Rinci<i class="m-icon-swapright m-icon-white"></i>
                                    </a> -->
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="dashboard-stat blue">
                                    <div class="visual">
                                        <i class="fa fa-shopping-cartx"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <?= $diperiksa_dokter == 0 ? "0" : $diperiksa_dokter ?>
                                        </div>
                                        <div class="desc">
                                            PASIEN SELESAI DIPERIKSA
                                        </div>
                                    </div>
                                    <!-- <a data-toggle="modal" class="more" href="">
                                        Lihat Rinci<i class="m-icon-swapright m-icon-white"></i>
                                    </a> -->
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="dashboard-stat yellow">
                                    <div class="visual">
                                        <i class="fa fa-shopping-cartx"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">

                                        </div>
                                        <div class="desc">
                                            RATA RATA WAKTU TUNGGU
                                        </div>
                                    </div>
                                    <!-- <a data-toggle="modal" class="more" href="">
                                        Lihat Detail<i class="m-icon-swapright m-icon-white"></i>
                                    </a> -->
                                </div>
                            </div>
                        </div>
                        <br>
                        <table border="0"  class="table" width="100%">
                        <div class="portlet-body">
                            <div class="table-toolbar">
                                
                                <tr>
                                    <td style="width:20%">
                                        <!-- <h5 style="color: blue"> <b>Poliklinik: All Poli &nbsp; Dokter: All Dokter </b> &nbsp; </h5> -->
                                        <label for="unit">Poli</label>
                                        <select name="unit" id="unit" class="form-control " onChange="update()" style="width:100%;">
                                            <option value="">-- All --</option>
                                            <?php foreach($namapos as $pos): ?>
                                            <option value="<?= $pos->kodepos;?>"><?= $pos->namapost;?></option>
                                            <?php endforeach; ?>
                                        </select>
                                            
                                    </td>
                                    <td style="width:20%">
                                        <label for="nadokter">Dokter</label>
                                        <select name="kodokter" class="form-control" id="kodokter1">
                                        </select>
                                        

                                    </td>
                                    <td>
                                        <button class="btn btn-primary" id="proses" type="button" onclick="filterdata()" style="margin-top: 23.5px;">Proses</button>
                                    </td>
                                    <td class="" >
                                        
                                        <div class="btn-group pull-right">
                                            <button style="margin-top: 23.5px;" class="btn btn-default" onclick="reload_table()">
                                                <i name="refresh" id="refresh" class="glyphicon glyphicon-refresh"></i> Refresh 
                                            </button>
                                            <button style="margin-top: 23.5px;" class="btn dropdown-toggle" data-toggle="dropdown">Data 
                                                <i class="fa fa-angle-down"></i>
                                            </button>
                                            <ul style="margin-top: 23.5px;" class="dropdown-menu">
                                                <li>
                                                    <a data-toggle="modal" href="#hperiode">Filter Data</a>
                                                </li>
                                            </ul>
                                        </div>
                                        
                                    </td>
                                </tr>
                                
                                <tr><td style="border-top:white" colspan="4" >&nbsp;</td></tr>
                                
                                                
                            </div>
                        </div>
                        </table>
                        <div class="portlet-body">
                            <table id="tableraj" class="table table-striped table-bordered table-hover" style="overflow: auto;" width="100%">
                            <!-- <table id="tableraj" class="table table-striped table-bordered table-hover" style="overflow: auto; white-space: nowrap; display: inline-block;cellspacing:0;" width="100%"> -->

                                <thead class="page-breadcrumb breadcrumb">
                                <tr>
                                    <th class="title-white" style="text-align: center">E-MR</th>
                                    <!-- <th class="title-white" style="text-align: center">Diperiksa Perawat</th> -->
                                    <!-- <th class="title-white" style="text-align: center">Diperiksa Dokter</th> -->
                                    <th class="title-white" style="text-align: center">No antri</th>
                                    <th class="title-white" style="text-align: center">Status</th>
                                    <th class="title-white" style="text-align: center">No REG</th>
                                    <th class="title-white" style="text-align: center">No RM</th>
                                    <th class="title-white" style="text-align: center">Tanggal Daftar</th>
                                    <th class="title-white" style="text-align: center">Nama Pasien</th>
                                    <th class="title-white" style="text-align: center">Poliklinik</th>
                                    <th class="title-white" style="text-align: center">Dokter</th>
                                    <th class="title-white" style="text-align: center">Pembayaran</th>
                                    <!-- <th class="title-white">&nbsp;</th>
                                    <th class="title-white">&nbsp;</th>
                                    <th class="title-white">&nbsp;</th> -->
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
</div>  

<div class="modal fade" id="hperiode" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-small">
        <div class="modal-content">
            <span id="nopilih">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Data</h4>
                </div>
                <div class="modal-body">
                    <form action="#" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-4 control-label">Tanggal Masuk:</label>
                            <div class="col-md-6">
                                <input id="tglmasuk" name="tglmasuk" class="form-control input-medium" type="date"
                                    value="<?php echo date('Y-m-d');?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Sampai Tanggal:</label>
                            <div class="col-md-6">
                                <input id="tglakhir" name="tglakhir" class="form-control input-medium" type="date"
                                    value="<?php echo date('Y-m-d');?>" />
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label class="col-md-4 control-label">Kode Poli :</label>
                            <div class="col-md-6">
                                <select style='color:black;' id="kodepos" name="kodepos" class="selectpicker"
                                    data-live-search="true" data-placeholder="Pilih..."
                                    onkeypress="return tabE(this,event)">
                                    <option value=''>-- Pilih Data --</option>
                                    <?php
                                    foreach($listKodePoli as $key){
                                        echo "<option>$key->kodepos</option>";
                                    }
                                ?>
                                </select>
                                
                            </div>
                        </div> -->
                        <!-- <div class="form-group">
                            <label class="col-md-4 control-label">Nama Dokter :</label>
                            <div class="col-md-6">

                                <select style='color:black;' id="nadokter" name="nadokter" class="selectpicker"
                                    data-live-search="true" data-placeholder="Pilih..."
                                    onkeypress="return tabE(this,event)">
                                    <option value=''>-- Pilih Data --</option>
                                    <?php
                                    foreach($naDokter as $key){
                                        echo "<option>$key->nadokter</option>";
                                    }
                                ?>
                                </select>
                            </div>
                        </div> -->

                    </form>
                </div>
                <div class="modal-footer">
                    <p align="center">
                        <button type="button" id="btnfilter" class="btn green" onclick="filterdata()"
                            data-dismiss="modal">Buka Data</button>
                    </p>
                </div>
        </div>
    </div>
</div>



<div class="modal fade " id="modal-detail" role="dialog" >
    <div class="container"> 
    <div class="modal-dialog modal-sm"> 
        <div class="modal-content">
            <div class="modal-header header-custom" >
                
                <table border="0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <!-- <span aria-hidden="true">&times;</span> -->
                </button>
                <tr>
                    <td>
                        <input readonly type="text" id="nampasdet" name="nampasdet" class="form-control input-medium">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input readonly type="text" id="noregdet" name="noregdet" class="form-control input-medium">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input readonly type="text" id="rekmeddet" name="rekmeddet" class="form-control input-medium">
                    </td>
                </tr>
                </table>
            </div>
            
            <div class="modal-body" class="btn-group">
                <table width="100%" >
                <tr align="center">
                    <td>
                    <button type="button" class="btn green" onclick="cek_per();">
                    <i class="fa fa-solid fa-check"></i> <b>Pemeriksaan Perawat &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></button>
                    <br><br>
                    </td>
                </tr>

                <tr align="center">
                    <td>
                    <button type="button" class="btn green" onclick="cek_dok();">
                    <i class="fa fa-solid fa-check"></i> <b>Pemeriksaan Dokter &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></button>
                    <br><br>
                    </td>
                </tr>

                <tr align="center">
                    <td>
                    <button type="button" class="btn green" onclick="cek_odon();">
                    <i class="fa fa-solid fa-check"></i> <b>Odontogram &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></button>
                    <br><br>
                    </td>
                </tr>
                <tr align="center">
                    <td>
                    <button type="button" class="btn green"><b>Surat Perintah Rawat Inap &nbsp;&nbsp;</b></button>
                    <br><br>
                    </td>
                </tr>
                <tr align="center">
                    <td>
                    <button type="button" class="btn green"><b>Surat Persetujuan Tindakan</b></button>
                    <br><br>
                    </td>
                </tr>
                </table>
            </div>
            <div class="modal-footer" >
                <button type="button"  class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
    </div>
</div>

<div class="modal fade" id="loading" data-toggle="modal" role="dialog" aria-hidden="true"> 
    <div class="modal-dialog vertical-align-center2" style="margin-top: 350px;"> 
    <table align="center">
        <tr>
            <td>Loading...</td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr align="center">
            <td>
                <img id="search1" height="50px" width="50px" src="<?php echo base_url();?>assets/img/loadinghar2.gif"  /></td>
        </tr>
    </table>
    </div>
</div>

<?php
$this->load->view('template/footer_tb');
$this->load->view('template/v_periode');
?>
    

<script type="text/javascript">
var save_method; //for save method string
var tableraj;

function alltrim(kata){
    b = (kata.split(' ').join(''));
    c = (b.replace( /\s/g, ""));
    return c
}

function playAudio(antriangka="", noantri="satu") {
    
    var bel   = new Audio('audio/bell_long.wav');
    var bel2  = new Audio('audio/nomor_antrian.wav');
    var bel4  = new Audio('audio/belas.wav');
    var bel5  = new Audio('audio/puluh.wav');
    var bel7  = new Audio('audio/ratus.wav');
    var to    = new Audio('audio/silakan_menuju_ke.wav');
    var poli  = new Audio('audio/poliklinik.wav');

    totalwaktu=0; 
    
    setTimeout(function() { bel.pause(); bel.currentTime=0; bel.play(); }, totalwaktu);
    totalwaktu=totalwaktu+1500;
    setTimeout(function() { bel2.pause(); bel2.currentTime=0; bel2.play(); }, totalwaktu);
    totalwaktu=totalwaktu+1500;
    
    if(antriangka<=11 || antriangka==100){

        var bel3  = new Audio('audio/'+noantri+'.wav');
        setTimeout(function() { bel3.pause(); bel3.currentTime=0; bel3.play(); }, totalwaktu);
        totalwaktu=totalwaktu+1500;
        
    }else if(antriangka>11 && antriangka <20){

        noantri2   = alltrim(noantri.split("belas").join(""));
        var bel3  = new Audio('audio/'+noantri2+'.wav');

        setTimeout(function() { bel3.pause(); bel3.currentTime=0; bel3.play(); }, totalwaktu);
        totalwaktu=totalwaktu+1000;
        setTimeout(function() { bel4.pause(); bel4.currentTime=0; bel4.play(); }, totalwaktu);
        totalwaktu=totalwaktu+1000;

    }else if(antriangka==20 || antriangka==30 || antriangka==40 || antriangka==50 || antriangka==60 || antriangka==70 || antriangka==80 ||antriangka==90){
        
        noantri2   = alltrim(noantri.split("puluh").join(""));
        var bel3  = new Audio('audio/'+noantri2+'.wav');

        setTimeout(function() { bel3.pause(); bel3.currentTime=0; bel3.play(); }, totalwaktu);
        totalwaktu=totalwaktu+1000;
        // belas
        setTimeout(function() { bel5.pause(); bel5.currentTime=0; bel5.play(); }, totalwaktu);
        totalwaktu=totalwaktu+1000;
        
    }else if(antriangka>20 && antriangka<100 && antriangka!=20 && antriangka!=30 && antriangka!=40 && antriangka!=50 && antriangka!=60 && antriangka!=70 && antriangka!=80 &&antriangka!=90){

        noantri2    = alltrim(noantri.split("puluh").join("-"));
        $dat        = noantri2.split("-");
        $bel3       = $dat[0];
        $bel6       = $dat[1];
        
        var bel3  = new Audio('audio/'+$bel3+'.wav');
        var bel6  = new Audio('audio/'+$bel6+'.wav');

        setTimeout(function() { bel3.pause(); bel3.currentTime=0; bel3.play(); }, totalwaktu);
        totalwaktu=totalwaktu+1000;
        // puluh
        setTimeout(function() { bel5.pause(); bel5.currentTime=0; bel5.play(); }, totalwaktu);
        totalwaktu=totalwaktu+1000;
        setTimeout(function() { bel6.pause(); bel6.currentTime=0; bel6.play(); }, totalwaktu);
        totalwaktu=totalwaktu+1000;
    }else if(antriangka<=110 || antriangka ==111){

        noantri2    = alltrim(noantri.split("seratus").join(""));
        var bel3    = new Audio('audio/seratus.wav');
        var bel4    = new Audio('audio/'+noantri2+'.wav');

        setTimeout(function() { bel3.pause(); bel3.currentTime=0; bel3.play(); }, totalwaktu);
        totalwaktu=totalwaktu+1000;
        setTimeout(function() { bel4.pause(); bel4.currentTime=0; bel4.play(); }, totalwaktu);
        totalwaktu=totalwaktu+1000;

    } else if(antriangka>111 && antriangka <120){

        noantri2    = alltrim(noantri.split("seratus").join(""));
        noantri3    = alltrim(noantri2.split("belas").join(""));
        var bel3    = new Audio('audio/seratus.wav');
        var bel11   = new Audio('audio/'+noantri3+'.wav');

        setTimeout(function() { bel3.pause(); bel3.currentTime=0; bel3.play(); }, totalwaktu);
        totalwaktu=totalwaktu+1000;
        setTimeout(function() { bel11.pause(); bel11.currentTime=0; bel11.play(); }, totalwaktu);
        totalwaktu=totalwaktu+1000;
        setTimeout(function() { bel4.pause(); bel4.currentTime=0; bel4.play(); }, totalwaktu);
        totalwaktu=totalwaktu+1000;

    }else if(antriangka>120 && antriangka<200 ){

        noantri2    = alltrim(noantri.split("seratus").join(""));
        noantri3    = alltrim(noantri2.split("puluh").join("-"));
        $dat        = noantri3.split("-");
        $bel8       = $dat[0];
        $bel9       = $dat[1];

        var bel3  = new Audio('audio/seratus.wav');
        var bel8  = new Audio('audio/'+$bel8+'.wav');
        var bel9  = new Audio('audio/'+$bel9+'.wav');

        // seratus
        setTimeout(function() { bel3.pause(); bel3.currentTime=0; bel3.play(); }, totalwaktu);
        totalwaktu=totalwaktu+1000;

        setTimeout(function() { bel8.pause(); bel8.currentTime=0; bel8.play(); }, totalwaktu);
        totalwaktu=totalwaktu+1000;
        // puluh
        setTimeout(function() { bel5.pause(); bel5.currentTime=0; bel5.play(); }, totalwaktu);
        totalwaktu=totalwaktu+1000;
        setTimeout(function() { bel9.pause(); bel9.currentTime=0; bel9.play(); }, totalwaktu);
        totalwaktu=totalwaktu+1000;
        
    }else if(antriangka>200 ){

        noantri2    = alltrim(noantri.split("ratus").join("-"));
        noantri3    = alltrim(noantri2.split("puluh").join("-"));
        $dat        = noantri3.split("-");
        $bel3       = $dat[0];
        $bel8       = $dat[1];
        $bel9       = $dat[2];

        var bel3  = new Audio('audio/'+$bel3+'.wav');
        var bel8  = new Audio('audio/'+$bel8+'.wav');
        var bel9  = new Audio('audio/'+$bel9+'.wav');

        // seratus
        setTimeout(function() { bel3.pause(); bel3.currentTime=0; bel3.play(); }, totalwaktu);
        totalwaktu=totalwaktu+1000;
        // seratus
        setTimeout(function() { bel7.pause(); bel7.currentTime=0; bel7.play(); }, totalwaktu);
        totalwaktu=totalwaktu+1000;

        setTimeout(function() { bel8.pause(); bel8.currentTime=0; bel8.play(); }, totalwaktu);
        totalwaktu=totalwaktu+1000;
        // puluh
        setTimeout(function() { bel5.pause(); bel5.currentTime=0; bel5.play(); }, totalwaktu);
        totalwaktu=totalwaktu+1000;
        setTimeout(function() { bel9.pause(); bel9.currentTime=0; bel9.play(); }, totalwaktu);
        totalwaktu=totalwaktu+1000;

    }
    
    setTimeout(function() { to.pause(); to.currentTime=0; to.play(); }, totalwaktu);
        totalwaktu=totalwaktu+1700;

    setTimeout(function() { poli.pause(); poli.currentTime=0; poli.play(); }, totalwaktu);
        totalwaktu=totalwaktu+1500;
    
}

function getdiag(sab) {
    if(sab==0){
        swal({
            title: "JENIS DIAGNOSA",
            html: "<p>HARUS DI PILIH</p>",
            type: "error",
            confirmButtonText: "OK" 
        });    
        return;
    }else{
        if(sab=='DG01'){
            str='ICD10_1998';
        }else{
            str='ICD9CM_2005';
        }
        initailizeSelect2_icdind(str);
    }
    
}

function showdiag(str, id) {

    var vid = id;
    $('#sat' + vid).val(data.satuan1);
    $('#harga' + vid).val(formatCurrency1(data.hargabeli));
    totalline(vid);
    var vtotal = $('#_vtotal').text();
    var xtotal = parseInt(vtotal.replaceAll(',', ''));


}

$(document).ready(function() {

    //datatables
    // $('#loading').modal('show');
    var display = $.fn.dataTable.render.number( '.', ',', 2, ' ' ).display;
    tableraj = $('#tableraj').DataTable({ 

        
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
    
        "ajax": {
            "url": "<?php echo site_url('poliklinik/ajax_list/1')?>",
            "type": "POST"
        },
        
        //scrollX: true,
        //"scrollY":        "200px",
        "scrollCollapse": false,
        "paging":         true,
        
        "oLanguage": {
            "sEmptyTable": "<div class='text-center'>Data Kosong</div>",
            "sInfoEmpty": "",
            "sInfoFiltered": " - Dipilih dari _MAX_ data",
            "sSearch": "Pencarian Data : ",
            "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
            "sLengthMenu": "_MENU_ Baris",
            "sLoadingRecords": "Loading...",
            "sProcessing":"Tunggu Sebentar... Loading...",
            // "sProcessing":$('#loading').modal('show'),
            "sZeroRecords": "Tida ada data",
            "oPaginate": {
                "sPrevious": "Sebelumnya",
                "sNext": "Berikutnya"
            }
        },

                
        "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "Semua"] // change per page values here
                ],		

        //Set column definition initialisation properties.
        "columnDefs": [{  
            //    "targets": [10], //last column
            "orderable": true, //set not orderable
            "className" : "text-right",
                render: function ( data, type, row ) {
                //    return '<b>' + display(row[10]) + '</b>';
                }
        }],
    });
    
    // setTimeout(function() { $('#loading').modal('hide'); }, 2000);

    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });

});

$('#berat, #tinggi').keyup(function(){
    
    var vberat    = $('#berat').val();
    var vtinggi   = $('#tinggi').val();

    if(vberat== null){
        var vberat=0;
    }else{
        var vberat=vberat;
    }

    if(vtinggi== null){
        var vtinggi=0;
    }else{
        var vtinggi=vtinggi;
    }

    // alert(vberat2);
    
    var tbb   = eval(vtinggi)/100;
    var bmi   = vberat/(tbb*tbb);   

    if(bmi>0){
        bmi2=Math.ceil(bmi);
    }else{
        bmi2=0;
    }
    
    if(bmi>1 && bmi<18.5){
        bmi_res='Under Weight';
    }else if(bmi>18.5 && bmi<=25){
        bmi_res='Normal Weight';
    }else if(bmi>25 && bmi<=30){
        bmi_res='Over Weight';
    }else if(bmi>30 && bmi<1000){
        bmi_res='Obese';
    }else{
        bmi_res='';
    }

    $('[name="bmi"]').val(bmi2);
    $('[name="bmi_result"]').val(bmi_res);
    
    
});

function c_ceknyeri($cekn)
{
    if($cekn==1){
        $("#ceknyeri2").attr("checked",false);
        $("#ceknyeri3").attr("checked",false);
        $("#ceknyeri4").attr("checked",false);
        $("#ceknyeri5").attr("checked",false);
        $("#ceknyeri6").attr("checked",false);
    }else if($cekn==2){
        $("#ceknyeri1").attr("checked",false);
        $("#ceknyeri3").attr("checked",false);
        $("#ceknyeri4").attr("checked",false);
        $("#ceknyeri5").attr("checked",false);
        $("#ceknyeri6").attr("checked",false);
    }else if($cekn==3){
        $("#ceknyeri1").attr("checked",false);
        $("#ceknyeri2").attr("checked",false);
        $("#ceknyeri4").attr("checked",false);
        $("#ceknyeri5").attr("checked",false);
        $("#ceknyeri6").attr("checked",false);
    }else if($cekn==4){
        $("#ceknyeri1").attr("checked",false);
        $("#ceknyeri2").attr("checked",false);
        $("#ceknyeri3").attr("checked",false);
        $("#ceknyeri5").attr("checked",false);
        $("#ceknyeri6").attr("checked",false);
    }else if($cekn==5){
        $("#ceknyeri1").attr("checked",false);
        $("#ceknyeri2").attr("checked",false);
        $("#ceknyeri3").attr("checked",false);
        $("#ceknyeri4").attr("checked",false);
        $("#ceknyeri6").attr("checked",false);
    }else if($cekn==6){
        $("#ceknyeri1").attr("checked",false);
        $("#ceknyeri2").attr("checked",false);
        $("#ceknyeri3").attr("checked",false);
        $("#ceknyeri4").attr("checked",false);
        $("#ceknyeri5").attr("checked",false);
    }else{
        $("#ceknyeri1").attr("checked",false);
        $("#ceknyeri2").attr("checked",false);
        $("#ceknyeri3").attr("checked",false);
        $("#ceknyeri4").attr("checked",false);
        $("#ceknyeri5").attr("checked",false);
        $("#ceknyeri6").attr("checked",false);
    }
}

function add_data()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title
}
    
function save()
{	    
    var v_nadi          = $('[name="nadi"]').val();
    var v_berat         = $('[name="berat"]').val();
    var v_nafas         = $('[name="nafas"]').val();
    var v_tinggi        = $('[name="tinggi"]').val();
    var v_oksi          = $('[name="oksi"]').val();
    var v_bmi           = $('[name="bmi"]').val();
    var v_bmi_result    = $('[name="bmi_result"]').val();
    var v_suhu          = $('[name="suhu"]').val();
    var v_tekanan       = $('[name="tekanan"]').val();
    var v_tekanan1      = $('[name="tekanan1"]').val();
    var v_doa           = $('[name="doa"]').val();
    var v_simpulfisik   = $('[name="simpulfisik"]').val();
    var namapass        = $('[name="nampas_per"]').val();


    if (v_nadi='' || v_nadi== null){
    swal({
            title: "NADI",
            html: "<p>HARUS DI ISI !</p>",
            type: "error",
            confirmButtonText: "OK" 
        });    
    return;
    } 
    if (v_berat='' || v_berat== null){
    swal({
            title: "BERAT BADAN",
            html: "<p>HARUS DI ISI !</p>",
            type: "error",
            confirmButtonText: "OK" 
        });    
    return;
    } 
    if (v_nafas='' || v_nafas== null){
    swal({
            title: "PERNAFASAN",
            html: "<p>HARUS DI ISI !</p>",
            type: "error",
            confirmButtonText: "OK" 
        });    
    return;
    } 
    if (v_tinggi='' || v_tinggi== null){
    swal({
            title: "TINGGI BADAN",
            html: "<p>HARUS DI ISI !</p>",
            type: "error",
            confirmButtonText: "OK" 
        });    
    return;
    } 
    if (v_oksi='' || v_oksi== null){
    swal({
            title: "SPO2",
            html: "<p>HARUS DI ISI !</p>",
            type: "error",
            confirmButtonText: "OK" 
        });    
    return;
    } 
    if (v_bmi='' || v_bmi== null){
    swal({
            title: "BMI",
            html: "<p>HARUS DI ISI !</p>",
            type: "error",
            confirmButtonText: "OK" 
        });    
    return;
    } 
    if (v_bmi_result='' || v_bmi_result== null){
    swal({
            title: "BMI RESULT",
            html: "<p>HARUS DI ISI !</p>",
            type: "error",
            confirmButtonText: "OK" 
        });    
    return;
    } 
    if (v_suhu='' || v_suhu== null){
    swal({
            title: "SUHU",
            html: "<p>HARUS DI ISI !</p>",
            type: "error",
            confirmButtonText: "OK" 
        });    
    return;
    } 
    if (v_tekanan='' || v_tekanan== null){
    swal({
            title: "TEKANAN DARAH ATAS",
            html: "<p>HARUS DI ISI !</p>",
            type: "error",
            confirmButtonText: "OK" 
        });    
    return;
    } 
    if (v_tekanan1='' || v_tekanan1== null){
    swal({
            title: "TEKANAN DARAH BAWAH",
            html: "<p>HARUS DI ISI !</p>",
            type: "error",
            confirmButtonText: "OK" 
        });    
    return;
    } 
    if (v_simpulfisik='' || v_simpulfisik== null){
    swal({
            title: "KESIMPULAN FISIK",
            html: "<p>HARUS DI ISI !</p>",
            type: "error",
            confirmButtonText: "OK" 
        });    
    return;
    } 
    
            
    $.ajax({				
        url:"<?php echo site_url('poliklinik/ajax_add/1')?>",				
        data:$('#form_periksa_perawat').serialize(),				
        type:'POST',
        dataType : "json",
        success:function(data){ 
        // data1 = JSON.parse(data);
        // alert(data1.status);
            if(data.status=='1'){   
                swal({
                    title: "DATA",
                    html: 
                        "<p> Nama   : <b>"+namapass+"</b> </p>"+ 
                        "<br> <p> No. Register   : <b>"+data.nomor+"</b> </p>"+
                        "<br>Berhasil di Perbarui...",
                    type: "success",
                    confirmButtonText: "OK" 
                    }).then((value) => {
                            // location.href = "<?php echo base_url()?>poliklinik";
                            $('#modal_periksa_perawat').modal('hide');
                });	
            }else{
                swal({
                    title: "DATA",
                    html: 
                        "<p> Nama   : <b>"+namapass+"</b> </p>"+ 
                        "<br> <p> No. Register   : <b>"+data.nomor+"</b> </p>"+
                        "<br>Berhasil Tersimpan...",
                    type: "success",
                    confirmButtonText: "OK" 
                    }).then((value) => {
                            // location.href = "<?php echo base_url()?>poliklinik";
                            $('#modal_periksa_perawat').modal('hide');
                });	
            }			
                                
    
        },
        error:function(data){
            swal('EMR','Data gagal disimpan ...','');   	
        }
    });
}	


function reload_table()
{
    tableraj.ajax.reload(null,false); //reload datatable ajax 
}

function filterdata(){	
    // $('#loading').modal('show');    
    var poli        = $('[name="unit"]').val();
    var kodokter    = document.getElementById("kodokter1").value;
    var tgl1        = document.getElementById("tglmasuk").value;
    var tgl2        = document.getElementById("tglakhir").value;
    var id          = 2;
    var str         = id+'~'+tgl1+'~'+tgl2+'~'+poli+'~'+kodokter;
    tableraj.ajax.url("<?php echo base_url('poliklinik/ajax_list/')?>"+str).load();
    // $('#').modal('hide');
    // setTimeout(function() { $('#loading').modal('hide'); }, 2000);

}

function closemod() {
    // $('#modal-detail').modal('hide');
    // alert('aaa');
}

function add_list(id)
{
    var select = id;
    $('#loading').modal('show');
    $.ajax({
        url: "<?= site_url('Poliklinik/get_detail');?>",
        type: "POST",
        dataType: "JSON",
        data: {ceknoreg:select},
        success: function(data) {
            var namapase = data.namapas;   
            var norege = data.noreg;   
            var rekmede = data.rekmed;   
            $('#nampasdet').val(namapase);
            $('#noregdet').val(norege);
            $('#rekmeddet').val(rekmede);
            $('#loading').modal('hide');
        }
    });
    $('#modal-detail').modal('show'); // show bootstrap modal
}

function cek_per()
{
    var nampasdet = document.getElementById("nampasdet").value;
    var noregdet  = document.getElementById("noregdet").value;
    var rekmeddet = document.getElementById("rekmeddet").value;
    // url=baseurl+'poliklinik/pemeriksaan_perawat/?id='+id+'&nobukti='+nobukti;
    
    // url="<?php echo base_url()?>poliklinik/pemeriksaan_perawat/"+nampasdet+'/'+noregdet+'/'+rekmeddet
    url="<?php echo base_url()?>poliklinik/pemeriksaan_perawat/?noreg="+noregdet+"&rekmed="+rekmeddet
    // window.open(url,'');
    window.open(url,'_blank');
    window.focus();
}

function cek_dok()
{
    var nampasdet = document.getElementById("nampasdet").value;
    var noregdet  = document.getElementById("noregdet").value;
    var rekmeddet = document.getElementById("rekmeddet").value;
    // url=baseurl+'poliklinik/pemeriksaan_perawat/?id='+id+'&nobukti='+nobukti;
    
    // url="<?php echo base_url()?>poliklinik/pemeriksaan_perawat/"+nampasdet+'/'+noregdet+'/'+rekmeddet
    url="<?php echo base_url()?>poliklinik/pemeriksaan_dokter/?noreg="+noregdet+"&rekmed="+rekmeddet
    // window.open(url,'');
    window.open(url,'_blank');
    window.focus();
}

function cek_odon()
{
    var nampasdet = document.getElementById("nampasdet").value;
    var noregdet  = document.getElementById("noregdet").value;
    var rekmeddet = document.getElementById("rekmeddet").value;
    // url=baseurl+'poliklinik/pemeriksaan_perawat/?id='+id+'&nobukti='+nobukti;
    
    // url="<?php echo base_url()?>poliklinik/pemeriksaan_perawat/"+nampasdet+'/'+noregdet+'/'+rekmeddet
    url="<?php echo base_url()?>poliklinik/pemeriksaan_odontogram/?noreg="+noregdet+"&rekmed="+rekmeddet
    // window.open(url,'');
    window.open(url,'_blank');
    window.focus();
}

function update() {
    // var select = document.getElementById('unit');
    // $('#loading').modal('show'); // show bootstrap modal
    var select = $('[name="unit"]').val();
    $.ajax({
        url: "<?= site_url('Poliklinik/get_dokter_ajax');?>",
        type: "POST",
        dataType: "JSON",
        data: {cekunit:select},
        success: function(data) {
            // console.log(data);
            var opt = data;
            var nadokter = $("#kodokter1");
            nadokter.empty();
            $(opt).each(function() {
                var option = $("<option/>");
                option.html(this.nadokter);
                option.val(this.kodokter);
                nadokter.append(option);
            });
            
            $('#loading').modal('hide');
        }
    });
}

</script>
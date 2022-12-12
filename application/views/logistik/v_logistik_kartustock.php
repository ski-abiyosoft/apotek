<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">&nbsp;<?php echo $this->session->userdata('unit'); ?> </span>&nbsp;
            -
            <span class="title-web">Logistik <small>Kartu Stock</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i style="color:white;" class="fa fa-home"></i>
                <a class="title-white" href="../home.php">Awal</a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="#">Logistik</a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="#">Kartu Stock</a>
            </li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="note note-success">
            <p>Kartu Stock</p>
        </div>

        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-reorder"></i>Parameter Laporan
                </div>
            </div>

            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form id="frmlaporan" class="form-horizontal form-bordered1" method="post">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Mulai</label>
                                    <div class="col-md-2">
                                        <input id="tanggal1" name="tanggal1" class="form-control" type="date"
                                            value="<?php echo date('Y-m-d');?>" placeholder="" />
                                    </div>
                                    <label class="col-md-2 control-label">s/d</label>
                                    <div class="col-md-2">
                                        <input id="tanggal2" name="tanggal2" class="form-control" type="date"
                                            value="<?php echo date('Y-m-d');?>" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Cabang</label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="cabang" id="cabang">
                                            <option value="<?= $this->session->userdata("unit") ?>" selected>
                                                <?= $cabang->namars ?></option>
                                        </select>
                                        <!--<select name="cabang" id="cabang" class="select2_el_cabang form-control"></select>	-->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Gudang</label>
                                    <div class="col-md-6">
                                        <select name="gudang" id="gudang" class="select2_el_farmasi_depo form-control"
                                            onchange="getgudang()"></select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Kode Barang</label>
                                    <div class="col-md-6">
                                        <select name="kodebarang" id="kodebarang"
                                            class="select2_el_log_baranggud form-control"></select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <br />
                                <center>
                                    <!-- <button class="btn btn-primary btn-sm" type="button"><i
                                            class="fa fa-desktop fa-fw"></i>&nbsp;Layar</button> -->
                                    <a class="btn btn-sm blue print_laporan" onclick="urltampil();"><i
                                            class="fa fa-desktop fa-fw"></i><b>
                                            LAYAR </b></a>
                                <button class="btn btn-danger btn-sm" type="button" onclick="cetak('pdf')"><i
                                            class="fa fa-file fa-fw"></i>&nbsp;PDF</button>
                                    <!-- <button class="btn btn-success btn-sm" type="button"><i class="fa fa-file fa-fw"
                                            onclick="exp()"></i>&nbsp;Excel</button> -->
                                    <a class="btn btn-sm green print_laporan" onclick="exp()"><i title=" CETAK PDF"
                                            class="fa fa-download"></i><b> EXCEL </b></a>
                                </center>
                                <br />
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php  
   $this->load->view('template/footer');  
   $this->load->view('template/v_report');
?>


<script>
$(window).on("load", function() {
    initailizeSelect2_log_baranggud(null);
});

function getgudang() {
    console.log($("#gudang").val());
    initailizeSelect2_log_baranggud($("#gudang").val());
}

function urltampil() {
    var baseurl = "<?php echo base_url()?>";
    var tanggal1 = $("#tanggal1").val();
    var tanggal2 = $("#tanggal2").val();
    var cabang = $("#cabang").val();
    var gudang = $("#gudang").val();
    var kodebarang = $("#kodebarang").val();
    var param = '?tanggal1=' + tanggal1 + '&tanggal2=' + tanggal2 + '&cabang=' + cabang + '&gudang=' + gudang +
        '&kodebarang=' + kodebarang;
    if (kodebarang == null || gudang == null || cabang == null) {
        swal({
            title: "PILIHAN",
            html: "Belum lengkap",
            type: "error",
            confirmButtonText: "OK"
        });
    } else {
        hasil = baseurl + 'Logistik_kartustock/tampil/' + param;
        window.open(hasil, '_blank');
    }


}

function exp() {
    var tanggal1 = document.getElementById('tanggal1').value;
    var tanggal2 = document.getElementById('tanggal2').value;
    var cabang = document.getElementById('cabang').value;
    var gudang = document.getElementById('gudang').value;
    var kodebarang = document.getElementById('kodebarang').value;
    location.href = '<?= site_url('Logistik_kartustock/excel/?tanggal1=')?>' + tanggal1 + '&tanggal2=' + tanggal2 +
        '&cabang=' + cabang + '&gudang=' + gudang + '&kodebarang=' + kodebarang;
}

function cetak(param) {
    var tanggal1 = $("#tanggal1").val();
    var tanggal2 = $("#tanggal2").val();
    var cabang = $("#cabang").val();
    var gudang = $("#gudang").val();
    var kodebarang = $("#kodebarang").val();
    if (kodebarang == null || gudang == null || cabang == null) {
        swal({
            title: "PILIHAN",
            html: "Belum lengkap",
            type: "error",
            confirmButtonText: "OK"
        });
    } else {
        if (param == "pdf") {
            window.open("/logistik_kartustock/cetak2/?barang=" + kodebarang + "&gudang=" + gudang + "&cabang=" + cabang +
                "&tgl1=" + tanggal1 + "&tgl2=" + tanggal2, "_blank");
        }
    }
}

// function _urlcetak(){
// 	var baseurl = "<?php echo base_url()?>";
// 	var barang = $('[name="kodebarang"]').val();			
// 	var gudang = $('[name="gudang"]').val();			
// 	var cbg  = $('[name="cabang"]').val();	
//     var tgl1 = $('[name="tanggal1"]').val();				
// 	var tgl2 = $('[name="tanggal2"]').val();	
// 	var param= '?barang='+barang+'&gudang='+gudang+'&cabang='+cbg+'&tgl1='+tgl1+'&tgl2='+tgl2;	

// 	if(barang==null || gudang==null || cbg==null){
// 	  swal('','Pilihan belum lengkap',''); 		  
// 	} else {	
//       hasil = baseurl+'farmasi_kartustock/cetak/'+param;
// 	  window.open(hasil,'_blank');
// 	}

// }
</script>
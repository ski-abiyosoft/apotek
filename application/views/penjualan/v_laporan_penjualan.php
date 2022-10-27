<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">
                &nbsp;<?php echo $this->session->userdata('unit'); ?>
            </span>
            -
            <span class="title-web">Farmasi <small>Laporan Penjualan Resep</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">

            <li>
                <i class="fa fa-home"></i>
                <a href="<?php echo base_url();?>dashboard">
                    Awal
                </a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="">
                    Farmasi
                </a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="<?php echo base_url();?>Laporan_penjualan">
                    Laporan Penjualan
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="note note-success">
            <p>Laporan - laporan untuk Penjualan Resep<br></p>
        </div>
        <br>
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-reorder"></i> Parameter Laporan
                </div>
            </div>
            <div class="portlet-body form">
                <form id="frmlaporan" class="form-horizontal form-bordered1" method="post">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Dari</label>
                                    <div class="col-md-9">
                                        <input id="dari" name="dari" class="form-control input-medium" type="date"
                                            value="<?php echo date('Y-m-d');?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Sampai</label>
                                    <div class="col-md-9">
                                        <input id="sampai" name="sampai" class="form-control input-medium" type="date"
                                            value="<?php echo date('Y-m-d');?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Dari Jam</label>
                                    <div class="col-md-9">
                                        <input id="dari_jam" name="dari_jam" class="form-control input-medium" type="time"
                                            value="<?php echo date('H:i');?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Sampai Jam</label>
                                    <div class="col-md-9">
                                        <input id="sampai_jam" name="sampai_jam" class="form-control input-medium" type="time"
                                            value="<?php echo date('H:i');?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Jenis</label>
                                    <div class="col-md-9">
                                        <table>
                                            <tr>
                                                <td align="center" width="10%">
                                                    <label for="label">
                                                        <input type="radio" name="jenis" value="3" id="jenis1">
                                                    </label>
                                                </td>
                                                <td>
                                                    <label for="label" style="margin-top:15px;">All</label>
                                                </td>
                                                <td align="center" width="10%">
                                                    <label for="label">
                                                        <input type="radio" name="jenis" value="1" id="jenis2">
                                                    </label>
                                                </td>
                                                <td>
                                                    <label for="label" style="margin-top:15px;">Rajal</label>
                                                </td>
                                                <td align="center" width="10%">
                                                    <label for="label">
                                                        <input type="radio" name="jenis" value="2" id="jenis3">
                                                    </label>
                                                </td>
                                                <td>
                                                    <label for="label" style="margin-top:15px;">Ranap</label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Store/depo</label>
                                    <div class="col-md-3">
                                        <select name="depo" id="depo" class="select2_depo form-control"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Laporan</label>
                                    <div class="col-md-3">
                                        <select name="laporan" id="laporan" class="select2_laporan form-control">
                                            <option value="1">01 LAPORAN PENJUALAN RESEP PERDOKTER</option>
                                            <option value="2">02 LAPORAN REKAP PENJUALAN RESEP</option>
                                            <option value="3">03 LAPORAN RINCIAN PENJUALAN RESEP</option>
                                            <option value="4">04 LAPORAN ANALISA PENJUALAN OBAT</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        &nbsp;
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <!-- <a class=" btn btn-sm red print_laporan  print_laporan" id="cetak" href="#report"
                                    data-toggle="modal">Cetak PDF</a> -->
                                <a class="btn btn-sm red " onclick="cetak()"><i title=" CETAK PDF"
                                    class="fa fa-print"></i><b> CETAK </b></a>
                                <a class="btn btn-sm green " onclick="exp()"><i title=" CETAK PDF"
                                        class="fa fa-download"></i><b> EXCEL </b></a>
                                <br />
                                <h4>
                                    <div class="err" id="resultss"></div>
                                </h4>
                                <div>
                                    <img id="proses" src="<?php echo base_url();?>assets/img/loading-spinner-blue.gif"
                                        class="img-responsive" style="visibility:hidden" />
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
$('.select2_laporan').select2();

// $('.print_laporan').on("click", function() {
//     var laporan = document.getElementById('laporan').value;
//     if (laporan != '') {
//         var dari = document.getElementById('dari').value;
//         var sampai = document.getElementById('sampai').value;
//         var jenis = $('input[name="jenis"]:checked').val();
//         var depo = document.getElementById('depo').value;
//         $('.modal-title').text('CETAK LAPORAN PENJUALAN');
//         //    console.log(jenis);
//         $("#simkeureport").html('<iframe src="<?php echo base_url();?>Laporan_penjualan/cetak?dari=' + dari +
//             '&sampai=' + sampai + '&jenis=' + jenis + '&depo=' + depo + '&laporan=' + laporan +
//             '" frameborder="no" width="100%" height="520"></iframe>');
//     }
// });


    function cetak() {
        var laporan = document.getElementById('laporan').value;
        if(laporan != ''){
            var dari = document.getElementById('dari').value;
            var sampai = document.getElementById('sampai').value;
            var dari_jam = document.getElementById('dari_jam').value;
            var sampai_jam = document.getElementById('sampai_jam').value;
            var jenis = $('input[name="jenis"]:checked').val();
            var depo = document.getElementById('depo').value;
            var baseurl = "<?php echo base_url() ?>";
            var urlnya = baseurl + 'Laporan_penjualan/cetak/?dari=' +dari + "&dari_jam="+dari_jam+ "&sampai_jam="+sampai_jam+'&sampai=' + sampai + '&jenis=' + jenis + '&depo=' + depo + '&laporan=' + laporan;
            window.open(urlnya, '_blank');
        }
    }

function exp() {
    var dari = document.getElementById('dari').value;
    var sampai = document.getElementById('sampai').value;
    var jenis = $('input[name="jenis"]:checked').val();
    var depo = document.getElementById('depo').value;
    var laporan = document.getElementById('laporan').value;
    location.href = '<?= site_url('Laporan_penjualan/excel/?dari=')?>' + dari + '&sampai=' + sampai +
        '&jenis=' + jenis + '&depo=' + depo + '&laporan=' + laporan;

}
</script>
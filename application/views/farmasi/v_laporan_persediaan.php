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
               <span class="title-web">Farmasi <small>Laporan Persediaan</small>
          </h3>
          <ul class="page-breadcrumb breadcrumb">

               <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo base_url(); ?>dashboard">
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
                    <a href="<?php echo base_url(); ?>Laporan_persediaan">
                         Laporan Persediaan
                    </a>
               </li>
          </ul>
     </div>
</div>
<div class="row">
     <div class="col-md-12">
          <div class="note note-success">
               <p>Laporan - laporan untuk persediaan<br></p>
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
                              <div class="row" style="margin-top:15px;">
                                   <div class="col-md-12">
                                        <div class="row" id="semua">
                                             <div class="col-md-3 control-label"></div>
                                             <div class="col-md-9 control-label">
                                                  <table>
                                                       <tr>
                                                            <td align="center" width="20%">
                                                                 <label for="label">
                                                                      <input class="form-control" type="checkbox" name="depoall" value="7" id="depoall" disabled>
                                                                 </label>
                                                            </td>
                                                            <td>
                                                                 <label for="label" style="text-decoration: line-through;">Semua Depo</label>
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
                                             <label class="col-md-3 control-label">Depo/Gudang</label>
                                             <div class="col-md-3">
                                                  <select name="depo" id="depo" class="select2_depo form-control" onchange="cekdepo()"></select>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="row">
                                   <div class="col-md-12">
                                        <div class="form-group">
                                             <label class="col-md-3 control-label">Dari</label>
                                             <div class="col-md-9">
                                                  <input id="dari" name="dari" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d'); ?>" />
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="row">
                                   <div class="col-md-12">
                                        <div class="form-group">
                                             <label class="col-md-3 control-label">Sampai</label>
                                             <div class="col-md-9">
                                                  <input id="sampai" name="sampai" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d'); ?>" />
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
                                                       <!-- <option value="1">01 Laporan Mutasi Barang</option>
                                                       <option value="2">02 Laporan Rekap Mutasi Barang</option> -->
                                                       <option value="1">01 Laporan Produksi Farmasi</option>
                                                       <option value="2">02 Laporan Stock Opname</option>
                                                       <!-- <option value="5">05 Laporan Permusnahan Barang</option> -->
                                                       <option value="3">03 Laporan Persediaan Barang</option>
                                                  </select>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="row">
                                   <div class="col-md-12">
                                        <div class="row">
                                             <div class="col-md-3 control-label"></div>
                                             <div class="col-md-9 control-label">
                                                  <table>
                                                       <tr>
                                                            <td align="center" width="20%">
                                                                 <label for="label">
                                                                      <input type="checkbox" name="keperluan" id="keperluan">
                                                                 </label>
                                                            </td>
                                                            <td>
                                                                 <label for="label">Keperluan SO</label>
                                                            </td>
                                                       </tr>
                                                  </table>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              &nbsp;
                              <div class="row">
                                   <div class="col-md-offset-3 col-md-9">
                                        <a class=" btn btn-sm red print_laporan" id="cetak" data-toggle="modal">Cetak PDF</a>
                                        <a class="btn btn-sm green" onclick="exp()"><i title=" CETAK PDF" class="fa fa-download"></i><b> EXCEL </b></a>
                                        <br />
                                        <h4>
                                             <div class="err" id="resultss"></div>
                                        </h4>
                                        <div>
                                             <img id="loader" src="<?php echo base_url(); ?>assets/img/loading-spinner-blue.gif" class="img-responsive" style="visibility:hidden" />
                                        </div>
                                   </div>
                              </div>
                    </form>
               </div>
          </div>
     </div>
</div>

<div class="modal fade loading"  role="dialog" aria-hidden="true"> 
    <div class="modal-dialog" style="margin-top: 350px;"> 
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
$this->load->view('template/footer');
$this->load->view('template/v_report');
?>

<script>
     $("#semua").hide();
     // function depoallx() {
     //      // if (document.getElementById('depoall').checked === true) {
     //      var cek = $("#depo").val();
     //      if (cek != '') {
     //           $("#depo").val("").change();
     //      }
     //      // }
     // }

     function cekdepo() {
          $("#depoall").prop("checked", false);
     }

     $('.select2_laporan').select2();

     $('.print_laporan').on("click", function() {
          
          
          $('.loading').modal('show');

          var laporan   = document.getElementById('laporan').value;
          var depo      = document.getElementById('depo').value;

          if (document.getElementById('keperluan').checked === true) {
               var keperluan = 1;
          } else {
               var keperluan = 0;
          }

          if (document.getElementById('depoall').checked === true) {
               var da = 1;
          } else {
               var da = 0;
          }

          if ((depo == '' || depo == null) && da == 0) {
               swal({
                    title: "DEPO",
                    html: " Depo Harus Dipilih",
                    type: "error",
                    confirmButtonText: "OK"
               });
          } else {
               if (laporan != '' && laporan != 5) {
                    var dari = document.getElementById('dari').value;
                    var sampai = document.getElementById('sampai').value;
                    $('#report').modal('show');
                    $('.modal-title').text('CETAK LAPORAN PENJUALAN');
                    $("#simkeureport").html('<iframe src="<?php echo base_url(); ?>Laporan_persediaan/cetak2?dari=' + dari + '&sampai=' + sampai + '&da=' + da + '&depo=' + depo + '&laporan=' + laporan + '&keperluan=' + keperluan + '&pdf=1' + '" frameborder="no" width="100%" height="520"></iframe>');
               } else if (laporan == 5){
                    swal({
                         title: "LAPORAN PEMUSNAHAN BARANG",
                         html: " Masih Belum di Pakai .!!!",
                         type: "info",
                         confirmButtonText: "OK"
                    });
               } else {
                    swal({
                         title: "LAPORAN",
                         html: " Tidak Boleh Kosong .!!!",
                         type: "error",
                         confirmButtonText: "OK"
                    });
               }
               $('.loading').modal('hide');
          }
     });

     function exp() {
          // var laporan = document.getElementById('laporan').value;
          // var dari = document.getElementById('dari').value;
          // var sampai = document.getElementById('sampai').value;
          // if (document.getElementById('depoall').checked === true) {
          //      var da = 1;
          // } else {
          //      var da = 0;
          // }
          // var depo = document.getElementById('depo').value;
          // if (laporan != '') {
          //      location.href = '<?= site_url('Laporan_persediaan/excel/?laporan=') ?>' + laporan + '&dari=' + dari + '&sampai=' + sampai + '&da=' + da + '&depo=' + depo;
          // } else {
          //      swal({
          //           title: "LAPORAN",
          //           html: " Tidak Boleh Kosong .!!!",
          //           type: "error",
          //           confirmButtonText: "OK"
          //      });
          // }

          var laporan   = document.getElementById('laporan').value;
          var depo      = document.getElementById('depo').value;
          var baseurl   = "<?php echo base_url() ?>";

          if (document.getElementById('keperluan').checked === true) {
               var keperluan = 1;
          } else {
               var keperluan = 0;
          }

          if (document.getElementById('depoall').checked === true) {
               var da = 1;
          } else {
               var da = 0;
          }

          if ((depo == '' || depo == null) && da == 0) {
               swal({
                    title: "DEPO",
                    html: " Depo Harus Dipilih",
                    type: "error",
                    confirmButtonText: "OK"
               });
          } else {
               if (laporan != '' && laporan != 5) {
                    var dari = document.getElementById('dari').value;
                    var sampai = document.getElementById('sampai').value;

                    var param = '?dari=' + dari + '&sampai=' + sampai + '&da=' + da + '&depo=' + depo + '&laporan=' + laporan + '&keperluan=' + keperluan + '&pdf=2';

                    url = baseurl + 'Laporan_persediaan/cetak2/' + param;
                    window.open(url, '');

                    // $("#simkeureport").html('<iframe src="<?php echo base_url(); ?>Laporan_persediaan/cetak?dari=' + dari + '&sampai=' + sampai + '&da=' + da + '&depo=' + depo + '&laporan=' + laporan + '&keperluan=' + keperluan + '&pdf=2'+'" frameborder="no" width="100%" height="520"></iframe>');
               } else if (laporan == 5){
                    swal({
                         title: "LAPORAN PEMUSNAHAN BARANG",
                         html: " Masih Belum di Pakai .!!!",
                         type: "info",
                         confirmButtonText: "OK"
                    });
               } else {
                    swal({
                         title: "LAPORAN",
                         html: " Tidak Boleh Kosong .!!!",
                         type: "error",
                         confirmButtonText: "OK"
                    });
               }
          }
     }
</script>
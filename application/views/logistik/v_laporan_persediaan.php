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
               <span class="title-web">Logistik <small>Laporan Persediaan</small>
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
                         Logistik
                    </a>
                    <i class="fa fa-angle-right"></i>
               </li>
               <li>
                    <a href="<?php echo base_url(); ?>Laporan_log">
                         Laporan Log
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
                                        <div class="row">
                                             <div class="col-md-3 control-label"></div>
                                             <div class="col-md-9 control-label">
                                                  <table>
                                                       <tr style='display:none;'>
                                                            <td align="center" width="20%">
                                                                 <label for="label">
                                                                      <input type="checkbox" name="depoall" value="7" id="depoall">
                                                                 </label>
                                                            </td>
                                                            <td>
                                                                 <label for="label">Semua Depo</label>
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
                                                       <option value="1">01 Laporan Mutasi Logistik</option>
                                                       <option value="2">02 Laporan Stock Opname</option>
                                                       <option value="3">03 Laporan Pemakaian Logistik</option>
                                                       <option value="4">04 Laporan Persediaan (Detail)</option>
                                                  </select>
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
                                             <img id="proses" src="<?php echo base_url(); ?>assets/img/loading-spinner-blue.gif" class="img-responsive" style="visibility:hidden" />
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
     function cekdepo() {
          $("#depoall").prop("checked", false);
     }

     $('.select2_laporan').select2();

     $('.print_laporan').on("click", function() {
          var laporan = document.getElementById('laporan').value;
          if (laporan != '') {
               var dari = document.getElementById('dari').value;
               var sampai = document.getElementById('sampai').value;
               if (document.getElementById('depoall').checked === true) {
                    var da = 1;
               } else {
                    var da = 0;
               }
               $('#report').modal('show');
               var depo = document.getElementById('depo').value;
               $('.modal-title').text('CETAK LAPORAN PENJUALAN');
               $("#simkeureport").html('<iframe src="<?php echo base_url(); ?>Laporan_log/cetak?dari=' + dari + '&sampai=' + sampai + '&da=' + da + '&depo=' + depo + '&laporan=' + laporan + '" frameborder="no" width="100%" height="520"></iframe>');
          } else {
               swal({
                    title: "LAPORAN",
                    html: " Tidak Boleh Kosong .!!!",
                    type: "error",
                    confirmButtonText: "OK"
               });
          }
     });

     function exp() {
          var laporan = document.getElementById('laporan').value;
          var dari = document.getElementById('dari').value;
          var sampai = document.getElementById('sampai').value;
          if (document.getElementById('depoall').checked === true) {
               var da = 1;
          } else {
               var da = 0;
          }
          var depo = document.getElementById('depo').value;
          if (laporan != '') {
               location.href = '<?= site_url('Laporan_log/excel/?laporan=') ?>' + laporan + '&dari=' + dari + '&sampai=' + sampai + '&da=' + da + '&depo=' + depo;
          } else {
               swal({
                    title: "LAPORAN",
                    html: " Tidak Boleh Kosong .!!!",
                    type: "error",
                    confirmButtonText: "OK"
               });
          }
     }
</script>
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
     <span class="title-web">KLINIK <small>Pendaftaran</small>
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
               Klinik
               </a>
               <i style="color:white;" class="fa fa-angle-right"></i>
          </li>
          <li>
               <a class="title-white" href="#">
               Pendaftaran
               </a>
          </li>
     </ul>
</div>
</div>
        

<div class="row">
<div class="col-md-12">
<div class="portlet">
     <div class="row"> <!-- header -->
          <div class="col-lg-4 col-md-3 col-sm-6 col-xs-12" style='margin-bottom:20px'>
               <div class="dashboard-stat yellow">
                    <div class="visual">
                         <i class="fa fa-barcodex"></i>
                    </div>
                    <div class="details">
                         <div class="number">
                              <?= $pasienrjtoday; ?> PASIEN
                         </div>
                         <div class="desc">
                              TOTAL PASIEN RJ HARI INI
                         </div>
                    </div>
                    <a data-toggle="tab" href="#tab2" class="more" onclick="tabrj()">
                         Lihat Detail<i class="m-icon-swapright m-icon-white"></i>
                    </a>
               </div>
          </div>
          <div class="col-lg-4 col-md-3 col-sm-6 col-xs-12" style='margin-bottom:20px'>
               <div class="dashboard-stat green">
                    <div class="visual">
                         <i class="fa fa-printx"></i>
                    </div>
                    <div class="details">
                         <div class="number">
                              <?= $pasienritoday; ?> PASIEN
                         </div>
                         <div class="desc">
                              TOTAL PASIEN RAWAT INAP
                         </div>
                    </div>
                    <a data-toggle="tab" class="more" href="#tab4" onclick="tabri()">
                         Lihat Detail<i class="m-icon-swapright m-icon-white"></i>
                    </a>
               </div>
          </div>
          <div class="col-lg-4 col-md-3 col-sm-6 col-xs-12" style='margin-bottom:20px'>
               <div class="dashboard-stat blue">
                    <div class="visual">
                         <i class="fa fa-shopping-cartx"></i>
                    </div>
                    <div class="details">
                         <div class="number">
                              <?= $pasienigdtoday; ?> PASIEN
                         </div>
                         <div class="desc">
                              TOTAL PASIEN IGD HARI INI
                         </div>
                    </div>
                    <a data-toggle="tab" href="#tab3" class="more" onclick="tabigd()">
                         Lihat Detail<i class="m-icon-swapright m-icon-white"></i>
                    </a>
               </div>
          </div>
     </div> <!-- end header -->
     <br>
     <div class="row">
          <div class="col-md-12">
               <div class="portlet box blue">
                    <div class="portlet-title">
                         <div class="caption">
                              <i class="fa fa-reorder"></i><b>Pendaftaran Pasien</b>
                         </div>
                         <div class="tools">
                              <a href="" class="collapse">
                              </a>						
                         </div>
                    </div>
                    <div class="portlet-body">	
                         <form id="frmpasien" class="form-horizontal" method="post">
                              <div class="form-body">
                                   <div class="tabbable tabbable-custom tabbable-full-width">
                                        <ul class="nav nav-tabs">
                                             <li class="active" id="reservasi">
                                                  <a href="#tab1" data-toggle="tab">
                                                       Reservasi
                                                  </a>
                                             </li>
                                             <li class="" id="rj">
                                                  <a href="#tab2" onclick="tab2()" data-toggle="tab">
                                                       Rawat Jalan
                                                  </a>
                                             </li>
                                             <li class="" id="igd">
                                                  <a href="#tab3" data-toggle="tab" onclick="tab3()">
                                                       IGD
                                                  </a>
                                             </li>
                                             <li class="" id="ri">
                                                  <a href="#tab4" data-toggle="tab" onclick="tab4()">
                                                       Rawat Inap
                                                  </a>
                                             </li>
                                             <li class="" id="rr">
                                                  <a href="#tab5" data-toggle="tab" onclick="">
                                                       Rencana Rawat
                                                  </a>
                                             </li>
                                        </ul>
                                        <div class="tab-content">
                                             <div class="tab-pane active" id="tab1">
                                             </div>

                                             <div class="tab-pane" id="tab2">
                                                  <div class="portlet-body">
                                                       <div class="table-toolbar">
                                                            <div class="btn-group"> 
                                                            <?php 
                                                            $cek =  $this->session->userdata('user_level'); 
                                                            if($cek==0){?> 
                                                            <?php }else{ ?>

                                                                 <a href="<?php echo base_url()?>PendaftaranVRS/entri_rj" class="btn btn-success">
                                                                      <i class="fa fa-plus"></i> Daftarkan Pasien
                                                                 </a>

                                                            <?php } ?>
                                                            </div>
                                                            <div class="btn-group pull-right" id="filter">
                                                                 <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
                                                                 </button>
                                                                 <ul class="dropdown-menu pull-right">
                                                                      <li>
                                                                           <a data-toggle="modal" href="#lupperiode">Filter Data</a>			
                                                                      </li>			
                                                                 </ul>
                                                            </div>
                                                       </div>
                                                       <div class="table-responsive">
                                                                 <table id="tableRJ" class="table table-striped table-bordered table-hover table-condensed table-scrollable" cellspacing="0" width="100%">
          
                                                                 <thead class="page-breadcrumb breadcrumb">
                                                                 <tr>
                                                                      <th class="title-white text-center" width="1%">No.</th>
                                                                      <th class="title-white text-center" width="5%">Cab.</th>
                                                                      <th class="title-white text-center" width="50">User ID</th>
                                                                      <th class="title-white text-center" width="5%">No. Antri</th>
                                                                      <th class="title-white text-center">No. Reg</th>
                                                                      <th class="title-white text-center">No. RM</th>
                                                                      <th class="title-white text-center">Tgl. Masuk</th>
                                                                      <th class="title-white text-center">Nama Pasien</th>
                                                                      <th class="title-white text-center">Tujuan</th>
                                                                      <th class="title-white text-center">Dokter</th>
                                                                      <th class="title-white text-center">Jenis Bayar</th>
                                                                      <th class="title-white" style="text-align: center;width:10%;">No. Kartu</th>
                                                                      <th class="title-white text-center">Status</th>
                                                                      <th class="title-white text-center">Aksi</th>
                                                                 </tr>
                                                                 </thead>
                                                                 <tbody>
                                                                 </tbody>
                                                            </table>
                                                       </div>
                                                  </div>
                                             </div>
                                             <div class="tab-pane" id="tab3">
                                                  <div class="portlet-body">
                                                       <div class="table-toolbar">
                                                            <div class="btn-group">
                                                            <a href="<?php echo base_url()?>PendaftaranVRS/entri_igd" class="btn btn-success">
                                                                 <i class="fa fa-plus"></i> Daftarkan Pasien
                                                            </a>
                                                            </div>
                                                            <div class="btn-group pull-right" id="filter">
                                                                 <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
                                                                 </button>
                                                                 <ul class="dropdown-menu pull-right">
                                                                      <li>
                                                                           <a data-toggle="modal" href="#lupperiodeIGD">Filter Data</a>
                                                                      </li>			
                                                                 </ul>
                                                            </div>
                                                       </div>
                                                       <div class="table-responsive">
                                                            <table id="tableIGD" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                                                 <thead class="page-breadcrumb breadcrumb">
                                                                      <tr>
                                                                           <th class="title-white text-center" width="1%">No.</th>
                                                                           <th class="title-white text-center" width="5%">Cab.</th>
                                                                           <th class="title-white text-center" width="50">User ID</th>
                                                                           <th class="title-white text-center" width="5%">No. Antri</th>
                                                                           <th class="title-white text-center">No. Reg</th>
                                                                           <th class="title-white text-center">No. RM</th>
                                                                           <th class="title-white text-center">Tgl. Masuk</th>
                                                                           <th class="title-white text-center">Nama Pasien</th>
                                                                           <th class="title-white text-center">Tujuan</th>
                                                                           <th class="title-white text-center">Dokter</th>
                                                                           <th class="title-white text-center">Jenis Bayar</th>
                                                                           <th class="title-white" style="text-align: center;width:10%;">No. Kartu</th>
                                                                           <th class="title-white text-center">Status</th>
                                                                           <th class="title-white text-center">Aksi</th>
                                                                      </tr>
                                                                 </thead>
                                                                 <tbody>
                                                                 </tbody>
                                                            </table>
                                                       </div>
                                                  </div>
                                             </div>
                                             <div class="tab-pane" id="tab4">
                                                  <div class="portlet-body">
                                                       <div class="table-toolbar">
                                                            <div class="btn-group">
                                                            <a href="<?php echo base_url()?>PendaftaranVRS/entri_ri" class="btn btn-success">
                                                                 <i class="fa fa-plus"></i> Daftarkan Pasien
                                                            </a>
                                                            </div>
                                                            <div class="btn-group pull-right" id="filter">
                                                                 <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
                                                                 </button>
                                                                 <ul class="dropdown-menu pull-right">
                                                                      <li>
                                                                           <a data-toggle="modal" href="#lupperiodeRI">Filter Data</a>
                                                                      </li>			
                                                                 </ul>
                                                            </div>
                                                       </div>
                                                       <div class="table-responsive">
                                                            <table id="tableRI" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                                                 <thead class="page-breadcrumb breadcrumb">
                                                                      <tr>
                                                                           <th class="title-white text-center" width="1%">No.</th>
                                                                           <th class="title-white text-center" width="5%">Cab.</th>
                                                                           <th class="title-white text-center" width="50">User ID</th>
                                                                           <th class="title-white text-center" width="5%">No. Antri</th>
                                                                           <th class="title-white text-center">No. Reg</th>
                                                                           <th class="title-white text-center">No. RM</th>
                                                                           <th class="title-white text-center">Tgl. Masuk</th>
                                                                           <th class="title-white text-center">Nama Pasien</th>
                                                                           <th class="title-white text-center">Tujuan</th>
                                                                           <th class="title-white text-center">Dokter</th>
                                                                           <th class="title-white text-center">Jenis Bayar</th>
                                                                           <th class="title-white" style="text-align: center;width:10%;">No. Kartu</th>
                                                                           <th class="title-white text-center">Status</th>
                                                                           <th class="title-white text-center">Aksi</th>
                                                                      </tr>
                                                                 </thead>
                                                                 <tbody>
                                                                 </tbody>
                                                            </table>
                                                       </div>
                                                  </div>
                                             </div>
                                             <div class="tab-pane" id="tab5">
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </form>
                    </div>
               </div>
          </div>
     </div>			
</div>
</div>
</div>

<!-- <?php var_dump($data_pasien) ?> -->

<!-- filter RJ -->
<div class="modal fade" id="lupperiode" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-small">
     <div class="modal-content">
          <span id="nopilih">
          <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
               <h4 class="modal-title">Periode Data</h4>	
          </div>
          <div class="modal-body">										 		  
               <form action="#" class="form-horizontal">
                    <div class="form-group">
                         <label class="col-md-4 control-label">Mulai</label>
                         <div class="col-md-6">
                         <input id="tanggal1" name="tanggal1" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d');?>" />
                         
                         </div>
                    </div>
                    <div class="form-group">
                         <label class="col-md-4 control-label">s/d</label>
                         <div class="col-md-6">
                         <input id="tanggal2" name="tanggal2" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d');?>" />
                         
                         </div>
                    </div>											
               </form>
          </div>   
          <div class="modal-footer">
               <p align="center">
               <button type="button" id="btnfilter" class="btn green" onclick="filterdataRJ()" data-dismiss="modal">Buka Data</button>
               </p>		 			
          </div>											
     </div>									
</div>								
</div>

<!-- filter IGD -->
<div class="modal fade" id="lupperiodeIGD" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-small">
     <div class="modal-content">
          <span id="nopilih">
          <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
               <h4 class="modal-title">Periode Data</h4>	
          </div>
          <div class="modal-body">										 		  
               <form action="#" class="form-horizontal">
                    <div class="form-group">
                         <label class="col-md-4 control-label">Mulai</label>
                         <div class="col-md-6">
                         <input id="tanggal1igd" name="tanggal1" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d');?>" />
                         
                         </div>
                    </div>
                    <div class="form-group">
                         <label class="col-md-4 control-label">s/d</label>
                         <div class="col-md-6">
                         <input id="tanggal2igd" name="tanggal2" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d');?>" />
                         
                         </div>
                    </div>											
               </form>
          </div>   
          <div class="modal-footer">
               <p align="center">
               <button type="button" id="btnfilter" class="btn green" onclick="filterdataIGD()" data-dismiss="modal">Buka Data</button>
               </p>		 			
          </div>											
     </div>									
</div>								
</div>

<!-- filter RI -->
<div class="modal fade" id="lupperiodeRI" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-small">
     <div class="modal-content">
          <span id="nopilih">
          <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
               <h4 class="modal-title">Periode Data</h4>	
          </div>
          <div class="modal-body">										 		  
               <form action="#" class="form-horizontal">
                    <div class="form-group">
                         <label class="col-md-4 control-label">Mulai</label>
                         <div class="col-md-6">
                         <input id="tanggal1ri" name="tanggal1" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d');?>" />
                         </div>
                    </div>
                    <div class="form-group">
                         <label class="col-md-4 control-label">s/d</label>
                         <div class="col-md-6">
                         <input id="tanggal2ri" name="tanggal2" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d');?>" />
                         
                         </div>
                    </div>											
               </form>
          </div>   
          <div class="modal-footer">
               <p align="center">
               <button type="button" id="btnfilter" class="btn green" onclick="filterdataRI()" data-dismiss="modal">Buka Data</button>
               </p>		 			
          </div>											
     </div>									
</div>								
</div>

<?php
$this->load->view('template/footer_tb');
?>


<script type="text/javascript">

// $('#table_IGD').DataTable({
//     "lengthMenu": [
//           [5, 15, 20, -1],
//           [5, 15, 20, "Semua"]
//     ],
//     "oLanguage": {
//         "sEmptyTable": "<div class='text-center'>Data Kosong</div>",
//         "sInfoEmpty": "",
//         "sInfoFiltered": " - Dipilih dari _MAX_ data",
//         "sSearch": "Pencarian Data : ",
//         "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
//         "sLengthMenu": "_MENU_ Baris",
//         "sZeroRecords": "<div class='text-center'>Tida ada data</div>",
//         "oPaginate": {
//             "sPrevious": "Sebelumnya",
//             "sNext": "Berikutnya"
//         }
//     },
// });

//igd

var tableIGD;

function tabigd(){
$('#reservasi').removeClass('active');
$('#rr').removeClass('active');
$('#rj').removeClass('active');
$('#ri').removeClass('active');
$('#igd').addClass('active');
$('#lupperiode').modal('hide');
tab3();
}
function tab3(){


tableIGD = $('#tableIGD').DataTable({ 
     destroy: true,
     "processing": true,
     "responsive":true,
     "serverSide": true,
     "order": [],
     "ajax": {
          "url": "<?php echo site_url('pendaftaranVRS/igd/1')?>",
          "type": "POST"
     },
     "scrollCollapse": false,
     "paging":true,
     "oLanguage": {
          "sEmptyTable": "<div class='text-center'>Data Kosong</div>",
          "sInfoEmpty": "",
          "sInfoFiltered": " - Dipilih dari _TOTAL_ data",
          "sSearch": "Pencarian Data : ",
          "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
          "sLengthMenu": "_MENU_ Baris",
          "sZeroRecords": "<div class='text-center'>Tida ada data</div>",
          "oPaginate": {
          "sPrevious": "Sebelumnya",
          "sNext": "Berikutnya"
          }
     },		
     "aLengthMenu": [
          [5, 15, 20, -1],
          [5, 15, 20, "Semua"]
     ],
     "columnDefs": [
          { 
               "targets": [ -1 ], //last column
               "orderable": false, //set not orderable
          },
     ],
});


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
//     tableIGD.clear();
}

function filterdataIGD(){
var tgl1 = document.getElementById("tanggal1igd").value;
var tgl2 = document.getElementById("tanggal2igd").value;
var id   = 2; 
var str  = id+'~'+tgl1+'~'+tgl2; 
tableIGD.ajax.url("<?php echo base_url('pendaftaranVRS/igd/')?>"+str).load();		 
}

//rawat jalan

var tableRJ;

function tabrj(){
$('#reservasi').removeClass('active');
$('#rr').removeClass('active');
$('#rj').addClass('active');
$('#ri').removeClass('active');
$('#igd').removeClass('active');
$('#lupperiodeIGD').modal('hide');
tab2();
}

function tab2(){

tableRJ = $('#tableRJ').DataTable({ 
     destroy: true,
     "processing": true,
     "responsive":true,
     "serverSide": true,
     "order": [],
     "ajax": {
          "url": "<?php echo site_url('pendaftaranVRS/rawatjalan/1')?>",
          "type": "POST"
     },
     "scrollCollapse": false,
     "paging":true,
     "oLanguage": {
          "sEmptyTable": "<div class='text-center'>Data Kosong</div>",
          "sInfoEmpty": "",
          "sInfoFiltered": " - Dipilih dari _TOTAL_ data",
          "sSearch": "Pencarian Data : ",
          "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
          "sLengthMenu": "_MENU_ Baris",
          "sZeroRecords": "<div class='text-center'>Tida ada data</div>",
          "oPaginate": {
          "sPrevious": "Sebelumnya",
          "sNext": "Berikutnya"
          }
     },		
     "aLengthMenu": [
          [5, 15, 20, -1],
          [5, 15, 20, "Semua"]
     ],
     "columnDefs": [
          { 
               "targets": [ -1 ], //last column
               "orderable": false, //set not orderable
          },
     ],
});

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
// tableRJ.clear();
}

function filterdataRJ(){
var tgl1 = document.getElementById("tanggal1").value;
var tgl2 = document.getElementById("tanggal2").value;
var id   = 2; 
var str  = id+'~'+tgl1+'~'+tgl2; 
tableRJ.ajax.url("<?php echo base_url('pendaftaranVRS/rawatjalan/')?>"+str).load();		 
}

// table RI
var tableRI;

function tabri(){
$('#reservasi').removeClass('active');
$('#rr').removeClass('active');
$('#rj').removeClass('active');
$('#ri').addClass('active');
$('#igd').removeClass('active');
$('#lupperiode').modal('hide');
tab4();
}


function tab4(){


tableRI = $('#tableRI').DataTable({ 
     destroy: true,
     "processing": true,
     "responsive":true,
     "serverSide": true,
     "order": [],
     "ajax": {
          "url": "<?php echo site_url('pendaftaranVRS/ri/1')?>",
          "type": "POST"
     },
     "scrollCollapse": false,
     "paging":true,
     "oLanguage": {
          "sEmptyTable": "<div class='text-center'>Data Kosong</div>",
          "sInfoEmpty": "",
          "sInfoFiltered": " - Dipilih dari _TOTAL_ data",
          "sSearch": "Pencarian Data : ",
          "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
          "sLengthMenu": "_MENU_ Baris",
          "sZeroRecords": "<div class='text-center'>Tida ada data</div>",
          "oPaginate": {
          "sPrevious": "Sebelumnya",
          "sNext": "Berikutnya"
          }
     },		
     "aLengthMenu": [
          [5, 15, 20, -1],
          [5, 15, 20, "Semua"]
     ],
     "columnDefs": [
          { 
               "targets": [ -1 ], //last column
               "orderable": false, //set not orderable
          },
     ],
});


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
//     tableIGD.clear();
}

function filterdataRI(){
var tgl1 = document.getElementById("tanggal1ri").value;
var tgl2 = document.getElementById("tanggal2ri").value;
var id   = 2; 
var str  = id+'~'+tgl1+'~'+tgl2; 
tableRI.ajax.url("<?php echo base_url('pendaftaranVRS/ri/')?>"+str).load();		 
}

function Batalkan(id,tab) {	
swal({
     //title: 'PENDAFTARAN',
     text: "Alasan Dibatalkan : ",
     type: 'info',
     input: 'text',
     showCancelButton: true,
     confirmButtonClass: 'btn btn-success',
     cancelButtonClass: 'btn btn-danger m-l-10',
     confirmButtonText: 'Ya, Batalkan',     
     cancelButtonText: 'Tidak',     
}).then(function (alasan) {			
     $.ajax({
          type : 'POST',
          dataType : "json",
          data : {alasan : alasan},
          url : '<?php echo base_url()?>PendaftaranVRS/pembatalan/'+id,
          success:function(response){
               if(response.status == 1) {
                    swal(
                         'PEMBATALAN!',
                         'Berhasil dilakukan',
                         'success'
                    )
               } else {
                    swal({
                         title: "PEMBATALAN",
                         html: "Gagal dilakukan !<br>Pasien sudah melakukan rekam medis",
                         type: "error"
                    })
               }
               if(tab=='igd'){
                    reload_tableigd();

               }else if(tab=='rajal'){
                    reload_tablerj();
               }else{
                    reload_tableri();
               }
          }
     });
});
}


function reload_tablerj()
{
     tableRJ.ajax.reload(null,false); //reload datatable ajax 
}

function reload_tableri()
{
     tableRJ.ajax.reload(null,false); //reload datatable ajax 
}

function reload_tableigd()
{
     tableRJ.ajax.reload(null,false); //reload datatable ajax 
}

function send_wa(id, hp){	 
swal({
     title: 'KIRIM WhatsApp',
     text: "Nomor HP : "+hp,
     type: 'info',
     
     showCancelButton: true,
     confirmButtonClass: 'btn btn-success',
     cancelButtonClass: 'btn btn-danger m-l-10',
     confirmButtonText: 'Ya, Kirim',     
     cancelButtonText: 'Tidak',     
}).then(function () {			
     $.ajax({
          type : 'POST',
          dataType : "json",
          data : {id : id},
          url : '<?php echo base_url()?>pendaftaranVRS/send_wa',
          success:function(response){
               if(response.status == 1) {
                    swal(
                         'Sending!',
                         'Kirim WhatsApp berhasil.',
                         'success'
                    )
               } else {
                    swal(
                         'Failed!',
                         'Kirim WhatsApp gagal',
                         'failed'
                    )
               }
               //reload_table();
          }
     });
});   
}

function send_email(id, email){	 
swal({
     title: 'KIRIM Email',
     text: "Alamat : "+email,
     type: 'info',
     
     showCancelButton: true,
     confirmButtonClass: 'btn btn-success',
     cancelButtonClass: 'btn btn-danger m-l-10',
     confirmButtonText: 'Ya, Kirim',     
     cancelButtonText: 'Tidak',     
}).then(function () {			
     $.ajax({
          type : 'POST',
          dataType : "json",
          data : {id : id},
          url : '<?php echo base_url()?>pendaftaran/send_email',
          success:function(response){
               if(response.status == 1) {
                    swal(
                         'Sending!',
                         'Kirim Email berhasil.',
                         'success'
                    )
               } else {
                    swal(
                         'Failed!',
                         'Kirim Email gagal',
                         'failed'
                    )
               }
               //reload_table();
          }
     });
});   
}	

</script>

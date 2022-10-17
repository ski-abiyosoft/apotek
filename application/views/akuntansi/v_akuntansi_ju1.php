    <?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>	

	<link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css-')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css')?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url('assets/plugins/bootstrap-modal/css/bootstrap-modal.css')?>" rel="stylesheet" type="text/css"/>
	
	<style>	
	div.tableContainer {
		clear: both;
		border: 0px solid #963;
		height: 150px;
		overflow: auto;
		width: 100%
	}

	html>body div.tableContainer {
		overflow: hidden;
		width: 100%
	}


	div.tableContainer table {
		float: left;	
	}


	html>body div.tableContainer table {

	}


	thead.fixedHeader tr {
		position: relative;
	}

	thead.fixedHeader th {
		background: #C96;
		border-left: 1px solid #EB8;
		border-right: 1px solid #B74;
		border-top: 1px solid #EB8;	
		padding: 4px 3px;
		text-align: left
	}

	html>body tbody.scrollContent {
		display: block;
		height: 150px;
		overflow: auto;
		width: 100%
	}

	html>body thead.fixedHeader {
		display: table;
		overflow: auto;
		width: 100%
	}


	tbody.scrollContent td, tbody.scrollContent tr.normalRow td {
		background: #FFF;
		border-bottom: 0px solid #CCC;
		border-left: 0px solid #CCC;
		border-right: 0px solid #CCC;
		border-top: 0px solid #DDD;
		padding: 2px 3px 3px 4px
	}


	tbody.scrollContent tr.alternateRow td {
		background: #EEE;
		border-bottom: none;
		border-left: none;
		border-right: 1px solid #CCC;
		border-top: 1px solid #DDD;
		padding: 2px 3px 3px 4px
	}

	</style>	
	
    <!--div class="page-content-wrapper">
		<div class="page-content"-->
			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
						<span class="title-unit">
                            &nbsp;<?php echo $this->session->userdata('unit'); ?> 
                        </span>
                      - 
                      <span class="title-web">Buku Besar <small>Daftar Jurnal</small>
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
							<a href="#">
                               Buku Besar
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="<?php echo base_url();?>akuntansi_ju">
                               Daftar Jurnal
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
								Daftar Jurnal
							</div>

						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								<div class="btn-group">
									<!--button id="master-bank_new" class="btn green">
									Data Baru <i class="fa fa-plus"></i>
									</button-->
									
									
								</div>
								<button class="btn btn-success" onclick="add_data()"><i class="glyphicon glyphicon-plus"></i> Data Baru</button>
                                <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Refresh</button>
								<div class="btn-group pull-right">
									<button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu pull-right">
										
										<li>											
											<a data-toggle="modal" href="#lupperiode">Ganti Periode Data</a>										
										</li>	
									</ul>
								</div>
							</div>
							<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                               <thead>
                                     <tr>
                                         <th style="text-align: center; width:10%">Nomor</th>
                                         <th style="text-align: center; width:10%">No. Transaksi</th>
                                         <th style="text-align: center; width:10%">Tanggal</th>
										 <th style="text-align: center; width:15%">Keterangan</th>
										 <th style="text-align: center; width:15%">Debet</th>
										 <th style="text-align: center; width:15%">Kredit</th>
										 <th style="text-align: center; width:10%">Tipe Transaksi</th>
                                         <th style="text-align: center;width:16%;">&nbsp</th>

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

<?php
  $this->load->view('template/footero');
  $this->load->view('template/v_report');
  $this->load->view('template/v_periode'); 
?>
	
<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
<script src="<?php echo base_url('assets/scripts/custom/components-pickers.js')?>"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-modal/js/bootstrap-modalmanager.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-modal/js/bootstrap-modal.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/scripts/core/app.js')?>"></script>
<script src="<?php echo base_url('assets/scripts/custom/ui-extended-modals.js')?>"></script>


<script type="text/javascript">
var save_method; //for save method string
var sfilter;
var table;
var table2;

$(document).ready(function() {

   
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('akuntansi_ju/ajax_list')?>",
		    "type": "POST"
        },
		
		"oLanguage": {
                    "sEmptyTable": "Tidak ada data",
                    "sInfoEmpty": "Tidak ada data",
                    "sInfoFiltered": " - Dipilih dari _MAX_ data",
                    "sSearch": "Pencarian Data : ",
                    "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
                    "sLengthMenu": "_MENU_ Baris",
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
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],

    });
	
	
	var nomorbukti = $('[name="nomorbukti"]').val();
	
	table2 = $('#table2').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('akuntansi_ju/ajax_list_entry/')?>"+nomorbukti,
		    "type": "POST"
        },
		
		"oLanguage": {
                    "sEmptyTable": "Tidak ada data",
                    "sInfoEmpty": "Tidak ada data",
                    "sInfoFiltered": " - Dipilih dari _MAX_ data",
                    "sSearch": "Pencarian Data : ",
                    "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
                    "sLengthMenu": "_MENU_ Baris",
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
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],

    });

    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,  
    });

    //set input/textarea/select event when change value, remove class error and remove text help block 
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



function add_data()
{
    save_method = 'add';
    $('#frmentry')[0].reset(); // reset form on modals
	getnomor();
	filterdata_entry();
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('*Data Baru Jurnal'); // Set Title to Bootstrap modal title
}

function add_data2()
{
    save_method = 'add';
    $('#frmentry')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form_entry').modal('show'); // show bootstrap modal
    $('.modal-title').text('*Data Baru Jurnal'); // Set Title to Bootstrap modal title
}

function edit_data(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('akuntansi_sa/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="kodeakun"]').val(data.kodeakun);
            $('[name="debet"]').val(data.debet);
            $('[name="kredit"]').val(data.kredit);
            
            //$('[name="dob"]').datepicker('update',data.dob);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Data'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function reload_table2()
{
    table2.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    //if(save_method == 'add') {
        url = "<?php echo site_url('akuntansi_ju/ajax_add_header')?>";
    //} else {
    //    url = "<?php echo site_url('akuntansi_ju/ajax_update')?>";
   // }

	
    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#frmentryh').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                //$('#modal_form').modal('hide');
				getnomor();
                filterdata_entry();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('Simpan'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}

function formatCurrency(num) {
	num = num.toString().replace(/\$|\,/g,'');
	if(isNaN(num))
	num = "0";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10)
	cents = "0" + cents;
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
	num = num.substring(0,num.length-(4*i+3))+'.'+
	num.substring(num.length-(4*i+3));
	//return (((sign)?'':'-') + '' + num + '.' + cents);
	return (((sign)?'':'-') + '' + num);
}

function delete_data(id)
{
    if(confirm('Yakin data Jurnal dengan ID '+id+' ini akan dihapus ?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('akuntansi_ju/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

function delete_data2(id)
{
    if(confirm('Yakin data Jurnal dengan ID '+id+' ini akan dihapus ?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('akuntansi_ju/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
               // $('#modal_form').modal('hide');
                reload_table2();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

$(document).ready(function() {
		
		$('.print_laporan').on("click", function(){
		$('.modal-title').text('BUKU BESAR');
		var no_daftar= this.id;
		$("#simkeureport").html('<iframe src="<?php echo base_url();?>akuntansi_sa/cetak/'+no_daftar+'" frameborder="no" width="100%" height="420"></iframe>');
		});	
	});
	
	
function filterdata()
{
	var tgl1 = document.getElementById("tanggal1").value;
	var tgl2 = document.getElementById("tanggal2").value;
	var str  = tgl1+'~'+tgl2; 
	table.ajax.url("<?php echo base_url('akuntansi_ju/ajax_list2/')?>"+str).load();	
}

function filterdata_entry()
{
	var nomor = document.getElementById("nomorbukti").value;
	var str  = nomor; 
	alert(nomor);
	table2.ajax.url("<?php echo base_url('akuntansi_ju/ajax_list_entry/')?>"+str).load();	
}	
	
jQuery(document).ready(function() {
        ComponentsPickers.init();
        UIExtendedModals.init();		
});

function showentry() {
  var nomor = $('[name="nomorbukti"]').val();
  
  if(nomor==''){
	alert('nomor Bukti kosong ...');  
  } else {
  var str = nomor;  
  var xhttp;
  if (str == "") {
    document.getElementById("_hasilentry").innerHTML = "";
    return;
  }
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    document.getElementById("_hasilentry").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "<?php echo base_url(); ?>akuntansi_ju/getentry/"+str, true);  
  xhttp.send();
  }
  gettotal();
}

function delete_data(id)
{
    if(confirm('Yakin Akun ini akan dihapus ?'))
    {
        $.ajax({
            url : "<?php echo site_url('akuntansi_ju/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {                
             	showentry();			
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

function getakun(str) { 
  var xhttp;      
	$.ajax({
        url : "<?php echo base_url();?>akuntansi_ju/getakun/"+str,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {					
		    $('[name="namaakun"]').val(data.namaakun);												
		}
	});	    
}

function getnomor() { 
  var str = 'JU';
  var xhttp;      
	$.ajax({
        url : "<?php echo base_url();?>akuntansi_ju/getnomor/"+str,
        type: "GET",
        dataType: "JSON",
		
        success: function(data)
        {					
		    $('[name="nomorbukti"]').val(data.kdtr+'-'+data.cdno);												
		}
	});	    
}

function gettotal() { 
  var str = $('[name="nomorbukti"]').val();
  var xhttp;      
	$.ajax({
        url : "<?php echo base_url();?>akuntansi_ju/gettotal/"+str,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {						
		    $('[name="tdebet"]').text(formatCurrency(data.debet));												
			$('[name="tkredit"]').text(formatCurrency(data.kredit));
            
			var tot = parseInt(data.debet)+parseInt(data.kredit);
			
			if (data.debet==data.kredit  && tot>0 ){
			  $('#btnSave').attr('disabled',false); 		
			} else {
			  $('#btnSave').attr('disabled',true); 				  
			}
			
			if (tot>0){			  
			  $('#btnBatal').attr('disabled',false); 
			} else {			  
			  $('#btnBatal').attr('disabled',true); 
			}
		}
	});	    
}

function resetentry(){
	$('[name="kodeakun"]').val('');						
	$('[name="namaakun"]').val('');						
	$('[name="debet"]').val(0);							
	$('[name="kredit"]').val(0);							
	$('[name="kodeakun"]').focus();
	
}

function saveentry()
{ 
    var url;
    url = "<?php echo site_url('akuntansi_ju/ajax_add')?>";    
    $.ajax({
        url : url,
        type: "POST",
        data: $('#frmentry').serialize(),
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {
				reload_table2();
                showentry();
				resetentry();
            }
            else
            {               
				
            }
          
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
           

        }
    });
}

function batalsi()
{
	var id = $('[name="nomorbukti"]').val();
    if(confirm('Yakin Jurnal '+id+' ini akan dibatalkan ?'))
    {
        $.ajax({
            url : "<?php echo site_url('akuntansi_ju/ajax_delete_all')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {                               
				showentry();
				gettotal();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}
   
showentry();

   
</script>

<!-- Bootstrap modal -->SS
<div  id="modal_form" role="dialog" class="modal container fade" tabindex="-1">
    <div class="modal-dialog-full">
        <div class="modal-content">
		 <div class="portlet box blue">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-reorder"></i>*Data Baru Jurnal
				</div>
				<div class="tools">											
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				
			</div>
			<div class="portlet-body form">
            <!--div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Entry Jurnal</h3>
            </div-->
            <div class="modal-body form">
                <form action="#" id="frmentryh" class="form-horizontal" method="post">
                    <div class="form-body">
                        
						<div class="row">
						    <div class="col-md-6">
								 <div class="form-group">
									<label class="col-md-3 control-label">Tanggal</label>
									<div class="col-md-6">
									   <div class="input-icon">
										<i class="fa fa-calendar"></i>
										<input id="tanggal" name="tanggal" class="form-control date-picker input-medium" type="text" value="<?php echo $tanggal;?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="" onkeypress="return tabE(this,event)"/>
									   </div>
									</div>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-3 control-label">Nomor Bukti</label>
									<div class="col-md-6">
										<input type="text" class="form-control" placeholder="" name="nomorbukti" id="nomorbukti" value="<?php echo $nojurnal;?>" readonly>
									</div>

								</div>
							</div>
							<!--/span-->
							
						</div>
						
						
                        <div class="row">
													
							<div class="col-md-6">
								 <div class="form-group">
									<label class="col-md-3 control-label">Tipe Transaksi</label>
									<div class="col-md-6">
									  <select id="jenis" name="jenis" class="bs-select form-control input-medium" data-show-subtext="true" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">            												  															  
									   <?php 
										foreach($jenis->result_array() as $row){?>
										<option data-subtext="<?php echo $row['jurnal_nama'];?>" value="<?php echo $row['jurnal_kode'];?>"><?php echo $row['jurnal_kode'];?></option>
									   <?php } ?>                                                            
									</select>
									</div>

								</div>
							</div>

							<div class="col-md-6">
								 <div class="form-group">
									<label class="col-md-3 control-label">Keterangan</label>
									<div class="col-md-9">														        
										<input type="text" class="form-control" placeholder="" name="ket" value="" id="ket" >
									</div>

								</div>
							</div>
							
						</div>
						
						<div class="row">
												 <div class="col-md-12">
                                                   	<div class="table-responsive" >
							                    	<table class="table table-borderedx table-condensed">
								                    <theads>
                                                      <tr>
                    								
                    									<th width="20%" style="text-align: center">Kode Akun</th>														
                    									<th width="40%" style="text-align: center">Uraian</th>
                    									<th width="20%" style="text-align: center">Debet</th>
                    									<th width="20%" style="text-align: center">Kredit</th>
                    								</tr>
                    								</theads>
													</table>
													

													<div class="bodycontainer scrollable">
													<table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
													
													
                    								<tbody>
													<tr>
													
														<td width="20%">
														  <div class="input-group">														        
                                                                <input name="akun[]"  id="akun0"  required maxlength="20" onkeyup="showakunname(this.value, this.id)" style="color:blue;"  type="text" class="form-control " size="100%" onkeypress="return tabE(this,event)">																																																
																<span class="input-group-btn">
																	<a class="btn default" id="0" data-toggle="modal" href="#lupakun" onclick="getid(this.id)"><i class="fa fa-search"></i></a>
																</span>
																
														   										                
														  </div>
														  <p class="help-block"><span style="color:green" id="namaakun0"></span></p>	
														</td>
																									                                                       
                                                        <td width="40%" >
														    <input name="ket[]"    id="ket1" type="text" class="form-control " size="100%" onkeypress="return tabE(this,event)">															
														</td>
                                                        <td width="20%" ><input name="debet[]"  id="debet1"; type="text" class="form-control rightJustified" size="40%" value="0" onChange="total();formatCurrency(this)" onkeypress="return tabE(this,event);formatCurrency(this)"></td>
                                                        <td width="20%" ><input name="kredit[]" id="kredit1" type="text" class="form-control rightJustified" size="40%" value="0" onChange="total();formatCurrency(this)" onkeypress="return tabE(this,event);formatCurrency(this)"></td>
								                      </tr>
                    								
								                    </tbody>
													</table>
													</div>
													<table class="table table-condensed">
								                    <tfoot>
                                                      <tr>
													    <td width="15%"><button type="button" onclick="tambah()" class="btn default"><i class="fa fa-plus"></i> </button>
												        <button type="button" onclick="hapus()" class="btn default"><i class="fa fa-times"></i></button></td>
                                                        <td width="40%" align="center"><font color="red"><b><span id="_selisih"></b></font></span></td>
                                                        
                                                        <td width="20%"  align="right"><font color="red"><b><span id="_jumdebet"></b></font></span></td>
														<td width="20%"  align="right"><font color="red"><b><span id="_jumkredit"></b></font></span></td>
														                                                        
														
                                                      </tr>
                                                     </tfoot>
								                    </table>
								                    </div>
								                   </div>
												</div>
												
						<div class="row">
							 <div class="col-md-12">
                                                   
							<!--table class="table table-striped table-hover table-bordered">
							  <tr>                    								
								<th width="10%" style="text-align: center">Akun Perkiraan</th>														
								<th width="20%" style="text-align: center">Nama Perkiraan</th>
								<th width="10%" style="text-align: center">Debet</th>
								<th width="10%" style="text-align: center">Kredit</th>
								<th width="2%" style="text-align: center">Action</th>
								<th width="32%" style="text-align: center"></th>
							</tr>
							</table>
							<div id="_hasilentry"></div-->
							<div class="portlet box- blue-">
							<!--div class="portlet-title">
								<div class="caption">
									<i class="fa fa-reorder"></i>Data Jurnal
								</div>
								
							</div-->
							<div class="portlet-body form">
							<div class="table-toolbar">
								<div class="btn-group">
									<!--button id="master-bank_new" class="btn green">
									Data Baru <i class="fa fa-plus"></i>
									</button-->
									
									
								</div>								
								<a class="btn btn-success" data-target="#stack1" data-toggle="modal"><i class="glyphicon glyphicon-plus"></i>Data Baru</a>	
								<a class="btn btn-success" data-target="" onclick="getnomor();filterdata_entry();"><i class="glyphicon glyphicon-plus"></i>Refresh</a>	
                                
							</div>
							<table id="table2" class="table table-striped table-bordered" cellspacing="0" width="100%">
                               <thead>
                                     <tr>
                                         <th style="text-align: center; width:5%">Akun Perkiraan</th>
                                         <th style="text-align: center; width:49%">Nama Perkiraan</th>
                                         <th style="text-align: center; width:15%">Debet</th>
										 <th style="text-align: center; width:15%">Kredit</th>										 
                                         <th style="text-align: center;width:16%;">&nbsp</th>

                                     </tr>
                                </thead>
                                <tbody>
                                </tbody>
							</table>
							</div>
                            </div>
							
							<!--table class="table table-bordereds">
							  <tr>
							    <td colspan="2" style="text-align:right">TOTAL</td>
								<td width="10%" style="font-size:16px;text-align:right"><b><span name="tdebet"></span></b></td>
								<td width="10%" style="font-size:16px;text-align:right"><b><span name="tkredit"></span></b></td>								
							  </tr>
							</thead>  
							  <tr>
							
								<td width="10%">
									<select name="kodeakun" class="form-control select2me"  onchange="getakun(this.value)">            											
									  <option value="">-- Kode Akun ---</option>
									  <?php 
										foreach($kodeakun  as $row){?>
										<option value="<?php echo $row->kodeakun;?>"><?php echo $row->kodeakun.' - '.$row->namaakun;?></option>
									  <?php } ?>
									</select>								                
								
								</td>
																																   
								<td width="20%" >
									<input name="namaakun" type="text" class="form-control" readonly>															
								</td>
								<td width="10%" ><input name="debet"  style="text-align:right" type="text" class="form-control rightJustified" value="0" onchange="totalline()" onkeypress="return tabE(this,event);totalline()" ></td>
								<td width="10%" ><input name="kredit"  style="text-align:right" type="text" class="form-control rightJustified" value="0" onchange="totalline()" onkeypress="return tabE(this,event);totalline()" ></td>																
								<td width="2%" ><a class="btn btn-sm btn-success" onclick="saveentry();"><i class="glyphicon glyphicon-plus"></i></a></td>
								<td width="32%" ></td>								
							  </tr>
							</table>
							</div-->							                   
						</div>
											
						
						
												
						
                        
                    </div>
                </form>
            </div>
											
            <!--div class="modal-footer"-->
			<div class="form-actions">
			   <p align="center">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Simpan</button>
                <button type="button" id="btnBatal" class="btn btn-danger" onclick="batalsi()">Batal</button>				
			   </p>	
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
   </div><!-- /.modal-dialog --> 
  </div><!-- /.modal-dialog -->  
</div><!-- /.modal -->


<div id="stack1" class="modal fade" tabindex="-1" data-focus-on="input:first">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title">Item Jurnal</h4>
	</div>
	<div class="modal-body">
	  <form action="#" id="frmentry" class="form-horizontal" method="post">
		<table class="table">
		    <tr>
			  <td>Kode Akun</td>
			  <td>:</td>
			  <td>
			    <select name="kodeakun" class="form-control select2me input-large"  onchange="getakun(this.value)">            											
				  <option value="">-- Kode Akun ---</option>
				  <?php 
					foreach($kodeakun  as $row){?>
					<option value="<?php echo $row->kodeakun;?>"><?php echo $row->kodeakun.' - '.$row->namaakun;?></option>
				  <?php } ?>
				</select>	
			  </td>
			</tr>
			<tr>
			  <td>Debet</td>
			  <td>:</td>
			  <td><input type="text" name="debet" class="form-control input-medium" value="0"></td>
			</tr>
			<tr>
			  <td>Kredit</td>
			  <td>:</td>
			  <td><input type="text" name="kredit" class="form-control input-medium" value="0"></td>
			</tr>
		    
			
		</table>						
	   </form>	    			
		
			 
		
							
	</div>
	<div class="modal-footer">
	   <p align="center">
		<button type="button" data-dismiss="modal" class="btn btn-default">Tutup</button>
		<button type="button" class="btn btn-primary" onclick="saveentry();">Simpan</button>
	   </p>	
	</div>
</div>




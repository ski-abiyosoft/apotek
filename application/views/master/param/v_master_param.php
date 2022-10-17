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
                      <span class="title-web">Master <small>Konfigurasi Aplikasi</small>
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
								Konfigurasi Aplikasi
							</a>

						</li>

					</ul>
				</div>
			</div>

			<div class="row profile">
				<div class="col-md-12">
					<div class="tabbable tabbable-custom tabbable-full-width">
						<ul class="nav nav-tabs">
                       <li class="active">
							<!--a href="#tab_1_1" data-toggle="tab">
                                   Beranda
							</a-->
							</li>
							<li class="active">
								<a href="#tab_1_3" data-toggle="tab">
                                   Konfigurasi Aplikasi
								</a>
							</li>

						</ul>
						<div class="tab-content">
                        <div class="tab-pane active" id="tab_1_1">
								<div class="row">
									
								</div>
							</div>
							<div class="tab-pane active" id="tab_1_3">
								<div class="row profile-account">
									<div class="col-md-3">
										<ul class="ver-inline-menu tabbable margin-bottom-10">
											<li class="active">
												<a data-toggle="tab" href="#tab_1-1">
													<i class="fa fa-angle-double-right"></i> Identitas Perusahaan
												</a>
												<span class="after">
												</span>
											</li>
											
											<li>
												<a data-toggle="tab" href="#tab_3-3">
													<i class="fa fa-angle-double-right"></i> Periode Data
												</a>
											</li>
											<li>
												<a data-toggle="tab" href="#tab_4-4">
													<i class="fa fa-angle-double-right"></i> Akun Referensi
												</a>
											</li>
											<li>
												<a data-toggle="tab" href="#tab_5-5">
													<i class="fa fa-angle-double-right"></i> Nomor Urut Transaksi
												</a>
											</li>
											<li>
												<a data-toggle="tab" href="#tab_6-6">
													<i class="fa fa-angle-double-right"></i> Nomor Urut MR
												</a>
											</li>

										</ul>
									</div>
									<div class="col-md-9">
										<div class="tab-content">
											<div id="tab_1-1" class="tab-pane active">
												<form id="frmidusaha" role="form" action="#">
												    <div class="form-group">
														<label class="control-label">Cabang </label>
														
													</div>
													<div class="form-group">
														<label class="control-label">Nama </label>
														<input type="text" placeholder="" class="form-control" value="" id="_nama" name="_nama"/>
													</div>
													<div class="form-group">
														<label class="control-label">Nomor Telepon</label>
														<input type="text" placeholder="" class="form-control" value="" id="_telpon" name="_telpon"/>
													</div>
													
													

													<div class="margiv-top-10">
														<a href="#" class="btn green" id="btnsimpanid">
															 Simpan
														</a>
														<a href="#" class="btn default">
															 Batal
														</a>
													</div>
													<h4><span id="error1" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success1" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
												</form>
											</div>
											


											<div id="tab_3-3" class="tab-pane">
                                                	<form id="frmperiode" role="form" action="#">
													<h3 class="form-section">Klinik & Apotek</h3>
													<div class="row">
													<div class="col-md-4">
													<div class="form-group">
														<label class="control-label">Periode Transaksi Klinik </label>
														<input type="date" placeholder="" class="form-control input-medium" value="" id="_periodeklinik" name="_periodeklinik" />														
													</div>
													</div>
													<div class="col-md-4">
													<div class="form-group">
														<label class="control-label">Periode Transaksi Apotek </label>
														<input type="date" placeholder="" class="form-control input-medium" value="" id="_periodeapotek" name="_periodeapotek" />														
													</div>
													</div>
													</div>
													
													<h3 class="form-section">Keuangan</h3>
													<div class="row">
													<div class="col-md-4">
													<div class="form-group">
														<label class="control-label">Awal Periode </label>
														<input type="date" placeholder="" class="form-control input-medium" value="" id="_periodekeu1" name="_periodekeu1" />														
													</div>
													</div>
													<div class="col-md-4">
													<div class="form-group">
														<label class="control-label">Periode yang Aktif </label>
														<input type="date" placeholder="" class="form-control input-medium" value="" id="_periodekeu1" name="_periodekeu1" />														
													</div>
													</div>
													</div>

													<div class="margiv-top-10">
														<a href="#" class="btn green" id="btnsimpanperiode">
															 Simpan
														</a>
														<a href="#" class="btn default">
															 Batal
														</a>
													</div>
													<h4><span id="error3" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success3" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
												    </form>
											</div>
                                            <div id="tab_4-4" class="tab-pane">
                                                	<form id="frmakun" role="form" action="#">
													<div class="row">
														<div class="form-group">
															<label class="col-md-3 control-label">Akun L/R Berjalan </label>
															<select name="_akunlrberjalan" id="_akunlrberjalan" class="select2_el form-control input-xlarge" >
															 <option value=""></option>
														   </select>
															
														</div>
													</div>
													<div class="row">
														<div class="form-group">
															<label class="col-md-3 control-label">Akun L/R Lalu </label>
															<select class="select2_el form-control input-xlarge" name="_akunlrlalu" id="_akunlrlalu">
																<option value=""></option> 														  
															</select>
														</div>
													</div>
													
													
													
													<div class="margiv-top-10">
														<a href="#" class="btn green" id="btnsimpanakun">
															 Simpan
														</a>
														<a href="#" class="btn default">
															 Batal
														</a>
													</div>
													<h4><span id="error4" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success4" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
												    </form>
											</div>
											
											<div id="tab_5-5" class="tab-pane">
                                                	<form id="frmnomor" role="form" action="#">
													<div class="portlet">
													<div class="portlet-title">
														<div class="caption">
															No. Urut Transaksi
														</div>

													</div>
													<div class="portlet-body">
													<div class="table-toolbar">
														<div class="btn-group">
														
															
														</div>
														<button class="btn btn-success" onclick="add_data()"><i class="glyphicon glyphicon-plus"></i> Data Baru</button>
														<div class="btn-group pull-right">	
															<label>Cabang : </label>
															<select class="form-control input-large select2_el_cabang" id="cabang" name="cabang" onchange="getnomorcabang()">
															  <?php 
			                                                      $cabang = $this->session->userdata('unit');
                                                                  if($cabang=="") $cabang = 'DPS';																  
																  if($cabang){
																  $datacabang = data_master("tbl_namers", array("koders" => $cabang));
															  } ?>
															  <option value="<?= $cabang?>"><?= $datacabang->namars;?></option>
															</select>
															</div>
													</div>
													  <table id="table" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
														   <thead class="breadcrumb header-custom-">
																 <tr>
																	 <th style="text-align: center">Cabang</th>
																	 <th style="text-align: center">Kode</th>
																	 <th style="text-align: center">Keterangan</th>
																	 <th style="text-align: center">Param1</th>
																	 <th style="text-align: center">Param2</th>
																	 <th style="text-align: center">Param3</th>
																	 <th style="text-align: center">Nourut</th>
																	 <th style="text-align: center;width:15;">Aksi</th>

																 </tr>
															</thead>
															<tbody>
															</tbody>
														</table>
													  </div>
													  </div>
												    </form>
											</div>
											
											<div id="tab_6-6" class="tab-pane">
                                                	<form id="frmnomor_mr" role="form" action="#">
													<div class="portlet">
													<div class="portlet-title">
														<div class="caption">
															No. Urut MR
														</div>

													</div>
													<div class="portlet-body">
													<div class="table-toolbar">
														<div class="btn-group">
														
															
														</div>
														
													</div>
													<div class="row">
													<div class="col-md-5">
													  <table id="tablemr_nomor" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
														   <thead class="breadcrumb header-custom-">
																 <tr>
																	 <th style="text-align: center">Abjad</th>
																	 <th style="text-align: center">Urut</th>
																	 <th style="text-align: center;width:5;">Aksi</th>

																 </tr>
															</thead>
															<tbody>
															</tbody>
														</table>
													 </div>
													 <div class="col-md-7">
														<table id="tablemr_pasien" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
														   <thead class="breadcrumb header-custom">
																 <tr>
																	 <th style="text-align: center">Member ID</th>
																	 <th style="text-align: center">Nama Pasien</th>																	 

																 </tr>
															</thead>
															<tbody>
															</tbody>
														</table>
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
				</div>
			</div>
		</div>
	</div>
</div>

<?php
  $this->load->view('template/footer_tb');    
?>
	
<script>

$('#btnsimpanid').on("click", function(){
		   
		   $.ajax({				
        		url:'<?php echo site_url('master_param/update/1')?>',				
        		data:$('#frmidusaha').serialize(),				
        		type:'POST',
        		success:function(data){        		
				alert('Data Berhasil Disimpan ...');								
        		},
        		error:function(data){
					
        			alert('Data Gagal Disimpan ...');
        		}
        		});
		});	
		
		$('#btnsimpanbos').on("click", function(){
		   $.ajax({				
        		url:'<?php echo site_url('master_param/update/2')?>',				
        		data:$('#frmbos').serialize(),				
        		type:'POST',
        		success:function(data){        		
				alert('Data Berhasil Disimpan ...');								
        		},
        		error:function(data){
					
        			alert('Data Gagal Disimpan ...');
        		}
        		});
		});	
		
		$('#btnsimpanperiode').on("click", function(){
		   $.ajax({				
        		url:'<?php echo site_url('master_param/update/3')?>',				
        		data:$('#frmperiode').serialize(),				
        		type:'POST',
        		success:function(data){        		
				alert('Data Berhasil Disimpan ...');								
        		},
        		error:function(data){
					
        			alert('Data Gagal Disimpan ...');
        		}
        		});
		});	
		$('#btnsimpanakun').on("click", function(){
		   
		   $.ajax({				
        		url:'<?php echo site_url('master_param/update/4')?>',				
        		data:$('#frmakun').serialize(),				
        		type:'POST',
        		success:function(data){        		
				alert('Data Berhasil Disimpan ...');								
        		},
        		error:function(data){
					
        			alert('Data Gagal Disimpan ...');
        		}
        		});
		});	
		$('#btnsimpannomor').on("click", function(){
		   $.ajax({				
        		url:'<?php echo site_url('master_param/update/5')?>',				
        		data:$('#frmnomor').serialize(),				
        		type:'POST',
        		success:function(data){        		
				alert('Data Berhasil Disimpan ...');								
        		},
        		error:function(data){
					
        			alert('Data Gagal Disimpan ...');
        		}
        		});
		});	

</script>
</body>
</html>

<script type="text/javascript">
var save_method; //for save method string
var table;
var tablemr_nomor;
var tablemr_pasien;

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('master_nourut/ajax_list/DPS')?>",
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


     tablemr_nomor = $('#tablemr_nomor').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('master_nomr/ajax_list')?>",
            "type": "POST"
        },
		
		"oLanguage": {
                    "sEmptyTable": "Tidak ada data",
                    "sInfoEmpty": "Tidak ada data",
                    "sInfoFiltered": " - _MAX_",
                    "sSearch": "",
                    "sInfo": "  _TOTAL_ (_START_ - _END_)",
                    "sLengthMenu": "_MENU_ ",
                    "sZeroRecords": "Tida ada data",
                    "oPaginate": {
                        "sPrevious": "<",
                        "sNext": ">"
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
	
	tablemr_pasien = $('#tablemr_pasien').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('master_nomr_pasien/ajax_list/A')?>",
            "type": "POST"
        },
		
		"oLanguage": {
                    "sEmptyTable": "Tidak ada data",
                    "sInfoEmpty": "Tidak ada data",
                    "sInfoFiltered": " -  _MAX_ data",
                    "sSearch": "",
                    "sInfo": "  _TOTAL_  (_START_ - _END_)",
                    "sLengthMenu": "_MENU_ ",
                    "sZeroRecords": "Tida ada data",
                    "oPaginate": {
                        "sPrevious": "<",
                        "sNext": ">"
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
    $('#form')[0].reset(); // reset form on modals
	var cabang = $('#cabang').val();
	$('#cabangfe').val(cabang);    
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title
}

function edit_data(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $.ajax({
        url : "<?php echo site_url('master_nourut/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
		    $('[name="id"]').val(data.id);
			$('[name="cabang"]').val(data.koders);
            $('[name="kode"]').val(data.kode_urut);
            $('[name="nama"]').val(data.hedket);
			$('[name="param1"]').val(data.param1);
			$('[name="param2"]').val(data.param2);
			$('[name="param3"]').val(data.param3);
			$('[name="nourut"]').val(data.nourut);
			$('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Data'); // Set title to Bootstrap modal title
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function edit_data_nomr(id)
{
    save_method = 'update';
    $('#form_mr')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $.ajax({
        url : "<?php echo site_url('master_nomr/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
		    $('[name="idmr"]').val(data.mrkey);
			$('[name="kodemr"]').val(data.mrkey);
            $('[name="nourutmr"]').val(data.urut);
			$('#modal_form_mr').modal('show'); // show bootstrap modal when complete loaded
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

function reload_table_mr()
{
    tablemr_nomor.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('master_nourut/ajax_add')?>";
    } else {
        url = "<?php echo site_url('master_nourut/ajax_update')?>";
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                reload_table();
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

function save_mr()
{
    var url;

    url = "<?php echo site_url('master_nomr/ajax_update')?>";
    

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_mr').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form_mr').modal('hide');
                reload_table_mr();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            
        }
    });
}

function getnomorcabang(){
	var cabang = $('#cabang').val();	
	table.ajax.url("<?php echo base_url('master_nourut/ajax_list/')?>"+cabang).load();
}

function detil_data( kode ){	    
	tablemr_pasien.ajax.url("<?php echo base_url('master_nomr_pasien/ajax_list/')?>"+kode).load();
}


</script>

<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header header-custom">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Bank</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
					    <div class="form-group">
                            <label class="control-label col-md-3">Cabang</label>
                            <div class="col-md-9">
                                <input id="cabangfe" name="cabang" placeholder="" class="form-control" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Kode</label>
                            <div class="col-md-9">
                                <input name="kode" placeholder="Kode" class="form-control" type="text" maxlength="20">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Keterangan</label>
                            <div class="col-md-9">
                                <input name="nama" placeholder="Uraian" class="form-control" type="text" maxlength="50">
                                <span class="help-block"></span>
                            </div>
                        </div>	
                        <div class="form-group">
                            <label class="control-label col-md-3">Param1</label>
                            <div class="col-md-9">
                                <input name="param1" placeholder="" class="form-control" type="text" maxlength="10">
                                <span class="help-block"></span>
                            </div>
                        </div>	
                         <div class="form-group">
                            <label class="control-label col-md-3">Param2</label>
                            <div class="col-md-9">
                                <input name="param2" placeholder="" class="form-control" type="text" maxlength="10">
                                <span class="help-block"></span>
                            </div>
                        </div>	
                         <div class="form-group">
                            <label class="control-label col-md-3">Param3</label>
                            <div class="col-md-9">
                                <input name="param3" placeholder="" class="form-control" type="text" maxlength="10">
                                <span class="help-block"></span>
                            </div>
                        </div>	
                         <div class="form-group">
                            <label class="control-label col-md-3">No. Urut</label>
                            <div class="col-md-9">
                                <input name="nourut" placeholder="" class="form-control" type="number">
                                <span class="help-block"></span>
                            </div>
                        </div>							
                        
                        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="modal_form_mr" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header header-custom">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Urut</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_mr" class="form-horizontal">
                    <input type="hidden" value="" name="idmr"/> 
                    <div class="form-body">
					   
                        <div class="form-group">
                            <label class="control-label col-md-3">Kode</label>
                            <div class="col-md-9">
                                <input name="kodemr" placeholder="Kode" class="form-control" type="text" maxlength="20" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
                       
                         <div class="form-group">
                            <label class="control-label col-md-3">No. Urut</label>
                            <div class="col-md-9">
                                <input name="nourutmr" placeholder="" class="form-control" type="number">
                                <span class="help-block"></span>
                            </div>
                        </div>							
                        
                        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave_mr" onclick="save_mr()" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
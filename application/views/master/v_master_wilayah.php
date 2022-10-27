    <?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>	
	<style>
	  
      .select2-container {
       z-index: 99999;
      }
   
	</style>

	
			<div class="row">
			    
				<div class="col-md-12">
					<h3 class="page-title">
                    <span class="title-unit">
                            &nbsp;<?php echo $this->session->userdata('unit'); ?> 
                        </span>
                      - 
                      <span class="title-web">Master <small>Wilayah</small>
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
                               Master
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="#">
                               Wilayah
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								Provinsi
							</div>

						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								<div class="btn-group">
								
									
								</div>
								<button class="btn btn-success" onclick="add_provinsi()"><i class="glyphicon glyphicon-plus"></i> Data Baru</button>
                               
							</div>
							<table id="table_provinsi" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                               <thead class="breadcrumb header-custom">
                                     <tr>
                                        <th style="text-align: center">Kode</th>
                                        <th style="text-align: center">Nama</th>
                                        <th style="text-align: center;width:5;">Aksi</th>
                                     </tr>
                                </thead>
                                <tbody>
                                </tbody>
							</table>
						</div>
					</div>
				</div>
				
				<div class="col-md-3">
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								Kabupaten/Kota
							</div>

						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								<div class="btn-group">
								
									
								</div>
								<button class="btn btn-success" onclick="add_kota()"><i class="glyphicon glyphicon-plus"></i> Data Baru</button>
                               
							</div>
							<table id="table_kota" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                               <thead class="breadcrumb header-custom">
                                     <tr>
                                        <th style="text-align: center;width:10%">Kode</th>
                                        <th style="text-align: center;width:20%">Nama</th>
                                        <th style="text-align: center;width:20%;">Aksi</th>
                                     </tr>
                                </thead>
                                <tbody>
                                </tbody>
							</table>
						</div>
					</div>
				</div>
				
				<div class="col-md-3">
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								Kecamatan
							</div>

						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								<div class="btn-group">
								
									
								</div>
								<button class="btn btn-success" onclick="add_kecamatan()"><i class="glyphicon glyphicon-plus"></i> Data Baru</button>
                               
							</div>
							<table id="table_kecamatan" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                               <thead class="breadcrumb header-custom">
                                     <tr>
                                        <th style="text-align: center;width:10%">Kode</th>
                                        <th style="text-align: center;width:40%">Nama</th>
                                        <th style="text-align: center;width:20;">Aksi</th>
                                     </tr>
                                </thead>
                                <tbody>
                                </tbody>
							</table>
						</div>
					</div>
				</div>
				
				<div class="col-md-3">
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								Desa/Kelurahan
							</div>

						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								<div class="btn-group">
								
									
								</div>
								<button class="btn btn-success" onclick="add_desa()"><i class="glyphicon glyphicon-plus"></i> Data Baru</button>
                               
							</div>
							
							<table id="table_desa" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                               <thead class="breadcrumb header-custom">
                                     <tr>
                                        <th style="text-align: center;width:10%">Kode</th>
                                        <th style="text-align: center;width:20%">Nama</th>
                                        <th style="text-align: center;width:20%;">Aksi</th>
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
  $this->load->view('template/footer_tb');
  $this->load->view('template/v_report');
?>
	

<script type="text/javascript">
var save_method; //for save method string
var table;
var table_kota;
var table_kecamatan;
var table_desa;

$(document).ready(function() {

    //datatables
    table = $('#table_provinsi').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('master_wilayah/provinsi_list')?>",
            "type": "POST"
        },
		
		"oLanguage": {
                    "sEmptyTable": "Tidak ada data",
                    "sInfoEmpty": "Tidak ada data",
                    "sInfoFiltered": " - Dipilih dari _MAX_ data",
                    "sSearch": "",
                    "sInfo": " _TOTAL_ (_START_ - _END_)",
                    "sLengthMenu": "_MENU_ Baris",
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
	
	table_kota = $('#table_kota').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('master_wilayah/kota_list')?>",
            "type": "POST"
        },
		
		"oLanguage": {
                    "sEmptyTable": "Tidak ada data",
                    "sInfoEmpty": "Tidak ada data",
                    "sInfoFiltered": " - Dipilih dari _MAX_ data",
                    "sSearch": "",
                    "sInfo": " _TOTAL_ (_START_ - _END_)",
                    "sLengthMenu": "_MENU_ Baris",
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
	
	table_kecamatan = $('#table_kecamatan').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('master_wilayah/kecamatan_list')?>",
            "type": "POST"
        },
		
		"oLanguage": {
                    "sEmptyTable": "Tidak ada data",
                    "sInfoEmpty": "Tidak ada data",
                    "sInfoFiltered": " - Dipilih dari _MAX_ data",
                    "sSearch": "",
                    "sInfo": " _TOTAL_ (_START_ - _END_)",
                    "sLengthMenu": "_MENU_ Baris",
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
	
	table_desa = $('#table_desa').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('master_wilayah/desa_list')?>",
            "type": "POST"
        },
		
		"oLanguage": {
                    "sEmptyTable": "Tidak ada data",
                    "sInfoEmpty": "Tidak ada data",
                    "sInfoFiltered": " - Dipilih dari _MAX_ data",
                    "sSearch": "",
                    "sInfo": " _TOTAL_ (_START_ - _END_)",
                    "sLengthMenu": "_MENU_ Baris",
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



function add_provinsi()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Data Provinsi'); // Set Title to Bootstrap modal title
}

function add_kota()
{
    save_method = 'add';
	$('#form_kota')[0].reset(); 
	var selectElement = document.getElementById('kode_prop');
	var opt = document.createElement('option');			
	var id_prov =  $('#id_provinsi_pilih').val();	
	opt.value = id_prov;
	opt.innerHTML = $('#nama_provinsi_pilih').val();	
	opt.selected;
	selectElement.appendChild(opt);
	
	$('[name="kode_prop"]').val(id_prov);
			
    
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form_kota').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Data Kabupaten/Kota'); // Set Title to Bootstrap modal title
}

function add_kecamatan()
{
    save_method = 'add';
	$('#form_kecamatan')[0].reset(); 
	var selectElement = document.getElementById('kode_kota_kec');
	var opt = document.createElement('option');			
	var id_kota =  $('#id_kota_pilih').val();	
	opt.value = id_kota;
	opt.innerHTML = $('#nama_kota_pilih').val();	
	opt.selected;
	selectElement.appendChild(opt);
	
	$('[name="kode_kota_kec"]').val(id_kota);
			
    
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form_kecamatan').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Data Kecamatan'); // Set Title to Bootstrap modal title
}

function add_desa()
{
    save_method = 'add';
	$('#form_desa')[0].reset(); 
	var selectElement = document.getElementById('kode_kecamatan_desa');
	var opt = document.createElement('option');			
	var id_kec =  $('#id_kecamatan_pilih').val();	
	opt.value = id_kec;
	opt.innerHTML = $('#nama_kecamatan_pilih').val();	
	opt.selected;
	selectElement.appendChild(opt);
	
	$('[name="kode_kecamatan_desa"]').val(id_kec);
			
    
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form_desa').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Data Desa'); // Set Title to Bootstrap modal title
}


function edit_data(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('master_wilayah/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

		    $('[name="id"]').val(data.id);
            $('[name="kode"]').val(data.kodeprop);
            $('[name="nama"]').val(data.namaprop);
            
            //$('[name="dob"]').datepicker('update',data.dob);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Data Provinsi'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function edit_data_kota(id)
{
    save_method = 'update';
    $('#form_kota')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('master_wilayah/ajax_edit_kota/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {            
		    $('[name="id_kota"]').val(data.id);			
			var selectElement = document.getElementById('kode_prop');
			var opt = document.createElement('option');			
			opt.value = data.kodeprop;
			opt.innerHTML = data.namaprop;
			opt.selected;
			selectElement.appendChild(opt);
			
			$('#id_provinsi_pilih').val(data.kodeprop);	
			$('#nama_provinsi_pilih').val(data.namaprop);	
			
			$('[name="kode_prop"]').val(data.kodeprop);
            $('[name="kode_kota"]').val(data.kodekab);
            $('[name="nama_kota"]').val(data.namakab);
            
            $('#modal_form_kota').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Data Kabupaten/Kota'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function edit_data_kecamatan(id)
{
    save_method = 'update';
    $('#form_kecamatan')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('master_wilayah/ajax_edit_kecamatan/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {            
		    $('[name="id_kecamatan"]').val(data.id);			
			var selectElement = document.getElementById('kode_kota_kec');
			var opt = document.createElement('option');			
			opt.value = data.kodekab;
			opt.innerHTML = data.namakab;
			opt.selected;
			selectElement.appendChild(opt);
			
			$('#id_kota_pilih').val(data.kodekab);	
			$('#nama_kota_pilih').val(data.namakab);	
			
			$('[name="kode_kota_kec"]').val(data.kodekab);
			$('[name="kode_kecamatan"]').val(data.kodekec);
            $('[name="nama_kecamatan"]').val(data.namakec);
            
            $('#modal_form_kecamatan').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Data Kecamatan'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function edit_data_desa(id)
{
    save_method = 'update';
    $('#form_desa')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('master_wilayah/ajax_edit_desa/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {            
		    $('[name="id_desa"]').val(data.id);						
			$('#form_desa')[0].reset(); 
			var selectElement = document.getElementById('kode_kecamatan_desa');
			var opt = document.createElement('option');			
			var id_kec =  data.kodekec;	
			opt.value = id_kec;
			opt.innerHTML = data.namakec;	
			opt.selected;
			selectElement.appendChild(opt);
			
			$('id_kecamatan_pilih').val(data.kodekec);
			$('nama_kecamatan_pilih').val(data.namakec);
			
			
			$('[name="kode_kecamatan_desa"]').val(id_kec);	
			$('[name="kode_desa"]').val(data.kodedesa);
            $('[name="nama_desa"]').val(data.namadesa);
            
            $('#modal_form_desa').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Data Desa'); // Set title to Bootstrap modal title

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

function reload_table_kota()
{
    table_kota.ajax.reload(null,false); //reload datatable ajax 
}

function reload_table_kecamatan()
{
    table_kecamatan.ajax.reload(null,false); //reload datatable ajax 
}

function reload_table_desa()
{
    table_desa.ajax.reload(null,false); //reload datatable ajax 
}


function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('master_wilayah/ajax_add')?>";
    } else {
        url = "<?php echo site_url('master_wilayah/ajax_update')?>";
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

function save_kota()
{
    $('#btnSave_kota').text('saving...'); //change button text
    $('#btnSave_kota').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('master_wilayah/ajax_add_kota')?>";
    } else {
        url = "<?php echo site_url('master_wilayah/ajax_update_kota')?>";
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_kota').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form_kota').modal('hide');
                reload_table_kota();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave_kota').text('Simpan'); //change button text
            $('#btnSave_kota').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave_kota').text('save'); //change button text
            $('#btnSave_kota').attr('disabled',false); //set button enable 

        }
    });
}

function save_kecamatan()
{
    $('#btnSave_kecamatan').text('saving...'); //change button text
    $('#btnSave_kecamatan').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('master_wilayah/ajax_add_kecamatan')?>";
    } else {
        url = "<?php echo site_url('master_wilayah/ajax_update_kecamatan')?>";
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_kecamatan').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form_kecamatan').modal('hide');
                reload_table_kecamatan();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave_kecamatan').text('Simpan'); //change button text
            $('#btnSave_kecamatan').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave_kecamatan').text('save'); //change button text
            $('#btnSave_kecamatan').attr('disabled',false); //set button enable 

        }
    });
}

function save_desa()
{
    $('#btnSave_desa').text('saving...'); //change button text
    $('#btnSave_desa').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('master_wilayah/ajax_add_desa')?>";
    } else {
        url = "<?php echo site_url('master_wilayah/ajax_update_desa')?>";
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_desa').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form_desa').modal('hide');
                reload_table_desa();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave_desa').text('Simpan'); //change button text
            $('#btnSave_desa').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave_desa').text('save'); //change button text
            $('#btnSave_desa').attr('disabled',false); //set button enable 

        }
    });
}


function delete_data(id)
{
    if(confirm('Yakin data Provinsi ini akan dihapus ?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('master_wilayah/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {                
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

function delete_data_kota(id)
{
    if(confirm('Yakin data Kabupaten/Kota ini akan dihapus ?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('master_wilayah/ajax_delete_kota')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {                
                $('#modal_form_kota').modal('hide');
                reload_table_kota();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

function delete_data_kecamatan(id)
{
    if(confirm('Yakin data Kecamatan ini akan dihapus ?'))
    {
        
        $.ajax({
            url : "<?php echo site_url('master_wilayah/ajax_delete_kecamatan')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {                
                $('#modal_form_kecamatan').modal('hide');
                reload_table_kecamatan();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

function delete_data_desa(id)
{
    if(confirm('Yakin data Desa ini akan dihapus ?'))
    {
        
        $.ajax({
            url : "<?php echo site_url('master_wilayah/ajax_delete_desa')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {                
                $('#modal_form_desa').modal('hide');
                reload_table_desa();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}


function detil_provinsi( kode, nama ){
    $('#id_provinsi_pilih').val(kode);    
	$('#nama_provinsi_pilih').val(nama);      
	table_kota.ajax.url("<?php echo base_url('master_wilayah/kota_list/')?>"+kode).load();
}

function detil_kota( kode, nama ){	
    $('#id_kota_pilih').val(kode);    
	$('#nama_kota_pilih').val(nama);     
	initailizeSelect2_kota(kode);
	table_kecamatan.ajax.url("<?php echo base_url('master_wilayah/kecamatan_list/')?>"+kode).load();
}

function detil_kecamatan( kode, nama ){	    
    $('#id_kecamatan_pilih').val(kode);    
	$('#nama_kecamatan_pilih').val(nama);     
	initailizeSelect2_kecamatan(kode);
	table_desa.ajax.url("<?php echo base_url('master_wilayah/desa_list/')?>"+kode).load();
}



</script>


<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header header-custom">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Provinsi</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Kode</label>
                            <div class="col-md-9">
                                <input name="kode" placeholder="Kode" class="form-control input-small" maxlength="5" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Keterangan</label>
                            <div class="col-md-9">
                                <input name="nama" placeholder="Uraian" class="form-control" maxlength="100" type="text">
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


<div class="modal fade" id="modal_form_kota" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header header-custom">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Kota</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_kota" class="form-horizontal">
                    <input type="hidden" value="" name="id_kota"/> 		
                    <input type="hidden" id="id_provinsi_pilih">
                    <input type="hidden" id="nama_provinsi_pilih"> 					
                    <div class="form-body">
					    <div class="form-group">
                            <label class="control-label col-md-3">Provinsi</label>
                            <div class="col-md-9">
                                <select name="kode_prop" id="kode_prop" class="form-control select2_el_provinsi input-large"></select>
                                <span class="help-block"></span>
                            </div>
                        </div>
						
                        <div class="form-group">
                            <label class="control-label col-md-3">Kode</label>
                            <div class="col-md-9">
                                <input name="kode_kota" placeholder="Kode kota" class="form-control input-small" maxlength="10" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Keterangan</label>
                            <div class="col-md-9">
                                <input name="nama_kota" placeholder="Uraian" class="form-control" maxlength="100" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>						
                        
                        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave_kota" onclick="save_kota()" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modal_form_kecamatan" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header header-custom">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Kecamatan</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_kecamatan" class="form-horizontal">
                    <input type="hidden" value="" name="id_kecamatan"/> 		
                    <input type="hidden" id="id_kota_pilih">
                    <input type="hidden" id="nama_kota_pilih"> 					
                    <div class="form-body">
					    <div class="form-group">
                            <label class="control-label col-md-3">Kabupaten/Kota</label>
                            <div class="col-md-9">
                                <select name="kode_kota_kec" id="kode_kota_kec" class="form-control select2_el_kota input-large"></select>
                                <span class="help-block"></span>
                            </div>
                        </div>
						
                        <div class="form-group">
                            <label class="control-label col-md-3">Kode</label>
                            <div class="col-md-9">
                                <input name="kode_kecamatan" placeholder="" class="form-control input-small" maxlength="10" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Keterangan</label>
                            <div class="col-md-9">
                                <input name="nama_kecamatan" placeholder="Uraian" class="form-control" maxlength="100" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>						
                        
                        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave_kecamatan" onclick="save_kecamatan()" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modal_form_desa" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header header-custom">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Desa</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_desa" class="form-horizontal">
                    <input type="hidden" value="" name="id_desa"/> 		
                    <input type="hidden" id="id_kecamatan_pilih">
                    <input type="hidden" id="nama_kecamatan_pilih"> 					
                    <div class="form-body">
					    <div class="form-group">
                            <label class="control-label col-md-3">Kecamatan</label>
                            <div class="col-md-9">
                                <select name="kode_kecamatan_desa" id="kode_kecamatan_desa" class="form-control select2_el_kecamatan input-large"></select>
                                <span class="help-block"></span>
                            </div>
                        </div>
						
                        <div class="form-group">
                            <label class="control-label col-md-3">Kode</label>
                            <div class="col-md-9">
                                <input name="kode_desa" placeholder="" class="form-control input-small" maxlength="10" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Keterangan</label>
                            <div class="col-md-9">
                                <input name="nama_desa" placeholder="Uraian" class="form-control" maxlength="100" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>						
                        
                        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave_desa" onclick="save_desa()" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script>
  initailizeSelect2_provinsi();
  initailizeSelect2_kota('');
  initailizeSelect2_kecamatan('');
</script>
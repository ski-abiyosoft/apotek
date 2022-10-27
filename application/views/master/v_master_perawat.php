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
                      <span class="title-web">Master <small>Perawat</small>
					</h3>
                      <ul class="page-breadcrumb breadcrumb">

						<li>
							<i class="fa fa-home"></i>
							<a class="title-white" href="<?php echo base_url();?>dashboard">
                               Awal
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a class="title-white" href="#">
                               Master
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a class="title-white" href="#">
                               Perawat
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								Daftar Perawat
							</div>

						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								<div class="btn-group">
								
									
								</div>
								<button class="btn btn-success" onclick="add_perawat()"><i class="glyphicon glyphicon-plus"></i> Data Baru</button>
                               
							</div>
							<table id="table" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                               <thead class="breadcrumb">
                                     <tr>
                                         <th class="title-white" style="text-align: center">Cabang</th>
										 <th class="title-white" style="text-align: center">Kode</th>
                                         <th class="title-white" style="text-align: center">Nama</th>
                                         <th class="title-white" style="text-align: center;width:10;">Aksi</th>

                                     </tr>
                                </thead>
                                <tbody>
                                </tbody>
							</table>
						</div>
					</div>
				</div>
				
				<div class="col-md-6">
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								Perawat Poli
							</div>

						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								<div class="btn-group">
								
									
								</div>
								<button class="btn btn-success" onclick="add_poli()"><i class="glyphicon glyphicon-plus"></i> Data Baru</button>
                                
							</div>
							<table id="table_poli" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                               <thead class="breadcrumb header-custom">
                                     <tr>
                                         <th class="title-white" style="text-align: center">Perawat</th>
										 <th class="title-white" style="text-align: center">Kode</th>
                                         <th class="title-white" style="text-align: center">Nama Poli</th>
                                         <th class="title-white" style="text-align: center;width:5;">Aksi</th>

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
var table_poli;

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('master_perawat/ajax_list')?>",
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
	
	
	table_poli = $('#table_poli').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('master_perawat/poli_list')?>",
            "type": "POST"
        },
		
		"oLanguage": {
                    "sEmptyTable": "Tidak ada data",
                    "sInfoEmpty": "Tidak ada data",
                    "sInfoFiltered": " - Dipilih dari _MAX_ data",
                    "sSearch": "Cari :",
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



function add_perawat()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title
}

function add_poli()
{
    save_method = 'add';
	$('#form_poli')[0].reset(); // reset form on modals
	var kode = $('#id_dokter_pilih').val();  
	var nama = $('#nama_dokter_pilih').val();  
	$('#kode_dokter').val(kode);
	$('#nama_dokter').val(nama);
    
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form_poli').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Data Perawat Poli'); // Set Title to Bootstrap modal title
}

function edit_data(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('master_perawat/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

		    $('[name="id"]').val(data.id);
			$('[name="cabang"]').val(data.koders);
            $('[name="kode"]').val(data.kodokter);
            $('[name="nama"]').val(data.nadokter);
            
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

function reload_table_poli()
{
    table_poli.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('master_perawat/ajax_add')?>";
    } else {
        url = "<?php echo site_url('master_perawat/ajax_update')?>";
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

function save_poli()
{
    $('#btnSavepoli').text('saving...'); //change button text
    $('#btnSavepoli').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('master_perawat/ajax_add_poli')?>";
    } else {
        url = "<?php echo site_url('master_perawat/ajax_update_poli')?>";
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_poli').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form_poli').modal('hide');
                reload_table_poli();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSavepoli').text('Simpan'); //change button text
            $('#btnSavepoli').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSavepoli').text('save'); //change button text
            $('#btnSavepoli').attr('disabled',false); //set button enable 

        }
    });
}


function delete_data(id)
{
    if(confirm('Yakin data Bank dengan kode '+id+' ini akan dihapus ?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('master_perawat/ajax_delete')?>/"+id,
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

function delete_data_poli(id)
{
    if(confirm('Yakin data Perawat Poli dengan kode '+id+' ini akan dihapus ?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('master_perawat/ajax_delete_poli')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form_poli').modal('hide');
                reload_table_poli();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

function detil_dokter( kode, nama ){
    $('#id_dokter_pilih').val(kode);    
	$('#nama_dokter_pilih').val(nama);      
	table_poli.ajax.url("<?php echo base_url('master_perawat/poli_list/')?>"+kode).load();
}

</script>

<script>
	$(document).ready(function() {
		
		$('.print_laporan').on("click", function(){
		$('.modal-title').text('MASTER');
		var no_daftar= this.id;
		$("#simkeureport").html('<iframe src="<?php echo base_url();?>master_policetak/'+no_daftar+'" frameborder="no" width="100%" height="420"></iframe>');
		});	
	});
	
	</script>	

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Dokter</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
					    <div class="form-group">
                            <label class="control-label col-md-3">Cabang</label>
                            <div class="col-md-9">
                                <select name="cabang" class="form-control">
								  <option value="">--- Pilih Cabang ---</option>
								  <?php foreach($cabang as $row) { ?>
								   <option value="<?= $row->koders;?>"><?= $row->namars;?></option>
								  <?php } ?>
								</select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Kode</label>
                            <div class="col-md-9">
                                <input name="kode" placeholder="Kode" class="form-control input-medium" value="auto" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama</label>
                            <div class="col-md-9">
                                <input name="nama" placeholder="Nama" class="form-control" maxlength="100" type="text">
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

<div class="modal fade" id="modal_form_poli" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header header-custom">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Perawat Poli</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_poli" class="form-horizontal">
                    <input type="hidden" value="" name="id_poli"/> 
					<input type="hidden" value="" id="id_dokter_pilih"/> 
					<input type="hidden" value="" id="nama_dokter_pilih"/> 
                    <div class="form-body">
					    <div class="form-group">
                            <label class="control-label col-md-3">Kode</label>
                            <div class="col-md-9">
                                <input id="kode_dokter" name="kode_dokter" placeholder="Kode" class="form-control input-small" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama Perawat</label>
                            <div class="col-md-9">
                                <input id="nama_dokter" name="nama_dokter" placeholder="Nama" class="form-control" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>	
					    <div class="form-group">
                            <label class="control-label col-md-3">Poli</label>
                            <div class="col-md-9">
                                <select name="poli" class="form-control">
								  <option value="">--- Pilih Poli ---</option>
								  <?php 
								  $poli = $this->db->query("select * from tbl_namapos")->result();
								  foreach($poli as $row) { ?>
								   <option value="<?= $row->kodepos;?>"><?= $row->namapost;?></option>
								  <?php } ?>
								</select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        				
                        
                        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSavepoli" onclick="save_poli()" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
    <?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>	

    <!--div class="page-content-wrapper">
		<div class="page-content"-->
			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
                    <span class="title-unit">
                            &nbsp;<?php echo $this->session->userdata('unit'); ?> 
                        </span>
                      - 
                      <span class="title-web">Master <small>Perkiraan</small>
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
                               Perkiraan
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
								Daftar Perkiraan
							</div>

						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								<div class="btn-group">
								
								</div>
								 <?php if($akses->uadd==1){ ?>
								 <button class="btn btn-success" onclick="add_data()"><i class="glyphicon glyphicon-plus"></i> Data Baru</button>
								 <?php } ?>
                                <div class="btn-group pull-right">
									<button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu pull-right">
										<li>
											<a href="#report" class="print_laporan" data-toggle="modal" id="1">Cetak</a>
										</li>
										<li>
											<a href="<?php echo base_url()?>akuntansi_akun/export">
												 Export
											</a>
										</li>
									</ul>
								</div>
							</div>
							<table id="table" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                               <thead class="breadcrumb">
                                     <tr>
                                         <th style="text-align: center">Kode Akun</th>
                                         <th style="text-align: center">Nama Akun</th>
                                         <th style="text-align: center">Tipe Akun</th>
										 <th style="text-align: center">Level</th>
										 <th style="text-align: center;width:10%;">Aksi</th>

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

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('akuntansi_akun/ajax_list')?>",
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
		{ 
            "targets": [ 3 ], //last column
            "className" : "text-center",
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
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title
}

function edit_data(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('akuntansi_akun/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
            $('[name="kodeakun"]').val(data.accountno);
            $('[name="namaakun"]').val(data.acname);
            $('[name="jenis"]').val(data.actype);
            $('[name="level"]').val(data.aclevel);
			$('[name="kasbank"]').val(data.kasbank);
			$('[name="aktif"]').val(data.aktif);
			
            $('#modal_form_large').modal('show'); // show bootstrap modal when complete loaded
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

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('akuntansi_akun/ajax_add')?>";
    } else {
        url = "<?php echo site_url('akuntansi_akun/ajax_update')?>";
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

function delete_data(id)
{
    if(confirm('Yakin data akun dengan id '+id+' ini akan dihapus ?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('akuntansi_akun/ajax_delete')?>/"+id,
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

$(document).ready(function() {
		
		$('.print_laporan').on("click", function(){
		$('.modal-title').text('BUKU BESAR');
		var no_daftar= this.id;
		$("#simkeureport").html('<iframe src="<?php echo base_url();?>akuntansi_akun/cetak/'+no_daftar+'" frameborder="no" width="100%" height="420"></iframe>');
		});	
	});
	

</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Akun</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Kode Akun</label>
                            <div class="col-md-9">
                                <input name="kodeakun" placeholder="Kode Akun" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama Akun</label>
                            <div class="col-md-9">
                                <input name="namaakun" placeholder="Nama Akun" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Tipe Akun</label>
                            <div class="col-md-9">
                                 <select id="jenis" name="jenis" class="form-control input-mediumx select2" data-placeholder="Pilih..." required>
            											
								<?php 
									foreach($tipeakun as $row){?>
									<option value="<?php echo $row['actype'];?>"><?php echo $row['typename'];?></option>
								<?php } ?>
								</select>
                                <span class="help-block"></span>
                            </div>
                        </div>
						
						
						<div class="form-group">
                            <label class="control-label col-md-3">Akun Level</label>
                            <div class="col-md-9">
                                <select id="level" name="level" class="form-control input-mediumx" data-placeholder="Pilih..." required>
            					<option value="">&nbsp;</option>						
								<option value="1">1</option>						
								<option value="2">2</option>						
								<option value="3">3</option>						
								<option value="4">4</option>						
								</select>
                                <span class="help-block"></span>
                            </div>
                        </div>	
						<div class="form-group">
                            <label class="control-label col-md-3">Kas/Bank</label>
                            <div class="col-md-9">
                                <select id="kasbank" name="kasbank" class="form-control input-mediumx" data-placeholder="Pilih..." required>
            					<option value="0">Tidak</option>						
								<option value="1">Ya</option>						
								
								</select>
                                <span class="help-block"></span>
                            </div>
                        </div>	
						<div class="form-group">
                            <label class="control-label col-md-3">Aktif</label>
                            <div class="col-md-9">
                                <select id="aktif" name="aktif" class="form-control input-mediumx" data-placeholder="Pilih..." required>
            					<option value="0">Ya</option>						
								<option value="1">Tidak</option>						
								
								</select>
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



<!-- Bootstrap modal -->
<div class="modal fade bd-example-modal-lg" id="modal_form_large" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Akun</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Kode Akun</label>
                            <div class="col-md-9">
                                <input name="kodeakun" placeholder="Kode Akun" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama Akun</label>
                            <div class="col-md-9">
                                <input name="namaakun" placeholder="Nama Akun" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Tipe Akun</label>
                            <div class="col-md-9">
                                 <select id="jenis" name="jenis" class="form-control input-mediumx select2" data-placeholder="Pilih..." required>
            											
								<?php 
									foreach($tipeakun as $row){?>
									<option value="<?php echo $row['actype'];?>"><?php echo $row['typename'];?></option>
								<?php } ?>
								</select>
                                <span class="help-block"></span>
                            </div>
                        </div>
						
						
						<div class="form-group">
                            <label class="control-label col-md-3">Akun Level</label>
                            <div class="col-md-9">
                                <select id="level" name="level" class="form-control input-mediumx" data-placeholder="Pilih..." required>
            					<option value="">&nbsp;</option>						
								<option value="1">1</option>						
								<option value="2">2</option>						
								<option value="3">3</option>						
								<option value="4">4</option>						
								</select>
                                <span class="help-block"></span>
                            </div>
                        </div>	
						<div class="form-group">
                            <label class="control-label col-md-3">Kas/Bank</label>
                            <div class="col-md-9">
                                <select id="kasbank" name="kasbank" class="form-control input-mediumx" data-placeholder="Pilih..." required>
            					<option value="0">Tidak</option>						
								<option value="1">Ya</option>						
								
								</select>
                                <span class="help-block"></span>
                            </div>
                        </div>	
						<div class="form-group">
                            <label class="control-label col-md-3">Aktif</label>
                            <div class="col-md-9">
                                <select id="aktif" name="aktif" class="form-control input-mediumx" data-placeholder="Pilih..." required>
            					<option value="0">Ya</option>						
								<option value="1">Tidak</option>						
								
								</select>
                                <span class="help-block"></span>
                            </div>
                        </div>
						
                        
                    </div>
                </form>
            </div>
            <div class="modal-body">
                <h4>Saldo Awal (Jika Ada)</h4>
                <table id="table" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                    <thead class="breadcrumb">
                            <tr>
                                <th style="text-align: center">Tahun</th>
                                <th style="text-align: center">Jan</th>
                                <th style="text-align: center">Feb</th>
                                <th style="text-align: center">Mar</th>
                                <th style="text-align: center">Apr</th>
                                <th style="text-align: center">Mei</th>
                                <th style="text-align: center">Jun</th>
                                <th style="text-align: center">Jul</th>
                                <th style="text-align: center">Agt</th>
                                <th style="text-align: center">Sep</th>
                                <th style="text-align: center">Okt</th>
                                <th style="text-align: center">Nov</th>
                                <th style="text-align: center">Des</th>
                            </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <br>
                <h4>Budgeting / Anggaran (Jika Ada)</h4>
                <table id="table" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                    <thead class="breadcrumb">
                            <tr>
                                <th style="text-align: center">Tahun</th>
                                <th style="text-align: center">Jan</th>
                                <th style="text-align: center">Feb</th>
                                <th style="text-align: center">Mar</th>
                                <th style="text-align: center">Apr</th>
                                <th style="text-align: center">Mei</th>
                                <th style="text-align: center">Jun</th>
                                <th style="text-align: center">Jul</th>
                                <th style="text-align: center">Agt</th>
                                <th style="text-align: center">Sep</th>
                                <th style="text-align: center">Okt</th>
                                <th style="text-align: center">Nov</th>
                                <th style="text-align: center">Des</th>
                            </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
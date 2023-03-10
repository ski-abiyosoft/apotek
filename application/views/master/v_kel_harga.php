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
                      <span class="title-web">Master <small>Kelompok Harga</small>
					</h3>
                      <ul class="page-breadcrumb breadcrumb">

						<li>
							<i style="color:white;" class="fa fa-home"></i>
							<a style="color:white;" href="<?php echo base_url();?>dashboard">
                               Awal
							</a>
							<i style="color:white;" class="fa fa-angle-right"></i>
						</li>
						<li>
							<a style="color:white;" href="#">
                               Master
							</a>
							<i style="color:white;" class="fa fa-angle-right"></i>
						</li>
						<li>
							<a style="color:white;" href="#">
                            Kelompok Harga
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
								Daftar Kelompok Harga
							</div>

						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								<div class="btn-group">
								
									
								</div>
								<button class="btn btn-success" onclick="add_bank()"><i class="glyphicon glyphicon-plus"></i> Data Baru</button>
                                <div class="btn-group pull-right">
									<button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu pull-right">
										<li>
											<a href="#report" class="print_laporan" data-toggle="modal" id="1">Cetak</a>
										</li>
										<li>
											<a href="<?php echo base_url()?>master_poliexport">
												 Export
											</a>
										</li>
									</ul>
								</div>
							</div>
							<table id="table" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                               <thead class="breadcrumb">
                                     <tr>
                                         <th style="text-align: center;color:white;">Kode</th>
                                         <th style="text-align: center;color:white;">Nama Kelompok Harga</th>
										 <!-- <th style="text-align: center">Parameter</th> -->
                                         <th style="text-align: center;color:white;width:12%;">Aksi</th>

                                     </tr>
                                </thead>
                                <tbody>
                                </tbody>
							</table>
						</div>
					</div>

                    <div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								<b>Daftar Kelompok Harga Detail</b> 
							</div>

						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								<div class="btn-group">
								
									
								</div>
								
                                <div class="btn-group pull-right">
									<button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu pull-right">
										<li>
											<a href="#report" class="print_laporan" data-toggle="modal" id="1">Cetak</a>
										</li>
										<li>
											<a href="<?php echo base_url()?>master_poliexport">
												 Export
											</a>
										</li>
									</ul>
								</div>
							</div>
							<table id="table2" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                               <thead class="breadcrumb">
                                     <tr>
										 <th style="color:white;text-align: center">Koders</th>
                                         <th style="color:white;text-align: center">Kode harga</th>
                                         <th style="color:white;text-align: center">Nama Kelompok Harga</th>
										 <th style="color:white;text-align: center">Umum %</th>
										 <th style="color:white;text-align: center">Member %</th>
                                         <th style="color:white;text-align: center;width:12%;">Aksi</th>

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
            "url": "<?php echo site_url('master_kel_harga/ajax_list')?>",
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
   
    //datatables
    table2 = $('#table2').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('master_kel_harga/ajax_list2')?>",
            "type": "POST"
        },
		
		"oLanguage": {
                    "sEmptyTable"   : "Tidak ada data",
                    "sInfoEmpty"    : "Tidak ada data",
                    "sInfoFiltered" : " - Dipilih dari _MAX_ data",
                    "sSearch"       : "Pencarian Data : ",
                    "sInfo"         : " Jumlah _TOTAL_ Data (_START_ - _END_)",
                    "sLengthMenu"   : "_MENU_ Baris",
                    "sZeroRecords"  : "Tida ada data",
                    "oPaginate": {
                        "sPrevious"   : "Sebelumnya",
                        "sNext"       : "Berikutnya"
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
    // $('.datepicker').datepicker({
    //     autoclose: true,
    //     format: "yyyy-mm-dd",
    //     todayHighlight: true,
    //     orientation: "top auto",
    //     todayBtn: true,
    //     todayHighlight: true,  
    // });

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



function add_bank()
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
        url         : "<?php echo site_url('master_kel_harga/ajax_edit/')?>/" + id,
        type        : "GET",
        dataType    : "JSON",
        success     : function(data)
        {
		    $('[name="kode"]').val(data.kodeharga);
            $('[name="nama"]').val(data.kelompok);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Data'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            // alert('Error get data from ajax');
            swal({
                    html: "Error get data from ajax",
                    type: "error",
                    confirmButtonText: "OK",
                    confirmButtonColor: "red"
                });
        }
    });
}

function edit_data_detail(id,koders)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url         : "<?php echo site_url('master_kel_harga/ajax_edit_detail/')?>/"+id+"/"+koders,
        type        : "GET",
        dataType    : "JSON",
        success     : function(data)
        {
		    $('[name="koded"]').val(data.koders);
            $('[name="kodehargar"]').val(data.kodeharga);
            $('[name="namad"]').val(data.kelompok);
		    $('[name="umum"]').val(data.umum);
            $('[name="member"]').val(data.member);
            $('#modal_form2').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Data'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            // alert('Error get data from ajax');
            swal({
                    html                : "Error get data from ajax",
                    type                : "error",
                    confirmButtonText   : "OK",
                    confirmButtonColor  : "red"
                });
        }
    });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
    table2.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('master_kel_harga/ajax_add')?>";
    } else {
        url = "<?php echo site_url('master_kel_harga/ajax_update')?>";
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
                swal({
                    html: "Data Kelompok Berhasil Tersimpan",
                    type: "success",
                    confirmButtonText: "Ok",
                    confirmButtonColor: "green"
                });
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

function savedetail()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    url = "<?php echo site_url('master_kel_harga/ajax_update_detail')?>";

    // ajax adding data to database
    $.ajax({
        url         : url,
        type        : "POST",
        data        : $('#formd').serialize(),
        dataType    : "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                swal({
                    html: "Data Detail Berhasil Terupdate",
                    type: "success",
                    confirmButtonText: "Ok",
                    confirmButtonColor: "green"
                });
                $('#modal_form2').modal('hide');
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
    // if(confirm('Yakin data Bank dengan kode '+id+' ini akan dihapus ?'))
    $kett = "Yakin data Kelompok <br> <b>"+id+"</b><br> ini akan dihapus ?";
    swal({
        title: 'KELOMPOK HARGA',
        html: $kett,
        type: 'question',
        showCancelButton: true,
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-success',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal'
    }).then(function() 
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('master_kel_harga/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                swal({
                    html: "Data Kelompok Berhasil Terhapus",
                    type: "success",
                    confirmButtonText: "Ok",
                    confirmButtonColor: "green"
                });
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                swal({
                    html: "Gagal Terhapus",
                    type: "error",
                    confirmButtonText: "Ok",
                    confirmButtonColor: "red"
                });
            }
        });

    });
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
                <h3 class="modal-title">Data Kelompok Harga</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Kode</label>
                            <div class="col-md-9">
                                <input name="kode" placeholder="Kode" class="form-control input-small" maxlength="5" type="text" readonly value="AUTO">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama Kelompok</label>
                            <div class="col-md-9">
                                <input name="nama" placeholder="Uraian" class="form-control" maxlength="100" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>			
                        
                        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary"><b>Simpan</b></button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Batal</b></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form2" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Kelompok Harga Detail</h3>
            </div>
            <div class="modal-body formd">
                <form action="#" id="formd" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Kode</label>
                            <div class="col-md-9">
                                <input name="koded" placeholder="Kode" class="form-control input-small" maxlength="5" type="text" readonly>
                                <span class="help-block"></span>
                                <input name="kodehargar" placeholder="Kode" class="form-control input-small" maxlength="5" type="hidden" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Kelompok</label>
                            <div class="col-md-9">
                                <input name="namad" placeholder="Uraian" class="form-control" maxlength="100" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>			
                        <div class="form-group">
                            <label class="control-label col-md-3">Umum</label>
                            <div class="col-md-5">
                                <input name="umum" placeholder="Umum" class="form-control" maxlength="100" type="number">
                                <span class="help-block"></span>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label">%</label>
                                <span class="help-block"></span>
                            </div>
                        </div>			
                        <div class="form-group">
                            <label class="control-label col-md-3">Member</label>
                            <div class="col-md-5">
                                <input name="member" placeholder="Member" class="form-control" maxlength="100" type="number">
                                <span class="help-block"></span>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label">%</label>
                                <span class="help-block"></span>
                            </div>
                        </div>			
                        
                        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="savedetail()" class="btn btn-primary"><b>Simpan</b></button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Batal</b></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

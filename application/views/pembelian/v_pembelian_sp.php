    <?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>	

	<link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css-')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">
		
  
			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
                    <span class="title-unit">
                            &nbsp;<?php echo $this->session->userdata('unit'); ?> 
                        </span>
                      - 
                      <span class="title-web">Pembelian <small>Data Supplier</small>
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
                               Pembelian
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="#">
                               Supplier
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
								Daftar Supplier
							</div>

						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								<div class="btn-group">
									
								</div>
								<?php if($akses->uadd){?>
								<button class="btn btn-success" onclick="add_data()"><i class="glyphicon glyphicon-plus"></i> Data Baru</button>
								<?php } ?>
                                <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Refresh</button>								
							</div>
							<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                               <thead>
                                     <tr>
                                         <th style="text-align: center;width:5%">Kode</th>
                                         <th style="text-align: center;width:20%">Nama</th>                                         
										 <th style="text-align: center;width:30%">Alamat</th>                                         
										 <th style="text-align: center;width:10%">Telpon</th>                                         
										 <th style="text-align: center;width:10%">Kontak</th>                                         
                                         <th style="text-align: center;width:16%">&nbsp</th>

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
?>
	
<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>


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
            "url": "<?php echo site_url('pembelian_sp/ajax_list')?>",
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
        url : "<?php echo site_url('pembelian_sp/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="kode"]').val(data.kode);            
			$('[name="telp"]').val(data.telp);            
			$('[name="nama"]').val(data.nama);
			$('[name="alamat1"]').val(data.alamat1);
			$('[name="alamat2"]').val(data.alamat2);
			$('[name="kota"]').val(data.kota);
			$('[name="kodepos"]').val(data.kodepos);
			$('[name="pkp"]').val(data.pkp);
			$('[name="fax"]').val(data.fax);
			$('[name="hp"]').val(data.hp);
			$('[name="contactname"]').val(data.contactname);
			$('[name="email"]').val(data.email);
			$('[name="top"]').val(data.top);
			$('[name="saldoawal"]').val(data.saldoawal);
			$('[name="saldojln"]').val(data.saldojln);
			$('[name="tglbeliakhir"]').val(data.tglbeliakhir);
			$('[name="jmlbelithn"]').val(data.jmlbelithn);
			
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

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('pembelian_sp/ajax_add')?>";
    } else {
        url = "<?php echo site_url('pembelian_sp/ajax_update')?>";
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
    if(confirm('Yakin data Karyawan dengan kode '+id+' ini akan dihapus ?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('pembelian_sp/ajax_delete')?>/"+id,
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

function cetaklap(str) {
  var xhttp;
  if (str == "") {
    document.getElementById("txtHint").innerHTML = "";
    return;
  }
 			
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    document.getElementById("txtHint").innerHTML = this.responseText;
    }
  };  
  
  xhttp.open("GET", "<?php echo base_url(); ?>pembelian_sp/cetak/"+str, true);  
  xhttp.send();
}


</script>

<script>
	$(document).ready(function() {
		
		$('.print_laporan').on("click", function(){
		$('.modal-title').text('MASTER');
		var no_daftar= this.id;
		$("#simkeureport").html('<iframe src="<?php echo base_url();?>pembelian_sp/cetak/'+no_daftar+'" frameborder="no" width="100%" height="420"></iframe>');
		});	
	});
	
	</script>	

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Supplier</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
					
					  <div class="row">
					   <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Kode</label>
                            <div class="col-md-6">
                                <input name="kode" placeholder="Kode" class="form-control input-small" maxlength="10" type="text"> 
                                <span class="help-block"></span>								
                            </div>
                        </div>
                       </div> 
					   <div class="col-md-6">
						<div class="form-group">
                            <label class="control-label col-md-3">Telpon</label>
                            <div class="col-md-9">
                                <input name="telp" placeholder="No. Telpon" class="form-control " maxlength="12" type="text">                                
                            </div>
                        </div>
					  </div>	
					  </div>	
					  
					  <div class="row">
					   <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama</label>
                            <div class="col-md-9">
                                <input name="nama" placeholder="Nama Supplier" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
					   </div>	
					   <div class="col-md-6">
						<div class="form-group">
                            <label class="control-label col-md-3">Fax</label>
                            <div class="col-md-9">
                                <input name="fax" placeholder="Nomor Fax" class="form-control" maxlength="16" type="text">
                                
                            </div>
                        </div>
					   </div>	
					  </div>	
					  
					  <div class="row">
					   <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Alamat 1</label>
                            <div class="col-md-9">
                                <input name="alamat1" placeholder="Alamat Supplier" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
					   </div>	
					   <div class="col-md-6">
						<div class="form-group">
                            <label class="control-label col-md-3">Handphone</label>
                            <div class="col-md-9">
                                <input name="hp" placeholder="Nomor HP" class="form-control" maxlength="16" type="text">
                                
                            </div>
                        </div>
					   </div>	
					  </div>
					  
					  <div class="row">
					   <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Alamat 2</label>
                            <div class="col-md-9">
                                <input name="alamat2" placeholder="Alamat Supplier" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
					   </div>	
					   <div class="col-md-6">
						<div class="form-group">
                            <label class="control-label col-md-3">Contactname</label>
                            <div class="col-md-9">
                                <input name="contactname" placeholder="Nama Kontak Person" class="form-control" maxlength="40" type="text">
                                
                            </div>
                        </div>
					   </div>	
					  </div>
					  
					  <div class="row">
					   <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Kota</label>
                            <div class="col-md-9">
                                <input name="kota" placeholder="Nama Kota" class="form-control" type="text">
                              
                            </div>
                        </div>
					   </div>	
					   <div class="col-md-6">
						<div class="form-group">
                            <label class="control-label col-md-3">Email</label>
                            <div class="col-md-9">
                                <input name="email" placeholder="Alamat email" class="form-control" maxlength="40" type="text">
                                
                            </div>
                        </div>
					   </div>	
					  </div>
					  
					  
					  <div class="row">
					   <div class="col-md-6">
                        <div class="form-group">
						    
                            <label class="control-label col-md-3">Kode Pos</label>
                            <div class="col-md-3">
                                <input name="kodepos" placeholder="Kode Pos" class="form-control" type="text">                                
                            </div>
							<label class="control-label col-md-3">PKP</label>
                            <div class="col-md-3">
                                <select name="pkp" class="form-control">
								   <option value="Y">Ya</option>
								   <option value="T" selected="true">Tidak</option>
								</select>                              
                            </div>
                        </div>
					   </div>	
					   <div class="col-md-6">
						<div class="form-group">
                            <label class="control-label col-md-3">T.O.P</label>
                            <div class="col-md-9">
                                <input name="top" placeholder="" class="form-control"  type="text">
                                
                            </div>
                        </div>
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

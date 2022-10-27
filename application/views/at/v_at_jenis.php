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
                      <span class="title-web">Aktiva Tetap <small>Data Jenis Aktiva Tetap</small>
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
                               Aktiva Tetap
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="#">
                               Jenis Aktiva Tetap
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
								Daftar Jenis Aktiva Tetap
							</div>

						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								<div class="btn-group">
									
								</div>
								<button class="btn btn-success" onclick="add_data()"><i class="glyphicon glyphicon-plus"></i> Data Baru</button>
                                <div class="btn-group pull-right">
									<button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu pull-right">
										<li>
											<a href="#report" class="print_laporan" data-toggle="modal" id="1">Cetak</a>
										</li>
										<li>
											<a href="<?php echo base_url()?>at_jenis/export">
												 Export
											</a>
										</li>
									</ul>
								</div>
							</div>
							<table id="table" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                               <thead class="breadcrumb">
                                     <tr>
									     <th style="text-align: center">Cabang</th>
                                         <th style="text-align: center">Kode</th>
                                         <th style="text-align: center">Tipe Aset</th>                                         
										 <th style="text-align: center">Metode Penyusutan</th>                                         
										 <th style="text-align: center">% Susut</th>                                         
										 <th style="text-align: center">Lama Penyusutan</th>                                         
										 
                                         <th style="text-align: center;width:12%;">Aksi</th>

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
    $('.select1').select2({
        dropdownParent: $('#select1')
    })

    //datatables
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('at_jenis/ajax_list')?>",
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
    $('.select1').val('').trigger('change');
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
        url : "<?php echo site_url('at_jenis/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

		    $('[name="id"]').val(data.id);
			$('[name="cabang"]').val(data.koders);
            $('[name="kode"]').val(data.fixid);
			$('[name="metode"]').val('Garis Lurus');
            $('[name="nama"]').val(data.groupname);
			$('[name="umurekonomis"]').val(data.tahunsusut);
			$('[name="tarif"]').val(data.fixrate);
            $('[name="fix_account"]').val(data.fix_account).trigger('change');
            $('[name="depreciation_account"]').val(data.depreciation_account).trigger('change');
            $('[name="cost_account"]').val(data.cost_account).trigger('change');
            
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
        url = "<?php echo site_url('at_jenis/ajax_add')?>";
    } else {
        url = "<?php echo site_url('at_jenis/ajax_update')?>";
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
    if(confirm('Yakin data bank dengan kode '+id+' ini akan dihapus ?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('at_jenis/ajax_delete')?>/"+id,
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
  //xhttp.open("GET", "getlaporan.php?q="+x, true);  
  xhttp.open("GET", "<?php echo base_url(); ?>at_jenis/cetak/"+str, true);  
  xhttp.send();
}
</script>

<script>
	$(document).ready(function() {
		
		$('.print_laporan').on("click", function(){
		$('.modal-title').text('MASTER');
		var no_daftar= this.id;
		$("#MyModalBody").html('<iframe src="<?php echo base_url();?>at_jenis/cetak/'+no_daftar+'" frameborder="no" width="100%" height="420"></iframe>');
		});	
	});	
</script>	

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Jenis AT</h3>
            </div>
            <div class="modal-body form" id="modal_form_body">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
					    <div class="form-group">
                            <label class="control-label col-md-3">Cabang</label>
                            <div class="col-md-9">
                                <select name="cabang" class="form-control">
                                    <option value="">--Pilih --</option>
									<?php 
									foreach($unit->result_array() as $db)
					                {
									?>
                                    <option value="<?php echo $db['koders'];?>"><?php echo $db['namars'];?></option>
                                    <?php } ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Kode</label>
                            <div class="col-md-9">
                                <input name="kode" class="form-control" type="text" value="Generated automatically" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama Kelompok Aktiva</label>
                            <div class="col-md-9">
                                <input name="nama" placeholder="Nama Kelompok AT" class="form-control" maxlength="30" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>  
                        <div class="form-group">
                            <label class="control-label col-md-3">Metode Penyusutan</label>
                            <div class="col-md-9">
                                <input id="metode" name="metode" class="form-control" type="text" value="Garis Lurus" readonly>
                            </div>
                        </div> 						
						
						<div class="form-group">
                            <label class="control-label col-md-3">Estimasi Umur (Tahun)</label>
                            <div class="col-md-9">
                                <input name="umurekonomis" placeholder="" class="form-control" maxlength="30" type="text">
                                <span class="help-block">Tahun</span>
                            </div>
                        </div>  
						<div class="form-group">
                            <label class="control-label col-md-3">Penyusutan per tahun (%)</label>
                            <div class="col-md-9">
                                <input name="tarif" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>  
                        <div class="form-group">
                            <label class="control-label col-md-3">Akun Aktiva</label>
                            <div class="col-md-9">
                                <select name="fix_account" class="select1 form-select" style="width: 100%;">
                                    <option value="">-- Pilik Akun --</option>
                                    <?php foreach ($coa as $account) : ?>
                                        <option value="<?= $account->accountno; ?>"><?= $account->accountno . ' | ' . $account->acname; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group" id="select1">
                            <label class="control-label col-md-3">Akun Akumulasi Penyusutan</label>
                            <div class="col-md-9">
                                <select name="depreciation_account" class="select1 form-select" style="width: 100%;">
                                    <option value="">-- Pilik Akun --</option>
                                    <?php foreach ($coa as $account) : ?>
                                        <option value="<?= $account->accountno; ?>"><?= $account->accountno . ' | ' . $account->acname; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Akun Biaya Penyusutan</label>
                            <div class="col-md-9">
                                <select name="cost_account" class="select1 form-select" style="width: 100%;">
                                    <option value="">-- Pilik Akun --</option>
                                    <?php foreach ($coa as $account) : ?>
                                        <option value="<?= $account->accountno; ?>"><?= $account->accountno . ' | ' . $account->acname; ?></option>
                                    <?php endforeach; ?>
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

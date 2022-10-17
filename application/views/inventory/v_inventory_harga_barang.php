    <?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>	

			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					  Inventory <small>Data Harga Barang per Customer</small>
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
                               Inventory
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="#">
                               Harga Barang
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
								Daftar Harga Barang per Customer
							</div>

						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								<div class="btn-group">
									<!--button id="master-bank_new" class="btn green">
									Data Baru <i class="fa fa-plus"></i>
									</button-->
									
									
								</div>
								<a href="<?php echo base_url()?>inventory_harga/entri" class="btn btn-success">
									<i class="fa fa-plus"></i>
                                    Data Baru
								</a>
									
								<button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Refresh</button>
								<!--div class="btn-group pull-right">
									<button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu pull-right">
										<li>
											<a href="#report" class="print_laporan" data-toggle="modal" id="1">Cetak</a>
										</li>
										<li>
											<a href="<?php echo base_url()?>">
												 Export
											</a>
										</li>
									</ul>
								</div-->
							</div>
							<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                               <thead>
                                     <tr>
                                         <th style="text-align: center;width:10%">Kode</th>
										 <th style="text-align: center;width:30%">Nama Barang</th>                                         
										 <th style="text-align: center;width:20%">Nama Customer</th>                                         
                                         <th style="text-align: center;width:14%">Harga Jual Net</th>     
										 <th style="text-align: center;width:16%;">Aksi</th>

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
	
<script src="<?php echo base_url('assets/plugins/jquery-1.10.2.min.js');?>" type="text/javascript"></script>	
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
            "url": "<?php echo site_url('inventory_harga/ajax_list')?>",
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
            "targets": [3], //last column
            "className" : "text-right",
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
        url = "<?php echo site_url('inventory_harga/ajax_add')?>";
    } else {
        url = "<?php echo site_url('inventory_harga/ajax_update')?>";
    }
    
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
				//alert('Data sudah disimpan...');
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
            $('#btnSave').text('Simpan'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}

function delete_data(id)
{
    if(confirm('Yakin data barang dengan kode '+id+' ini akan dihapus ?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('inventory_harga/ajax_delete')?>/"+id,
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
  
  xhttp.open("GET", "<?php echo base_url(); ?>inventory_harga/cetak/"+str, true);  
  xhttp.send();
}



</script>

<script>
	$(document).ready(function() {
		
		$('.print_laporan').on("click", function(){
		$('.modal-title').text('MASTER');
		var no_daftar= this.id;
		$("#simkeureport").html('<iframe src="<?php echo base_url();?>inventory_harga/cetak/'+no_daftar+'" frameborder="no" width="100%" height="420"></iframe>');
		});	
	});
	
	
	jQuery(document).ready(function() {		  
		  UIGeneral.init();		  		  
		});		
	</script>	

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog" >
    <div class="modal-dialog-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Barang</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
					
					  <div class="row">
					   <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Kategori</label>
                            <div class="col-md-9">
                                <select name="kateg" class="form-control">
                                    <option value="">--Pilih --</option>
									<?php 
									foreach($kateg->result_array() as $db) {?>
                                    <option value="<?php echo $db['kode'];?>"><?php echo $db['nama'];?></option>
                                    <?php } ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
							
                        </div>
                       </div> 
					   <div class="col-md-6">
						<div class="form-group">
                           
							<label class="control-label col-md-3">Kode Item *</label>
                            <div class="col-md-6">
                                <input name="kodeitem" placeholder="Kode Barang" class="form-control" maxlength="12" type="text">
                                <span class="help-block"></span>
                            </div>							
                        </div>
					  </div>	
					  </div>	
					  
					  
					  
					  <div class="row">
					   <div class="col-md-6">
                        <div class="form-group">                            
							<label class="control-label col-md-3">Nama Barang</label>
                            <div class="col-md-9">
                                <input name="namabarang" placeholder="Nama Barang" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
					   </div>	
					   <div class="col-md-6">
						<div class="form-group">
                            
							<label class="control-label col-md-3">Satuan</label>
                            <div class="col-md-6">
                                <select name="satuan" class="form-control">
                                    <option value="">--Pilih --</option>
									<?php 
									foreach($satuan->result_array() as $db) {?>
                                    <option value="<?php echo $db['nama'];?>"><?php echo $db['nama'];?></option>
                                    <?php } ?>
                                </select>                                
                            </div>
                        </div>
					   </div>	
					  </div>
					  
					  <div class="row">
					   <div class="col-md-6">
						<div class="form-group">
                            <label class="control-label col-md-3">Harga Jual</label>
                            <div class="col-md-3">
                                <input name="hargajual" placeholder="" class="form-control" type="text" value="0">                                
                            </div>
							<label class="control-label col-md-3">Harga Beli</label>
                            <div class="col-md-3">
                                <input name="hargabeli" placeholder="" class="form-control" type="text" value="0">                                
                            </div>
							
							
                        </div>
					   </div>		
					   <div class="col-md-6">
						<div class="form-group">
                            <label class="control-label col-md-3">Stok</label>
                            <div class="col-md-3">
                                <input name="stok" placeholder="" class="form-control" maxlength="40" type="text" value="0" readonly>                                
                            </div>
							
                        </div>
					   </div>	
					  </div>
					  
					  <div class="row">
					   <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-3">Gudang</label>
                            <div class="col-md-3">
                                <select name="gudang" class="form-control">
                                    <option value="">--Pilih --</option>
									<?php 
									foreach($gudang->result_array() as $db) {?>
                                    <option value="<?php echo $db['kode'];?>"><?php echo $db['nama'];?></option>
                                    <?php } ?>
                                </select>
                              
                            </div>
							<label class="control-label col-md-3">Rak</label>
                            <div class="col-md-3">
                                <select name="rak" class="form-control">
                                    <option value="">--Pilih --</option>
									<?php 
									foreach($rak->result_array() as $db) {?>
                                    <option value="<?php echo $db['kode'];?>"><?php echo $db['nama'];?></option>
                                    <?php } ?>
                                </select>
                                
                            </div>
                        </div>
					   </div>	
					   <div class="col-md-6">
					     <div class="form-group">
					       <label class="control-label col-md-3">Stok Min</label> 
						   <div class="col-md-3">						        
                                <input name="stokmin" placeholder="Min" class="form-control" type="text" value="0">                                								
                            </div>
							<label class="control-label col-md-3">Stok Max</label> 
							<div class="col-md-3">
                                <input name="stokmax" placeholder="Max" class="form-control" type="text" value="0">                                								
                            </div>
						  </div>	
					   </div>
					  </div>
					 					  
					  
					  
					  
					  
					  
					  
					  
					  
					  <div class="row">
					   <div class="col-md-6">
                        <div class="form-group">                            
							<label class="control-label col-md-3">PPN</label>
                            <div class="col-md-3">
                                <select name="ppn" class="form-control">
								   <option value="Y">Ya</option>
								   <option value="T">Tidak</option>
								</select>
                            </div>
							<label class="control-label col-md-3">Merk</label>
                            <div class="col-md-3">
                                <select name="merk" class="form-control">
                                    <option value="">--Pilih --</option>
									<?php 
									foreach($merk->result_array() as $db) {?>
                                    <option value="<?php echo $db['kode'];?>"><?php echo $db['nama'];?></option>
                                    <?php } ?>
                                </select>
                                
                            </div>
							
                        </div>
					   </div>	
					   
					   
				        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
			   <p align="center">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
			   </p>	
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

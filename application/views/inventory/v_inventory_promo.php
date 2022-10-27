    <?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>	

	<link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css-')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">
		
	<style>	
	div.tableContainer {
		clear: both;
		border: 1px solid #963;
		height: 285px;
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
		height: 262px;
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
		border-bottom: none;
		border-left: none;
		border-right: 1px solid #CCC;
		border-top: 1px solid #DDD;
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
	
  
			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					  Inventory <small>Promo</small>
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
                               Promo
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
								Seting Master Promo
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
									     <th style="text-align: center;width:10%">Tgl. Awal</th>
                                         <th style="text-align: center;width:10%">Tgl. Akhir</th>
										 <th style="text-align: center;width:10%">Kode Item</th>
										 <th style="text-align: center;width:30%">Nama Barang</th>
										 <th style="text-align: center;width:10%">Qty</th>
										 <th style="text-align: center;width:10%">Satuan</th>                                         
										 <th style="text-align: center">Retail</th>
                                         <th style="text-align: center">Pemborong</th>
										 <th style="text-align: center">Toko</th>
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
?>
	
<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
<script src="<?php echo base_url('assets/scripts/custom/components-pickers.js')?>"></script>


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
            "url": "<?php echo site_url('inventory_promo/ajax_list')?>",
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
    $('.modal-title').text('Seting Master Promo'); // Set Title to Bootstrap modal title
}

function edit_data(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('inventory_promo/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
			$('[name="id"]').val(data.id);
            $('[name="tglawal"]').val(data.tglawal);
			$('[name="tglakhir"]').val(data.tglakhir);
            $('[name="kodeitem1"]').val(data.kodeitem);
			$('[name="kodeitem2"]').val(data.bnsitem);
            $('[name="satuan1"]').val(data.satuan);
			$('[name="satuan2"]').val(data.bnssat);
			$('[name="qty1"]').val(data.qty);
            $('[name="qty2"]').val(data.bnsqty);
			
			$('[name="harga11"]').val(data.hrg1);
			$('[name="harga21"]').val(data.hrg2);
			$('[name="harga31"]').val(data.hrg3);
			
			$('[name="harga12"]').val(data.bnshrg1);
			$('[name="harga22"]').val(data.bnshrg2);
			$('[name="harga32"]').val(data.bnshrg3);
			setitem2(data.kodeitem,1);
			setitem2(data.bnsitem,2);
			
			
			
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
        url = "<?php echo site_url('inventory_promo/ajax_add')?>";
    } else {
        url = "<?php echo site_url('inventory_promo/ajax_update')?>";
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
    if(confirm('Yakin data barang dengan kode '+id+' ini akan dihapus ?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('inventory_promo/ajax_delete')?>/"+id,
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
  
  xhttp.open("GET", "<?php echo base_url(); ?>inventory_promo/cetak/"+str, true);  
  xhttp.send();
}


function setitem(str, jns) {   
  var xhttp;      
	$.ajax({
        url : "<?php echo base_url();?>inventory_promo/getinfoitem/"+str,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {					
            if(jns==1){		
				$('[name="namabarang1"]').val(data.namabarang);
				$('[name="qty1"]').val(data.qty);
				$('[name="satuan1"]').val(data.satuan);	
				$('[name="harga11"]').val(data.hargajual1);	
				$('[name="harga21"]').val(data.hargajual2);	
				$('[name="harga31"]').val(data.hargajual3);	
			} else {
			    $('[name="namabarang2"]').val(data.namabarang);
				$('[name="qty2"]').val(data.qty);
				$('[name="satuan2"]').val(data.satuan);	
				$('[name="harga12"]').val(data.hargajual1);	
				$('[name="harga22"]').val(data.hargajual2);	
				$('[name="harga32"]').val(data.hargajual3);	

			}
		}
	});	    
}

function setitem2(str, jns) {   
  var xhttp;      
	$.ajax({
        url : "<?php echo base_url();?>inventory_promo/getinfoitem/"+str,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {					
            if(jns==1){		
				$('[name="namabarang1"]').val(data.namabarang);
			} else {
			    $('[name="namabarang2"]').val(data.namabarang);
			}
		}
	});	    
}



</script>

<script>
	$(document).ready(function() {
		
		$('.print_laporan').on("click", function(){
		$('.modal-title').text('INVENTORY');
		var no_daftar= this.id;
		$("#simkeureport").html('<iframe src="<?php echo base_url();?>inventory_promo/cetak/'+no_daftar+'" frameborder="no" width="100%" height="420"></iframe>');
		});	
	});
	
   jQuery(document).ready(function() {
        ComponentsPickers.init();
   });
	
	</script>	

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Seting Master Promo</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        
						<input type="hidden" name="id">
						<table class="table">
						<!--table class="table table-striped table-bordered table-condensed table-scrollable"-->
						<tr>
						  <th>Tgl. Awal</th>
						  <th>Tgl. Akhir</th>
						  <th>Kode Item</th>
						  <th>Nama Barang</th>
						  <th>Qty</th>
						  <th>Satuan</th>
						  <th>Retail</th>
						  <th>Pemborong</th>
						  <th>Toko</th>
						  <th>&nbsp</th>
						</tr>
						
						<tr>
						  <td>
							<div class="input-icon">
								<i class="fa fa-calendar"></i>
								<input name="tglawal" class="form-control date-picker input-small" type="text" value="<?php echo date('d-m-Y');?>" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="" onkeypress="return tabE(this,event)"/>
							</div>
							    
						  </td>
						  <td>
							<div class="input-icon">
								<i class="fa fa-calendar"></i>
								<input name="tglakhir" class="form-control date-picker input-small" type="text" value="<?php echo date('d-m-Y');?>" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="" onkeypress="return tabE(this,event)"/>
							</div>
							    
						  </td>
						  <td>
						    <select required name="kodeitem1" id="kodeitem1" class="form-control input-small" onchange="setitem(this.value,1)">
                                    <option value="">--Pilih --</option>
									<?php 
									foreach($item as $db) {?>
                                    <option value="<?php echo $db['kodeitem'];?>"><?php echo $db['kodeitem'];?></option>
                                    <?php } ?>
                            </select>
							<span class="help-block"></span>
						  </td>
						  <td><input name="namabarang1" class="form-control input-medium" disabled></td>
						  <td><input name="qty1" class="form-control input-xsmall"></td>
						  <td><input name="satuan1" class="form-control input-xsmall" readonly></td>
						  <td><input name="harga11" class="form-control input-small"></td>
						  <td><input name="harga21" class="form-control input-small"></td>
						  <td><input name="harga31" class="form-control input-small"></td>
						</tr>
						<tr>
						  <th colspan="2">Bonus</th>
						  <th>Kode Item</th>
						  <th>Nama Barang</th>
						  <th>Qty</th>
						  <th>Satuan</th>
						  <th>Retail</th>
						  <th>Pemborong</th>
						  <th>Toko</th>
						</tr>
						<tr>
						 <td colspan="2"></td>
						 <td>
						    <select required name="kodeitem2" id="kodeitem2" class="form-control input-small" onchange="setitem(this.value,2)">
                                    <option value="">--Pilih --</option>
									<?php 
									foreach($item as $db) {?>
                                    <option value="<?php echo $db['kodeitem'];?>"><?php echo $db['kodeitem'];?></option>
                                    <?php } ?>
                            </select>
							<span class="help-block"></span>
						  </td>
						  <td><input name="namabarang2" class="form-control input-medium" disabled></td>
						  <td><input name="qty2" class="form-control input-xsmall"></td>
						  <td><input name="satuan2" class="form-control input-xsmall" readonly></td>
						  <td><input name="harga12" class="form-control input-small"></td>
						  <td><input name="harga22" class="form-control input-small"></td>
						  <td><input name="harga32" class="form-control input-small"></td>
						</tr>
						
						  
						  
						   
						</table>
						
						
                        <div class="form-group">                          
			                <div id="txtHint"></div>			
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

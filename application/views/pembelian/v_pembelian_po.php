<?php 
	$this->load->view('template/headerfull');
    $this->load->view('template/bodyfull');    	  
?>	

<link href="<?php echo base_url()?>assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/clockface/css/clockface.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/bootstrap-select/bootstrap-select.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/select2/select2-metronic.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/jquery-multi-select/css/multi-select.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css"/>
<link href="<?php echo base_url()?>assets/plugins/data-tables/DT_bootstrap.css" rel="stylesheet" />
<link href="<?php echo base_url()?>assets/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url()?>assets/css/custom.css" rel="stylesheet" type="text/css"/>
			
<style>	
	div.tableContainer {
		clear: both;
		border: 0px solid #963;
		height: 100px;
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
		height: 100px;
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
	
			<div class="row profiles">
				<div class="col-md-12">
					
									<div class="portlet box blue">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-reorder"></i>Purchase Order
										</div>
										<div class="tools">											
											<a href="<?php echo base_url();?>dashboard" class="remove"></a>
										</div>
										
									</div>
									<div class="portlet-body form">
										<form id="frmentry" action="#" class="form-horizontal" method="post">
										    <!--input type="hidden" name="namae" value="<?php echo $addr->nama_usaha;?>">
											<input type="hidden" name="alamat1e" value="<?php echo $addr->alamat1;?>">
											<input type="hidden" name="alamat2e" value="<?php echo $addr->alamat2;?>">
											<input type="hidden" name="kdpose" value="<?php echo $addr->kodepos;?>">
											<input type="hidden" name="telpe" value="<?php echo $addr->telpon;?>"-->
											
											<div class="form-body">
											<div id="tableContainer" class="tableContainers">
											  <table border="0" class="tables" cellspacing="0" >
											   <tr>											    
											    <th width="10%">P.O No.</th>
												<th width="1%">:</th>
												<th width="20%"><span class="form-controls input-small" name="nopo" ><?php echo $nopo;?></span></th>
												<input type="hidden" name="kodepo" value="<?php echo $nopo;?>" >
												<th width="10%"></th>
												<th width="10%">Tgl</th>
												<th width="1%">:</th>
												<th width="10%" style="text-align:right"><span class="form-controls input-small" name="tanggal" id="tanggal"><?php echo date('d-M-Y');?></span></th>
												<th></th>
											   </tr>	
											   <tr>
											    <td>Kode Sup</td>
												<td>:</td>
												<td>
												   <select name="supp" class="form-controls input-small select2me"  onchange="getsuplier(this.value);showentry()">            											
													  <option value="">-- Pilih ---</option>
													  <?php 
														foreach($supp  as $row){?>
														<option value="<?php echo $row->kode;?>"><?php echo $row->kode;?></option>
													  <?php } ?>
            										</select>
												</td>
												<td>Kredit :
												  <select name="kredit">
												     <option value="Y">Y</option>
													 <option value="T">T</option>
													
												  </select>
												</td>
												<td><button type="button" class="btns btn-warning" onclick="showprofile()" >Kirim</button></td>
												<td></td>
												<td></td>
											    <td></td>
											   </tr>
												<tr>
											    <td>Nama</td>
												<td>:</td>
												<td><span class="form-controls" name="namasupp"></span></td>
												<td>HP : </td>
												<td><span class="form-controls" name="hp"></span></td>
												<td></td>
												<td></td>
												<td></td>
											   </tr>
											   <tr>
											    <td>Alamat1</td>
												<td>:</td>
												<td><label name="alamat1"></label></td>
												<td></td>
												<td style="font-size:12px;"><b><i>Subtotal</b></i></td>
												<td>:</td>
												<td style="font-size:12px;text-align:right"><input style="text-align:right" type="text" name="subtotal" value="0"></td>
												<td></td>
											   </tr>
											   <tr>
											    <td>Alamat2</td>
												<td>:</td>
												<td><label name="alamat2"></label></td>
												<td></td>
												<td style="font-size:12px;"><b><i>DPP</b></i></td>
												<td>:</td>
												<td style="font-size:12px;text-align:right"><input style="text-align:right" type="text" name="dpp" value="0"></td>
												<td></td>
											   </tr>
											   <tr>
											    <td>Kota</td>
												<td>:</td>
												<td><label name="kota"></label></td>
												<td></td>
												<td style="font-size:12px;"><b><i>PPN</b></i>
												   <select name="cppn" onchange="getPPN(this.value)" onclick="getPPN(this.value)">
												     <option value="T">T</option>
													 <option value="I">I</option>
													 <option value="E">E</option>
												  </select>
												</td>
												<td>:</td>
												<td style="font-size:12px;text-align:right"><input style="text-align:right" type="text" name="ppn" value="0"></td>
												<td></td>
											   </tr>
											   <tr>
											    <td>KdPos</td>
												<td>:</td>
												<td><label name="kodepos"></label></td>
												<td></td>
												<td style="font-size:12px;"><b><i>Ongkir</b></i></td>
												<td>:</td>
												<td style="font-size:12px;text-align:right"><input style="text-align:right" type="text" name="ongkir" value="0" onchange="totalsi();"></td>
												<td></td>
											   </tr>
											   <tr>
											    <td>Ket</td>
												<td>:</td>
												<td><label name="kota"></label></td>
												<td></td>
												<td style="font-size:12px;"><b><i>Total</b></i></td>
												<td>:</td>
												<td style="font-size:12px;text-align:right"><input style="text-align:right" type="text" name="totalpo" value="0"></td>
												<td></td>
											   </tr>
											  </table>
											</div>
												
												
                    						
										 </div>	
										 
										 <div class="row">
												 <div class="col-md-12">
                                                   
							                    	<table class="table table-bordereds">
								                      <tr>                    								
                    									<th width="10%" style="text-align: center">Kode Item</th>														
                    									<th width="20%" style="text-align: center">Jenis Barang</th>
                    									<th width="6%" style="text-align: center">Sat</th>
                    									<th width="6%" style="text-align: center">Qty</th>
														<th width="10%" style="text-align: center">Harga</th>
														<th width="10%" style="text-align: center">Total</th>
														<th width="2%" style="text-align: center">Action</th>
														<th width="32%" style="text-align: center"></th>
                    								</tr>
                    								</table>
													<div id="_entry"></div>

													<table class="table table-bordereds">
													  <tr>
													
														<td width="10%">
														    <select name="kodeitem" class="form-control select2me"  onchange="getproduk(this.value)">            											
															  <option value="">-- Item ---</option>
                                                              <?php 
									                            foreach($item  as $row){?>
            													<option value="<?php echo $row->kodeitem;?>"><?php echo $row->kodeitem.' - '.$row->namabarang;?></option>
                                                              <?php } ?>
            												</select>								                
														
														</td>
																									                                                       
                                                        <td width="19%" >
														    <input name="namabarang" type="text" class="form-control" readonly>															
														</td>
                                                        <td width="6%" ><input name="sat"   type="text" class="form-control rightJustified" readonly></td>
                                                        <td width="6%" ><input name="qty"  id="qty" type="text" class="form-control rightJustified"  value="1" onkeypress="totalline()" onchange="totalline();;next_item(2)"></td>
														<td width="10%" ><input name="harga"  type="text" class="form-control rightJustified" value="0" onchange="totalline()" onkeypress="return tabE(this,event);totalline()"></td>
														<td width="10%" ><input name="total"  type="text" class="form-control rightJustified" readonly></td>
														<td width="2%" ><a class="btn btn-sm btn-success" onclick="save();"><i class="glyphicon glyphicon-plus"></i></a></td>
														<td width="32%" ></td>
														<input type="hidden" name="hpp">
								                      </tr>
													</table>
													</div>
								                   </div>
												   <div class="form-actions">
												    
													<p align="left"> 
													<button type="button" class="btn btn-danger"  onclick="batalsi()">Batal</button>
													<button type="button" class="btn btn-primary" onclick="simpanhdr()" id="btnSave2">Simpan</button>
                                                    											
													</p>
													
											       </div>
												
											   
												</div>
												
										</form>
									</div>
								
								</div>
							<!--/div-->
						</div>
					</div>
				</div>
			</div>
		</div>

<?php
  //$this->load->view('template/footero');
?>

<script src="<?php echo base_url()?>assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/bootstrap/js/bootstrap2-typeahead.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/jquery-multi-select/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/clockface/js/clockface.js"></script>
<script src="<?php echo base_url()?>assets/scripts/custom/components-dropdowns.js"></script>
<script src="<?php echo base_url()?>assets/scripts/custom/components-pickers.js"></script>
<script src="<?php echo base_url()?>assets/scripts/custom/form-wizard.js"></script>
<script src="<?php echo base_url()?>assets/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/scripts/core/app.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/scripts/custom/jam.js" type="text/javascript"></script>
<script>

var subtotal;

showentry();

function next_item(no){
	if (no==1){
	  $('[name="qty"]').focus();
	} if (no==2){
	  $('[name="disc"]').focus();
	} 
} 

function getPPN(type){
	subtotal = $('[name="subtotal"]').val();
	if(type=='T') {
		$('[name="ppn"]').val(0);
		$('[name="dpp"]').val(subtotal);	
	} else 
	if(type=='I'){
		var dpp1= subtotal * (10/11);
		var dpp = Math.round(subtotal * (10/11));
		var ppn = Math.round(dpp1 * (10/100));
		$('[name="ppn"]').val(ppn);
		$('[name="dpp"]').val(dpp);	
	} else 
	if(type=='E'){
		var dpp = subtotal;
		var ppn = Math.round(dpp * (10/100));
		$('[name="ppn"]').val(ppn);
		$('[name="dpp"]').val(dpp);	
	}	
	totalsi();
}

function getsuplier(str) { 
  var xhttp;      
	$.ajax({
        url : "<?php echo base_url();?>pembelian_po/getsuplier/"+str,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {					
            $('[name="namasupp"]').text(data.nama);						
			$('[name="alamat1"]').text(data.alamat1);						
			$('[name="alamat2"]').text(data.alamat2);						
			$('[name="kota"]').text(data.kota);	
			$('[name="hp"]').text(data.hp);	
			$('[name="kodepos"]').text(data.kodepos);							
		}
	});	    
}

function getproduk(str) { 
  var tipecust = $('[name="tipecust"]').val();
  var xhttp;      
	$.ajax({
        url : "<?php echo base_url();?>pembelian_po/getproduk/"+str,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {					
		    $('[name="namabarang"]').val(data.namabarang);						
			$('[name="sat"]').val(data.satuan);						
			$('[name="harga"]').val(data.hargabeliakhir);							
			totalline();	
		}
	});	    
}


function resetentry(){
	$('[name="kodeitem"]').val('');						
	$('[name="namabarang"]').val('');						
	$('[name="qty"]').val(1);
    $('[name="harga"]').val(0);							
	$('[name="total"]').val(0);						
	$('[name="kodeitem"]').focus();	
}

function showentry() {
  var nosi = $('[name="kodepo"]').val();
  
  if(nosi==''){
	alert('nomor PO kosong ...');  
  } else {
  var str = nosi;  
  var xhttp;
  if (str == "") {
    document.getElementById("_entry").innerHTML = "";
    return;
  }
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    document.getElementById("_entry").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "<?php echo base_url(); ?>pembelian_po/getentry/"+str, true);  
  xhttp.send();
  }
  totalsi();
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
	
function totalsi(){
  var nosi = $('[name="kodepo"]').val();
  var ongkir = $('[name="ongkir"]').val();
  var ppn = $('[name="ppn"]').val();
  var dpp = $('[name="dpp"]').val();
  
  var str = nosi;  
  var xhttp;
  if (str == "") {
    document.getElementById("subtotal").Value = "0";
    return;
  }
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    $('[name="subtotal"]').val(this.responseText);
	if(dpp==0){
	$('[name="dpp"]').val(this.responseText);
	$('[name="totalpo"]').val(parseInt(this.responseText)+parseInt(ongkir)+parseInt(ppn));
	} else {
	$('[name="totalpo"]').val(parseInt(dpp)+parseInt(ongkir)+parseInt(ppn));
	}
    }
  };
  xhttp.open("GET", "<?php echo base_url(); ?>pembelian_po/gettotalsi/"+str, true);  
  xhttp.send();    
}

function totalline(){
  var stot = $('[name="qty"]').val()*$('[name="harga"]').val();
  var gtot = stot;
  $('[name="total"]').val(gtot);  
  totalsi();  
  $('[name="disctot"]').change();
}

function save()
{
 
    var url;

    url = "<?php echo site_url('pembelian_po/ajax_add')?>";
    
    $.ajax({
        url : url,
        type: "POST",
        data: $('#frmentry').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
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

function saveKirim()
{
 
    var url;

    url = "<?php echo site_url('pembelian_po/ajax_edit_kirim')?>";
	
	var kodepo  = $('[name="kodepo"]').val();
	var kota    = $('[name="kotae"]').val();
	var namae   = $('[name="namae"]').val();
	var alamat1e= $('[name="alamat1e"]').val();
	var alamat2e= $('[name="alamat2e"]').val();
	var kdpose= $('[name="kdpose"]').val();
	var telpe= $('[name="telpe"]').val();
	
	var sql = 'kodepo='+kodepo+'&kota='+kota+'&namae='+namae+'&alamat1e='+alamat1e+'&alamat2e='+alamat2e+'&kdpose='+kdpose+'&telpe='+telpe;    
    $.ajax({
        url : url,
        type: "POST",
        data: sql,
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
               
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


function simpanhdr()
{
	
    $('#btnSave2').text('Simpan...'); //change button text
    $('#btnSave2').attr('disabled',true); //set button disable 
    var url;

    url = "<?php echo site_url('pembelian_po/ajax_add_header')?>";
    
    $.ajax({
        url : url,
        type: "POST",
        data: $('#frmentry').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) 
            {
				saveKirim();
                showentry();
				resetentry();
				$('[name="disctot"]').change();
				location.reload();
            }
            else
            {               
				
            }
            $('#btnSave2').text('Simpan'); //change button text
            $('#btnSave2').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave2').text('Simpan'); //change button text
            $('#btnSave2').attr('disabled',false); //set button enable 

        }
    });
	
}

function delete_data(id)
{
    if(confirm('Yakin item barang ini akan dihapus ?'))
    {
        $.ajax({
            url : "<?php echo site_url('pembelian_po/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                
                //reload_table();
				showentry();
				
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

function update_data(id, kirim)
{
	if(kirim){
		ket = 'akan dibawa sendiri ?';
		skirim = 'Y';
	} else {
		ket = 'batal dibawa sendiri ?';
		skirim = 'T';
	}
    if(confirm('Yakin item barang ini '+ket))
    {
		var param = id+'~'+skirim;
		
        $.ajax({
            url : "<?php echo site_url('penjualan_sl/ajax_kirim')?>/"+param,
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


function batalsi()
{
	var id = $('[name="kodepo"]').val();
    if(confirm('Yakin PO '+id+' ini akan dibatalkan ?'))
    {
        $.ajax({
            url : "<?php echo site_url('pembelian_po/ajax_delete_all')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {                               
				showentry();
				location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}


jQuery(document).ready(function() {      
    FormWizard.init();
	ComponentsPickers.init(); 
    
   
});

function savecustomer()
{
    $('#btnSaveCustomer').text('saving...'); //change button text
    $('#btnSaveCustomer').attr('disabled',true); //set button disable 
    var url;

    url = "<?php echo site_url('penjualan_cs/ajax_add')?>";
    
    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#formcustomer').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#form_customer').modal('hide');
                location.reload();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSaveCustomer').text('Simpan'); //change button text
            $('#btnSaveCustomer').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSaveCustomer').text('save'); //change button text
            $('#btnSaveCustomer').attr('disabled',false); //set button enable 

        }
    });
}


function add_customer()
{
    $('#formcustomer')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
	
	$('#form_customer').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Customer'); // Set Title to Bootstrap modal title
}

function showprofile()
{
    $('#formprofile')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
	
	$('#form_profile').modal('show'); // show bootstrap modal
    //$('.modal-title').text('Profile'); // Set Title to Bootstrap modal title
}

</script>

<div class="modal fade" id="form_profile" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Alamat Pengiriman</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="formprofile" class="form-horizontal">
                    <div class="form-body">
					 <table class="table table-bordereds">
					      <tr>
							<td width="5%">Nama</td>																																								
							<td width="1%">:</td>																								   
							<td width="50%"><input name="namae" id="namae" type="text" class="form-control" value="<?php echo $addr->nama_usaha;?>">															
							</td>
						  </tr>
						  <tr>
							<td width="5%">Alamat1</td>																																								
							<td width="1%">:</td>																								   
							<td width="94%"><input name="alamat1e" id="alamat1e" type="text" class="form-control" value="<?php echo $addr->alamat1;?>">															
							</td>
						  </tr>
						  <tr>
							<td width="5%">Alamat2</td>																																								
							<td width="1%">:</td>																								   
							<td width="94%"><input name="alamat2e" id="alamat2e" type="text" class="form-control" value="<?php echo $addr->alamat2;?>">															
							</td>
						  </tr>
						  <tr>
							<td width="5%">Kota</td>																																								
							<td width="1%">:</td>																								   
							<td width="94%"><input name="kotae" id="kotae" type="text" class="form-control" value="<?php echo $addr->kota;?>">															
							</td>
						  </tr>
						  <tr>
							<td width="5%">Kdpos</td>																																								
							<td width="1%">:</td>																								   
							<td width="94%"><input name="kdpose" id="kdpose" type="text" class="form-control" value="<?php echo $addr->kodepos;?>">															
							</td>
						  </tr>
						  <tr>
							<td width="5%">Telp</td>																																								
							<td width="1%">:</td>																								   
							<td width="94%"><input name="telpe" id="telpe" type="text" class="form-control" value="<?php echo $addr->telpon;?>">															
							</td>
						  </tr>
						  
						</table>  
					   
                    
                </form>
            </div>
            <div class="modal-footer">
        
                <button type="button" class="btn btn-danger" data-dismiss="modal">OK</button>
            </div>
		  </div>	
        </div>
    </div>
</div>






</body>
</html>

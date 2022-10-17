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
											<i class="fa fa-reorder"></i>Purchasing
										</div>
										<div class="tools">											
											<a href="<?php echo base_url();?>dashboard" class="remove"></a>
										</div>
										
									</div>
									<div class="portlet-body form">
										<form id="frmentry" action="#" class="form-horizontal" method="post">										 
											<div class="form-body">
											<div id="tableContainer" class="tableContainers">
											  <table border="0" class="tables" cellspacing="0" >
											   <tr>											    
											    <th width="10%">P.U No.</th>
												<th width="1%">:</th>
												<th width="20%"><input type="text" class="form-controls input-small" name="nopu" ></th>
												
												<th width="20%">P.O No:
												    <select name="kodepo" class="form-controls input-small select2me"  onchange="getpo(this.value);showentry()">            											
													  <option value="">-- Pilih ---</option>
													  <?php 
														foreach($po  as $row){?>
														<option value="<?php echo $row->kodepo;?>"><?php echo $row->kodepo.' , '.$row->kodesup;?></option>
													  <?php } ?>
            										</select>
												</th>
												<th width="10%">Tgl</th>
												<th width="1%">:</th>
												<th width="10%" style="text-align:right">
												  <!--span class="form-controls input-small" name="tanggal" id="tanggal"><?php echo date('d-M-Y');?></span-->
												  <div class="input-icon">
													   <i class="fa fa-calendar"></i>
													   <input name="tanggal" id="tanggal" class="form-control date-picker input-small" type="text" value="<?php echo date('d-m-Y');?>" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="" onkeypress="return tabE(this,event)"/>
												   </div>
												
												</th>
												<th></th>
											   </tr>	
											   <tr>
											    <td>Kode Sup</td>
												<td>:</td>
												<td><span class="form-controls" name="supp"></span></td>												
												<td>HP : <span class="form-controls" name="hp"></span></td>
												<td><button type="button" class="btns btn-warning" onclick="showprofile()" >Kirim</button></td>
												<td></td>
												<td></td>
											    <td></td>
											   </tr>
												<tr>
											    <td>Nama</td>
												<td>:</td>
												<td><span class="form-controls" name="namasupp"></span></td>
												<td></td>
												
												<td style="font-size:12px;"><b><i>Subtotal</b></i></td>
												<td>:</td>
												<td style="font-size:12px;text-align:right"><input style="text-align:right" type="text" name="subtotal" value="0"></td>
												<td></td>
											   </tr>
											   <tr>
											    <td>Alamat1</td>
												<td>:</td>
												<td><label name="alamat1"></label></td>
												<td></td>
												<td style="font-size:12px;"><b><i>DPP</b></i></td>
												<td>:</td>
												<td style="font-size:12px;text-align:right"><input style="text-align:right" type="text" name="dpp" value="0" onchange="hitungulang();"></td>
												<td></td>
											   </tr>
											   <tr>
											    <td>Alamat2</td>
												<td>:</td>
												<td><label name="alamat2"></label></td>
												<td></td>
												<td style="font-size:12px;"><b><i>PPN : </b></i>
												   <label name="cppn"></label>												   
												</td>
												<td>:</td>
												<td style="font-size:12px;text-align:right"><input style="text-align:right" type="text" name="ppn" value="0" onchange="hitungulang();"></td>
												<td></td>
											   </tr>
											   <tr>
											    <td>Kota</td>
												<td>:</td>
												<td><label name="kota"></label></td>
												<td></td>
												<td style="font-size:12px;"><b><i>Ongkir</b></i></td>
												<td>:</td>
												<td style="font-size:12px;text-align:right"><input style="text-align:right" type="text" name="ongkir" value="0" onchange="hitungulang();"></td>
												<td></td>
											   </tr>
											   <tr>
											    <td>KdPos</td>
												<td>:</td>
												<td><label name="kodepos"></label></td>
												<td></td>
												<td style="font-size:12px;"><b><i>Total</b></i></td>
												<td>:</td>
												<td style="font-size:12px;text-align:right"><input style="text-align:right" type="text" name="totalpo" value="0"></td>
												<td></td>
											   </tr>
											   <tr>
											    <td>Ket</td>
												<td>:</td>
												<td><label name="kota"></label></td>
												<td></td>
												<td style="font-size:12px;"><b><i>Uang Muka</b></i></td>
												<td>:</td>
												<td style="font-size:12px;text-align:right"><input style="text-align:right" type="text" name="uangmuka" value="0"></td>
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
                    									<th width="6%" style="text-align: center">Qty PO</th>
														<th width="10%" style="text-align: center">Hrg Beli</th>
														<th width="6%" style="text-align: center">Sdh Krm</th>
														<th width="6%" style="text-align: center">Qty Krm</th>
														<th width="8%" style="text-align: center">Total</th>
														<th width="27%" style="text-align: center"></th>
                    								</tr>
                    								</table>
													<div id="_entry"></div>

													
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

//showentry();


function getsuplier(str) { 
  var xhttp;      
	$.ajax({
        url : "<?php echo base_url();?>pembelian_pu/getsuplier/"+str,
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

function getuangmuka(str) { 
  var xhttp;      
	$.ajax({
        url : "<?php echo base_url();?>pembelian_pu/getum/"+str,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {					
            $('[name="uangmuka"]').val(Math.abs(data.saldojln));						
		}
	});	    
}


function getpo(str) { 
  var xhttp;      
	$.ajax({
        url : "<?php echo base_url();?>pembelian_pu/getpo/"+str,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {					
		    total = parseInt(data.dpp)+parseInt(data.ppn)+parseInt(data.ongkir);
            $('[name="supp"]').text(data.kodesup);						
			$('[name="dpp"]').val(data.dpp);	
			$('[name="subtotal"]').val(data.dpp);	
			$('[name="ppn"]').val(data.ppn);	
			$('[name="ongkir"]').val(data.ongkir);	
			$('[name="cppn"]').text(data.typeppn);	
			$('[name="totalpo"]').val(total);	
			
			$('[name="tipeppn"]').text(data.typeppn);				
			$('[name="namae"]').val(data.namakirim);	
			$('[name="alamat1e"]').val(data.alamat1);	
			$('[name="alamat2e"]').val(data.alamat2);	
			$('[name="kotae"]').val(data.kota);	
			$('[name="kdpose"]').val(data.kodepos);	
			$('[name="telpe"]').val(data.telp);				
            getsuplier(data.kodesup);
            getuangmuka(data.kodesup); 			
		}
	});	    
}


function hitungulang(){
	var dpp   = $('[name="dpp"]').val();	
	var ppn   = $('[name="ppn"]').val();	
	var ongkir= $('[name="ongkir"]').val();	
	var total = parseInt(dpp)+parseInt(ppn)+parseInt(ongkir);
    $('[name="totalpo"]').val(total);	
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
  xhttp.open("GET", "<?php echo base_url(); ?>pembelian_pu/getentry/"+str, true);  
  xhttp.send();
  }
  //totalsi();
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
	
function batalisi()
{
	location.reload();
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
  xhttp.open("GET", "<?php echo base_url(); ?>pembelian_pu/_totalsi/"+str, true);  
  xhttp.send();    
}

function totalline(){
  var stot = $('[name="qtyterima"]').val()*$('[name="harga"]').val();
  var gtot = stot;
  $('[name="total"]').val(gtot);  
  totalsi();  
}

function simpanhdr()
{
	
    $('#btnSave2').text('Simpan...'); //change button text
    $('#btnSave2').attr('disabled',true); //set button disable 
    var url;

    url = "<?php echo site_url('pembelian_pu/ajax_add')?>";
    
    $.ajax({
        url : url,
        type: "POST",
        data: $('#frmentry').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) 
            {
				showentry();
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





jQuery(document).ready(function() {      
    FormWizard.init();
	ComponentsPickers.init(); 
    
   
});


function showprofile()
{    
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
                <form action="#" name="formprofile" id="formprofile" class="form-horizontal">
                    <div class="form-body">
					 <table class="table table-bordereds">
					      <tr>
							<td width="5%">Nama</td>																																								
							<td width="1%">:</td>																								   
							<td width="50%"><input name="namae" id="namae" type="text" class="form-control">															
							</td>
						  </tr>
						  <tr>
							<td width="5%">Alamat1</td>																																								
							<td width="1%">:</td>																								   
							<td width="94%"><input name="alamat1e" id="alamat1e" type="text" class="form-control" >															
							</td>
						  </tr>
						  <tr>
							<td width="5%">Alamat2</td>																																								
							<td width="1%">:</td>																								   
							<td width="94%"><input name="alamat2e" id="alamat2e" type="text" class="form-control" >															
							</td>
						  </tr>
						  <tr>
							<td width="5%">Kota</td>																																								
							<td width="1%">:</td>																								   
							<td width="94%"><input name="kotae" id="kotae" type="text" class="form-control" >															
							</td>
						  </tr>
						  <tr>
							<td width="5%">Kdpos</td>																																								
							<td width="1%">:</td>																								   
							<td width="94%"><input name="kdpose" id="kdpose" type="text" class="form-control" >															
							</td>
						  </tr>
						  <tr>
							<td width="5%">Telp</td>																																								
							<td width="1%">:</td>																								   
							<td width="94%"><input name="telpe" id="telpe" type="text" class="form-control" >															
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

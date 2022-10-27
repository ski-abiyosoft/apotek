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
 hr.style-one {
    border: 0;
    height: 1px;
    background: #333;
    background-image: linear-gradient(to right, #ccc, #333, #ccc);
}

 hr.style-two {
    border: 0;
    height: 1px;
    background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));
}

hr.style-three {
    border: 0;
    border-bottom: 1px dashed #ccc;
    background: #999;
}

hr.style-six {
    border: 0;
    height: 0;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.3);
}

hr.style-seven {
    overflow: visible; /* For IE */
    height: 30px;
    border-style: solid;
    border-color: black;
    border-width: 1px 0 0 0;
    border-radius: 20px;
}
hr.style-seven:before { /* Not really supposed to work, but does */
    display: block;
    content: "";
    height: 30px;
    margin-top: -31px;
    border-style: solid;
    border-color: black;
    border-width: 0 0 1px 0;
    border-radius: 20px;
}
hr.style-eight {
    overflow: visible; /* For IE */
    padding: 0;
    border: none;
    border-top: medium double #333;
    color: #333;
    text-align: center;
}
hr.style-eight:after {
    content: "§";
    display: inline-block;
    position: relative;
    top: -0.7em;
    font-size: 1.5em;
    padding: 0 0.25em;
    background: white;
}
   
.CSSTableGenerator {

 margin:0px;padding:0px;

 width:100%;
 box-shadow: 10px 10px 5px #888888;

 border:1px solid #ffffff;

 

 -moz-border-radius-bottomleft:15px;

 -webkit-border-bottom-left-radius:15px;

 border-bottom-left-radius:15px;

 

 -moz-border-radius-bottomright:15px;

 -webkit-border-bottom-right-radius:15px;

 border-bottom-right-radius:15px;

 

 -moz-border-radius-topright:15px;

 -webkit-border-top-right-radius:15px;

 border-top-right-radius:15px;

 

 -moz-border-radius-topleft:15px;

 -webkit-border-top-left-radius:15px;

 border-top-left-radius:15px;

}.CSSTableGenerator table{

 width:100%;

 height:100%;

 margin:0px;padding:0px;

}.CSSTableGenerator tr:last-child td:last-child {

 -moz-border-radius-bottomright:15px;

 -webkit-border-bottom-right-radius:15px;

 border-bottom-right-radius:15px;

}

.CSSTableGenerator table tr:first-child td:first-child {

 -moz-border-radius-topleft:15px;

 -webkit-border-top-left-radius:15px;

 border-top-left-radius:15px;

}

.CSSTableGenerator table tr:first-child td:last-child {

 -moz-border-radius-topright:15px;

 -webkit-border-top-right-radius:15px;

 border-top-right-radius:15px;

}.CSSTableGenerator tr:last-child td:first-child{

 -moz-border-radius-bottomleft:15px;

 -webkit-border-bottom-left-radius:15px;

 border-bottom-left-radius:15px;

}.CSSTableGenerator tr:hover td{

 

}
.CSSTableGenerator tr:nth-child(odd){ background-color:#aad4ff; }

.CSSTableGenerator tr:nth-child(even)    { background-color:#ffffff; }
.CSSTableGenerator td{

 vertical-align:middle;

 

 

 border:1px solid #ffffff;

 border-width:0px 1px 1px 0px;

 text-align:left;

 padding:7px;

 font-size:10px;

 font-family:Georgia;

 font-weight:normal;

 color:#000000;

}.CSSTableGenerator tr:last-child td{

 border-width:0px 1px 0px 0px;

}.CSSTableGenerator tr td:last-child{

 border-width:0px 0px 1px 0px;

}.CSSTableGenerator tr:last-child td:last-child{

 border-width:0px 0px 0px 0px;

}

.CSSTableGenerator tr:first-child td{

  background:-o-linear-gradient(bottom, #005fbf 5%, #003f7f 100%); background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #005fbf), color-stop(1, #003f7f) );
 background:-moz-linear-gradient( center top, #005fbf 5%, #003f7f 100% );
 filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#005fbf", endColorstr="#003f7f"); background: -o-linear-gradient(top,#005fbf,003f7f);


 background-color:#005fbf;

 border:0px solid #ffffff;

 text-align:center;

 border-width:0px 0px 1px 1px;

 font-size:14px;

 font-family:Georgia;

 font-weight:bold;

 color:#ffffff;

}

.CSSTableGenerator tr:first-child:hover td{

 background:-o-linear-gradient(bottom, #005fbf 5%, #003f7f 100%); background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #005fbf), color-stop(1, #003f7f) );
 background:-moz-linear-gradient( center top, #005fbf 5%, #003f7f 100% );
 filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#005fbf", endColorstr="#003f7f"); background: -o-linear-gradient(top,#005fbf,003f7f);


 background-color:#005fbf;

}

.CSSTableGenerator tr:first-child td:first-child{

 border-width:0px 0px 1px 0px;

}

.CSSTableGenerator tr:first-child td:last-child{

 border-width:0px 0px 1px 1px;

}
 
    input[type="text"] { border: none }
	
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
		height: 80px;
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
					<!--div class="tabbable tabbable-custom tabbable-full-width"-->
						
						<div class="tab-content">
							
							<div class="tab-pane active" id="tab2">
									<div class="portlet box blue">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-reorder"></i>Penjualan Tunai
										</div>
										<div class="tools">											
											<a href="<?php base_url();?>dashboard" class="remove">
											</a>
										</div>
										
									</div>
									<div class="portlet-body form">
										<form id="frmentry" action="#" class="form-horizontal" method="post">
											<div class="form-body">
											<div id="tableContainers" class="tableContainers">
											  <table border="0" class="tables" cellspacing="0" >
											   <tr>											    
											    <th width="10%">Invoice No.</th>
												<th width="1%">:</th>
												<th width="20%">
												    <select name="noinv" id="noinv" class="form-controls select2me"  onchange="getinv(this.value);showentry(this.value);totalsi();">            											
													  <option value="">-- Pilih ---</option>
													  <?php 
														foreach($dafsi  as $row){?>
														<option value="<?php echo $row->kodesi;?>"><?php echo $row->kodesi;?></option>
													  <?php } ?>
            										</select>
												</th>												
												<th width="6%"></th>
												<th width="10%">Tanggal</th>
												<th width="1%">:</th>
												<th width="10%" style="text-align:right"><input type="text" name="tanggal" id="tanggal" value="<?php echo date('d-m-Y');?>"></span></th>
												<th></th>
												
											   </tr>	
											   <tr>
											    <td>Kode Cust</td>
												<td>:</td>
												<td><input type="text" name="kodecust" readonly></td>
												<td></td>
												<td>Kredit</td>
												<td>:</td>
												<td>N</td>
											    <td></td>
											   </tr>
												<tr>
											    <td>Nama</td>
												<td>:</td>
												<td><span class="form-controls" name="nama"></span></td>												
												<td></td>
												<td>Kirim</td>
												<td>:</td>
												<td>
												   <select name="kirim" class="form-controls">
												      <option value="Y">Y</option>
													  <option value="N" selected="true">N</option>
												   </select>
												</td>
												<td></td>
											   </tr>
											   <tr>
											    <td>Alamat 1</td>
												<td>:</td>
												<td colspan="6"><label name="alamat1"></label></td>
												
											   </tr>
											   <tr>
											    <td>Alamat 2</td>
												<td>:</td>
												<td colspan="6"><label name="alamat2"></label></td>
												
											   </tr>
											  </table>
											</div>
											<hr class="style-two">	
												
                    						
										 
										 
										    <div class="row">
												 <div class="col-md-9">
                                                  
							                    	<table class="table table-bordereds CSSTableGenerator">
								                      <tr>                    								
                    									<th width="10%" style="text-align: center">Kode Item</th>														
                    									<th width="20%" style="text-align: center">Jenis Barang</th>
                    									<th width="6%" style="text-align: center">Sat</th>
                    									<th width="6%" style="text-align: center">Qty</th>
														<th width="10%" style="text-align: center">Harga</th>
														<th width="6%" style="text-align: center">Disc</th>
														<th width="10%" style="text-align: center">Harga</th>
														<th width="2%" style="text-align: center">Sj</th>                                                        
														<!--th width="30%" style="text-align: center"></th-->
                    								</tr>
                    								</table>
													<span id="_entry"></span>

													
													</div>								                   
										    </div>
											<hr class="style-two">
											<div id="tableContainers" class="tableContainers">
											  <table border="0" class="tables" cellspacing="0" >
											   <tr>											    
											    <th width="10%">Uang Muka</th>
												<th width="1%">:</th>
												<th width="10%" ><input style="text-align:right" type="text" name="uangmuka" id="uangmuka" value="0" readonly></th>									
												<th width="10%"></th>
												<th width="2%"></th>
												<th width="10%">Subtotal</th>
												<th width="1%">:</th>
												<th width="10%"><input style="text-align:right" type="text" name="subtotal" id="subtotal" readonly></th>
												<th></th>
											   </tr>	
											   <tr>											    
											    <td></td>
												<td></td>
												<td></td>									
												<td></td>
												<td></td>
												<td>Disc %</td>
												<td>:</td>
												<td><input style="text-align:right" type="text" name="disc" id="disc" value="0" onchange="totalsi()" readonly></td>
												<td></td>
											   </tr>
											   <tr>											    
											    <td>Tunai</td>
												<td>:</td>
												<td>
												   <select name="kas" id="kas" class="form-controls select2me"  >            											
													  <option value="">-- Pilih ---</option>
													  <?php 
														foreach($kas  as $row){?>
														<option value="<?php echo $row->bank_kode;?>"><?php echo $row->bank_nama;?></option>
													  <?php } ?>
            										</select>												  
												</td>
												<td><input style="text-align:right" type="text" name="tunai" id="tunai" value="0" onchange="totalsi()" onclick="totalsi()"></td>
												<td></td>
												<td>Ongkir</td>
												<td>:</td>
												<td><input style="text-align:right" type="text" name="ongkir" id="ongkir" value="0" onchange="totalsi()" readonly></td>
												<td></td>
											   </tr>
											   <tr>											    
											    <td>CC/D Card</td>
												<td>:</td>
												<td>
												   <select name="namabank" id="namabank" class="form-controls select2me"  >            											
													  <option value="">-- Pilih ---</option>
													  <?php 
														foreach($bank  as $row){?>
														<option value="<?php echo $row->bank_kode;?>"><?php echo $row->bank_nama;?></option>
													  <?php } ?>
            										</select>												  
												</td>									
												<td><input style="text-align:right" type="text" name="bank" value="0" onchange="totalsi()" onclick="totalsi()"></td>
												<td></td>
												<td>Total</td>
												<td>:</td>
												<td><input style="text-align:right" type="text" name="total" id="total" readonly></td>
												<td></td>
											   </tr>
											   <tr>											    
											    <td>No. Kartu</td>
												<td>:</td>
												<td><input type="text" name="nokartu" placeholder="No. Kartu"></td>									
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
											   </tr>
											   <tr>											    
											    <td>Sisa</td>
												<td>:</td>
												<td></td>									
												<td><input style="text-align:right" type="text" name="sisa" id="sisa" value="0" readonly></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
											   </tr>
											   
											  </table>
											</div>
											
                                         </div>	
										 
										 
											 <div class="form-actions">
												<p align="center"> 
												<button type="button" class="btn btn-danger"  onclick="batalsi()">Batal</button>																							
												<button type="button" class="btn btn-primary" onclick="simpan()" id="btnSave">Simpan</button>
												
												</p>
														
											</div>	
											
										</form>
									</div>
								</div>
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

var uangmuka;
var ongkir;
var diskon;


function getinv(str) { 
  var xhttp;      
	$.ajax({
        url : "<?php echo base_url();?>kasir_tunai/getinv/"+str,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {					
		    uangmuka = data.saldoawal*-1;
			ongkir   = data.ongkir;
			diskon   = data.discamount;
		    $('[name="kodecust"]').val(data.kodecust);						
			$('[name="uangmuka"]').val(data.saldoawal*-1);	
            $('[name="nama"]').text(data.nama);						
			$('[name="alamat1"]').text(data.alamat1);						
			$('[name="alamat2"]').text(data.alamat2);
            $('[name="ongkir"]').val(data.ongkir);			
			$('[name="disc"]').val(data.discamount);
			$('[name="kirim"]').val(data.kirim);
		}
	});	    
}


function showentry( nosi ) {
  var nosi = nosi;
  
  if(nosi==''){
	alert('nomor SI kosong ...');  
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
  xhttp.open("GET", "<?php echo base_url(); ?>kasir_tunai/getentry/"+str, true);  
  xhttp.send();
  }
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
  var nosi   = $('[name="noinv"]').val();
  var tunai  = $('[name="tunai"]').val();
  var bank   = $('[name="bank"]').val();
  var str = nosi;  
  var xhttp;
  if (str == "") {
    document.getElementById("subtotal").innerHTML = "";
    return;
  }
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    document.getElementById("subtotal").value = formatCurrency(this.responseText);
	total = (parseInt(this.responseText))+parseInt(ongkir)-parseInt(diskon);
	document.getElementById("total").value = formatCurrency(total);
	if(tunai==0){
	  tunai = total;	
	}
	sisa  = parseInt(uangmuka)+parseInt(tunai)+parseInt(bank)-total;
	document.getElementById("sisa").value = sisa;
	document.getElementById("kembali").innerHTML = 'Rp '+formatCurrency(sisa)+',-';
    }
  };
  xhttp.open("GET", "<?php echo base_url(); ?>kasir_tunai/gettotalsi/"+str, true);  
  xhttp.send();    
}


function simpan()
{
	var kodesi = $('[name="noinv"]').val();
	if(kodesi!=''){
    $('#btnSave').text('Simpan...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    url = "<?php echo site_url('kasir_tunai/ajax_add')?>";
    
    $.ajax({
        url : url,
        type: "POST",
        data: $('#frmentry').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) 
            {
				cetak_inv();
				$('#form_kembali').modal('show'); 
				$('.modal-title').text('Kasir'); 
				//location.reload();
            }
            else
            {               
				
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
	
}



function update_data(id, kirim)
{
	if(kirim){
		ket = 'item barang ini akan dikirim ?';
		skirim = 'Y';
	} else {
		ket = 'batal dibawa sendiri ?';
		skirim = 'T';
	}
    if(confirm('Yakin item barang ini '+ket))
    {
		var param = id+'~'+skirim;
		
        $.ajax({
            url : "<?php echo site_url('kasir_tunai/ajax_kirim')?>/"+param,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                
				//showentry();
				
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
	var id = $('[name="nosi"]').val();
    if(confirm('Yakin Si '+id+' ini akan dibatalkan ?'))
    {
       location.reload();

    }
}

function cetak_inv()
{
	var id = $('[name="noinv"]').val();
	var sj = $('[name="kirim"]').val();
	window.open("<?php echo site_url('kasir_tunai/cetak_inv/')?>"+id,'_blank');    
	if(sj=='Y'){
	window.open("<?php echo site_url('kasir_tunai/cetak_sj/')?>"+id,'_blank');    
	}
}



jQuery(document).ready(function() {      
    FormWizard.init();
	ComponentsPickers.init(); 
    
   
});




</script>


<div class="modal fade" id="form_kembali" role="dialog">
    <div class="modal-dialog modal-small">
        <div class="modal-content">
            <!--div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data Promo</h3>
            </div-->
            <div class="modal-body form">
                <form action="#" id="formpromo" class="form-horizontal">
                    <div class="form-body">
					 <div class="row">
			             <h3 align="center">Kembali<h3>
						 <h2 align="center"><strong><span id="kembali"></span></strong><h2>

					   
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <p align="center">   
                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="location.reload()">Tutup</button>
				</p>
            </div>
		  </div>	
        </div>
    </div>
</div>



</body>
</html>

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
											<i class="fa fa-reorder"></i>Uang Muka Ke Supplier
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
											  <table border="0" class="tables" cellspacing="0" width="100%">											   											   
											   <tr>
											    <td width="10%">Kode Sup</td>												
												<td width="1%">:</td>
												<td width="34%">
												   <select name="supp" class="form-controls input-small select2me"  onchange="getsuplier(this.value);showpo(this.value)">            											
													  <option value="">-- Pilih ---</option>
													  <?php 
														foreach($supp  as $row){?>
														<option value="<?php echo $row->kode;?>"><?php echo $row->kode;?></option>
													  <?php } ?>
            										</select>
												</td>
												<!--td><button type="button" class="btns btn-warning" onclick="showprofile()" >Pilih</button></td-->
												<td width="5%"></td>
												<td width="10%">Kredit</td>					  																								
												<td width="1%">:</td>
												<td width="34%"><span class="form-controls" name="kredit"></span></td>
											    
											   </tr>
												<tr>
											    <td>Nama</td>
												<td>:</td>
												<td><span class="form-controls" name="namasupp"></span></td>
												<td></td>
												<td>Telpon </td>
												<td>:</td>
												<td><span class="form-controls" name="telpon"></span></td>
												<td></td>
												<td></td>
												<td></td>
											   </tr>
											   <tr>
											    <td>Alamat1</td>
												<td>:</td>
												<td><label name="alamat1"></label></td>
												<td></td>
												<td>Fax</td>
												<td>:</td>
												<td><span class="form-controls" name="fax"></span></td>
												<td></td>
											   </tr>
											   <tr>
											    <td>Alamat2</td>
												<td>:</td>
												<td><label name="alamat2"></label></td>
												<td></td>
												<td>Handphone</td>
												<td>:</td>
												<td><span class="form-controls" name="hp"></span></td>
												<td></td>
											   </tr>
											   <tr>
											    <td>Kota</td>
												<td>:</td>
												<td><label name="kota"></label></td>
												<td></td>
												<td>Contact Name</td>
												<td>:</td>
												<td><label name="contact"></label></td>
												<td></td>
											   </tr>
											   <tr>
											    <td>Kode Pos</td>
												<td>:</td>
												<td><label name="kodepos"></label></td>
												<td></td>
												<td>@Email</td>
												<td>:</td>
												<td><label name="email"></label></td>
												<td></td>
											   </tr>
											   <tr>
											    <td></td>
												<td></td>
												<td></td>
												<td></td>
												<td>TOP</td>
												<td>:</td>
												<td><label name="top"></label></td>
												<td></td>
											   </tr>
											   <tr>
											    <td>Batas Kredit</td>
												<td>:</td>
												<td><label name="bataskredit"></label></td>
												<td></td>
												<td>Tgl. Beli Akhir</td>
												<td>:</td>
												<td><label name="tglbeliakhir"></label></td>
												<td></td>
											   </tr>
											   <tr>
											    <td>Saldo Awal</td>
												<td>:</td>
												<td><label name="saldoawal"></label></td>
												<td></td>
												<td>Jml. Beli 1 Thn</td>
												<td>:</td>
												<td><label name="jmlbeli1thn"></label></td>
												<td></td>
											   </tr>											   
											   <tr>
											    <td>Saldo Jalan</td>
												<td>:</td>
												<td><label name="saldojalan"></label></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
											   </tr>
										</table>	  
												
                    					<hr>	
										            <table border="0" class="tables" cellspacing="0" width="100%">	
										 
										 		   
							                    	   <tr>
														<td width="10%">Kode Trans</td>												
														<td width="1%">:</td>
														<td width="3%">
														   <input type="text" name="kodetrans" value="<?php echo $nous;?>">
														</td>														
														<td width="8%" align="right">PO</td>					  																								
														<td width="1%"> :</td>
														<td width="3%">
														  <div id="listpo"></div>
														</td>
														<td width="5%"></td>
														<td width="10%">Jumlah PO</td>					  
                                                        <td width="1%">:</td>
														<td width="5%"><input type="text" name="jumlahpo" value="0"></td>														
														<td></td>
														
													   </tr>	
													   <tr>
														<td>Tanggal</td>												
														<td>:</td>
														<td>
														   <div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input name="tanggal" id="tanggal" class="form-control date-picker input-small" type="text" value="<?php echo date('d-m-Y');?>" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="" onkeypress="return tabE(this,event)"/>
														   </div>
														</td>														
														<td align="right"></td>					  																								
														<td> </td>
														<td></td>
														<td></td>
														<td></td>					  
                                                        <td></td>
														<td></td>														
														<td></td>
														
													   </tr>
													   <tr>
														<td>Kas/Bank</td>												
														<td>:</td>
														<td>
														  <select name="kasbank" class="form-controls input-small select2me"  >            											
														  <option value="">-- Pilih ---</option>
														  <?php 
															foreach($bank  as $row){?>
															<option value="<?php echo $row->bank_kode;?>"><?php echo $row->bank_nama;?></option>
														  <?php } ?>
														  </select>
														</td>														
														<td align="right">Rek Tujuan</td>					  																								
														<td> :</td>
														<td><input type="text" name="rektujuan"></td>
														<td></td>
														<td>Jumlah</td>					  
                                                        <td>:</td>
														<td><input type="text" name="jumlah" value="0"></td>														
														<td></td>
														
														
													   </tr>
                                                      </table>
													  
													  <table border="0" class="tables" cellspacing="0" width="100%">	
										 
										 		   
							                    	   <tr>
														<td width="10%">Keterangan</td>												
														<td width="1%">:</td>
														<td>
														   <input type="text" class="form-controls" name="ket" size="59%">
														</td>														
														<td></td>
													  
                                                      </table>
													  
													  
                                                    </div>  													  
													
													
								                   
												   <div class="form-actions">
												    
													<p align="left"> 
													<button type="button" class="btn btn-primary" onclick="simpanhdr()" id="btnSave2">Simpan</button>
													<button type="button" class="btn btn-danger"  onclick="batalisi()">Batal</button>
													
                                                    											
													</p>
													
											       </div>
												
											   
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



function getsuplier(str) { 
  var xhttp;      
	$.ajax({
        url : "<?php echo base_url();?>pembelian_us/getsuplier/"+str,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {					
		    var bataskredit = formatCurrency(data.bataskredit);
            $('[name="namasupp"]').text(data.nama);						
			$('[name="alamat1"]').text(data.alamat1);						
			$('[name="alamat2"]').text(data.alamat2);						
			$('[name="kota"]').text(data.kota);	
			$('[name="hp"]').text(data.hp);	
			$('[name="kodepos"]').text(data.kodepos);
			$('[name="kredit"]').text(data.kredit);
			$('[name="email"]').text(data.email);
			$('[name="top"]').text(data.top);
			$('[name="contact"]').text(data.contactname);
			$('[name="telpon"]').text(data.telp);
			$('[name="fax"]').text(data.fax);
			$('[name="saldoawal"]').text(formatCurrency(data.saldoawal));
			$('[name="saldojalan"]').text(formatCurrency(data.saldojln));
			$('[name="bataskredit"]').text(bataskredit);
			$('[name="tglbeliakhir"]').text(data.tglbeliakhir);
			$('[name="jmlbeli1thn"]').text(formatCurrency(data.jmlbelithn));
			
			
		}
	});	    
}

function showpo(str) {
  var xhttp;
  $('[name="jumlahpo"]').val(0); 
  if (str == "") {
    document.getElementById("listpo").innerHTML = "";
    return;
  }
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    document.getElementById("listpo").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "<?php echo base_url(); ?>pembelian_us/getlistpo/"+str, true);  
  xhttp.send();
}

function getpo(str) { 
  var xhttp;      
  if(str==""){
	$('[name="jumlahpo"]').val(0);  
  }  else  {
	$.ajax({
        url : "<?php echo base_url();?>pembelian_us/getpo/"+str,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {					
		    $('[name="jumlahpo"]').val(parseInt(data.dpp)+parseInt(data.ppn)+parseInt(data.ongkir));						
		}
	});	    
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

function batalisi()
{
	location.reload();
}

function simpanhdr()
{
	
    $('#btnSave2').text('Simpan...'); //change button text
    $('#btnSave2').attr('disabled',true); //set button disable 
    var url;

    url = "<?php echo site_url('pembelian_us/ajax_add_header')?>";
    
    $.ajax({
        url : url,
        type: "POST",
        data: $('#frmentry').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) 
            {
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


</script>




</body>
</html>

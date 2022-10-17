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
                      <span class="title-web">Finance <small>Penerimaan Piutang</small>
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
							<a href="<?php echo base_url();?>penjualan_penerimaan">
                               Daftar Penerimaan Piutang
                              							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>							
							<a href="">
                               Entri Penerimaan
							</a>
						</li>
					</ul>
				</div>
			</div>
            <div class="portlet box blue">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-reorder"></i>*Data Baru
					</div>
					
				</div>
				
				<div class="portlet-body form">									
				  <form id="frmpenjualan" class="form-horizontal" method="post">
					<div class="form-body">
					  <div class="tabbable tabbable-custom tabbable-full-width">
					    <ul class="nav nav-tabs">
							<li class="active">
								<a href="#tab1" data-toggle="tab">
                                   Penerimaan
								</a>
							</li>							
							
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab1">		
												<div class="row">
												    
                                                    <div class="col-md-6">
														 <div class="form-group">	
														   <label class="col-md-3 control-label">Penjamin<font color="red">*</font></label>
															<div class="col-md-9">
																 <select id="cust_id" name="cust_id" class="form-control select2_el_penjamin" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
															
															     </select>																														    													            															
															</div>
														   </div>
													</div>	
														
													<div class="col-md-6">
														<div class="form-group">
                                                            <label class="col-md-3 control-label">AR No.<font color="red">*</font></label>
													        <div class="col-md-9">
															    <input type="text" class="form-control rightJustified" name="arno"  id="arno" value="<?php echo $arno;?>" readonly>																																															
																
													        </div>

														</div>
													</div>
												</div>	
												
												
												<div class="row">	
                                                    <div class="col-md-6">
														 <div class="form-group">	
														   <label class="col-md-3 control-label">Kas/Bank<font color="red">*</font></label>
															<div class="col-md-9">
																 <select id="accountno" name="accountno" class="form-control select2_el_kasbank" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
															
															     </select>																														    													            															
															</div>
														   </div>
													</div>												
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Tgl Bayar<font color="red">*</font></label>
													        <div class="col-md-9">
														        <input id="ardate" name="ardate" class="form-control rightJustified" type="date" value="<?php echo date('Y-m-d');?>" />
													    	   
													        </div>



														</div>
													</div>
													
													
												</div>
																	
												<div class="row">

													<div class="col-md-6">
														<div class="form-group">
															<label class="col-md-3 control-label">Jenis Bayar<font color="red">*</font></label>
															<div class="col-md-3">
																<select name="jenisbayar" id="jenisbayar" class="form-control">
																	<option value="" disabled selected>Pilih...</option>
																	<?php
																		foreach($jenis_pembayaran as $key){
																			echo "<option value='".$key->id."'>".$key->jenis."</option>";
																		}
																	?>
																</select>		
															</div>
															<div class="col-md-6">
																 <input type="text" class="form-control rightJustified" name="cek_no"  id="cek_no" value="" >																																																																												    													            
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-3 control-label">Jumlah Bayar<font color="red">*</font></label>
													        <div class="col-md-9">
                                                                 <input type="text" class="form-control rightJustified" name="totalterima_view"  id="totalterima_view" value="0" readonly>	
																 <input type="hidden" class="form-control rightJustified" name="totalterima"  id="totalterima" value="0" readonly>	
																 
																 <!-- <span class="input-group-btn">
																	<a class="btn green" onclick="total()"><i class="fa fa-refresh"></i></a>
															     </span>	 -->
															  
															</div>
														</div>
													</div>

													<div class="col-md-6">
														<div class="form-group">
															<label class="col-md-3 control-label">Catatan<font color="red">*</font></label>
															<div class="col-md-9">
																<textarea id="keterangan" name="keterangan" class="form-control" rows="4" cols="50"></textarea>
															</div>
														</div>
													</div>

												</div>
												
												<button type="button" onclick="list_tagihan()" class="btn green"><i class="fa fa-plus"></i> Pilih Tagihan</button>
												&nbsp;&nbsp;&nbsp;<span id="alertVendor" style='color:red;'></span>
												<br/><br/>
												<table id="tabel_tagihan_pilihan" 
													class="table table-striped table-hover table-bordered" >
													<thead>
														<tr>
															<th style="text-align: center">Invoice No</th>
															<th style="text-align: center">Tgl Tagihan</th>
															<th style="text-align: center">Faktur Pasien</th>
															<th style="text-align: center">Nama Pasien</th>
															<th style="text-align: center">Total Tagihan</th>
															<th style="text-align: center">Dibayar Rp</th>
															<th style="text-align: center">Diskon</th>
															<th style="text-align: center">Akun Diskon</th>
														</tr>
													</thead>
													<tbody id="body_tagihan_pilihan"></tbody>
												</table>
											
							</div>
							<!-- tab1-->
							
							
							
						</div><!--tab-->	
						
						<div class="row">
							<div class="col-xs-8">
								<div class="wells">		
								   
                                   
									<button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
									   
									<div class="btn-group">
									  <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> Data Baru</button>                																							
									</div>
									<button type="button" class="btn red" onclick="javascript:history.go(-1)"><i class="fa fa-undo"></i>Kembali</button>
									
									<h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>								
								</div>															
							</div>
							
							<div class="col-xs-4 invoice-block">
							  <div class="well">
								<table>					  
								  <tr>
									<td width="40%"><strong>TOTAL</strong></td>
									<td width="1%"><strong>:</strong></td>
									<td width="59" align="right"><strong><span id="_vtotal"></span></strong></td>
								  </tr>
								  
								</table>
							   </div>	
							  </div>													
						</div>
													
													
					  </div>	
					</div>  
					
					
				   </form>
				</div>
            </div>
		</div>
	</div>
</div>

<?php
  $this->load->view('template/footer'); 
?>

<script>


var idrow  = 2;

function tambah(){
    var x=document.getElementById('datatable').insertRow(idrow);
    var td1=x.insertCell(0);
    var td2=x.insertCell(1);
    var td3=x.insertCell(2);
	var td4=x.insertCell(3);
	var td5=x.insertCell(4);
	var td6=x.insertCell(5);
	var td7=x.insertCell(6);    	
	var td8=x.insertCell(7);    	
	 	
	td1.innerHTML="<input name='faktur[]'      id=faktur"+idrow+" type='text' class='form-control'  readonly>";
	td2.innerHTML="<input name='tglfaktur[]'   id=tglfaktur"+idrow+" type='text' class='form-control'  readonly>";
	td3.innerHTML="<input name='totfaktur[]'   id=totfaktur"+idrow+" onchange='totalline("+idrow+")' value='1'  type='text' class='form-control rightJustified'  readonly>";
	td4.innerHTML="<input name='hutang[]'      id=hutang"+idrow+" type='text' class='form-control rightJustified' readonly >";
	td5.innerHTML="<input name='uangmuka[]'    id=uangmuka"+idrow+" type='text' class='form-control rightJustified' readonly >";
	td6.innerHTML="<input name='bayar[]'       id=bayar"+idrow+" onchange='totalline("+idrow+")'  type='text' class='form-control rightJustified' onclick='formatCurrency(this)'>";	
	td7.innerHTML="<input name='disc[]'        id=disc"+idrow+" onchange='totalline("+idrow+")' type='text' class='form-control rightJustified' onclick='formatCurrency(this)'>";
	td8.innerHTML="<input name='jumlah[]'      id=jumlah"+idrow+" type='text' class='form-control rightJustified' size='40%' readonly>";
	
    idrow++;
}


function list_tagihan()
{
    
    var cust_id = $('#cust_id').val();
    var arno = $('#arno').val();
    var accountno = $('#accountno').val();
    var ardate = $('#ardate').val();
    var jenisbayar = $('#jenisbayar').val();
    var cek_no = $('#cek_no').val();
    var keterangan = $('#keterangan').val();
    var totalterima = $('#totalterima').val();

    if(
        cust_id === '' || cust_id === null ||
        arno === '' || arno === null ||
        accountno === '' || accountno === null ||
        ardate === '' || ardate === null ||
        jenisbayar === '' || jenisbayar === null || 
        cek_no === '' || cek_no === null || 
        keterangan === '' || keterangan === null || 
        totalterima === '' || totalterima === null
    ) {
		swal('FINANCE - Penerimaan Piutang','Mohon isi form terlebih dahulu!',''); 
    } else {
        console.log("masuk");

        //Ajax Load data from ajax
        $.ajax({
            url : "<?php echo site_url('penjualan_penerimaan/getTagihan?vendor=')?>"+cust_id, // +"&startdate="+startdate+"&enddate="+enddate,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {           
                console.log(data);
                
                $('#daftar_tagihan').empty();
                $.each(data, function( key, value ) {

                    $('#daftar_tagihan').append("<tr>\
                				<td style='text-align: center'><input type='checkbox' id='pilih' name='pilih["+value.invoiceno+"]'></td>\
                                <td style='text-align: center'>"+value.invoiceno+"</td>\
                                <td style='text-align: center'>"+moment(value.invoicedate).format('DD/MM/YYYY')+"</td>\
                                <td style='text-align: center'>"+moment(value.duedate).format('DD/MM/YYYY')+"</td>\
                                <td style='text-align: center'>"+currencyFormat(value.totalnetrp)+"</td>\
                                </tr>");
                
                });
                
                $('#modal_form_large').modal('show'); // show bootstrap modal when complete loaded
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }
}
var idrow = 1;
function pilih_tagihan(){
    
    var fdt = $('#formDaftarTagihan').serialize();
    if(fdt == '') {
		swal('FINANCE - Penerimaan Piutang','Mohon pilih tagihan terlebih dahulu!',''); 
    } else {
        $.ajax({
            url: '<?php echo site_url('penjualan_penerimaan/pilih_daftar_tagihan/')?>',
            data: $('#formDaftarTagihan').serialize(),
            type: 'POST',
            dataType: "JSON",
            success: function(data) {
				console.log(data);

				// var new_tbody = document.createElement('tbody');
				// populate_with_new_rows(new_tbody);
				// old_tbody.parentNode.replaceChild(new_tbody, old_tbody)


				// $('#tabel_tagihan_pilihan').empty();
				var table = document.getElementById('tabel_tagihan_pilihan');


				while(table.rows.length > 1) {
					table.deleteRow(table.rows.length-1);				
				}


				idrow = table.rows.length + 1;

				console.log(idrow);

				var totalterima = 0;
                $.each(data, function( key, value ) {

					var x=document.getElementById('tabel_tagihan_pilihan').insertRow(1);
					var td1=x.insertCell(0);
					var td2=x.insertCell(1);
					var td3=x.insertCell(2);
					var td4=x.insertCell(3);
					var td5=x.insertCell(4);
					var td6=x.insertCell(5);
					var td7=x.insertCell(6);    	
					var td8=x.insertCell(7);    	
						
					// td1.innerHTML="<input name='faktur[]'      id=faktur"+idrow+" type='text' class='form-control'  readonly>";
					// td2.innerHTML="<input name='tglfaktur[]'   id=tglfaktur"+idrow+" type='text' class='form-control'  readonly>";
					// td3.innerHTML="<input name='totfaktur[]'   id=totfaktur"+idrow+" onchange='totalline("+idrow+")' value='1'  type='text' class='form-control rightJustified'  readonly>";
					// td4.innerHTML="<input name='hutang[]'      id=hutang"+idrow+" type='text' class='form-control rightJustified' readonly >";
					// td5.innerHTML="<input name='uangmuka[]'    id=uangmuka"+idrow+" type='text' class='form-control rightJustified' readonly >";
					// td6.innerHTML="<input name='bayar[]'       id=bayar"+idrow+" onchange='totalline("+idrow+")'  type='text' class='form-control rightJustified' onclick='formatCurrency(this)'>";	
					// td7.innerHTML="<input name='disc[]'        id=disc"+idrow+" onchange='totalline("+idrow+")' type='text' class='form-control rightJustified' onclick='formatCurrency(this)'>";
					// td8.innerHTML="<input name='jumlah[]'      id=jumlah"+idrow+" type='text' class='form-control rightJustified' size='40%' readonly>";
					
					td1.innerHTML = "<td style='text-align: center'>"+value.invoiceno+"</td>\
										<input type='hidden' id='invoiceno[]' name='invoiceno[]' value='"+value.invoiceno+"'>";
					td2.innerHTML = "<td style='text-align: center'>"+moment(value.tglposting).format('DD/MM/YYYY')+"</td>\
										<input type='hidden' id='tglposting[]' name='tglposting[]' value='"+value.tglposting+"'>";
					td3.innerHTML = "<td style='text-align: center'>"+(value.fakturno == null ? '' : value.fakturno)+"</td>\
										<input type='hidden' id='fakturno[]' name='fakturno[]' value='"+value.fakturno+"'>";
					td4.innerHTML = "<td style='text-align: center'>"+value.namapas+"</td>\
										<input type='hidden' id='namapas[]' name='namapas[]' value='"+value.namapas+"'>\
										<input type='hidden' id='noreg[]' name='noreg[]' value='"+value.noreg+"'>\
										<input type='hidden' id='rekmed[]' name='rekmed[]' value='"+value.rekmed+"'>\
										<input type='hidden' id='lunas[]' name='lunas[]' value='"+value.lunas+"'>";
					td5.innerHTML = "<td style='text-align: right'>"+currencyFormat(value.jumlahhutang)+"</td>\
										<input type='hidden' id='jumlahhutang[]' name='jumlahhutang[]' value='"+value.jumlahhutang+"'>";
					td6.innerHTML = "<td style='text-align: center'>"+currencyFormat(value.jumlahbayar)+"</td>\
										<input type='hidden' id='jumlahbayar[]' name='jumlahbayar[]' value='"+value.jumlahbayar+"'>";
					td7.innerHTML = "<td style='text-align: center'>"+currencyFormat(value.diskontotal)+"</td>\
										<input type='hidden' id='diskontotal[]' name='diskontotal[]' value='"+value.diskontotal+"'>";
					td8.innerHTML = "<td style='text-align: center'><center><select id='akundiskon[]' name='akundiskon[]' class='form-control select2_el_akundiskonadjust' data-placeholder='Pilih...' ><option value='633'>633 | Biaya Diskon</option></select></center></td>";

					idrow++;

					totalterima = (parseFloat(totalterima) + parseFloat(value.jumlahhutang)).toFixed(2);
					console.log(totalterima);
                	initailizeSelect2_akundiskonadjust();
				});

				$('#totalterima_view').val(currencyFormat(totalterima));
				$('#totalterima').val(totalterima);
				
				$('#_vtotal').text(currencyFormat(totalterima));

            },
            error: function(data) {
                swal('Pilih Daftar Tagihan Gagal', 'Failed ...', '');
            }
        });
        
        $('#modal_form_large').modal('toggle');
    }
}

function currencyFormat(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function save()
{	        
    
    var cust_id = $('#cust_id').val();
    var arno = $('#arno').val();
    var accountno = $('#accountno').val();
    var ardate = $('#ardate').val();
    var jenisbayar = $('#jenisbayar').val();
    var cek_no = $('#cek_no').val();
    var keterangan = $('#keterangan').val();
    var totalterima = $('#totalterima').val();
    var invoiceno  = $('input[name^="invoiceno"]').map(function(){return $(this).val();}).get();
	
	console.log($('#frmpenjualan').serialize());

	console.log(invoiceno.length);

    if(
        cust_id === '' || cust_id === null ||
        arno === '' || arno === null ||
        accountno === '' || accountno === null ||
        ardate === '' || ardate === null ||
        jenisbayar === '' || jenisbayar === null || 
        cek_no === '' || cek_no === null || 
        keterangan === '' || keterangan === null || 
        totalterima === '' || totalterima === null ||
		invoiceno.length == 0
    ) {
		swal('FINANCE - Penerimaan Piutang','Mohon isi form terlebih dahulu!',''); 
    } else {

		$.ajax({				
			url:'<?php echo site_url('penjualan_penerimaan/save/1')?>',				
			data:$('#frmpenjualan').serialize(),				
			type:'POST',
			dataType: "json",
			success:function(dt){
				console.log(dt);
                if (dt.status === true) {
					swal({
							title: "FINANCE-Penerimaan Piutang",
							html: "<p> No. Bukti   : <b>"+dt.data+"</b> </p>"+ 
							// "Tanggal :  " + moment(ardate).format('DD/MM/YYYY'),
                         	"<p>Tanggal :  " + moment(ardate).format('DD/MM/YYYY') + "</p>" +
                            "<p><b>Rp " + currencyFormat(totalterima) + "</b></p>",
							type: "info",
							confirmButtonText: "OK" 
							}).then((value) => {
									location.href = "<?php echo base_url()?>penjualan_penerimaan";
					});				
				} else {
                    swal('Pembayaran Hutang', 'Data gagal disimpan (' + dt.data + ')...', '');
				}
			},
			error:function(data){
				swal('FINANCE-Penerimaan Piutang','Data gagal disimpan ...',''); 		
			}
		});
	}	
}	


	function hapus(){
		if(idrow>2){
			var x=document.getElementById('datatable').deleteRow(idrow-1);
			idrow--;
			total();
		}
	}
	
  function total()
  {
    
   var table = document.getElementById('datatable');
   var rowCount = table.rows.length;

   tjumlah = 0;
   tdiskon = 0; 
   
   for(var i=1; i<rowCount; i++) 
   {
    var row = table.rows[i];
    
	jumlah      = row.cells[5].children[0].value;	
	diskon      = row.cells[6].children[0].value;    
    var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g,""));	
	var diskon1 = Number(diskon.replace(/[^0-9\.]+/g,""));

   	tjumlah  = tjumlah  + eval(jumlah1);			
   	tdiskon  = tdiskon  + eval(diskon1);			
   } 

   document.getElementById("_vsubtotal").innerHTML=formatCurrency1(tjumlah);
   document.getElementById("_vdiskon").innerHTML=formatCurrency1(tdiskon);   
   document.getElementById("_vtotal").innerHTML=formatCurrency1(tjumlah-tdiskon);
   document.getElementById("jumlahbayar").value=formatCurrency1(tjumlah-tdiskon);
  }
  
  function totalline(id)
  {
   	  
   var table = document.getElementById('datatable');
   var row = table.rows[id];       
   uangmuka    = row.cells[4].children[0].value;    
   jumlah      = row.cells[5].children[0].value;    
   diskon      = row.cells[6].children[0].value;
   
   hutang      = row.cells[3].children[0].value;    
   
   var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g,""));
   var diskon1 = Number(diskon.replace(/[^0-9\.]+/g,""));   
   var hutang1 = Number(hutang.replace(/[^0-9\.]+/g,""));
   var uangmuka1 = Number(uangmuka.replace(/[^0-9\.]+/g,""));   
   
   if((jumlah1+uangmuka1)>hutang1){
	  swal('PEMBELIAN','Jumlah pembayaran melebihi Hutang ...','');  
	  row.cells[5].children[0].value = 0; 
   }
   
   
   tot         = eval(jumlah1) - eval(diskon1);
   row.cells[7].children[0].value= formatCurrency1(tot);   
   total();
   
  }
  
   
	
function showfaktur() {
  var xhttp;
  var str = $('[name="supp"]').val(); 
  
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
  xhttp.open("GET", "<?php echo base_url(); ?>penjualan_penerimaan/getlistfaktur/"+str, true);  
  xhttp.send();
}

function getfaktur() { 
  var xhttp;      
  var str = $('[name=cust]').val();
  hapus();							
	$('[id=faktur1]').val('');
	$('[id=tglfaktur1]').val('');
	$('[id=totfaktur1]').val('');
	$('[id=hutang1]').val('');
	$('[id=bayar1]').val('');
	$('[id=disc1]').val('');	
	totalline(1);
  if(str==""){
	
  }  else  {
	$.ajax({
        url : "<?php echo base_url();?>penjualan_penerimaan/getfaktur/"+str,
        type: "GET",
        dataType: "JSON",
		
        success: function(data)
        {	
           
		    for(i=0; i <= data.length-1; i++){	
			hapus();
			}
			
            for(i=0; i <= data.length-1; i++){		
			  if(i>0){
		       tambah();
			  }
			  
			 
			  x = i+1;
			  document.getElementById("faktur"+x).value=data[i].kodesi;
			  document.getElementById("tglfaktur"+x).value=data[i].tglsi;		    
			  document.getElementById("totfaktur"+x).value=formatCurrency1(data[i].total);		    
              document.getElementById("hutang"+x).value=formatCurrency1(data[i].hutang);		    
			  document.getElementById("uangmuka"+x).value=formatCurrency1(data[i].uangmuka);		    
			  document.getElementById("bayar"+x).value=0;				  
			  document.getElementById("disc"+x).value=0;
			  totalline(x);
			}
			
		}
	});	    
  }	
}


</script>


<div class="modal fade" id="modal_form_large" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Daftar Invoice</h3>
            </div>
            <div class="modal-body">
                <form id="formDaftarTagihan" method="post">
                    <button type="button" id="btnSave" onclick="pilih_tagihan()" class="btn btn-primary">Proses Data Terpilih</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    &nbsp;&nbsp;
                    <span id='alertPilih' style='color:red;'></span>
                    <br><br>
                    <!-- <input type='hidden' id='vendor_id' name='vendor_id'></input> -->
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead class="breadcrumb">
                                <tr>
                                    <th style="text-align: center">Pilih</th>
                                    <th style="text-align: center">Invoice No</th>
                                    <th style="text-align: center">Tgl Invoice</th>
                                    <th style="text-align: center">Tgl Jatuh Tempo</th>
                                    <th style="text-align: center">Nilai Rp</th>
                                </tr>
                        </thead>
                        <tbody id="daftar_tagihan"></tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

			
<style>
/* .select2 select2-container select2-container--default select2-container--focus{
        width:165px !important;
    } */

.select2_el_akundiskonadjust {
    width: 165px !important;
}
</style>
	
</body>
</html> 

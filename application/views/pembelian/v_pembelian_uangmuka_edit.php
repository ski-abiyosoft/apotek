<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	 
    foreach($header as $rowh){}	
?>	


			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					<span class="title-unit">
                            &nbsp;<?php echo $this->session->userdata('unit'); ?> 
                        </span>
                      - 
                      <span class="title-web">Pembelian <small>Uang Muka Pembelian</small>
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
							<a href="<?php echo base_url();?>pembelian_uangmuka">
                               Daftar Uang Muka Pembelian
                              							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>							
							<a href="">
                               Edit Data
							</a>
						</li>
					</ul>
				</div>
			</div>
            <div class="portlet box blue">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-reorder"></i>Edi Data
					</div>
					<!--div class="tools">
						 <span class="label label-sm label-danger">										
						  REGISTER : 
						</span>

					</div-->
					
				</div>
				
				<div class="portlet-body form">									
				  <form id="frmpembelian" class="form-horizontal" method="post">
					<div class="form-body">
					   <div class="row">
							<div class="col-md-6">
								 <div class="form-group">
								   <label class="col-md-3 control-label">Pemasok</label>
									<div class="col-md-6">
									 <div class="input-group">
									  <select id="supp" name="supp" class="form-control input-medium select2me" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
									 
									<?php
									  foreach($supp as $row) { ?>
										<option value="<?php echo $row->kode;?>"><?php echo $row->nama;?></option>
									<?php } ?>
									</select>
									
									 </div>	 
									</div>
									</div>

							</div>
								
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-3 control-label">Nomor #</label>
									<div class="col-md-4">
										<input type="text" class="form-control input-medium" name="nomorbukti"  id="nomorbukti" value="<?php echo $rowh->kodeum;?>" readonly>																																															
										
									</div>

								</div>
							</div>
						</div>	
						
						
						<div class="row">												    												
							<div class="col-md-6">
								 <div class="form-group">
									<label class="col-md-3 control-label">Tanggal</label>
									<div class="col-md-4">
									   <input id="tanggal" name="tanggal" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d',strtotime($rowh->tglum));?>" />
									   
									</div>



								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-3 control-label">No. Faktur</label>
									<div class="col-md-4">
										<input type="text" class="form-control input-medium" placeholder="" name="nomorfaktur" id="nomorfaktur" value="<?php echo $rowh->nomorfaktur;?>">
									</div>

								</div>
							</div>
						
							
						</div>
						<div class="row">
						  <div class="col-md-6">
								 <div class="form-group">	
								   <label class="col-md-3 control-label">No. PO</label>
									<div class="col-md-6">
									  <div class="input-group">
										 	<input type="text" class="form-control input-medium" placeholder="" name="kodepo" id="kodepo" value="<?php echo $rowh->kodepo;?>">																													    													            
									  </div>
									</div>
								   </div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-3 control-label">Jumlah PO</label>
									<div class="col-md-4">
										<input type="text" class="form-control input-medium" placeholder="" name="jumlahpo" id="jumlahpo" value="<?php echo $rowh->jumlahpo;?>" readonly>
									</div>

								</div>
							</div>
						</div>
					    <h4><b><i>Pembayaran</i></b></h4>
						<div class="row">
						  <div class="col-md-6">
								 <div class="form-group">	
								   <label class="col-md-3 control-label">Uang Muka</label>
									<div class="col-md-6">
									  <div class="input-group">
										 <input type="text" class="form-control input-medium" placeholder="" name="uangmuka" id="uangmuka" value="<?php echo $rowh->jumlahum;?>" >																														    													            
									  </div>
									</div>
								   </div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-3 control-label">Keterangan</label>
									<div class="col-md-4">
										<input type="text" class="form-control input-medium" placeholder="" name="keterangan" id="keterangan" value="<?php echo $rowh->keterangan;?>" >
									</div>

								</div>
							</div>
						</div>
						<div class="row">
						  <div class="col-md-6">
								 <div class="form-group">	
								   <label class="col-md-3 control-label">Kas/Bank</label>
									<div class="col-md-6">
									  <div class="input-group">
										 <select id="kasbank" name="kasbank" class="form-control input-medium select2me" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
									<?php
									  foreach($bank as $row) { ?>
										<option value="<?php echo $row->kodeakun;?>"><?php echo $row->namaakun;?></option>
									<?php } ?>
									</select>																														    													            
									  </div>
									</div>
								   </div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-3 control-label">Rek. Tujuan</label>
									<div class="col-md-4">
										<input type="text" class="form-control input-medium" placeholder="" name="norek" id="norek" value="<?php echo $rowh->norektujuan;?>" >
									</div>

								</div>
							</div>
						</div>
					    <div class="form-actions">
							<button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
							<a class="btn yellow print_laporan" onclick="javascript:window.open(_urlcetak(),'_blank');" ><i class="fa fa-print"></i> Cetak</a>
							<div class="btn-group">
								<a href="<?php echo base_url()?>pembelian_uangmuka/entri" class="btn btn-success">
								<i class="fa fa-plus"></i>
								Data Baru
								</a>
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


function getnobukti() {
  var xhttp;
  
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    document.getElementById("nomorbukti").value = this.responseText;
    }
  };
 
  xhttp.open("GET", "<?php echo base_url(); ?>pembelian_pesanan/getnobukti", true); 
  xhttp.send();
}

function save()
{	        
    var noform   = $('[name="nomorbukti"]').val();    
	var nofaktur = $('[name="nomorfaktur"]').val(); 
    if(nofaktur==""){
		$.gritter.add({
						title: '<b><?php echo 'Pembelian';?></b>',
						text: 'Nomor Faktur untuk formulir '+noform+' masih kosong ',					
                        image: '<?php echo base_url('assets/img/logoi.png');?>',   						
						position: 'bottom',
						class_name: 'gritter-red',
					});
	} else {
	$.ajax({				
		url:'<?php echo site_url('pembelian_uangmuka/save/2')?>',				
		data:$('#frmpembelian').serialize(),				
		type:'POST',
		
		success:function(data){ 
		  $.gritter.add({
						title: '<b><?php echo 'Pembelian';?></b>',
						text: 'Uang Muka dengan nomor '+noform+' BERHASIL disimpan ',					
                        image: '<?php echo base_url('assets/img/logoi.png');?>',   						
						position: 'bottom',
						class_name: 'color success',
					});					
	
		},
		error:function(data){
			$("#error").show().fadeOut(5000);
			
			$.gritter.add({
						title: '<b><?php echo 'Pembelian';?></b>',
						text: 'Uang Muka dengan nomor '+noform+' GAGAL disimpan, periksa kembali data yang dimasukan dan lakukan SIMPAN ulang ',					
                        image: '<?php echo base_url('assets/img/logoi.png');?>',   						
						position: 'bottom',
						class_name: 'color warning',
					});
								
					
		}
		});
	}	
}	


function formatCurrency1(num) {
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
	num = num.substring(0,num.length-(4*i+3))+','+
	num.substring(num.length-(4*i+3));
	//return (((sign)?'':'-') + '' + num + '.' + cents);
	return (((sign)?'':'-') + '' + num);
	}

  function formatCurrency(fieldObj)
    {

        if (isNaN(fieldObj.value)) { return false; }
        fieldObj.value =formatCurrency1(fieldObj.value);
        return true;

    }

   
	
   
  
function tabE(obj,e){
      var e=(typeof event!='undefined')?window.event:e;// IE : Moz

      if(e.keyCode==13){
         var ele = document.forms[0].elements;

      for(var i=0;i<ele.length;i++){
          var q=(i==ele.length-1)?0:i+1;// if last element : if any other
      if(obj==ele[i]){ele[q].focus();break}
    }
    return false;
    }
    }

	
function showpo() {
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
  xhttp.open("GET", "<?php echo base_url(); ?>pembelian_faktur/getlistpo/"+str, true);  
  xhttp.send();
}

function getpo() { 
  var xhttp;      
  var str = $('[name=kodepo]').val();
  if(str==""){
	$('[id=jumlahpo]').val(0);
  }  else  {
	$.ajax({
        url : "<?php echo base_url();?>pembelian_uangmuka/getpo/"+str,
        type: "GET",
        dataType: "JSON",
		
        success: function(data)
        {		            
			$('[name=jumlahpo]').val(data.dpp);
		}
	});	    
  }	
}

function getpoheader() { 
  var xhttp;      
  var str = $('[name=kodepo]').val();
  if(str==""){	
  //$('[name="jumlahpo"]').val(0);
  }  else  {
	$.ajax({
        url : "<?php echo base_url();?>pembelian_uangmuka/getpoheader/"+str,
        type: "GET",
        dataType: "JSON",
		 
        success: function(data)
        {		            	                   
             total = parseInt(data.dpp)+parseInt(data.ppn)-parseInt(data.diskon)+parseInt(data.biayalain);		
			 $('[name="jumlahpo"]').val(total);
		}
	});	    
  }	
}  

function _urlcetak()
{
	var baseurl = "<?php echo base_url()?>";
	var param= $('[name="nomorbukti"]').val();				
    return baseurl+'pembelian_uangmuka/cetak/'+param;
}

</script>



	
</body>
</html> 

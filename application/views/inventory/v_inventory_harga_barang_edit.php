<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    
    $data_barang = $this->db->query("select * from inv_barang where kodeitem = '$barang'")->row();
?>	


			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					  Inventory <small>Harga Barang per Customer</small>
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
							<a href="<?php echo base_url();?>inventory_harga">
                               Daftar Harga Barang per Customer
                              							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>							
							<a href="">
                               Edit Harga
							</a>
						</li>
					</ul>
				</div>
			</div>
            <div class="portlet box blue">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-reorder"></i>*Edit Data 
					</div>
					
					
				</div>
				
				<div class="portlet-body form">									
				  <form id="frmharga" class="form-horizontal" method="post">
					<div class="form-body">
					  <div class="tabbable tabbable-custom tabbable-full-width">
					    <ul class="nav nav-tabs">
							<li class="active">
								<a href="#tab1" data-toggle="tab">
                                   <i class="fa fa-file"></i> 
								   Harga Barang
								</a>
							</li>
							<!--li class="">
								<a href="#tab2" data-toggle="tab">                                   
								   <i class="fa fa-info-circle"></i>
								   Info
								</a>
							</li-->
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab1">		
												
												<div class="row">												    												
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Tanggal</label>
													        <div class="col-md-4">
														        <input id="tanggal" name="tanggal" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d', strtotime($tanggal));?>" />
													    	   
													        </div>



														</div>
													</div>
													<div class="col-md-6">
                                                         <div class="form-group">	
                                                           <label class="col-md-3 control-label">Barang</label>
													        <div class="col-md-6">
															     <select name="barang" id="barang" class="select2_el_barang form-control input-large" onchange="infobarang()">															  
																  <option value="<?= $barang; ?>"><?= $data_barang->namabarang; ?></option>
																 </select>
															
                                                           </div>
														</div>    
													</div>
												</div>
												<div class="row">												    												
													<div class="col-md-6">
                                                        
													</div>
													<div class="col-md-6">
                                                         <div class="form-group">	
                                                           <label class="col-md-3 control-label">Harga Modal</label>
													        <div class="col-md-6">
															    <input type="text" name="hargamodal" id="hargamodal" value="<?= number_format($data_barang->hargabeli,2,'.',','); ?>" class="form-control rightJustified" readonly>
															
                                                           </div>
														</div>    
													</div>
												</div>
												
												
												
												

												<div class="row">
												 <div class="col-md-12">
                                                   	
													<table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
													<thead>
                                                      <tr>
                    									<th width="5%" style="text-align: center">Kode</th>
                    									<th width="20%" style="text-align: center">Nama Customer</th>
														<th width="5%" style="text-align: center">Margin (%)</th>
														<th width="5%" style="text-align: center">Partisipasi (%)</th>
														<th width="10%" style="text-align: center">Harga Gros (Rp)</th>
														<th width="5%" style="text-align: center">Diskon (%)</th>
														<th width="10%" style="text-align: center">Harga Jual Net (Rp)</th>
														
                    								</tr>
                    								<thead>
													
                    								<tbody>
													
													   <?php
													     $customer = $this->db->query("select inv_barang_harga.*, ar_customer.nama 
														 from inv_barang_harga inner join ar_customer on inv_barang_harga.kode_customer=ar_customer.kode
														 where tanggal='$tanggal' and kodeitem='$barang'")->result();
														 $idx = 1;
														 foreach($customer as $row){ ?>
														   <tr>	
														   	<td width="5%" ><input  id="kode<?= $idx;?>" name="kode[]" type="text" class="form-control" value="<?= $row->kode_customer;?>" readonly></td> 
															<td width="20%" ><input id="nama<?= $idx;?>"name="nama[]" type="text" class="form-control" value="<?= $row->nama;?>" readonly></td> 
															<td width="5%" ><input  id="margin<?= $idx;?>"  onchange="totalline(<?= $idx;?>)" name="margin[]" type="text" value="<?= $row->margin_pr; ?>" class="form-control rightJustified"></td> 
															<td width="5%" ><input  id="partisipasi<?= $idx;?>" onchange="totalline(<?= $idx;?>)" name="partisipasi[]" type="text" value="<?= $row->partisipasi_pr; ?>" class="form-control rightJustified"></td> 
															<td width="10%" ><input id="hargagros<?= $idx;?>" name="hargagros[]" type="text" value="<?= number_format($row->harga_gros,2,'.',','); ?>" class="form-control rightJustified" readonly></td> 
															<td width="5%" ><input  id="diskon<?= $idx;?>" onchange="totalline(<?= $idx;?>)" name="diskon[]" type="text" value="<?= $row->diskon_pr; ?>" class="form-control rightJustified"></td> 
															<td width="10%" ><input id="harganet<?= $idx;?>" name="harganet[]" type="text" value="<?= number_format($row->hargajual_net,2,'.',','); ?>" class="form-control rightJustified" readonly></td> 
														   </tr>
														 <?php 
														 $idx++;
														 } 
														 
													   ?>
                                                       
								                      
                    								
								                    </tbody>
													</table>
													
													

								                   
								                   </div>
												</div>
												

											
							</div>
							<!-- tab1-->
							
							<div class="tab-pane" id="tab2">	
							   <div class="row">
							       
								</div>
                                
							</div>
							<!-- tab2-->
							
						</div><!--tab-->	
						
						<div class="row">
							<div class="col-xs-12">
								<div class="well">		
								   
                                   
									<button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
									   
									
									<h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>								
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

function save()
{	      
    var noform   = $('[name="barang"]').val(); 
	var tanggal  = $('[name="tanggal"]').val(); 
	if(noform==""){
		swal('HARGA BARANG','NAMA BARANG belum diisi ...',''); 
	} else {      
	$.ajax({				
		url:'<?php echo site_url('inventory_harga/ajax_add')?>',				
		data:$('#frmharga').serialize(),				
		type:'POST',
		success:function(data){        		
		swal({
					  title: "HARGA BARANG",
					  html: "<p> Kode Barang   : <b>"+noform+"</b> </p>"+ 
					  "Tanggal :  " + tanggal,
					  type: "info",
					  confirmButtonText: "OK" 
					 }).then((value) => {
							location.href = "<?php echo base_url()?>inventory_harga";
		         });								
	
		},
		error:function(data){
			swal('HARGA BARANG','Data gagal disimpan ...',''); 
		}
		});
	}		
}	
   
function totalline(id)
  {
   
   var table = document.getElementById('datatable');
   var row = table.rows[id];  
   var hargamodal  = $('#hargamodal').val();
   var vhargamodal = Number(hargamodal.replace(/[^0-9\.]+/g,""));
   
   var margin    = row.cells[2].children[0].value;
   
   
   var partisipasi    = row.cells[3].children[0].value;
   var diskon    = row.cells[5].children[0].value;
   
   var hargagros = vhargamodal + (vhargamodal*(margin/100))+vhargamodal*(partisipasi/100);
   row.cells[4].children[0].value= formatCurrency1(hargagros);   
   
   var harganet = hargagros - ((diskon/100)*hargagros);
   row.cells[6].children[0].value= formatCurrency1(harganet);    
   
  }
  
 function infobarang() {   
  var xhttp; 
  var vid = $('#barang').val();
  
   $.ajax({
        url : "<?php echo base_url();?>inventory_harga/getinfobarang/"+vid,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {			
		  $('#hargamodal').val(formatCurrency1(data.hargabeli));	
		}
	});	
  
  
} 

</script>


							
</body>
</html>


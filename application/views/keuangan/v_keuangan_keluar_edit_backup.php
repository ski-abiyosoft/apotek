<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  	
?>	

			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					  KAS/BANK<small>Edit Pengeluaran Kas/Bank </small>
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
							<a href="<?php echo base_url();?>keuangan_keluar">
                               Daftar Pengeluaran Kas/Bank
                              							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>							
							<a href="<?php echo base_url();?>keuangan_keluar/entri">
                               Entri Pengeluaran Kas/Bank
							</a>
						</li>
					</ul>
				</div>
			</div>
            <div class="portlet box blue">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-reorder"></i>Form Entri Pengeluaran
										</div>
										<div class="tools">
											
											 <span class="label label-sm label-warning">										
											  DIBUAT OLEH : <?php echo $header->username;?>
											</span>

										</div>

										
									</div>
									<div class="portlet-body form">
										<!-- BEGIN FORM-->
										<form id="frmkeuangan" class="form-horizontal" method="post">
											<div class="form-body">
												
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
                                                            <label class="col-md-3 control-label">Nomor Bukti</label>
													        <div class="col-md-6">
														        
																<div class="input-group">														        
                                                                <input type="text" class="form-control" placeholder="" name="nomorbukti" id="nomorbukti" value="<?php echo $header->bayarno;?>" onkeypress="return tabE(this,event)" readonly>																																															
																															
														   										               
															    </div>
													        </div>

														</div>
													</div>
													<!--/span-->
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Tanggal</label>
													        <div class="col-md-6">
														       <div class="input-icon">
															    <i class="fa fa-calendar"></i>
															    <input id="tanggal" name="tanggal" class="form-control date-picker input-medium" type="date" value="<?php echo date('Y-m-d',strtotime($header->tglbayar));?>" />
													    	   </div>
													        </div>
													        


														</div>
													</div>
												</div>
												
												<div class="row">
													<!--/span-->
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Kas/Bank</label>
													        <div class="col-md-9">
                                                              <select name="kasbank" id="kasbank" class="select2_el_kasbank form-control" >
															   <option value="<?= $header->accountno;?>"><?= $header->acname;?></option>
															  </select>
													        </div>

														</div>
													</div>
													
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Cabang</label>
													        <div class="col-md-9">
                                                              <select name="cabang" id="cabang" class="select2_el_cabang form-control" >
															    <option value="<?= $header->koders;?>"><?= $header->namars;?></option>
															  </select>
													        </div>

														</div>
													</div>
													


												</div>
												
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
                                                            <label class="col-md-3 control-label">Diterima dari</label>
													        <div class="col-md-6">
														        <input type="text" class="form-control input-medium" placeholder="" name="penerima" id="penerima" value="<?php echo $header->kodokter;?>" >
													        </div>

														</div>
													</div>
													
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Keterangan</label>
													        <div class="col-md-9">
														      
																<input type="text" class="form-control input-large" placeholder="" name="keterangan" id="keterangan" value="<?php echo $header->keterangan;?>" onkeypress="return tabE(this,event)">
													        </div>

														</div>
													</div>

												</div>


												
												<div class="row">
												 <div class="col-md-12">
                                                    <table id="datatable" class="table table-bordered- table-condensed">
								                    <thead class="breadcrumb">
                                                      <tr>                    									
                    									<th width="20%" style="text-align: center">Kode Akun</th>
                    									<th width="60%" style="text-align: center">Uraian</th>
                    									<th width="20%" style="text-align: center">Jumlah</th>                    									
                    								  </tr>
                    								</thead>
																								
                    								<tbody>
													<?php 
													$no = 1;
													foreach($detil as $row){ ?>
													<tr>
													   <td width="20%">
														   <select name="akun[]" id="akun<?= $no;?>" class="select2_el form-control" style="width:300px" >
															  <option value="<?= $row->accountno;?>"><?= $row->acname;?></option>
														   </select>
                                                     	</td>
                                                       
                                                        
                                                        <td width="60%" ><input name="ket[]"    value="<?= $row->keterangan;?>" id="ket<?= $no;?>" type="text" class="form-control " size="100%" onkeypress="return tabE(this,event)"></td>
                                                        <td width="20%" ><input name="jumlah[]" value="<?= $row->jumlah;?>" id="jumlah<?= $no;?>" data-type="currency" type="text" class="form-control rightJustified" size="40%" value="0" onkeyup="total()" data-type="currency" onkeypress="return tabE(this,event);formatCurrency(this)"></td>
                                                       
								                      </tr>
                    								<?php 
													$no++;
													} ?>
								                    </tbody>
													</div>
													<tfoot>
                                                      <tr>
													    <td width="20%"><button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i> </button>
												        <button type="button" onclick="hapus()" class="btn red"><i class="fa fa-trash-o"></i></button></td>
                                                        <td width="60%" align="right">TOTAL</td>
                                                        <td width="20%" align="right"><font color="red"><b><input class="form-control rightJustified" type="text" id="_jumlah" readonly><b></td>
														
														                                                        
														
                                                      </tr>
                                                     </tfoot>
								                    </table>
								                   </div>
												</div>
												

											<div class="form-actions">
												
												<button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
												
												<div class="btn-group">
                                                <div class="btn-group">
													<a href="<?php echo base_url()?>keuangan_keluar/entri" class="btn btn-success">
													<i class="fa fa-plus"></i>
													Data Baru
													</a>
												</div>

											</div>
											<h2><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h2>
                                            <input type="hidden" name="register" id="register" value="<?php echo $register;?>" />
											 
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

var idrow = <?php echo $jumdata+1;?>;

function tambah(){
    var x=document.getElementById('datatable').insertRow(idrow);
    var td1=x.insertCell(0);
    var td2=x.insertCell(1);
    var td3=x.insertCell(2);
  
	var akun1="<select name='akun[]' class='select2_el form-control select2me' id='akun'"+idrow+" style='width:300px' >";	
	td1.innerHTML=akun1; 
	td2.innerHTML="<input name=ket[]'    type='text' class='form-control'>";
	td3.innerHTML="<input name='jumlah[]'  data-type='currency' type='text' class='form-control rightJustified' size='40%' value='0' onkeyup='total()'>";
    initailizeSelect2();
	idrow++;
}


function save()
{	        
    var tanggal   = $('[name="tanggal"]').val();
	var total     = $('#_jumlah').val();
	var bank      = $('#kasbank').val();
	var cabang    = $('#cabang').val();
	
	if(total==0 || total=="" || bank=="" || cabang =="" ){
		 swal('','Data belum lengkap...','')
                        
	} else {
	$.ajax({				
		url:'<?php echo site_url('keuangan_keluar/pengeluaran_save/2')?>',				
		data:$('#frmkeuangan').serialize(),				
		type:'POST',
		success:function(data){        		
		swal({
					  title: "PENGELUARAN KAS/BANK",
					  html: "<p> No. Bukti   : <b>"+data+"</b> </p>"+ 
					  "Tanggal :  " + tanggal,
					  type: "info",
					  confirmButtonText: "OK" 
					 }).then((value) => {
							location.href = "<?php echo base_url()?>keuangan_keluar";
		  });								
	
		},
		error:function(data){
			$("#error").show().fadeOut(5000);
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

   tjumlah=0;
  
   
   for(var i=1; i<rowCount-1; i++) 
   {
    var row = table.rows[i];
    
	jumlah      = row.cells[2].children[0].value;
    var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g,""));

   	tjumlah  = tjumlah  + eval(jumlah1);
		  
    
   }
   document.getElementById("_jumlah").value=formatCurrency1(tjumlah);


  }
  
total();  
  
</script>

</body>
</html>
<div class="modal fade" id="luppasien" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-large">
	<div class="modal-content">
		<span id="nopilih">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h4 class="modal-title">Data Pasien</h4>											
		</div>
		<div class="modal-body">										 		  
		  <form action="#" class="form-horizontal">
		        <div class="form-group">
					<label class="col-md-3 control-label">No. Rek Med</label>
					<div class="col-md-9">
					  <input id="rekmed" name="rekmed" class="form-control" type="text"  />
					  
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Nama</label>
					<div class="col-md-9">
					  <input id="carinama" name="carinama" class="form-control" type="text"  />
					  
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Alamat</label>
					<div class="col-md-9">
					   <input id="carialamat" name="carialamat" class="form-control" type="text" value="" />
					</div>
				</div>	
                <div class="form-group">
					<label class="col-md-3 control-label">No. Identitas</label>
					<div class="col-md-9">
					   <input id="carinoid" name="carinoid" class="form-control" type="text" value="" />
					</div>
				</div>	
				
				<div class="form-group">
					<label class="col-md-3 control-label">Tanggal Lahir (YYYY-MM-DD)</label>
					<div class="col-md-9">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-9">
										<input type="text" id="xxx" name="xxx" class="form-control" placeholder="yyyy-mm-dd" readonly>
									</div>
									<div class="col-md-3">
										<input id="tglLahir" name="tglLahir" class="form-control" type="date" onchange="tgl_x()" style="width:45px;"/> 
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>	
				
				<div class="form-group">
					<label class="col-md-3 control-label">Handphone</label>
					<div class="col-md-9">
					  <input id="noTlp" name="noTlp" class="form-control" type="text"  />  
					</div>
				</div>	
				<div class="form-group">
					<label class="col-md-3 control-label">No. Kartu</label>
					<div class="col-md-9">
					  <input id="nocard" name="nocard" class="form-control" type="text"  />  
					</div>
				</div>		
		 </form>
		</div>   
		<div class="modal-footer">
		     <p align="center">
			  <button type="button" id="btnfilter" class="btn green" onclick="filterdata()" data-dismiss="modal">Proses Query</button>		</p>																				 			
		</div>											
	</div>									
</div>								
</div>

<script>
	// $('#xxx').hide();
	function tgl_x(){
		// $('#tglLahir').hide();
		var tgl = document.getElementById('tglLahir').value;
		console.log(tgl);
		$('#xxx').show();
		$('#xxx').val(tgl);
	}
</script>


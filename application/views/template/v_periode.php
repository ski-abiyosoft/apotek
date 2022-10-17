<div class="modal fade" id="lupperiode" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-small">
	<div class="modal-content">
		<span id="nopilih">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h4 class="modal-title">Periode Data</h4>											
		</div>
		<div class="modal-body">										 		  
		  <form action="#" class="form-horizontal">
		        
				<div class="form-group">
					<label class="col-md-4 control-label">Mulai</label>
					<div class="col-md-6">
					  <input id="tanggal1" name="tanggal1" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d');?>" />
					  
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label">s/d</label>
					<div class="col-md-6">
					   <input id="tanggal2" name="tanggal2" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d');?>" />
					   
					</div>
				</div>											
		 </form>
		</div>   
		<div class="modal-footer">
		     <p align="center">
			  <button type="button" id="btnfilter" class="btn green" onclick="filterdata()" data-dismiss="modal">Buka Data</button>		</p>																				 			
		</div>											
	</div>									
</div>								
</div>

<div class="modal fade" id="eresepperiode" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-small">
	<div class="modal-content">
		<span id="nopilih">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h4 class="modal-title">Periode Data</h4>											
		</div>
		<div class="modal-body">										 		  
		  <form action="#" class="form-horizontal">
		        
				<div class="form-group">
					<label class="col-md-4 control-label">Mulai</label>
					<div class="col-md-6">
					  <input id="tanggale1" name="tanggale1" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d');?>" />
					  
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label">s/d</label>
					<div class="col-md-6">
					   <input id="tanggale2" name="tanggale2" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d');?>" />
					   
					</div>
				</div>											
		 </form>
		</div>   
		<div class="modal-footer">
		     <p align="center">
			  <button type="button" id="btnfilter" class="btn green" onclick="filterdataEresep()" data-dismiss="modal">Buka Data</button>		</p>																				 			
		</div>											
	</div>									
</div>								
</div>
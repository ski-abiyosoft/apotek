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
					<label class="col-md-4 control-label">Bulan</label>
					<div class="col-md-6">
					<select id="bulan" name="bulan" class="form-control input-sm select2me input-medium"  data-placeholder="Pilih...">
					 <option value="NONE">&nbsp;</option>
					 <?php
					   $bulan = date('n');
					   $tahun = date('Y');
					   for($i=1;$i<=12;$i++)
					   { ?>
						 <option value="<?php echo $i;?>" <?php if($bulan==$i){ echo "selected=true";}?>><?php echo $this->M_global->_namabulan($i);?></option>
					   <?php 
					   }
					 
					 ?>

					</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label">Tahun</label>
					<div class="col-md-6">
					   <input maxlength="4" type="text" size="3" name="tahun" id="tahun" class="form-control" value="<?php echo $tahun;?>" />
					   
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
<div class="page-footer-fixed">
<div class="footer">
	<div class="footer-inner">      
	  <?php echo $this->config->item('name_app');?> | Periode : <?php echo $this->M_global->_periodebulan().'-'.$this->M_global->_periodetahun();?>
	  | <?php echo $this->M_global->tgln();?><span id="jam" style="font-size: 18px;"></span></p>									  
	</div>
	
	<div class="footer-tools">	    
		<span class="go-top">
			<i class="fa fa-angle-up"></i>			
		</span>
	</div>
</div>
</div>

<script src="<?php echo base_url();?>assets/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/scripts/core/app.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/scripts/custom/jam.js" type="text/javascript"></script>


<script>
  
   window.onload = function()   {
     show2();         
   }
  
</script>


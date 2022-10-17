<div class="page-footer-fixed">
<div class="footer">
<div style="color:white;font-size: 13px;" class="footer-inner">      
	  <?php echo $this->config->item('name_app');?> | Periode : <?php echo $this->M_global->_periodebulan2().' - '.$this->M_global->_periodetahun();?>
	  | <?php echo $this->M_global->tgln();?><span id="jam"></span></p>									  
	</div>
	<div class="footer-tools">
		<span class="go-top">
			<i class="fa fa-angle-up"></i>
		</span>
	</div>
</div>
</div>

<script src="<?php echo base_url();?>assets/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/scripts/core/app.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/scripts/custom/jam.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/scripts/core/sheetjs.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/scripts/core/jquery-2.1.1.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/scripts/core/select2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/sweet-alert2/sweetalert2.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/sweet-alert2/sweetalert2.js"></script>

<script>
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
	return (((sign)?'':'-') + '' + num + '.' + cents);
	//return (((sign)?'':'-') + '' + num);
	}
	
  jQuery(document).ready(function() {
    App.init();	    
  });  
  window.onload = function()   {
     show2();         
   }
</script>
</html>

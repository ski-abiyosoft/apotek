

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

<!-- <script src="<?php echo base_url();?>assets/jquery/jquery-2.1.4.min.js" type="text/javascript"></script> -->
<script src="<?php echo base_url();?>assets/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/scripts/core/app.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/scripts/custom/jam.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/sweet-alert2/sweetalert2.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/sweet-alert2/sweetalert2.js"></script>
<!-- <script src="<?php echo base_url();?>assets/scripts/core/jquery-2.1.1.min.js" type="text/javascript"></script> -->
<script src="<?php echo base_url();?>assets/scripts/core/select2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/scripts/core/sheetjs.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/scripts/core/erk.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/scripts/core/moment.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/currency.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/highcharts.js"></script>
<?php  $this->load->view('template/footer_all.php');?>
<script>

  
  jQuery(document).ready(function() {
    App.init();	
  });
  
   window.onload = function()   {
     show2();         
   }

$('#provinsi').change(function(event) {
	var option = "<option value=''>--- Pilih Kota/Kab ---</option>"
	var id_prov = $(this).val();			
	$.ajax({
		url: '<?php echo base_url() ?>app/getKota/'+id_prov,
		type: 'GET',
		dataType: 'json',
		success:function(data){					
			if(data.message == "Success"){						
				$.each(data.data, function(index, val) {
					option += "<option value='"+val.kodekab+"'>"+val.namakab+"</option>";
				});

				$('#kota').html(option);
			}
		}
	});

});

$('#poli').change(function(event) {
	var option = "<option value=''>--- Pilih Poli ---</option>"
	var id_poli = $(this).val();			
	$.ajax({
		url: '<?php echo base_url() ?>app/getDokterpoli/'+id_poli,
		type: 'GET',
		dataType: 'json',
		success:function(data){					
			if(data.message == "Success"){						
				$.each(data.data, function(index, val) {
					option += "<option value='"+val.kodokter+"'>"+val.nadokter+"</option>";
				});

				$('#dokter').html(option);
			}
		}
	});

});


$('#kota').change(function(event) {
	var option = "<option value=''>--- Pilih Kecamatan ---</option>"
	var id_kota = $(this).val();			
	$.ajax({
		url: '<?php echo base_url() ?>app/getKecamatan/'+id_kota,
		type: 'GET',
		dataType: 'json',
		success:function(data){					
			if(data.message == "Success"){						
				$.each(data.data, function(index, val) {
					option += "<option value='"+val.kodekec+"'>"+val.namakec+"</option>";
				});

				$('#kecamatan').html(option);
			}
		}
	});

});

$('#kecamatan').change(function(event) {
	var option = "<option value=''>--- Pilih Kelurahan/Desa ---</option>"
	var id_kec = $(this).val();			
	$.ajax({
		url: '<?php echo base_url() ?>app/getDesa/'+id_kec,
		type: 'GET',
		dataType: 'json',
		success:function(data){		
			console.log('test footer');			
			if(data.message == "Success"){						
				$.each(data.data, function(index, val) {
					option += "<option value='"+val.kodedesa+"'>"+val.namadesa+"</option>";
				});

				$('#kelurahan').html(option);
			}
		}
	});

});
		
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

  function formatCurrency2(fieldObj)
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

$("input[data-type='currency']").on({
    keyup: function() {
      formatCurrency($(this));
    },
    blur: function() { 
      formatCurrency($(this), "blur");
    }
});


function formatNumber(n) {
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}


function formatCurrency(input, blur) {
  var input_val = input.val();
  
  if (input_val === "") { return; }
  
  var original_len = input_val.length;

  var caret_pos = input.prop("selectionStart");
    
  if (input_val.indexOf(".") >= 0) {

    var decimal_pos = input_val.indexOf(".");

    var left_side = input_val.substring(0, decimal_pos);
    var right_side = input_val.substring(decimal_pos);

    left_side = formatNumber(left_side);
  right_side = formatNumber(right_side);
    
    if (blur === "blur") {
      right_side += "00";
    }
    
    right_side = right_side.substring(0, 2);

    input_val = "" + left_side + "." + right_side;

  } else {
    input_val = formatNumber(input_val);
    input_val = "" + input_val;
    
    if (blur === "blur") {
      input_val += ".00";
    }
  }
  
  input.val(input_val);

  
  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;
  input[0].setSelectionRange(caret_pos, caret_pos);
}

function formatRupiah(angka, prefix) {
  var number_string = angka.replace(/[^,\d]/g, "").toString(),
    split = number_string.split(","),
    sisa = split[0].length % 3,
    rupiah = split[0].substr(0, sisa),
    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

 
  if (ribuan) {
    separator = sisa ? "." : "";
    rupiah += separator + ribuan.join(".");
  }

  rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
  return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
}

	function numeric_restruct(param){
        var resone, restwo;

        resone = param.split(",").join("");
        restwo = resone.split(".00").join("");

        return restwo;
    }

	function numeric_restruct2(param){
        var resone;

        resone = param.toString().split(".").join("");

        return resone;
    }

	function num_restruct(param){
        var resone;

        resone = param.split(".00").join("");

        return resone;
    }

</script>

</body>
</html>

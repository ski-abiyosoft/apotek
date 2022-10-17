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
                      <span class="title-web">Buku Besar <small>Daftar Jurnal</small>
					</h3>
					<ul class="page-breadcrumb breadcrumb">

						<li>
							<i class="fa fa-home"></i>
							<a href="<?php echo base_url()?>dashboard">
                               Awal
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="<?php echo base_url()?>akuntansi_jurnal">
                              Entri Jurnal
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="">
                              Daftar Jurnal
							</a>
						</li>
					</ul>
				</div>
			</div>

            <div class="row">
				<div class="col-md-12">

					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-reorder"></i>Periode Data Jurnal
							</div>

						</div>
						<div class="portlet-body form1">
							<form class="form-inline" role="form">
							
                                <div class="form-group">
									<label >Tanggal Jurnal : </label>

								</div>
                                <div class="form-group">

									<div class="input-icon">
										<i class="fa fa-calendar"></i>
                                        <input id="tanggal1" name="tanggal1" class="form-control date-picker input-medium" type="text" value="<?php echo date('d-m-Y')?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Mulai"/>
									</div>

								</div>
								<div class="form-group">

									<div class="input-icon">
										<i class="fa fa-calendar"></i>
                                        <input id="tanggal2" name="tanggal2" class="form-control date-picker input-medium" type="text" value="<?php echo date('d-m-Y');?>" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="Sampai Dengan"/>
									</div>

								</div>


								<button type="button" class="btn green" onclick="_tabelhasil(document.getElementById('tanggal1').value,document.getElementById('tanggal2').value)">Tampilkan</button>

                                <div id="_hasil"></div>
				</div>
			</div>
			
		                 	

		</div>
	</div>

</div>

<?php  
   $this->load->view('template/footer');
?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/bootstrap-datepicker/css/datepicker.css"/>
<script src="<?php echo base_url()?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url()?>assets/scripts/custom/components-pickers.js"></script>


<script>
		
function _tabelhasil(tgl1, tgl2)
{
if (tgl1=="")
  {
  document.getElementById("_hasil").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
	document.getElementById("_hasil").innerHTML=xmlhttp.responseText;
	}
  }
//xmlhttp.open("GET","akuntansi_jurnalL1.php?tanggal1="+tgl1+"&tanggal2="+tgl2,true);
var str=tgl1+'~'+tgl2;
xmlhttp.open("GET", "<?php echo base_url(); ?>akuntansi_jurnall/jurnallist/"+str, true);  
xmlhttp.send();
}

function delete_data(id)
{
    if(confirm('Yakin data jurnal dengan nomor '+id+' ini akan dihapus ?'))
    {        
        $.ajax({
            url : "<?php echo site_url('akuntansi_jurnall/hapus')?>/"+id,
            type: "POST",
            dataType: "html",
            success: function(data)
            {
                alert('Data sudah dihapus');
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

jQuery(document).ready(function() {
ComponentsPickers.init();
});

	
</script>


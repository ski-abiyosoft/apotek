<?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
?>

<div class="col-md-12">
					<!-- BEGIN ALERTS PORTLET-->
	<div class="portlet green- box-">
		<div class="portlet-title">
			<div class="caption">
				<h2><i class="fa fa-inf-"></i><b>HMS</b></h2>
			</div>
			
		</div>
		<div class="portlet-body">
			<div class="note note-success">
				<h4 class="block">Selamat Datang : <b><?= $this->session->userdata('nama_lengkap');?></b></h4>
				<p>
				Terakhir Login pada :  <?= date('d-m-Y H:i:s', strtotime($this->session->userdata('lastlogin')));?> 	
				</p>
				
			</div>
			
			<div class="note note-info">
				<h1 class="block"><b><?= $title; ?></b></h1>
				
			</div>	

			<div style="padding: 20px; background: #eee;">
			<?php
			if ( $title == true){
			?>
				<h4 class="block"><b>Koneksi Internet tersambung </b></h4>
			<?php }else{ ?>
				
				<h4 class="block"><b>Tidak ada koneksi internet</b></h4>
			<?php } ?>
			</div>
				
			
			
		</div>
	</div>
	<!-- END ALERTS PORTLET-->
</div>

</div>
</div>
				
<?php
  $this->load->view('template/footer');  
?>			
			
			
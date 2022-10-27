<div class="page-container">
	<div class="page-sidebar-wrapper ">
		<div class="page-sidebar navbar-collapse collapse">
		  <ul class="page-sidebar-menu" data-auto-scroll="true" data-slide-speed="200">
		    <li class="sidebar-toggler-wrapper">
					<div class="sidebar-toggler hidden-phone" color="">
					</div>
					<div>&nbsp;
					</div>
				</li>
				<!-- <li class="sidebar-search-wrapper">
					<form id="frmcari" class="sidebar-search" action="" method="POST">
						<div class="form-container">
							<div class="input-box">
								<a href="javascript:;" class="remove"></a>
                                <input type="text" value="" id="periode" name="periode"/>
                                <input type="button" class="submit" name="btnupdateperiode" id="btnupdateperiode"/>
                            </div>
                            <div class="err" id="results"></div>
						</div>
					</form>
				</li> -->
				<?php 
				$_menu=$this->session->userdata('menuapp');
				if($_menu==""){ ?>
				<li class="start active"><?php } else { ?>
				<li>
				<?php } ?>
					<!--a href="<?php echo base_url();?>dashboard">
						<i class="fa fa-home"></i>
						<span class="title">
							Dashboard
						</span>
						<span class="selected">
						</span>
					</a-->
				</li>
				
				
				
			<?php
			  $this->load->view('template/v_menu');			  
			?>			
		  </ul>	
		</div>
	</div>	

	<div class="page-content-wrapper">
			<div class="page-content">
				

			<!--/div>
	</div>
</div-->






<?php 
 $level=$this->session->userdata('level');
 $_menu=$this->session->userdata('menuapp');
 $_submenu=$this->session->userdata('submenuapp');
?>

<div class="hor-menu hidden-sm hidden-xs">
	<ul class="nav navbar-nav">
		<?php if($_menu==""){ ?>
		<li class="classic-menu-dropdown active"><?php } else { ?>
		<li> <?php } ?>
			<a href="<?php echo base_url();?>home">
				 Dasbor
				<span class="selected">
				</span>
			</a>
		</li>
		
		
		<?php
		  $list_menu = $this->db->query("select * from ms_modul where main_menu=0 and aktif=1 order by kode")->result();
		  
		  foreach($list_menu as $menu1) {
			$_menu1 = $menu1->kode;
					
		    if($this->M_global->cek_menu($level,$menu1->kode)>0) {?>
				<?php if($_menu==$menu1->kode){?>
				<li class="mega-menu-dropdown mega-menu-full- active "> <?php } else { ?>
				<li class="mega-menu-dropdown mega-menu-full-  "> <?php } ?>
				
			<a href="#" data-hover="dropdown" data-close-others="true" href="" class="dropdown-toggle">
				 <?= $menu1->nama;?> <i class="fa fa-angle-down"></i>
				 <span class="selected">
				 </span>
			</a>
			<ul class="dropdown-menu">
				<li>
					<div class="mega-menu-content">
						<div class="row">
						    <?php
							    $list_sub_menu = $this->db->query("select * from ms_modul where main_menu='$_menu1' and aktif=1 order by kode")->result();
				  
				                foreach($list_sub_menu as $menu2) {
								  $_menu2 = $menu2->kode; ?>
								  <ul class="col-md-4 mega-menu-submenu">
								 	<li>
										<h3><?= $menu2->nama;?></h3>
									</li>
									
									<?php
										$list_sub_menu2 = $this->db->query("select * from ms_modul where main_menu='$_menu2' and lev=2 and aktif=1 order by kode")->result();
						  
										foreach($list_sub_menu2 as $menu3) {
										  if($this->M_global->cek_menu($level,$menu3->kode)>0) {
										    if($_submenu==$menu3->kode){?>
										      <li class="active"><?php } else { ?><li><?php } ?>	
										       <a href="<?php echo base_url(); ?><?= $menu3->url;?>">
											   <i class="fa fa-angle-right"></i> <?= $menu3->nama;?>
										       </a>
									          </li>
										<?php } }  ?>	  
									
									
								 </ul>
								 <?php } ?>
							
							
						</div>
					</div>
				</li>
			</ul>
		</li>
		  <?php } } ?>
		
		
		
		
		
	</ul>
</div>

                
			
				<?php 
                $level=$this->session->userdata('level');
				$_menu=$this->session->userdata('menuapp');
				$_submenu=$this->session->userdata('submenuapp');
				?>
				<li >
					<a href="<?php echo base_url(); ?>home">
						<i class="fa fa-home"></i>
						<span class="arrow"><b>Home</b></span>
					</a>
				</li>

				<?php
				  $list_menu = $this->db->query("SELECT * from ms_modul where lev=0 and aktif=1 order by kode")->result();
				  
				  foreach($list_menu as $menu1) {
					$_menu1 = $menu1->kode;
					
					if($this->M_global->cek_menu($level,$menu1->kode)>0) {
				       if($_menu==$menu1->kode){?>
						<li class="active"> <?php } else { ?>				
						<li><?php } ?>

						<?php if($menu1->kode=='999'){?>
						<!-- <a width="20px" href="<?php echo base_url(); ?><?= $menu1->url;?>"> 
						<i class="fa <?= $menu1->icon;?>"></i>
							<span class="title">
								<b><?= $menu1->nama; ?></b>
							</span>
							<?php if($_menu==$menu1->kode){?>
							<span class=""></span> <?php } else { ?>				
							<span class=""></span><?php } ?>
						</a> -->


						<?php } else { ?>
						<a width="20px" href="javascript:;">
						<i class="fa <?= $menu1->icon;?>"></i>
							<span class="title">
								<b><?= $menu1->nama; ?></b>
							</span>
							<?php if($_menu==$menu1->kode){?>
							<span class="selected"></span> <?php } else { ?>				
							<span class="arrow"></span><?php } ?>
						</a>
						<?php } ?>

							<ul class="sub-menu">
							    <?php
							    $list_sub_menu = $this->db->query("SELECT * from ms_modul where main_menu='$_menu1' and aktif=1 order by kode")->result();
				  
				                foreach($list_sub_menu as $menu2) {
								  $_menu2 = $menu2->kode;
								  
								  if($menu2->lev==2){	
							      if($this->M_global->cek_menu($level,$menu2->kode)>0) {
								    if($_submenu==$menu2->kode){ ?>
										<li class="active"><?php } else { ?><li><?php } ?>							
											<a href="<?php echo base_url(); ?><?= $menu2->url;?>">
											   <?= $menu2->nama; ?>
											</a>
										</li>						
								   <?php }
								  } else 
									
								  {
								   
								   if($this->M_global->cek_menu($level,$menu2->kode)>0) {
									 
									 
									 if($_submenu==$menu2->kode){ ?>
									<li class="active"><?php } else { ?><li><?php } ?>	
									<a href="javascript:;">
										<i class="fa <?= $menu2->icon;?>"></i>
										<b><?= $menu2->nama;?></b>
										<span class="arrow"></span>
									</a>
									<ul class="sub-menu"> 
										<?php
										$list_sub_menu2 = $this->db->query("SELECT * from ms_modul where main_menu='$_menu2' and aktif=1 order by kode")->result();
						  
										foreach($list_sub_menu2 as $menu3) {
										  if($this->M_global->cek_menu($level,$menu3->kode)>0) {
											if($_submenu==$menu3->kode){ ?>
												<li class="active"><?php } else { ?><li><?php } ?>							
													<a href="<?php echo base_url(); ?>
													<?= $menu3->url;?>">
													<i class="fa <?= $menu3->icon;?>"></i><b><?= $menu3->nama; ?>&nbsp;</b>
													</a>
												</li>						
										   <?php }	
										  
										   }
										 
										 ?>
									</ul>	 

								<?php
								}
								  }
								}
								?>
								
						    </ul>
						</li>
				        <?php } 	
						}	  
				  
				?>
				
				
                
				<li>&nbsp;
				</li>
			







<!DOCTYPE html>

<html lang="en" class="no-js">

<head>
  <meta charset="utf-8" />
  <title><?php echo $this->config->item('nama_app');?></title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <meta content="" name="description" />
  <meta content="" name="author" />
  <link href="<?php echo base_url();?>assets/css/font_css.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url();?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet"
    type="text/css" />
  <link href="<?php echo base_url();?>assets/plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet"
    type="text/css" />
  <link href="<?php echo base_url();?>assets/plugins/bootstrap/css/bootstrap.min.css?v=1.1" rel="stylesheet"
    type="text/css" />
  <link href="<?php echo base_url();?>assets/css/style-metronic2.css?v=1.1" rel="stylesheet" type="text/css" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link href="<?php echo base_url();?>assets/css/style.css?v=1.1" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url();?>assets/css/style-responsive.css?v=1.1" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url();?>assets/css/themes/blue.css?v=1.1" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url();?>assets/plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url();?>assets/plugins/sweet-alert2/sweetalert2.css" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url();?>assets/css/select2.min.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url();?>assets/img/hms.ico" rel="shortcut icon" />
  <link href="<?php echo base_url('assets/plugins/data-tables/DT_bootstrap.css')?>" rel="stylesheet" />
  <link href="<?php echo base_url();?>assets/css/custom.css?v=1.1" rel="stylesheet" type="text/css" />

  <link href="<?= base_url('assets/calendar/fullcalendar.css');?>" rel='stylesheet' />
  <script src="<?= base_url('assets/calendar/jquery.min.js');?>"></script>
  <script src="<?= base_url('assets/calendar/jquery-ui.min.js');?>"></script>
  <script src="<?= base_url('assets/calendar/moment.min.js');?>"></script>
  <script src="<?= base_url('assets/calendar/fullcalendar.min.js');?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


  <style>
    .select2_el_icdind-container{
        z-index:9999999         
    }
  .select2me-container--default .select2me-selection--single {
    border-radius: 0px;
  }


  .rightJustified {
    text-align: right;
  }

  .total {
    font-size: 14px;
    font-weight: bold;
    color: blue;
  }

  .bodycontainer {
    max-height: 150px;
    width: 100%;
    margin: 0;
    overflow-y: auto;
  }

  .table-scrollable {
    margin: 0;
    padding: 0;
  }


  /* .select2-container {
    z-index: 99999;
  } */

  .nav_menu {
    z-index: 999;
  }


  .header-custom- {
    background-image: -webkit-gradient(linear, left top, right top, from(#20b9ae), to(#006fd6));
    color: white;
  }

  .footer-custom {
    background-image: -webkit-gradient(linear, left top, right top, from(#000000), to(#000000));
    color: white;
  }

  .voucher-status {
    padding: 5px;
    border-radius: 100px !important
  }

  .voucher-status .success {
    background: #198754 !important
  }

  .voucher-status .primary {
    background: #0d6efd !important
  }

  .voucher-status .danger {
    background: #dc3545 !important
  }

  .voucher-status .warning {
    background: #fd7e14 !important
  }

  .voucher-status .info {
    background: #fd7e14 !important
  }
  </style>

  </style>

</head>

<body onunload="">
  <div class="">
    <div class="nav_menu">
      <nav>
        <div class="header-inner">
          <!-- <a class="navbar-brand" href="<?php echo base_url();?>dashboard">
			<img src="<?php echo base_url();?>assets/img/logo-e.png"  width="50%" class="img-responsive"/>            
		</a>
		<a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<img src="<?php echo base_url();?>assets/img/menu-toggler.png" alt=""/>			
		</a> -->

          <!-- <?php
		//   $this->load->view('template/v_menu_horizontal.php');
		?> -->

          <ul class="nav ">
            <li class="dropdown user">
              <a class="navbar-brand" href="<?php echo base_url();?>dashboard">
                <img src="<?php echo base_url();?>assets/img_user/<?php echo $this->session->userdata('avatar_cabang');?>" width="45" class="img-responsive" />
              </a>

            </li>

            <li class="navbar-nav pull-right dropdown user">
              <!-- navbar-nav pull-right -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                <img alt="" src="<?php echo base_url();?>assets/puser/<?php echo $this->session->userdata('photo');?>"
                  width="40" />

                <span class="username" style="color:#fff;">
                  &nbsp;&nbsp; <b><?php echo ucwords($this->session->userdata('username'));?></b>
                </span>
                <i class="fa fa-angle-down"></i>
              </a>

              <ul class="dropdown-menu" width="40%">
                <li>
                  <a href="<?php echo base_url();?>master_user_profile">
                    <i class="fa fa-user"></i> Profil
                  </a>
                </li>
                <li class="divider">
                </li>
                <li>
                  <a href="<?php echo base_url()?>app/logout">
                    <i class="fa fa-power-off"></i> Keluar
                  </a>
                </li>
                <li class="divider">
                </li>
              </ul>
            </li>

          </ul>
        </div>

    </div>
    <div>
      <ul>
        <li><br>
          <a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <img src="<?php echo base_url();?>assets/img/sidebar-toggler-bluu.png" alt="" />
          </a>
        </li>
      </ul>
    </div>
    </nav>
  </div>
  <div class="clearfix">
  </div>
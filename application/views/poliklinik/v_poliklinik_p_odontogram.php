
<?php 
$this->load->view('template/header');
$this->load->view('template/body');    
date_default_timezone_set("Asia/Jakarta");	
?>	

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
        <span class="title-unit">
                &nbsp;<?php echo $this->session->userdata('unit'); ?> 
            </span>
            - 
            <span class="title-web">e-HMS <small>Poliklinik</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i style="color:white;" class="fa fa-home"></i>
                <a class="title-white" href="<?php echo base_url();?>dashboard">
                    Awal
                </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="<?php echo base_url('poliklinik');?>">
                    Poliklinik
                                            </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>							
                <a class="title-white" href="">
                    Pemeriksaan Odontogram
                </a>
            </li>
        </ul>
    </div>
</div>
<form id="form_periksa_perawat" class="form-horizontal" method="post">
<div class="row"><!-- row -->
    <div class="col-md-12"> <!-- col-md-12 -->
        <div class="portlet box blue"> <!-- portlet box blue -->
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-reorder"></i><b>Pemeriksaan Odontogram</b>
                </div>
            </div>
        
            <div class="portlet-body"> 
                <table border="0" width="100%">
                    <tr>
                        <td width="60%">
                            <h4 class="box-title"><b>DETAIL PASIEN :  <?= $data_pas->rekmed ?> &nbsp; ( <?= $data_pas->namapas ?> ) </b></h4>
                            <input type="hidden" id="noreg" name="noreg"  value="<?= $noreg ?>">
                            <input type="hidden" id="rekmed" name="rekmed"  value="<?= $data_pas->rekmed ?>">
                        </td>
                        <td width="10%">
                            <a class="btn red btn-sm" style="color:white" onclick="cetak();"><i class="fa fa-print"></i>&nbsp;<b>CETAK</b></a>
                        </td>
                        <td width="30%">
                            <a class="btn red btn-sm" onclick="back()" ><i class="fa fa-undo"></i><b> KEMBALI </b></a>
                        </td>
                    </tr>
                </table>
                <!-- mulai odontogram -->
            <table style="border:1px solid #4b8df8" border="1" width="100%">
                <tr>
                    
                    <td width="60%" align="center" style="border-left: none;">
                        <?php
                        $pasien=$data_pas->rekmed ;
                        //$warna="white";
                        ?>
                        
                        <div id="svgselect" style="width: 700px; height: 330px; "> <!-- background-color:red -->
                            <svg version="1.1" height="100%" width="100%">
                                <g transform="scale(1.7)" id="gmain">
                                    
                                    <g id="P17" transform="translate(25,0)">
                                    <?php
                                    $P17C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P17-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P17C['id_kategori_warna']==""){
                                        $P17C_color="white";
                                    } else {
                                        $wP17C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P17C[id_kategori_warna]'")->row_array();	
                                        $P17C_color=$wP17C['nama_kategori_warna'];
                                    }
                                    
                                    $P17T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P17-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P17T['id_kategori_warna']==""){
                                        $P17T_color="white";
                                    } else {
                                        $wP17T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P17T[id_kategori_warna]'")->row_array();	
                                        $P17T_color=$wP17T['nama_kategori_warna'];
                                    }
                                    
                                    $P17B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P17-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P17B['id_kategori_warna']==""){
                                        $P17B_color="white";
                                    } else {
                                        $wP17B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P17B[id_kategori_warna]'")->row_array();	
                                        $P17B_color=$wP17B['nama_kategori_warna'];
                                    }
                                    
                                    $P17R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P17-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P17R['id_kategori_warna']==""){
                                        $P17R_color="white";
                                    } else {
                                        $wP17R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P17R[id_kategori_warna]'")->row_array();	
                                        $P17R_color=$wP17R['nama_kategori_warna'];
                                    }
                                    
                                    $P17L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P17-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P17L['id_kategori_warna']==""){
                                        $P17L_color="white";
                                    } else {
                                        $wP17L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P17L[id_kategori_warna]'")->row_array();	
                                        $P17L_color=$wP17L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P17C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P17T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P17B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P17R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P17L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">17</text>
                                    </g>
                                    <g id="P16" transform="translate(50,0)">
                                    <?php
                                    $P16C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P16-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P16C['id_kategori_warna']==""){
                                        $P16C_color="white";
                                    } else {
                                        $wP16C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P16C[id_kategori_warna]'")->row_array();	
                                        $P16C_color=$wP16C['nama_kategori_warna'];
                                    }
                                    
                                    $P16T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P16-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P16T['id_kategori_warna']==""){
                                        $P16T_color="white";
                                    } else {
                                        $wP16T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P16T[id_kategori_warna]'")->row_array();	
                                        $P16T_color=$wP16T['nama_kategori_warna'];
                                    }
                                    
                                    $P16B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P16-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P16B['id_kategori_warna']==""){
                                        $P16B_color="white";
                                    } else {
                                        $wP16B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P16B[id_kategori_warna]'")->row_array();	
                                        $P16B_color=$wP16B['nama_kategori_warna'];
                                    }
                                    
                                    $P16R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P16-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P16R['id_kategori_warna']==""){
                                        $P16R_color="white";
                                    } else {
                                        $wP16R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P16R[id_kategori_warna]'")->row_array();	
                                        $P16R_color=$wP16R['nama_kategori_warna'];
                                    }
                                    
                                    $P16L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P16-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P16L['id_kategori_warna']==""){
                                        $P16L_color="white";
                                    } else {
                                        $wP16L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P16L[id_kategori_warna]'")->row_array();	
                                        $P16L_color=$wP16L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P16C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P16T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P16B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P16R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P16L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">16</text>
                                    </g>
                                    <g id="P15" transform="translate(75,0)">
                                    <?php
                                    $P15C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P15-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P15C['id_kategori_warna']==""){
                                        $P15C_color="white";
                                    } else {
                                        $wP15C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P15C[id_kategori_warna]'")->row_array();	
                                        $P15C_color=$wP15C['nama_kategori_warna'];
                                    }
                                    
                                    $P15T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P15-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P15T['id_kategori_warna']==""){
                                        $P15T_color="white";
                                    } else {
                                        $wP15T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P15T[id_kategori_warna]'")->row_array();	
                                        $P15T_color=$wP15T['nama_kategori_warna'];
                                    }
                                    
                                    $P15B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P15-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P15B['id_kategori_warna']==""){
                                        $P15B_color="white";
                                    } else {
                                        $wP15B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P15B[id_kategori_warna]'")->row_array();	
                                        $P15B_color=$wP15B['nama_kategori_warna'];
                                    }
                                    
                                    $P15R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P15-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P15R['id_kategori_warna']==""){
                                        $P15R_color="white";
                                    } else {
                                        $wP15R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P15R[id_kategori_warna]'")->row_array();	
                                        $P15R_color=$wP15R['nama_kategori_warna'];
                                    }
                                    
                                    $P15L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P15-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P15L['id_kategori_warna']==""){
                                        $P15L_color="white";
                                    } else {
                                        $wP15L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P15L[id_kategori_warna]'")->row_array();	
                                        $P15L_color=$wP15L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P15C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P15T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P15B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P15R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P15L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">15</text>
                                    </g>
                                    <g id="P14" transform="translate(100,0)">
                                    <?php
                                    $P14C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P14-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P14C['id_kategori_warna']==""){
                                        $P14C_color="white";
                                    } else {
                                        $wP14C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P14C[id_kategori_warna]'")->row_array();	
                                        $P14C_color=$wP14C['nama_kategori_warna'];
                                    }
                                    
                                    $P14T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P14-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P14T['id_kategori_warna']==""){
                                        $P14T_color="white";
                                    } else {
                                        $wP14T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P14T[id_kategori_warna]'")->row_array();	
                                        $P14T_color=$wP14T['nama_kategori_warna'];
                                    }
                                    
                                    $P14B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P14-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P14B['id_kategori_warna']==""){
                                        $P14B_color="white";
                                    } else {
                                        $wP14B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P14B[id_kategori_warna]'")->row_array();	
                                        $P14B_color=$wP14B['nama_kategori_warna'];
                                    }
                                    
                                    $P14R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P14-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P14R['id_kategori_warna']==""){
                                        $P14R_color="white";
                                    } else {
                                        $wP14R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P14R[id_kategori_warna]'")->row_array();	
                                        $P14R_color=$wP14R['nama_kategori_warna'];
                                    }
                                    
                                    $P14L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P14-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P14L['id_kategori_warna']==""){
                                        $P14L_color="white";
                                    } else {
                                        $wP14L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P14L[id_kategori_warna]'")->row_array();	
                                        $P14L_color=$wP14L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P14C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P14T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P14B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P14R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P14L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">14</text>
                                    </g>
                                    <g id="P13" transform="translate(125,0)">
                                    <?php
                                    $P13C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P13-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P13C['id_kategori_warna']==""){
                                        $P13C_color="white";
                                    } else {
                                        $wP13C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P13C[id_kategori_warna]'")->row_array();	
                                        $P13C_color=$wP13C['nama_kategori_warna'];
                                    }
                                    
                                    $P13T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P13-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P13T['id_kategori_warna']==""){
                                        $P13T_color="white";
                                    } else {
                                        $wP13T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P13T[id_kategori_warna]'")->row_array();	
                                        $P13T_color=$wP13T['nama_kategori_warna'];
                                    }
                                    
                                    $P13B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P13-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P13B['id_kategori_warna']==""){
                                        $P13B_color="white";
                                    } else {
                                        $wP13B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P13B[id_kategori_warna]'")->row_array();	
                                        $P13B_color=$wP13B['nama_kategori_warna'];
                                    }
                                    
                                    $P13R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P13-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P13R['id_kategori_warna']==""){
                                        $P13R_color="white";
                                    } else {
                                        $wP13R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P13R[id_kategori_warna]'")->row_array();	
                                        $P13R_color=$wP13R['nama_kategori_warna'];
                                    }
                                    
                                    $P13L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P13-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P13L['id_kategori_warna']==""){
                                        $P13L_color="white";
                                    } else {
                                        $wP13L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P13L[id_kategori_warna]'")->row_array();	
                                        $P13L_color=$wP13L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P13C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P13T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P13B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P13R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P13L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">13</text>
                                    </g>
                                    <g id="P12" transform="translate(150,0)">
                                    <?php
                                    $P12C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P12-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P12C['id_kategori_warna']==""){
                                        $P12C_color="white";
                                    } else {
                                        $wP12C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P12C[id_kategori_warna]'")->row_array();	
                                        $P12C_color=$wP12C['nama_kategori_warna'];
                                    }
                                    
                                    $P12T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P12-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P12T['id_kategori_warna']==""){
                                        $P12T_color="white";
                                    } else {
                                        $wP12T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P12T[id_kategori_warna]'")->row_array();	
                                        $P12T_color=$wP12T['nama_kategori_warna'];
                                    }
                                    
                                    $P12B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P12-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P12B['id_kategori_warna']==""){
                                        $P12B_color="white";
                                    } else {
                                        $wP12B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P12B[id_kategori_warna]'")->row_array();	
                                        $P12B_color=$wP12B['nama_kategori_warna'];
                                    }
                                    
                                    $P12R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P12-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P12R['id_kategori_warna']==""){
                                        $P12R_color="white";
                                    } else {
                                        $wP12R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P12R[id_kategori_warna]'")->row_array();	
                                        $P12R_color=$wP12R['nama_kategori_warna'];
                                    }
                                    
                                    $P12L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P12-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P12L['id_kategori_warna']==""){
                                        $P12L_color="white";
                                    } else {
                                        $wP12L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P12L[id_kategori_warna]'")->row_array();	
                                        $P12L_color=$wP12L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P12C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P12T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P12B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P12R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P12L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">12</text>
                                    </g>
                                    <g id="P11" transform="translate(175,0)">
                                        <?php
                                    $P11C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P11-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P11C['id_kategori_warna']==""){
                                        $P11C_color="white";
                                    } else {
                                        $wP11C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P11C[id_kategori_warna]'")->row_array();	
                                        $P11C_color=$wP11C['nama_kategori_warna'];
                                    }
                                    
                                    $P11T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P11-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P11T['id_kategori_warna']==""){
                                        $P11T_color="white";
                                    } else {
                                        $wP11T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P11T[id_kategori_warna]'")->row_array();	
                                        $P11T_color=$wP11T['nama_kategori_warna'];
                                    }
                                    
                                    $P11B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P11-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P11B['id_kategori_warna']==""){
                                        $P11B_color="white";
                                    } else {
                                        $wP11B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P11B[id_kategori_warna]'")->row_array();	
                                        $P11B_color=$wP11B['nama_kategori_warna'];
                                    }
                                    
                                    $P11R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P11-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P11R['id_kategori_warna']==""){
                                        $P11R_color="white";
                                    } else {
                                        $wP11R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P11R[id_kategori_warna]'")->row_array();	
                                        $P11R_color=$wP11R['nama_kategori_warna'];
                                    }
                                    
                                    $P11L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P11-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P11L['id_kategori_warna']==""){
                                        $P11L_color="white";
                                    } else {
                                        $wP11L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P11L[id_kategori_warna]'")->row_array();	
                                        $P11L_color=$wP11L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P11C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P11T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P11B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P11R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P11L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">11</text>
                                    </g>
                                    
                                    <!-- Baris kedua -->
                            
                                    <g id="P55" transform="translate(75,40)">
                                        <?php
                                    $P55C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P55-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P55C['id_kategori_warna']==""){
                                        $P55C_color="white";
                                    } else {
                                        $wP55C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P55C[id_kategori_warna]'")->row_array();	
                                        $P55C_color=$wP55C['nama_kategori_warna'];
                                    }
                                    
                                    $P55T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P55-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P55T['id_kategori_warna']==""){
                                        $P55T_color="white";
                                    } else {
                                        $wP55T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P55T[id_kategori_warna]'")->row_array();	
                                        $P55T_color=$wP55T['nama_kategori_warna'];
                                    }
                                    
                                    $P55B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P55-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P55B['id_kategori_warna']==""){
                                        $P55B_color="white";
                                    } else {
                                        $wP55B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P55B[id_kategori_warna]'")->row_array();	
                                        $P55B_color=$wP55B['nama_kategori_warna'];
                                    }
                                    
                                    $P55R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P55-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P55R['id_kategori_warna']==""){
                                        $P55R_color="white";
                                    } else {
                                        $wP55R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P55R[id_kategori_warna]'")->row_array();	
                                        $P55R_color=$wP55R['nama_kategori_warna'];
                                    }
                                    
                                    $P55L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P55-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P55L['id_kategori_warna']==""){
                                        $P55L_color="white";
                                    } else {
                                        $wP55L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P55L[id_kategori_warna]'")->row_array();	
                                        $P55L_color=$wP55L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P55C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P55T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P55B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P55R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P55L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">55</text>
                                    </g>
                                    <g id="P54" transform="translate(100,40)">
                                        <?php
                                    $P54C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P54-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P54C['id_kategori_warna']==""){
                                        $P54C_color="white";
                                    } else {
                                        $wP54C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P54C[id_kategori_warna]'")->row_array();	
                                        $P54C_color=$wP54C['nama_kategori_warna'];
                                    }
                                    
                                    $P54T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P54-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P54T['id_kategori_warna']==""){
                                        $P54T_color="white";
                                    } else {
                                        $wP54T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P54T[id_kategori_warna]'")->row_array();	
                                        $P54T_color=$wP54T['nama_kategori_warna'];
                                    }
                                    
                                    $P54B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P54-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P54B['id_kategori_warna']==""){
                                        $P54B_color="white";
                                    } else {
                                        $wP54B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P54B[id_kategori_warna]'")->row_array();	
                                        $P54B_color=$wP54B['nama_kategori_warna'];
                                    }
                                    
                                    $P54R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P54-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P54R['id_kategori_warna']==""){
                                        $P54R_color="white";
                                    } else {
                                        $wP54R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P54R[id_kategori_warna]'")->row_array();	
                                        $P54R_color=$wP54R['nama_kategori_warna'];
                                    }
                                    
                                    $P54L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P54-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P54L['id_kategori_warna']==""){
                                        $P54L_color="white";
                                    } else {
                                        $wP54L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P54L[id_kategori_warna]'")->row_array();	
                                        $P54L_color=$wP54L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P54C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P54T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P54B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P54R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P54L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">54</text>
                                    </g>
                                    <g id="P53" transform="translate(125,40)"><?php
                                    $P53C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P53-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P53C['id_kategori_warna']==""){
                                        $P53C_color="white";
                                    } else {
                                        $wP53C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P53C[id_kategori_warna]'")->row_array();	
                                        $P53C_color=$wP53C['nama_kategori_warna'];
                                    }
                                    
                                    $P53T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P53-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P53T['id_kategori_warna']==""){
                                        $P53T_color="white";
                                    } else {
                                        $wP53T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P53T[id_kategori_warna]'")->row_array();	
                                        $P53T_color=$wP53T['nama_kategori_warna'];
                                    }
                                    
                                    $P53B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P53-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P53B['id_kategori_warna']==""){
                                        $P53B_color="white";
                                    } else {
                                        $wP53B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P53B[id_kategori_warna]'")->row_array();	
                                        $P53B_color=$wP53B['nama_kategori_warna'];
                                    }
                                    
                                    $P53R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P53-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P53R['id_kategori_warna']==""){
                                        $P53R_color="white";
                                    } else {
                                        $wP53R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P53R[id_kategori_warna]'")->row_array();	
                                        $P53R_color=$wP53R['nama_kategori_warna'];
                                    }
                                    
                                    $P53L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P53-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P53L['id_kategori_warna']==""){
                                        $P53L_color="white";
                                    } else {
                                        $wP53L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P53L[id_kategori_warna]'")->row_array();	
                                        $P53L_color=$wP53L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P53C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P53T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P53B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P53R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P53L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">53</text>
                                    </g>
                                    <g id="P52" transform="translate(150,40)">
                                        <?php
                                    $P52C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P52-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P52C['id_kategori_warna']==""){
                                        $P52C_color="white";
                                    } else {
                                        $wP52C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P52C[id_kategori_warna]'")->row_array();	
                                        $P52C_color=$wP52C['nama_kategori_warna'];
                                    }
                                    
                                    $P52T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P52-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P52T['id_kategori_warna']==""){
                                        $P52T_color="white";
                                    } else {
                                        $wP52T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P52T[id_kategori_warna]'")->row_array();	
                                        $P52T_color=$wP52T['nama_kategori_warna'];
                                    }
                                    
                                    $P52B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P52-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P52B['id_kategori_warna']==""){
                                        $P52B_color="white";
                                    } else {
                                        $wP52B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P52B[id_kategori_warna]'")->row_array();	
                                        $P52B_color=$wP52B['nama_kategori_warna'];
                                    }
                                    
                                    $P52R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P52-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P52R['id_kategori_warna']==""){
                                        $P52R_color="white";
                                    } else {
                                        $wP52R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P52R[id_kategori_warna]'")->row_array();	
                                        $P52R_color=$wP52R['nama_kategori_warna'];
                                    }
                                    
                                    $P52L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P52-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P52L['id_kategori_warna']==""){
                                        $P52L_color="white";
                                    } else {
                                        $wP52L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P52L[id_kategori_warna]'")->row_array();	
                                        $P52L_color=$wP52L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P52C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P52T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P52B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P52R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P52L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">52</text>
                                    </g>
                                    <g id="P51" transform="translate(175,40)">
                                        <?php
                                    $P51C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P51-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P51C['id_kategori_warna']==""){
                                        $P51C_color="white";
                                    } else {
                                        $wP51C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P51C[id_kategori_warna]'")->row_array();	
                                        $P51C_color=$wP51C['nama_kategori_warna'];
                                    }
                                    
                                    $P51T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P51-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P51T['id_kategori_warna']==""){
                                        $P51T_color="white";
                                    } else {
                                        $wP51T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P51T[id_kategori_warna]'")->row_array();	
                                        $P51T_color=$wP51T['nama_kategori_warna'];
                                    }
                                    
                                    $P51B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P51-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P51B['id_kategori_warna']==""){
                                        $P51B_color="white";
                                    } else {
                                        $wP51B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P51B[id_kategori_warna]'")->row_array();	
                                        $P51B_color=$wP51B['nama_kategori_warna'];
                                    }
                                    
                                    $P51R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P51-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P51R['id_kategori_warna']==""){
                                        $P51R_color="white";
                                    } else {
                                        $wP51R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P51R[id_kategori_warna]'")->row_array();	
                                        $P51R_color=$wP51R['nama_kategori_warna'];
                                    }
                                    
                                    $P51L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P51-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P51L['id_kategori_warna']==""){
                                        $P51L_color="white";
                                    } else {
                                        $wP51L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P51L[id_kategori_warna]'")->row_array();	
                                        $P51L_color=$wP51L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P51C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P51T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P51B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P51R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P51L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">51</text>
                                    </g>
                            
                                    <!-- row ke tiga baris pertama -->
                            
                                    <g id="P85" transform="translate(75,80)">
                                        <?php
                                    $P85C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P85-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P85C['id_kategori_warna']==""){
                                        $P85C_color="white";
                                    } else {
                                        $wP85C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P85C[id_kategori_warna]'")->row_array();	
                                        $P85C_color=$wP85C['nama_kategori_warna'];
                                    }
                                    
                                    $P85T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P85-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P85T['id_kategori_warna']==""){
                                        $P85T_color="white";
                                    } else {
                                        $wP85T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P85T[id_kategori_warna]'")->row_array();	
                                        $P85T_color=$wP85T['nama_kategori_warna'];
                                    }
                                    
                                    $P85B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P85-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P85B['id_kategori_warna']==""){
                                        $P85B_color="white";
                                    } else {
                                        $wP85B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P85B[id_kategori_warna]'")->row_array();	
                                        $P85B_color=$wP85B['nama_kategori_warna'];
                                    }
                                    
                                    $P85R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P85-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P85R['id_kategori_warna']==""){
                                        $P85R_color="white";
                                    } else {
                                        $wP85R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P85R[id_kategori_warna]'")->row_array();	
                                        $P85R_color=$wP85R['nama_kategori_warna'];
                                    }
                                    
                                    $P85L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P85-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P85L['id_kategori_warna']==""){
                                        $P85L_color="white";
                                    } else {
                                        $wP85L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P85L[id_kategori_warna]'")->row_array();	
                                        $P85L_color=$wP85L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P85C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P85T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P85B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P85R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P85L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">85</text>
                                    </g>
                                    <g id="P84" transform="translate(100,80)">
                                        <?php
                                    $P84C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P84-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P84C['id_kategori_warna']==""){
                                        $P84C_color="white";
                                    } else {
                                        $wP84C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P84C[id_kategori_warna]'")->row_array();	
                                        $P84C_color=$wP84C['nama_kategori_warna'];
                                    }
                                    
                                    $P84T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P84-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P84T['id_kategori_warna']==""){
                                        $P84T_color="white";
                                    } else {
                                        $wP84T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P84T[id_kategori_warna]'")->row_array();	
                                        $P84T_color=$wP84T['nama_kategori_warna'];
                                    }
                                    
                                    $P84B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P84-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P84B['id_kategori_warna']==""){
                                        $P84B_color="white";
                                    } else {
                                        $wP84B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P84B[id_kategori_warna]'")->row_array();	
                                        $P84B_color=$wP84B['nama_kategori_warna'];
                                    }
                                    
                                    $P84R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P84-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P84R['id_kategori_warna']==""){
                                        $P84R_color="white";
                                    } else {
                                        $wP84R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P84R[id_kategori_warna]'")->row_array();	
                                        $P84R_color=$wP84R['nama_kategori_warna'];
                                    }
                                    
                                    $P84L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P84-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P84L['id_kategori_warna']==""){
                                        $P84L_color="white";
                                    } else {
                                        $wP84L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P84L[id_kategori_warna]'")->row_array();	
                                        $P84L_color=$wP84L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P84C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P84T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P84B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P84R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P84L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">84</text>
                                    </g>
                                    <g id="P83" transform="translate(125,80)">
                                        <?php
                                    $P83C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P83-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P83C['id_kategori_warna']==""){
                                        $P83C_color="white";
                                    } else {
                                        $wP83C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P83C[id_kategori_warna]'")->row_array();	
                                        $P83C_color=$wP83C['nama_kategori_warna'];
                                    }
                                    
                                    $P83T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P83-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P83T['id_kategori_warna']==""){
                                        $P83T_color="white";
                                    } else {
                                        $wP83T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P83T[id_kategori_warna]'")->row_array();	
                                        $P83T_color=$wP83T['nama_kategori_warna'];
                                    }
                                    
                                    $P83B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P83-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P83B['id_kategori_warna']==""){
                                        $P83B_color="white";
                                    } else {
                                        $wP83B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P83B[id_kategori_warna]'")->row_array();	
                                        $P83B_color=$wP83B['nama_kategori_warna'];
                                    }
                                    
                                    $P83R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P83-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P83R['id_kategori_warna']==""){
                                        $P83R_color="white";
                                    } else {
                                        $wP83R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P83R[id_kategori_warna]'")->row_array();	
                                        $P83R_color=$wP83R['nama_kategori_warna'];
                                    }
                                    
                                    $P83L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P83-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P83L['id_kategori_warna']==""){
                                        $P83L_color="white";
                                    } else {
                                        $wP83L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P83L[id_kategori_warna]'")->row_array();	
                                        $P83L_color=$wP83L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P83C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P83T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P83B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P83R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P83L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">83</text>
                                    </g>
                                    <g id="P82" transform="translate(150,80)">
                                        <?php
                                    $P82C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P82-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P82C['id_kategori_warna']==""){
                                        $P82C_color="white";
                                    } else {
                                        $wP82C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P82C[id_kategori_warna]'")->row_array();	
                                        $P82C_color=$wP82C['nama_kategori_warna'];
                                    }
                                    
                                    $P82T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P82-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P82T['id_kategori_warna']==""){
                                        $P82T_color="white";
                                    } else {
                                        $wP82T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P82T[id_kategori_warna]'")->row_array();	
                                        $P82T_color=$wP82T['nama_kategori_warna'];
                                    }
                                    
                                    $P82B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P82-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P82B['id_kategori_warna']==""){
                                        $P82B_color="white";
                                    } else {
                                        $wP82B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P82B[id_kategori_warna]'")->row_array();	
                                        $P82B_color=$wP82B['nama_kategori_warna'];
                                    }
                                    
                                    $P82R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P82-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P82R['id_kategori_warna']==""){
                                        $P82R_color="white";
                                    } else {
                                        $wP82R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P82R[id_kategori_warna]'")->row_array();	
                                        $P82R_color=$wP82R['nama_kategori_warna'];
                                    }
                                    
                                    $P82L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P82-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P82L['id_kategori_warna']==""){
                                        $P82L_color="white";
                                    } else {
                                        $wP82L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P82L[id_kategori_warna]'")->row_array();	
                                        $P82L_color=$wP82L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P82C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P82T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P82B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P82R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P82L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">82</text>
                                    </g>
                                    <g id="P81" transform="translate(175,80)">
                                        <?php
                                    $P81C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P81-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P81C['id_kategori_warna']==""){
                                        $P81C_color="white";
                                    } else {
                                        $wP81C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P81C[id_kategori_warna]'")->row_array();	
                                        $P81C_color=$wP81C['nama_kategori_warna'];
                                    }
                                    
                                    $P81T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P81-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P81T['id_kategori_warna']==""){
                                        $P81T_color="white";
                                    } else {
                                        $wP81T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P81T[id_kategori_warna]'")->row_array();	
                                        $P81T_color=$wP81T['nama_kategori_warna'];
                                    }
                                    
                                    $P81B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P81-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P81B['id_kategori_warna']==""){
                                        $P81B_color="white";
                                    } else {
                                        $wP81B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P81B[id_kategori_warna]'")->row_array();	
                                        $P81B_color=$wP81B['nama_kategori_warna'];
                                    }
                                    
                                    $P81R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P81-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P81R['id_kategori_warna']==""){
                                        $P81R_color="white";
                                    } else {
                                        $wP81R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P81R[id_kategori_warna]'")->row_array();	
                                        $P81R_color=$wP81R['nama_kategori_warna'];
                                    }
                                    
                                    $P81L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P81-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P81L['id_kategori_warna']==""){
                                        $P81L_color="white";
                                    } else {
                                        $wP81L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P81L[id_kategori_warna]'")->row_array();	
                                        $P81L_color=$wP81L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P81C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P81T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P81B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P81R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P81L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">81</text>
                                    </g>
                            
                                    <!-- row 4 baris pertaman -->
                            
                                    <g id="P48" transform="translate(0,120)">
                                        <?php
                                    $P48C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P48-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P48C['id_kategori_warna']==""){
                                        $P48C_color="white";
                                    } else {
                                        $wP48C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P48C[id_kategori_warna]'")->row_array();	
                                        $P48C_color=$wP48C['nama_kategori_warna'];
                                    }
                                    
                                    $P48T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P48-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P48T['id_kategori_warna']==""){
                                        $P48T_color="white";
                                    } else {
                                        $wP48T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P48T[id_kategori_warna]'")->row_array();	
                                        $P48T_color=$wP48T['nama_kategori_warna'];
                                    }
                                    
                                    $P48B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P48-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P48B['id_kategori_warna']==""){
                                        $P48B_color="white";
                                    } else {
                                        $wP48B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P48B[id_kategori_warna]'")->row_array();	
                                        $P48B_color=$wP48B['nama_kategori_warna'];
                                    }
                                    
                                    $P48R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P48-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P48R['id_kategori_warna']==""){
                                        $P48R_color="white";
                                    } else {
                                        $wP48R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P48R[id_kategori_warna]'")->row_array();	
                                        $P48R_color=$wP48R['nama_kategori_warna'];
                                    }
                                    
                                    $P48L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P48-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P48L['id_kategori_warna']==""){
                                        $P48L_color="white";
                                    } else {
                                        $wP48L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P48L[id_kategori_warna]'")->row_array();	
                                        $P48L_color=$wP48L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P48C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P48T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P48B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P48R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P48L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">48</text>
                                    </g>
                                    <g id="P47" transform="translate(25,120)">
                                        <?php
                                    $P47C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P47-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P47C['id_kategori_warna']==""){
                                        $P47C_color="white";
                                    } else {
                                        $wP47C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P47C[id_kategori_warna]'")->row_array();	
                                        $P47C_color=$wP47C['nama_kategori_warna'];
                                    }
                                    
                                    $P47T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P47-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P47T['id_kategori_warna']==""){
                                        $P47T_color="white";
                                    } else {
                                        $wP47T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P47T[id_kategori_warna]'")->row_array();	
                                        $P47T_color=$wP47T['nama_kategori_warna'];
                                    }
                                    
                                    $P47B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P47-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P47B['id_kategori_warna']==""){
                                        $P47B_color="white";
                                    } else {
                                        $wP47B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P47B[id_kategori_warna]'")->row_array();	
                                        $P47B_color=$wP47B['nama_kategori_warna'];
                                    }
                                    
                                    $P47R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P47-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P47R['id_kategori_warna']==""){
                                        $P47R_color="white";
                                    } else {
                                        $wP47R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P47R[id_kategori_warna]'")->row_array();	
                                        $P47R_color=$wP47R['nama_kategori_warna'];
                                    }
                                    
                                    $P47L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P47-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P47L['id_kategori_warna']==""){
                                        $P47L_color="white";
                                    } else {
                                        $wP47L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P47L[id_kategori_warna]'")->row_array();	
                                        $P47L_color=$wP47L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P47C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P47T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P47B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P47R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P47L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">47</text>
                                    </g>
                                    <g id="P46" transform="translate(50,120)">
                                        <?php
                                    $P46C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P46-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P46C['id_kategori_warna']==""){
                                        $P46C_color="white";
                                    } else {
                                        $wP46C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P46C[id_kategori_warna]'")->row_array();	
                                        $P46C_color=$wP46C['nama_kategori_warna'];
                                    }
                                    
                                    $P46T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P46-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P46T['id_kategori_warna']==""){
                                        $P46T_color="white";
                                    } else {
                                        $wP46T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P46T[id_kategori_warna]'")->row_array();	
                                        $P46T_color=$wP46T['nama_kategori_warna'];
                                    }
                                    
                                    $P46B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P46-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P46B['id_kategori_warna']==""){
                                        $P46B_color="white";
                                    } else {
                                        $wP46B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P46B[id_kategori_warna]'")->row_array();	
                                        $P46B_color=$wP46B['nama_kategori_warna'];
                                    }
                                    
                                    $P46R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P46-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P46R['id_kategori_warna']==""){
                                        $P46R_color="white";
                                    } else {
                                        $wP46R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P46R[id_kategori_warna]'")->row_array();	
                                        $P46R_color=$wP46R['nama_kategori_warna'];
                                    }
                                    
                                    $P46L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P46-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P46L['id_kategori_warna']==""){
                                        $P46L_color="white";
                                    } else {
                                        $wP46L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P46L[id_kategori_warna]'")->row_array();	
                                        $P46L_color=$wP46L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P46C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P46T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P46B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P46R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P46L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">46</text>
                                    </g>
                                    <g id="P45" transform="translate(75,120)">
                                        <?php
                                    $P45C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P45-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P45C['id_kategori_warna']==""){
                                        $P45C_color="white";
                                    } else {
                                        $wP45C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P45C[id_kategori_warna]'")->row_array();	
                                        $P45C_color=$wP45C['nama_kategori_warna'];
                                    }
                                    
                                    $P45T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P45-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P45T['id_kategori_warna']==""){
                                        $P45T_color="white";
                                    } else {
                                        $wP45T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P45T[id_kategori_warna]'")->row_array();	
                                        $P45T_color=$wP45T['nama_kategori_warna'];
                                    }
                                    
                                    $P45B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P45-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P45B['id_kategori_warna']==""){
                                        $P45B_color="white";
                                    } else {
                                        $wP45B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P45B[id_kategori_warna]'")->row_array();	
                                        $P45B_color=$wP45B['nama_kategori_warna'];
                                    }
                                    
                                    $P45R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P45-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P45R['id_kategori_warna']==""){
                                        $P45R_color="white";
                                    } else {
                                        $wP45R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P45R[id_kategori_warna]'")->row_array();	
                                        $P45R_color=$wP45R['nama_kategori_warna'];
                                    }
                                    
                                    $P45L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P45-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P45L['id_kategori_warna']==""){
                                        $P45L_color="white";
                                    } else {
                                        $wP45L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P45L[id_kategori_warna]'")->row_array();	
                                        $P45L_color=$wP45L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P45C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P45T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P45B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P45R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P45L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">45</text>
                                    </g>
                                    <g id="P44" transform="translate(100,120)">
                                        <?php
                                    $P44C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P44-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P44C['id_kategori_warna']==""){
                                        $P44C_color="white";
                                    } else {
                                        $wP44C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P44C[id_kategori_warna]'")->row_array();	
                                        $P44C_color=$wP44C['nama_kategori_warna'];
                                    }
                                    
                                    $P44T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P44-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P44T['id_kategori_warna']==""){
                                        $P44T_color="white";
                                    } else {
                                        $wP44T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P44T[id_kategori_warna]'")->row_array();	
                                        $P44T_color=$wP44T['nama_kategori_warna'];
                                    }
                                    
                                    $P44B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P44-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P44B['id_kategori_warna']==""){
                                        $P44B_color="white";
                                    } else {
                                        $wP44B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P44B[id_kategori_warna]'")->row_array();	
                                        $P44B_color=$wP44B['nama_kategori_warna'];
                                    }
                                    
                                    $P44R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P44-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P44R['id_kategori_warna']==""){
                                        $P44R_color="white";
                                    } else {
                                        $wP44R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P44R[id_kategori_warna]'")->row_array();	
                                        $P44R_color=$wP44R['nama_kategori_warna'];
                                    }
                                    
                                    $P44L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P44-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P44L['id_kategori_warna']==""){
                                        $P44L_color="white";
                                    } else {
                                        $wP44L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P44L[id_kategori_warna]'")->row_array();	
                                        $P44L_color=$wP44L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P44C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P44T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P44B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P44R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P44L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">44</text>
                                    </g>
                                    <g id="P43" transform="translate(125,120)">
                                        <?php
                                    $P43C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P43-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P43C['id_kategori_warna']==""){
                                        $P43C_color="white";
                                    } else {
                                        $wP43C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P43C[id_kategori_warna]'")->row_array();	
                                        $P43C_color=$wP43C['nama_kategori_warna'];
                                    }
                                    
                                    $P43T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P43-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P43T['id_kategori_warna']==""){
                                        $P43T_color="white";
                                    } else {
                                        $wP43T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P43T[id_kategori_warna]'")->row_array();	
                                        $P43T_color=$wP43T['nama_kategori_warna'];
                                    }
                                    
                                    $P43B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P43-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P43B['id_kategori_warna']==""){
                                        $P43B_color="white";
                                    } else {
                                        $wP43B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P43B[id_kategori_warna]'")->row_array();	
                                        $P43B_color=$wP43B['nama_kategori_warna'];
                                    }
                                    
                                    $P43R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P43-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P43R['id_kategori_warna']==""){
                                        $P43R_color="white";
                                    } else {
                                        $wP43R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P43R[id_kategori_warna]'")->row_array();	
                                        $P43R_color=$wP43R['nama_kategori_warna'];
                                    }
                                    
                                    $P43L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P43-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P43L['id_kategori_warna']==""){
                                        $P43L_color="white";
                                    } else {
                                        $wP43L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P43L[id_kategori_warna]'")->row_array();	
                                        $P43L_color=$wP43L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P43C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P43T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P43B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P43R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P43L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">43</text>
                                    </g>
                                    <g id="P42" transform="translate(150,120)">
                                        <?php
                                    $P42C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P42-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P42C['id_kategori_warna']==""){
                                        $P42C_color="white";
                                    } else {
                                        $wP42C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P42C[id_kategori_warna]'")->row_array();	
                                        $P42C_color=$wP42C['nama_kategori_warna'];
                                    }
                                    
                                    $P42T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P42-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P42T['id_kategori_warna']==""){
                                        $P42T_color="white";
                                    } else {
                                        $wP42T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P42T[id_kategori_warna]'")->row_array();	
                                        $P42T_color=$wP42T['nama_kategori_warna'];
                                    }
                                    
                                    $P42B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P42-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P42B['id_kategori_warna']==""){
                                        $P42B_color="white";
                                    } else {
                                        $wP42B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P42B[id_kategori_warna]'")->row_array();	
                                        $P42B_color=$wP42B['nama_kategori_warna'];
                                    }
                                    
                                    $P42R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P42-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P42R['id_kategori_warna']==""){
                                        $P42R_color="white";
                                    } else {
                                        $wP42R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P42R[id_kategori_warna]'")->row_array();	
                                        $P42R_color=$wP42R['nama_kategori_warna'];
                                    }
                                    
                                    $P42L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P42-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P42L['id_kategori_warna']==""){
                                        $P42L_color="white";
                                    } else {
                                        $wP42L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P42L[id_kategori_warna]'")->row_array();	
                                        $P42L_color=$wP42L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P42C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P42T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P42B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P42R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P42L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">42</text>
                                    </g>
                                    <g id="P41" transform="translate(175,120)">
                                        <?php
                                    $P41C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P41-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P41C['id_kategori_warna']==""){
                                        $P41C_color="white";
                                    } else {
                                        $wP41C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P41C[id_kategori_warna]'")->row_array();	
                                        $P41C_color=$wP41C['nama_kategori_warna'];
                                    }
                                    
                                    $P41T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P41-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P41T['id_kategori_warna']==""){
                                        $P41T_color="white";
                                    } else {
                                        $wP41T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P41T[id_kategori_warna]'")->row_array();	
                                        $P41T_color=$wP41T['nama_kategori_warna'];
                                    }
                                    
                                    $P41B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P41-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P41B['id_kategori_warna']==""){
                                        $P41B_color="white";
                                    } else {
                                        $wP41B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P41B[id_kategori_warna]'")->row_array();	
                                        $P41B_color=$wP41B['nama_kategori_warna'];
                                    }
                                    
                                    $P41R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P41-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P41R['id_kategori_warna']==""){
                                        $P41R_color="white";
                                    } else {
                                        $wP41R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P41R[id_kategori_warna]'")->row_array();	
                                        $P41R_color=$wP41R['nama_kategori_warna'];
                                    }
                                    
                                    $P41L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P41-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P41L['id_kategori_warna']==""){
                                        $P41L_color="white";
                                    } else {
                                        $wP41L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P41L[id_kategori_warna]'")->row_array();	
                                        $P41L_color=$wP41L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P41C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P41T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P41B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P41R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P41L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">41</text>
                                    </g>
                                    
                                    <!-- Row pertama baris kedua -->
                                    
                                    <g id="P21" transform="translate(210,0)">
                                        <?php
                                    $P21C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P21-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P21C['id_kategori_warna']==""){
                                        $P21C_color="white";
                                    } else {
                                        $wP21C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P21C[id_kategori_warna]'")->row_array();	
                                        $P21C_color=$wP21C['nama_kategori_warna'];
                                    }
                                    
                                    $P21T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P21-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P21T['id_kategori_warna']==""){
                                        $P21T_color="white";
                                    } else {
                                        $wP21T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P21T[id_kategori_warna]'")->row_array();	
                                        $P21T_color=$wP21T['nama_kategori_warna'];
                                    }
                                    
                                    $P21B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P21-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P21B['id_kategori_warna']==""){
                                        $P21B_color="white";
                                    } else {
                                        $wP21B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P21B[id_kategori_warna]'")->row_array();	
                                        $P21B_color=$wP21B['nama_kategori_warna'];
                                    }
                                    
                                    $P21R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P21-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P21R['id_kategori_warna']==""){
                                        $P21R_color="white";
                                    } else {
                                        $wP21R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P21R[id_kategori_warna]'")->row_array();	
                                        $P21R_color=$wP21R['nama_kategori_warna'];
                                    }
                                    
                                    $P21L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P21-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P21L['id_kategori_warna']==""){
                                        $P21L_color="white";
                                    } else {
                                        $wP21L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P21L[id_kategori_warna]'")->row_array();	
                                        $P21L_color=$wP21L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P21C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P21T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P21B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P21R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P21L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">21</text>
                                    </g>
                                    <g id="P22" transform="translate(235,0)">
                                        <?php
                                    $P22C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P22-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P22C['id_kategori_warna']==""){
                                        $P22C_color="white";
                                    } else {
                                        $wP22C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P22C[id_kategori_warna]'")->row_array();	
                                        $P22C_color=$wP22C['nama_kategori_warna'];
                                    }
                                    
                                    $P22T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P22-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P22T['id_kategori_warna']==""){
                                        $P22T_color="white";
                                    } else {
                                        $wP22T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P22T[id_kategori_warna]'")->row_array();	
                                        $P22T_color=$wP22T['nama_kategori_warna'];
                                    }
                                    
                                    $P22B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P22-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P22B['id_kategori_warna']==""){
                                        $P22B_color="white";
                                    } else {
                                        $wP22B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P22B[id_kategori_warna]'")->row_array();	
                                        $P22B_color=$wP22B['nama_kategori_warna'];
                                    }
                                    
                                    $P22R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P22-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P22R['id_kategori_warna']==""){
                                        $P22R_color="white";
                                    } else {
                                        $wP22R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P22R[id_kategori_warna]'")->row_array();	
                                        $P22R_color=$wP22R['nama_kategori_warna'];
                                    }
                                    
                                    $P22L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P22-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P22L['id_kategori_warna']==""){
                                        $P22L_color="white";
                                    } else {
                                        $wP22L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P22L[id_kategori_warna]'")->row_array();	
                                        $P22L_color=$wP22L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P22C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P22T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P22B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P22R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P22L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">22</text>
                                    </g>
                                    <g id="P23" transform="translate(260,0)">
                                        <?php
                                    $P23C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P23-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P23C['id_kategori_warna']==""){
                                        $P23C_color="white";
                                    } else {
                                        $wP23C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P23C[id_kategori_warna]'")->row_array();	
                                        $P23C_color=$wP23C['nama_kategori_warna'];
                                    }
                                    
                                    $P23T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P23-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P23T['id_kategori_warna']==""){
                                        $P23T_color="white";
                                    } else {
                                        $wP23T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P23T[id_kategori_warna]'")->row_array();	
                                        $P23T_color=$wP23T['nama_kategori_warna'];
                                    }
                                    
                                    $P23B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P23-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P23B['id_kategori_warna']==""){
                                        $P23B_color="white";
                                    } else {
                                        $wP23B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P23B[id_kategori_warna]'")->row_array();	
                                        $P23B_color=$wP23B['nama_kategori_warna'];
                                    }
                                    
                                    $P23R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P23-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P23R['id_kategori_warna']==""){
                                        $P23R_color="white";
                                    } else {
                                        $wP23R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P23R[id_kategori_warna]'")->row_array();	
                                        $P23R_color=$wP23R['nama_kategori_warna'];
                                    }
                                    
                                    $P23L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P23-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P23L['id_kategori_warna']==""){
                                        $P23L_color="white";
                                    } else {
                                        $wP23L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P23L[id_kategori_warna]'")->row_array();	
                                        $P23L_color=$wP23L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P23C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P23T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P23B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P23R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P23L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">23</text>
                                    </g>
                                    <g id="P24" transform="translate(285,0)">
                                        <?php
                                    $P24C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P24-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P24C['id_kategori_warna']==""){
                                        $P24C_color="white";
                                    } else {
                                        $wP24C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P24C[id_kategori_warna]'")->row_array();	
                                        $P24C_color=$wP24C['nama_kategori_warna'];
                                    }
                                    
                                    $P24T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P24-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P24T['id_kategori_warna']==""){
                                        $P24T_color="white";
                                    } else {
                                        $wP24T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P24T[id_kategori_warna]'")->row_array();	
                                        $P24T_color=$wP24T['nama_kategori_warna'];
                                    }
                                    
                                    $P24B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P24-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P24B['id_kategori_warna']==""){
                                        $P24B_color="white";
                                    } else {
                                        $wP24B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P24B[id_kategori_warna]'")->row_array();	
                                        $P24B_color=$wP24B['nama_kategori_warna'];
                                    }
                                    
                                    $P24R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P24-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P24R['id_kategori_warna']==""){
                                        $P24R_color="white";
                                    } else {
                                        $wP24R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P24R[id_kategori_warna]'")->row_array();	
                                        $P24R_color=$wP24R['nama_kategori_warna'];
                                    }
                                    
                                    $P24L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P24-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P24L['id_kategori_warna']==""){
                                        $P24L_color="white";
                                    } else {
                                        $wP24L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P24L[id_kategori_warna]'")->row_array();	
                                        $P24L_color=$wP24L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P24C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P24T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P24B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P24R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P24L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">24</text>
                                    </g>
                                    <g id="P25" transform="translate(310,0)">
                                        <?php
                                    $P25C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P25-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P25C['id_kategori_warna']==""){
                                        $P25C_color="white";
                                    } else {
                                        $wP25C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P25C[id_kategori_warna]'")->row_array();	
                                        $P25C_color=$wP25C['nama_kategori_warna'];
                                    }
                                    
                                    $P25T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P25-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P25T['id_kategori_warna']==""){
                                        $P25T_color="white";
                                    } else {
                                        $wP25T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P25T[id_kategori_warna]'")->row_array();	
                                        $P25T_color=$wP25T['nama_kategori_warna'];
                                    }
                                    
                                    $P25B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P25-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P25B['id_kategori_warna']==""){
                                        $P25B_color="white";
                                    } else {
                                        $wP25B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P25B[id_kategori_warna]'")->row_array();	
                                        $P25B_color=$wP25B['nama_kategori_warna'];
                                    }
                                    
                                    $P25R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P25-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P25R['id_kategori_warna']==""){
                                        $P25R_color="white";
                                    } else {
                                        $wP25R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P25R[id_kategori_warna]'")->row_array();	
                                        $P25R_color=$wP25R['nama_kategori_warna'];
                                    }
                                    
                                    $P25L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P25-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P25L['id_kategori_warna']==""){
                                        $P25L_color="white";
                                    } else {
                                        $wP25L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P25L[id_kategori_warna]'")->row_array();	
                                        $P25L_color=$wP25L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P25C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P25T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P25B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P25R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P25L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">25</text>
                                    </g>
                                    <g id="P26" transform="translate(335,0)">
                                        <?php
                                    $P26C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P26-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P26C['id_kategori_warna']==""){
                                        $P26C_color="white";
                                    } else {
                                        $wP26C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P26C[id_kategori_warna]'")->row_array();	
                                        $P26C_color=$wP26C['nama_kategori_warna'];
                                    }
                                    
                                    $P26T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P26-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P26T['id_kategori_warna']==""){
                                        $P26T_color="white";
                                    } else {
                                        $wP26T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P26T[id_kategori_warna]'")->row_array();	
                                        $P26T_color=$wP26T['nama_kategori_warna'];
                                    }
                                    
                                    $P26B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P26-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P26B['id_kategori_warna']==""){
                                        $P26B_color="white";
                                    } else {
                                        $wP26B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P26B[id_kategori_warna]'")->row_array();	
                                        $P26B_color=$wP26B['nama_kategori_warna'];
                                    }
                                    
                                    $P26R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P26-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P26R['id_kategori_warna']==""){
                                        $P26R_color="white";
                                    } else {
                                        $wP26R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P26R[id_kategori_warna]'")->row_array();	
                                        $P26R_color=$wP26R['nama_kategori_warna'];
                                    }
                                    
                                    $P26L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P26-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P26L['id_kategori_warna']==""){
                                        $P26L_color="white";
                                    } else {
                                        $wP26L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P26L[id_kategori_warna]'")->row_array();	
                                        $P26L_color=$wP26L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P26C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P26T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P26B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P26R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P26L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">26</text>
                                    </g>
                                    <g id="P27" transform="translate(360,0)">
                                        <?php
                                    $P27C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P27-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P27C['id_kategori_warna']==""){
                                        $P27C_color="white";
                                    } else {
                                        $wP27C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P27C[id_kategori_warna]'")->row_array();	
                                        $P27C_color=$wP27C['nama_kategori_warna'];
                                    }
                                    
                                    $P27T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P27-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P27T['id_kategori_warna']==""){
                                        $P27T_color="white";
                                    } else {
                                        $wP27T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P27T[id_kategori_warna]'")->row_array();	
                                        $P27T_color=$wP27T['nama_kategori_warna'];
                                    }
                                    
                                    $P27B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P27-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P27B['id_kategori_warna']==""){
                                        $P27B_color="white";
                                    } else {
                                        $wP27B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P27B[id_kategori_warna]'")->row_array();	
                                        $P27B_color=$wP27B['nama_kategori_warna'];
                                    }
                                    
                                    $P27R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P27-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P27R['id_kategori_warna']==""){
                                        $P27R_color="white";
                                    } else {
                                        $wP27R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P27R[id_kategori_warna]'")->row_array();	
                                        $P27R_color=$wP27R['nama_kategori_warna'];
                                    }
                                    
                                    $P27L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P27-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P27L['id_kategori_warna']==""){
                                        $P27L_color="white";
                                    } else {
                                        $wP27L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P27L[id_kategori_warna]'")->row_array();	
                                        $P27L_color=$wP27L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P27C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P27T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P27B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P27R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P27L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">27</text>
                                    </g>
                                    <g id="P28" transform="translate(385,0)">
                                        <?php
                                    $P28C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P28-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P28C['id_kategori_warna']==""){
                                        $P28C_color="white";
                                    } else {
                                        $wP28C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P28C[id_kategori_warna]'")->row_array();	
                                        $P28C_color=$wP28C['nama_kategori_warna'];
                                    }
                                    
                                    $P28T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P28-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P28T['id_kategori_warna']==""){
                                        $P28T_color="white";
                                    } else {
                                        $wP28T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P28T[id_kategori_warna]'")->row_array();	
                                        $P28T_color=$wP28T['nama_kategori_warna'];
                                    }
                                    
                                    $P28B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P28-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P28B['id_kategori_warna']==""){
                                        $P28B_color="white";
                                    } else {
                                        $wP28B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P28B[id_kategori_warna]'")->row_array();	
                                        $P28B_color=$wP28B['nama_kategori_warna'];
                                    }
                                    
                                    $P28R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P28-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P28R['id_kategori_warna']==""){
                                        $P28R_color="white";
                                    } else {
                                        $wP28R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P28R[id_kategori_warna]'")->row_array();	
                                        $P28R_color=$wP28R['nama_kategori_warna'];
                                    }
                                    
                                    $P28L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P28-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P28L['id_kategori_warna']==""){
                                        $P28L_color="white";
                                    } else {
                                        $wP28L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P28L[id_kategori_warna]'")->row_array();	
                                        $P28L_color=$wP28L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P28C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P28T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P28B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P28R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P28L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">28</text>
                                    </g>
                                    
                                    <!-- Deret kedua baris ke kedua -->
                                    
                                    <g id="P61" transform="translate(210,40)">
                                        <?php
                                    $P61C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P61-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P61C['id_kategori_warna']==""){
                                        $P61C_color="white";
                                    } else {
                                        $wP61C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P61C[id_kategori_warna]'")->row_array();	
                                        $P61C_color=$wP61C['nama_kategori_warna'];
                                    }
                                    
                                    $P61T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P61-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P61T['id_kategori_warna']==""){
                                        $P61T_color="white";
                                    } else {
                                        $wP61T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P61T[id_kategori_warna]'")->row_array();	
                                        $P61T_color=$wP61T['nama_kategori_warna'];
                                    }
                                    
                                    $P61B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P61-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P61B['id_kategori_warna']==""){
                                        $P61B_color="white";
                                    } else {
                                        $wP61B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P61B[id_kategori_warna]'")->row_array();	
                                        $P61B_color=$wP61B['nama_kategori_warna'];
                                    }
                                    
                                    $P61R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P61-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P61R['id_kategori_warna']==""){
                                        $P61R_color="white";
                                    } else {
                                        $wP61R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P61R[id_kategori_warna]'")->row_array();	
                                        $P61R_color=$wP61R['nama_kategori_warna'];
                                    }
                                    
                                    $P61L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P61-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P61L['id_kategori_warna']==""){
                                        $P61L_color="white";
                                    } else {
                                        $wP61L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P61L[id_kategori_warna]'")->row_array();	
                                        $P61L_color=$wP61L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P61C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P61T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P61B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P61R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P61L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">61</text>
                                    </g>
                                    <g id="P62" transform="translate(235,40)">
                                        <?php
                                    $P62C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P62-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P62C['id_kategori_warna']==""){
                                        $P62C_color="white";
                                    } else {
                                        $wP62C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P62C[id_kategori_warna]'")->row_array();	
                                        $P62C_color=$wP62C['nama_kategori_warna'];
                                    }
                                    
                                    $P62T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P62-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P62T['id_kategori_warna']==""){
                                        $P62T_color="white";
                                    } else {
                                        $wP62T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P62T[id_kategori_warna]'")->row_array();	
                                        $P62T_color=$wP62T['nama_kategori_warna'];
                                    }
                                    
                                    $P62B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P62-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P62B['id_kategori_warna']==""){
                                        $P62B_color="white";
                                    } else {
                                        $wP62B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P62B[id_kategori_warna]'")->row_array();	
                                        $P62B_color=$wP62B['nama_kategori_warna'];
                                    }
                                    
                                    $P62R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P62-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P62R['id_kategori_warna']==""){
                                        $P62R_color="white";
                                    } else {
                                        $wP62R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P62R[id_kategori_warna]'")->row_array();	
                                        $P62R_color=$wP62R['nama_kategori_warna'];
                                    }
                                    
                                    $P62L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P62-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P62L['id_kategori_warna']==""){
                                        $P62L_color="white";
                                    } else {
                                        $wP62L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P62L[id_kategori_warna]'")->row_array();	
                                        $P62L_color=$wP62L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P62C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P62T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P62B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P62R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P62L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">62</text>
                                    </g>
                                    <g id="P63" transform="translate(260,40)">
                                        <?php
                                    $P63C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P63-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P63C['id_kategori_warna']==""){
                                        $P63C_color="white";
                                    } else {
                                        $wP63C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P63C[id_kategori_warna]'")->row_array();	
                                        $P63C_color=$wP63C['nama_kategori_warna'];
                                    }
                                    
                                    $P63T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P63-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P63T['id_kategori_warna']==""){
                                        $P63T_color="white";
                                    } else {
                                        $wP63T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P63T[id_kategori_warna]'")->row_array();	
                                        $P63T_color=$wP63T['nama_kategori_warna'];
                                    }
                                    
                                    $P63B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P63-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P63B['id_kategori_warna']==""){
                                        $P63B_color="white";
                                    } else {
                                        $wP63B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P63B[id_kategori_warna]'")->row_array();	
                                        $P63B_color=$wP63B['nama_kategori_warna'];
                                    }
                                    
                                    $P63R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P63-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P63R['id_kategori_warna']==""){
                                        $P63R_color="white";
                                    } else {
                                        $wP63R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P63R[id_kategori_warna]'")->row_array();	
                                        $P63R_color=$wP63R['nama_kategori_warna'];
                                    }
                                    
                                    $P63L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P63-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P63L['id_kategori_warna']==""){
                                        $P63L_color="white";
                                    } else {
                                        $wP63L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P63L[id_kategori_warna]'")->row_array();	
                                        $P63L_color=$wP63L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P63C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P63T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P63B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P63R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P63L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">63</text>
                                    </g>
                                    <g id="P64" transform="translate(285,40)">
                                        <?php
                                    $P64C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P64-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P64C['id_kategori_warna']==""){
                                        $P64C_color="white";
                                    } else {
                                        $wP64C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P64C[id_kategori_warna]'")->row_array();	
                                        $P64C_color=$wP64C['nama_kategori_warna'];
                                    }
                                    
                                    $P64T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P64-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P64T['id_kategori_warna']==""){
                                        $P64T_color="white";
                                    } else {
                                        $wP64T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P64T[id_kategori_warna]'")->row_array();	
                                        $P64T_color=$wP64T['nama_kategori_warna'];
                                    }
                                    
                                    $P64B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P64-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P64B['id_kategori_warna']==""){
                                        $P64B_color="white";
                                    } else {
                                        $wP64B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P64B[id_kategori_warna]'")->row_array();	
                                        $P64B_color=$wP64B['nama_kategori_warna'];
                                    }
                                    
                                    $P64R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P64-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P64R['id_kategori_warna']==""){
                                        $P64R_color="white";
                                    } else {
                                        $wP64R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P64R[id_kategori_warna]'")->row_array();	
                                        $P64R_color=$wP64R['nama_kategori_warna'];
                                    }
                                    
                                    $P64L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P64-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P64L['id_kategori_warna']==""){
                                        $P64L_color="white";
                                    } else {
                                        $wP64L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P64L[id_kategori_warna]'")->row_array();	
                                        $P64L_color=$wP64L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P64C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P64T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P64B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P64R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P64L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">64</text>
                                    </g>
                                    <g id="P65" transform="translate(310,40)">
                                        <?php
                                    $P65C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P65-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P65C['id_kategori_warna']==""){
                                        $P65C_color="white";
                                    } else {
                                        $wP65C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P65C[id_kategori_warna]'")->row_array();	
                                        $P65C_color=$wP65C['nama_kategori_warna'];
                                    }
                                    
                                    $P65T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P65-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P65T['id_kategori_warna']==""){
                                        $P65T_color="white";
                                    } else {
                                        $wP65T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P65T[id_kategori_warna]'")->row_array();	
                                        $P65T_color=$wP65T['nama_kategori_warna'];
                                    }
                                    
                                    $P65B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P65-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P65B['id_kategori_warna']==""){
                                        $P65B_color="white";
                                    } else {
                                        $wP65B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P65B[id_kategori_warna]'")->row_array();	
                                        $P65B_color=$wP65B['nama_kategori_warna'];
                                    }
                                    
                                    $P65R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P65-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P65R['id_kategori_warna']==""){
                                        $P65R_color="white";
                                    } else {
                                        $wP65R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P65R[id_kategori_warna]'")->row_array();	
                                        $P65R_color=$wP65R['nama_kategori_warna'];
                                    }
                                    
                                    $P65L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P65-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P65L['id_kategori_warna']==""){
                                        $P65L_color="white";
                                    } else {
                                        $wP65L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P65L[id_kategori_warna]'")->row_array();	
                                        $P65L_color=$wP65L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P65C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P65T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P65B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P65R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P65L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">65</text>
                                    </g>
                                    
                                    <!-- Deret ketiga baris kedua -->
                                    
                                    <g id="P71" transform="translate(210,80)">
                                        <?php
                                    $P71C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P71-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P71C['id_kategori_warna']==""){
                                        $P71C_color="white";
                                    } else {
                                        $wP71C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P71C[id_kategori_warna]'")->row_array();	
                                        $P71C_color=$wP71C['nama_kategori_warna'];
                                    }
                                    
                                    $P71T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P71-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P71T['id_kategori_warna']==""){
                                        $P71T_color="white";
                                    } else {
                                        $wP71T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P71T[id_kategori_warna]'")->row_array();	
                                        $P71T_color=$wP71T['nama_kategori_warna'];
                                    }
                                    
                                    $P71B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P71-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P71B['id_kategori_warna']==""){
                                        $P71B_color="white";
                                    } else {
                                        $wP71B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P71B[id_kategori_warna]'")->row_array();	
                                        $P71B_color=$wP71B['nama_kategori_warna'];
                                    }
                                    
                                    $P71R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P71-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P71R['id_kategori_warna']==""){
                                        $P71R_color="white";
                                    } else {
                                        $wP71R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P71R[id_kategori_warna]'")->row_array();	
                                        $P71R_color=$wP71R['nama_kategori_warna'];
                                    }
                                    
                                    $P71L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P71-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P71L['id_kategori_warna']==""){
                                        $P71L_color="white";
                                    } else {
                                        $wP71L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P71L[id_kategori_warna]'")->row_array();	
                                        $P71L_color=$wP71L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P71C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P71T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P71B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P71R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P71L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">71</text>
                                    </g>
                                    <g id="P72" transform="translate(235,80)">
                                        <?php
                                    $P72C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P72-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P72C['id_kategori_warna']==""){
                                        $P72C_color="white";
                                    } else {
                                        $wP72C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P72C[id_kategori_warna]'")->row_array();	
                                        $P72C_color=$wP72C['nama_kategori_warna'];
                                    }
                                    
                                    $P72T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P72-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P72T['id_kategori_warna']==""){
                                        $P72T_color="white";
                                    } else {
                                        $wP72T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P72T[id_kategori_warna]'")->row_array();	
                                        $P72T_color=$wP72T['nama_kategori_warna'];
                                    }
                                    
                                    $P72B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P72-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P72B['id_kategori_warna']==""){
                                        $P72B_color="white";
                                    } else {
                                        $wP72B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P72B[id_kategori_warna]'")->row_array();	
                                        $P72B_color=$wP72B['nama_kategori_warna'];
                                    }
                                    
                                    $P72R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P72-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P72R['id_kategori_warna']==""){
                                        $P72R_color="white";
                                    } else {
                                        $wP72R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P72R[id_kategori_warna]'")->row_array();	
                                        $P72R_color=$wP72R['nama_kategori_warna'];
                                    }
                                    
                                    $P72L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P72-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P72L['id_kategori_warna']==""){
                                        $P72L_color="white";
                                    } else {
                                        $wP72L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P72L[id_kategori_warna]'")->row_array();	
                                        $P72L_color=$wP72L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P72C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P72T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P72B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P72R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P72L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">72</text>
                                    </g>
                                    <g id="P73" transform="translate(260,80)">
                                        <?php
                                    $P73C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P73-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P73C['id_kategori_warna']==""){
                                        $P73C_color="white";
                                    } else {
                                        $wP73C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P73C[id_kategori_warna]'")->row_array();	
                                        $P73C_color=$wP73C['nama_kategori_warna'];
                                    }
                                    
                                    $P73T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P73-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P73T['id_kategori_warna']==""){
                                        $P73T_color="white";
                                    } else {
                                        $wP73T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P73T[id_kategori_warna]'")->row_array();	
                                        $P73T_color=$wP73T['nama_kategori_warna'];
                                    }
                                    
                                    $P73B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P73-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P73B['id_kategori_warna']==""){
                                        $P73B_color="white";
                                    } else {
                                        $wP73B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P73B[id_kategori_warna]'")->row_array();	
                                        $P73B_color=$wP73B['nama_kategori_warna'];
                                    }
                                    
                                    $P73R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P73-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P73R['id_kategori_warna']==""){
                                        $P73R_color="white";
                                    } else {
                                        $wP73R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P73R[id_kategori_warna]'")->row_array();	
                                        $P73R_color=$wP73R['nama_kategori_warna'];
                                    }
                                    
                                    $P73L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P73-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P73L['id_kategori_warna']==""){
                                        $P73L_color="white";
                                    } else {
                                        $wP73L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P73L[id_kategori_warna]'")->row_array();	
                                        $P73L_color=$wP73L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P73C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P73T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P73B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P73R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P73L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">73</text>
                                    </g>
                                    <g id="P74" transform="translate(285,80)">
                                        <?php
                                    $P74C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P74-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P74C['id_kategori_warna']==""){
                                        $P74C_color="white";
                                    } else {
                                        $wP74C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P74C[id_kategori_warna]'")->row_array();	
                                        $P74C_color=$wP74C['nama_kategori_warna'];
                                    }
                                    
                                    $P74T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P74-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P74T['id_kategori_warna']==""){
                                        $P74T_color="white";
                                    } else {
                                        $wP74T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P74T[id_kategori_warna]'")->row_array();	
                                        $P74T_color=$wP74T['nama_kategori_warna'];
                                    }
                                    
                                    $P74B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P74-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P74B['id_kategori_warna']==""){
                                        $P74B_color="white";
                                    } else {
                                        $wP74B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P74B[id_kategori_warna]'")->row_array();	
                                        $P74B_color=$wP74B['nama_kategori_warna'];
                                    }
                                    
                                    $P74R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P74-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P74R['id_kategori_warna']==""){
                                        $P74R_color="white";
                                    } else {
                                        $wP74R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P74R[id_kategori_warna]'")->row_array();	
                                        $P74R_color=$wP74R['nama_kategori_warna'];
                                    }
                                    
                                    $P74L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P74-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P74L['id_kategori_warna']==""){
                                        $P74L_color="white";
                                    } else {
                                        $wP74L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P74L[id_kategori_warna]'")->row_array();	
                                        $P74L_color=$wP74L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P74C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P74T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P74B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P74R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P74L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">74</text>
                                    </g>
                                    <g id="P75" transform="translate(310,80)">
                                        <?php
                                    $P75C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P75-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P75C['id_kategori_warna']==""){
                                        $P75C_color="white";
                                    } else {
                                        $wP75C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P75C[id_kategori_warna]'")->row_array();	
                                        $P75C_color=$wP75C['nama_kategori_warna'];
                                    }
                                    
                                    $P75T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P75-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P75T['id_kategori_warna']==""){
                                        $P75T_color="white";
                                    } else {
                                        $wP75T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P75T[id_kategori_warna]'")->row_array();	
                                        $P75T_color=$wP75T['nama_kategori_warna'];
                                    }
                                    
                                    $P75B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P75-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P75B['id_kategori_warna']==""){
                                        $P75B_color="white";
                                    } else {
                                        $wP75B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P75B[id_kategori_warna]'")->row_array();	
                                        $P75B_color=$wP75B['nama_kategori_warna'];
                                    }
                                    
                                    $P75R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P75-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P75R['id_kategori_warna']==""){
                                        $P75R_color="white";
                                    } else {
                                        $wP75R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P75R[id_kategori_warna]'")->row_array();	
                                        $P75R_color=$wP75R['nama_kategori_warna'];
                                    }
                                    
                                    $P75L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P75-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P75L['id_kategori_warna']==""){
                                        $P75L_color="white";
                                    } else {
                                        $wP75L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P75L[id_kategori_warna]'")->row_array();	
                                        $P75L_color=$wP75L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P75C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P75T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P75B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P75R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P75L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">75</text>
                                    </g>
                                    
                                    <!-- Deret ke empat baris kedua -->
                                    
                                    <g id="P31" transform="translate(210,120)">
                                        <?php
                                    $P31C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P31-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P31C['id_kategori_warna']==""){
                                        $P31C_color="white";
                                    } else {
                                        $wP31C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P31C[id_kategori_warna]'")->row_array();	
                                        $P31C_color=$wP31C['nama_kategori_warna'];
                                    }
                                    
                                    $P31T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P31-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P31T['id_kategori_warna']==""){
                                        $P31T_color="white";
                                    } else {
                                        $wP31T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P31T[id_kategori_warna]'")->row_array();	
                                        $P31T_color=$wP31T['nama_kategori_warna'];
                                    }
                                    
                                    $P31B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P31-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P31B['id_kategori_warna']==""){
                                        $P31B_color="white";
                                    } else {
                                        $wP31B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P31B[id_kategori_warna]'")->row_array();	
                                        $P31B_color=$wP31B['nama_kategori_warna'];
                                    }
                                    
                                    $P31R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P31-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P31R['id_kategori_warna']==""){
                                        $P31R_color="white";
                                    } else {
                                        $wP31R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P31R[id_kategori_warna]'")->row_array();	
                                        $P31R_color=$wP31R['nama_kategori_warna'];
                                    }
                                    
                                    $P31L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P31-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P31L['id_kategori_warna']==""){
                                        $P31L_color="white";
                                    } else {
                                        $wP31L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P31L[id_kategori_warna]'")->row_array();	
                                        $P31L_color=$wP31L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P31C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P31T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P31B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P31R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P31L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">31</text>
                                    </g>
                                    <g id="P32" transform="translate(235,120)">
                                        <?php
                                    $P32C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P32-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P32C['id_kategori_warna']==""){
                                        $P32C_color="white";
                                    } else {
                                        $wP32C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P32C[id_kategori_warna]'")->row_array();	
                                        $P32C_color=$wP32C['nama_kategori_warna'];
                                    }
                                    
                                    $P32T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P32-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P32T['id_kategori_warna']==""){
                                        $P32T_color="white";
                                    } else {
                                        $wP32T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P32T[id_kategori_warna]'")->row_array();	
                                        $P32T_color=$wP32T['nama_kategori_warna'];
                                    }
                                    
                                    $P32B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P32-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P32B['id_kategori_warna']==""){
                                        $P32B_color="white";
                                    } else {
                                        $wP32B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P32B[id_kategori_warna]'")->row_array();	
                                        $P32B_color=$wP32B['nama_kategori_warna'];
                                    }
                                    
                                    $P32R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P32-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P32R['id_kategori_warna']==""){
                                        $P32R_color="white";
                                    } else {
                                        $wP32R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P32R[id_kategori_warna]'")->row_array();	
                                        $P32R_color=$wP32R['nama_kategori_warna'];
                                    }
                                    
                                    $P32L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P32-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P32L['id_kategori_warna']==""){
                                        $P32L_color="white";
                                    } else {
                                        $wP32L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P32L[id_kategori_warna]'")->row_array();	
                                        $P32L_color=$wP32L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P32C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P32T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P32B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P32R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P32L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">32</text>
                                    </g>
                                    <g id="P33" transform="translate(260,120)">
                                        <?php
                                    $P33C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P33-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P33C['id_kategori_warna']==""){
                                        $P33C_color="white";
                                    } else {
                                        $wP33C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P33C[id_kategori_warna]'")->row_array();	
                                        $P33C_color=$wP33C['nama_kategori_warna'];
                                    }
                                    
                                    $P33T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P33-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P33T['id_kategori_warna']==""){
                                        $P33T_color="white";
                                    } else {
                                        $wP33T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P33T[id_kategori_warna]'")->row_array();	
                                        $P33T_color=$wP33T['nama_kategori_warna'];
                                    }
                                    
                                    $P33B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P33-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P33B['id_kategori_warna']==""){
                                        $P33B_color="white";
                                    } else {
                                        $wP33B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P33B[id_kategori_warna]'")->row_array();	
                                        $P33B_color=$wP33B['nama_kategori_warna'];
                                    }
                                    
                                    $P33R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P33-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P33R['id_kategori_warna']==""){
                                        $P33R_color="white";
                                    } else {
                                        $wP33R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P33R[id_kategori_warna]'")->row_array();	
                                        $P33R_color=$wP33R['nama_kategori_warna'];
                                    }
                                    
                                    $P33L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P33-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P33L['id_kategori_warna']==""){
                                        $P33L_color="white";
                                    } else {
                                        $wP33L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P33L[id_kategori_warna]'")->row_array();	
                                        $P33L_color=$wP33L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P33C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P33T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P33B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P33R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P33L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">33</text>
                                    </g>
                                    <g id="P34" transform="translate(285,120)">
                                        <?php
                                    $P34C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P34-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P34C['id_kategori_warna']==""){
                                        $P34C_color="white";
                                    } else {
                                        $wP34C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P34C[id_kategori_warna]'")->row_array();	
                                        $P34C_color=$wP34C['nama_kategori_warna'];
                                    }
                                    
                                    $P34T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P34-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P34T['id_kategori_warna']==""){
                                        $P34T_color="white";
                                    } else {
                                        $wP34T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P34T[id_kategori_warna]'")->row_array();	
                                        $P34T_color=$wP34T['nama_kategori_warna'];
                                    }
                                    
                                    $P34B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P34-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P34B['id_kategori_warna']==""){
                                        $P34B_color="white";
                                    } else {
                                        $wP34B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P34B[id_kategori_warna]'")->row_array();	
                                        $P34B_color=$wP34B['nama_kategori_warna'];
                                    }
                                    
                                    $P34R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P34-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P34R['id_kategori_warna']==""){
                                        $P34R_color="white";
                                    } else {
                                        $wP34R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P34R[id_kategori_warna]'")->row_array();	
                                        $P34R_color=$wP34R['nama_kategori_warna'];
                                    }
                                    
                                    $P34L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P34-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P34L['id_kategori_warna']==""){
                                        $P34L_color="white";
                                    } else {
                                        $wP34L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P34L[id_kategori_warna]'")->row_array();	
                                        $P34L_color=$wP34L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P34C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P34T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P34B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P34R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P34L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">34</text>
                                    </g>
                                    <g id="P35" transform="translate(310,120)">
                                        <?php
                                    $P35C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P35-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P35C['id_kategori_warna']==""){
                                        $P35C_color="white";
                                    } else {
                                        $wP35C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P35C[id_kategori_warna]'")->row_array();	
                                        $P35C_color=$wP35C['nama_kategori_warna'];
                                    }
                                    
                                    $P35T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P35-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P35T['id_kategori_warna']==""){
                                        $P35T_color="white";
                                    } else {
                                        $wP35T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P35T[id_kategori_warna]'")->row_array();	
                                        $P35T_color=$wP35T['nama_kategori_warna'];
                                    }
                                    
                                    $P35B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P35-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P35B['id_kategori_warna']==""){
                                        $P35B_color="white";
                                    } else {
                                        $wP35B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P35B[id_kategori_warna]'")->row_array();	
                                        $P35B_color=$wP35B['nama_kategori_warna'];
                                    }
                                    
                                    $P35R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P35-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P35R['id_kategori_warna']==""){
                                        $P35R_color="white";
                                    } else {
                                        $wP35R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P35R[id_kategori_warna]'")->row_array();	
                                        $P35R_color=$wP35R['nama_kategori_warna'];
                                    }
                                    
                                    $P35L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P35-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P35L['id_kategori_warna']==""){
                                        $P35L_color="white";
                                    } else {
                                        $wP35L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P35L[id_kategori_warna]'")->row_array();	
                                        $P35L_color=$wP35L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P35C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P35T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P35B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P35R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P35L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">35</text>
                                    </g>
                                    <g id="P36" transform="translate(335,120)">
                                        <?php
                                    $P36C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P36-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P36C['id_kategori_warna']==""){
                                        $P36C_color="white";
                                    } else {
                                        $wP36C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P36C[id_kategori_warna]'")->row_array();	
                                        $P36C_color=$wP36C['nama_kategori_warna'];
                                    }
                                    
                                    $P36T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P36-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P36T['id_kategori_warna']==""){
                                        $P36T_color="white";
                                    } else {
                                        $wP36T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P36T[id_kategori_warna]'")->row_array();	
                                        $P36T_color=$wP36T['nama_kategori_warna'];
                                    }
                                    
                                    $P36B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P36-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P36B['id_kategori_warna']==""){
                                        $P36B_color="white";
                                    } else {
                                        $wP36B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P36B[id_kategori_warna]'")->row_array();	
                                        $P36B_color=$wP36B['nama_kategori_warna'];
                                    }
                                    
                                    $P36R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P36-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P36R['id_kategori_warna']==""){
                                        $P36R_color="white";
                                    } else {
                                        $wP36R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P36R[id_kategori_warna]'")->row_array();	
                                        $P36R_color=$wP36R['nama_kategori_warna'];
                                    }
                                    
                                    $P36L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P36-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P36L['id_kategori_warna']==""){
                                        $P36L_color="white";
                                    } else {
                                        $wP36L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P36L[id_kategori_warna]'")->row_array();	
                                        $P36L_color=$wP36L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P36C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P36T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P36B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P36R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P36L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">36</text>
                                    </g>
                                    <g id="P37" transform="translate(360,120)">
                                        <?php
                                    $P37C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P37-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P37C['id_kategori_warna']==""){
                                        $P37C_color="white";
                                    } else {
                                        $wP37C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P37C[id_kategori_warna]'")->row_array();	
                                        $P37C_color=$wP37C['nama_kategori_warna'];
                                    }
                                    
                                    $P37T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P37-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P37T['id_kategori_warna']==""){
                                        $P37T_color="white";
                                    } else {
                                        $wP37T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P37T[id_kategori_warna]'")->row_array();	
                                        $P37T_color=$wP37T['nama_kategori_warna'];
                                    }
                                    
                                    $P37B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P37-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P37B['id_kategori_warna']==""){
                                        $P37B_color="white";
                                    } else {
                                        $wP37B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P37B[id_kategori_warna]'")->row_array();	
                                        $P37B_color=$wP37B['nama_kategori_warna'];
                                    }
                                    
                                    $P37R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P37-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P37R['id_kategori_warna']==""){
                                        $P37R_color="white";
                                    } else {
                                        $wP37R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P37R[id_kategori_warna]'")->row_array();	
                                        $P37R_color=$wP37R['nama_kategori_warna'];
                                    }
                                    
                                    $P37L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P37-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P37L['id_kategori_warna']==""){
                                        $P37L_color="white";
                                    } else {
                                        $wP37L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P37L[id_kategori_warna]'")->row_array();	
                                        $P37L_color=$wP37L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P37C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P37T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P37B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P37R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P37L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">37</text>
                                    </g>
                                    <g id="P38" transform="translate(385,120)">
                                        <?php
                                    $P38C = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P38-C' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P38C['id_kategori_warna']==""){
                                        $P38C_color="white";
                                    } else {
                                        $wP38C = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P38C[id_kategori_warna]'")->row_array();	
                                        $P38C_color=$wP38C['nama_kategori_warna'];
                                    }
                                    
                                    $P38T = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P38-T' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P38T['id_kategori_warna']==""){
                                        $P38T_color="white";
                                    } else {
                                        $wP38T = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P38T[id_kategori_warna]'")->row_array();	
                                        $P38T_color=$wP38T['nama_kategori_warna'];
                                    }
                                    
                                    $P38B = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P38-B' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P38B['id_kategori_warna']==""){
                                        $P38B_color="white";
                                    } else {
                                        $wP38B = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P38B[id_kategori_warna]'")->row_array();	
                                        $P38B_color=$wP38B['nama_kategori_warna'];
                                    }
                                    
                                    $P38R = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P38-R' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P38R['id_kategori_warna']==""){
                                        $P38R_color="white";
                                    } else {
                                        $wP38R = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P38R[id_kategori_warna]'")->row_array();	
                                        $P38R_color=$wP38R['nama_kategori_warna'];
                                    }
                                    
                                    $P38L = $this->db->query("SELECT id_kategori_warna FROM medical_record WHERE id_pasien='$pasien' AND id_odontogram='P38-L' ORDER BY id_medical_record DESC") ->row_array();
                                    if ($P38L['id_kategori_warna']==""){
                                        $P38L_color="white";
                                    } else {
                                        $wP38L = $this->db->query("SELECT nama_kategori_warna FROM kategori_warna WHERE id_kategori_warna='$P38L[id_kategori_warna]'")->row_array();	
                                        $P38L_color=$wP38L['nama_kategori_warna'];
                                    }
                        
                                    ?>
                                        <polygon points="5,5 	15,5 	15,15 	5,15" fill="<?php echo"$P38C_color";?>" stroke="navy" stroke-width="0.5" id="C" opacity="1"></polygon>
                                        <polygon points="0,0 	20,0 	15,5 	5,5" fill="<?php echo"$P38T_color";?>" stroke="navy" stroke-width="0.5" id="T" opacity="1"></polygon>
                                        <polygon points="5,15 	15,15 	20,20 	0,20" fill="<?php echo"$P38B_color";?>" stroke="navy" stroke-width="0.5" id="B" opacity="1"></polygon>
                                        <polygon points="15,5 	20,0 	20,20 	15,15" fill="<?php echo"$P38R_color";?>" stroke="navy" stroke-width="0.5" id="R" opacity="1"></polygon>
                                        <polygon points="0,0 	5,5 	5,15 	0,20" fill="<?php echo"$P38L_color";?>" stroke="navy" stroke-width="0.5" id="L" opacity="1"></polygon>
                                        <text x="6" y="30" stroke="navy" fill="navy" stroke-width="0.1" style="font-size: 6pt;font-weight:normal">38</text>
                                    </g>
                                </g>
                            </svg>
                        </div>
                    </td>

                    <td width="40%" rowspan="2" valign="top" >
                        <div style="line-height:30px;height:30px;">
                            <p>
                                <h4 class="box-title"><b>&nbsp;GIGI : </b></h4>
                            </p>
                        </div>
                        
                        <div id="piezadetail">&nbsp;
                            <label id="piezanumero" class="control-label" style="height:10px">XX</label>
                            -
                            <label id="piezacara" class="control-label" style="height:10px">X</label>
                        </div>
                        
                        <h1>
                        &nbsp;<a id="message"><b></b></a>
                        </h1>
                        <hr/>
                        <p>
                            <h4 class="box-title"><b>&nbsp;Jenis Penyakit : </b></h4>
                        </p>
                        <p style="overflow-y: scroll; height:200px;">
                        <?php
                        $queryData = $this->db->query("SELECT nama_kategori_warna,jenis_penyakit FROM kategori_warna WHERE status='Y'") ->result();
                        foreach($queryData as $q){
                        ?>
                        &nbsp;
                        <label style="background-color:<?=$q->nama_kategori_warna?>;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label> &nbsp;
                        <small><?= $q->jenis_penyakit ?></small><br/>

                        <?php }
                        ?>
                        </p>
                        
                    </td>
                </tr>
                
            </table>
                <!-- akhir odontogram -->

                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">History Pemeriksaan</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body table-responsive">
                                <table id="example1" id="table" id="datatable"
                        class="table table-hover table-striped table-bordered table-condensed table-scrollable">
                                    <thead class="breadcrumb">
                                    <tr>
                                        <th style="text-align:center; color:white;" >ID</th>
                                        <th style="text-align:center; color:white;" >Tanggal</th>
                                        <th style="text-align:center; color:white;" >Pasien</th>
                                        <th style="text-align:center; color:white;" >Gigi</th>
                                        <th style="text-align:center; color:white;" >Penyakit</th>
                                        <th style="text-align:center; color:white;" >Diagnosa</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php 	 
                                    $queryMedic = $this->db->query("SELECT * FROM medical_record a 
                                    JOIN tbl_pasien b ON a.id_pasien=b.rekmed 
                                    JOIN kategori_warna c ON a.id_kategori_warna=c.id_kategori_warna 
                                    WHERE a.id_pasien='$data_pas->rekmed'") ->result();

                                    foreach($queryMedic as $m){
                                        
                                    $myDateTime = $this->M_cetak->tanggal_format_indonesia( $m->tanggal).' | '.$m->jam; 
                                    // $newDateString = $myDateTime->format('m/d/Y');
                                    ?>

                                    <tr>
                                        <td><?= $m->id_medical_record?> </td>
                                        <td><?= $myDateTime ?></td>
                                        <td><?= $m->namapas?> </td>
                                        <td><?= $m->id_odontogram?> </td>
                                        <td align='center' 
                                        style="background-color:<?= $m->nama_kategori_warna ?>; color:white">
                                        <label>&nbsp;<?= $m->jenis_penyakit?>&nbsp;</label></td>
                                        <td><?= $m->remark?> </td>
                                    </tr>
                                            
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>
                </div>
            </div>		
        </div> <!-- portlet box blue -->
    </div> <!-- col-md-12 -->
</div>   <!-- row -->
</form> 
		  


<?php
$this->load->view('template/footer');  
?>


<script type="text/javascript">
	
	var nomencladores = {"practicas": [
			{"nomenclador": "02.01", "color": "cyan"},
			{"nomenclador": "02.02", "color": "magenta"},
    ]
	};
	
	var odontograma = {"dientes": [
        {"pieza": "P18", "anterior": {"C": "", "T": "", "B": "", "L": "", "R": ""}, "nuevo": {"C": "", "T": "", "B": "", "L": "", "R": ""} },
        {"pieza": "P17", "anterior": {"C": "", "T": "", "B": "", "L": "", "R": ""}, "nuevo": {"C": "", "T": "", "B": "", "L": "", "R": ""} },
        {"pieza": "P16", "anterior": {"C": "", "T": "", "B": "", "L": "", "R": ""}, "nuevo": {"C": "", "T": "", "B": "", "L": "", "R": ""} },
        {"pieza": "P15", "anterior": {"C": "", "T": "", "B": "", "L": "", "R": ""}, "nuevo": {"C": "", "T": "", "B": "", "L": "", "R": ""} },
        {"pieza": "P14", "anterior": {"C": "", "T": "", "B": "", "L": "", "R": ""}, "nuevo": {"C": "", "T": "", "B": "", "L": "", "R": ""} },
        {"pieza": "P13", "anterior": {"C": "", "T": "", "B": "", "L": "", "R": ""}, "nuevo": {"C": "", "T": "", "B": "", "L": "", "R": ""} },
        {"pieza": "P12", "anterior": {"C": "", "T": "", "B": "", "L": "", "R": ""}, "nuevo": {"C": "", "T": "", "B": "", "L": "", "R": ""} },
        {"pieza": "P11", "anterior": {"C": "", "T": "", "B": "", "L": "", "R": ""}, "nuevo": {"C": "", "T": "", "B": "", "L": "", "R": ""} },
    ]
	};
	
	var color_lapiz = 'navy';
	
	$(document).ready(function () {
		$('polygon').attr('stroke', color_lapiz);
		$('text').attr('stroke', color_lapiz);
		$('text').attr('fill', color_lapiz);
		
		//alert(odontograma.dientes[0].pieza);
		//alert(odontograma.dientes[0].anterior.C);
		
		$('polygon').mouseover(function (evt) {
			// var svg = $('#svgselect').svg('get'); 
			//alert(svg);
			var sector = $(evt.target);
			var cara = sector.attr('id');
			var diente = sector.parent().attr('id');
			$('#piezanumero').html(diente);
			$('#piezacara').html(cara);
			//sector.attr('fill', 'yellow');
			//var over = sector.clone();
			//over.attr('fill', 'yellow');
			//sector.parent().add(over);
		});
		
		$('polygon').mouseout(function (evt) {
			//var sector = $(evt.target);
			//sector.attr('fill', 'white');
			
			$('#piezanumero').html('XX');
			$('#piezacara').html('X');
		});
		
		$('polygon').click(function (evt) {
			var sector = $(evt.target); 
			var strdebug = sector.parent().attr('id') + '-' + sector.attr('id');
			$("#message").html('<a class="btn blue" style="color:white" href="<?php echo base_url(); ?>Poliklinik/add_medical_record/<?="$data_pas->rekmed"; ?>/'+strdebug+'/<?="$noreg"; ?>" ><b>'+strdebug+'</b></a>' );
			//console.debug(strdebug);
		});
		
		$('#chkAnterior').click( function() {
		});
		
		$('#chkNuevo').click( function() {
		});
		
	});

    function cetak() {
        // swal({
        //     title   : "Cetakan Odontogram",
        //     html    : "Sedang Dalam Perbaikan...<br>"+
        //     "Mohon Bersabar",
        //     type    : "info",
        //     confirmButtonText   : "OK"
        // });
        
        var baseurl   = "<?= base_url()?>";
        var noreg     = $('#noreg').val();
        var rekmed    = $('#rekmed').val();
        var param     = '?rekmed=' + rekmed + '&cekk=1&noreg='+noreg;

        url           = baseurl + 'Poliklinik/cetak_odonto/' + param
        window.open(url, '');
        
    }

    function back()
    {
        var thiloc = window.location;
        window.close(thiloc);
    }
	
</script>

</body>
</html> 
<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>
<style>
    .control-label {display:inline-block;text-align:right !important}
</style>
    
    <div class="row">
        <div class="col-md-12">
            <h3 class="page-title">
                <span class="title-unit">&nbsp;<?php echo $this->session->userdata('unit'); ?>&nbsp;</span>
                - 
                <span class="title-web">Kasir <small>Approval Debit / Kredit</small>
            </h3>
            <ul class="page-breadcrumb breadcrumb">
                <li>
                    <i style="color:white;" class="fa fa-home"></i>
                    <a class="title-white" href="../home.php">Awal</a>
                    <i style="color:white;" class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a class="title-white" href="#">Kasir</a>
                    <i style="color:white;" class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a class="title-white" href="#">Approval Debit / Kredit Entri</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-reorder"></i><b>Parameter
                </b>
            </div>
        </div>
        <div class="portlet-body form" style="padding-top:20px">
            <form action="" method="POST" style="width:50%;margin:auto" class="form-horizontal" id="approvalccdc">
            <div class="form-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3"><label class="control-label">User/Kasir</label></div>
                        <div class="col-md-9">
                            <select type="text" class="form-control" name="user" required>
                                <option>--- Pilih Kasir ---</option>
                                <?php
                                    foreach($cashier as $cakey => $caval){
                                        if($caval->uidlogin == $this->session->userdata("username")){
                                            echo '<option value="'. $caval->uidlogin .'" selected>'. $caval->username .'</option>';
                                        } else {
                                            echo '<option value="'. $caval->uidlogin .'">'. $caval->username .'</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3"><label class="control-label">Dari</label></div>
                        <div class="col-md-9"><input type="date" class="form-control" name="fromdate" value="<?= date("Y-m-d") ?>" required></div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3"><label class="control-label">Sampai</label></div>
                        <div class="col-md-9"><input type="date" class="form-control" name="todate" value="<?= date("Y-m-d") ?>" required></div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3"><label class="control-label">EDC</label></div>
                        <div class="col-md-9"><select id="edc" name="edc" class="form-control select2_el_kasbankedc" onkeypress="return tabE(this,event)"></select></div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3"><label class="control-label">Shift</label></div>
                        <div class="col-md-9">
                            <select type="text" class="form-control" name="shift" required>
                                <option>--- Pilih Shift ---</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                    </div>
                    <center>
                        <button class="btn btn-success" id="queryapproval" type="button"><i class="fa fa-refresh"></i>&nbsp; Proses</button>
                        <button class="btn btn-danger" type="button" onclick="location.href='/approval_ccdc'"><i class="fa fa-history"></i>&nbsp;Kembali</button>
                    </center>
                </div>
            </div>
            </form>
        </div>
    </div>

<?php
    $this->load->view('template/footer_tb');  
?>

<script>
    $("#queryapproval").on("click", function(){
        var post_value  = $("#approvalccdc").serialize();

        $.ajax({
            url: "/approval_ccdc/query",
            data: post_value,
            type: "POST",
            dataType: "JSON",
            success: function(data){
                if(data.status == 0){
                    swal({
                        title: "Kesalahan",
                        html: "gagal memuat hasil, hasil tidak ditemukan",
                        type: "error",
                        confirmButtonText: "Tutup",
                        confirmButtonColor: "#aaa"
                    });
                } else {
                    location.href = data.direct
                }
            }
        });
    });
</script>
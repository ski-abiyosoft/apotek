<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tindakan - Pcare Testing Center</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <h1 class="font-bold text-3xl text-center">P-Care Testing Center - Abiyosoft</h1>
    <div class="container">
        <div class="alert alert-danger">
            <p>
                <strong><i class="fa fa-exclamation-triangle fa-fw"></i>&nbsp; Perlu Diketahui</strong>
            </p>
            <br />
            <p>Sebelum melakukan bridging obat, pastikan bahwa database obat sudah tersinkronisasi dengan benar, jika belum lakukan sinkronisasi pada menu sinkronisasi database.</p>
        </div>
        <h4>
            <strong>P-Care Bridging System - Abiyosoft | TINDAKAN</strong>
        </h4>
        <hr stle="margin-bottom: 1rem;" />
        <form name="obat_form" id="obat_form">
            <input type="hidden" name="kdTpk" id="kdTkp" value="<?= $kdTkp ?>">
            <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                <label class="form-label" for="noKunjungan">No. Kunjungan</label>
                <input 
                    class="form-control" 
                    name="noKunjungan" 
                    id="noKunjungan" 
                    type="test" 
                    value="<?= $noKunjungan ?>"
                    readonly
                    >
            </div>
            <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                <label class="form-label" for="kdTindakan">Tindakan</label>
                <select name="kdTindakan" id="kdTindakan"></select>
            </div>
            <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                <label class="form-label" for="biaya">Biaya</label>
                <input type="number" name="biaya" id="biaya">
            </div>
            <div class="mb-3" style="display: grid; grid-template-columns: 1fr 3fr; gap: 20px;">
                <label class="form-label" for="keterangan">Keterangan</label>
                <textarea name="keterangan" id="keterangan" cols="30" rows="2" placeholder="Tulis keterangan tindakan ..."></textarea>
            </div>
        </form>
        <div
            style="display: flex; gap: 20px; align-items: center; justify-content: center; max-width: 80%; margin: 10px auto;">
            <button class="btn btn-success" type="button" onclick="save_tindakan()">
                <i class="fa fa-floppy-o"></i> Simpan
            </button>
            <button class="btn btn-danger" type="button">
                <i class="fa fa-undo"></i> Batal
            </button>
        </div>
        <p style="font-weight: bold; font-size: 16px; margin-top: 20px;">Hasil Bridging</p>
        <hr stle="margin-bottom: 1rem;" />
        <div style="width: 80%; margin: 10px auto; padding: 10px; border: 1px solid black;" id="bridging_result">
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(() => {
            $("#kdTindakan").select2({
                ajax: {
                    url: "<?= base_url("/test/get_ref_tindakan") ?>/" + $("#kdTkp").val(),
                    dataType: "json",
                    delay: 500,
                    minimumInputLength: 4,
                    processResults: function (data) {
                        return {
                        results: data
                    };
                },
                }
            })
        })

        function save_tindakan () {
            $.ajax({
                url: "<?= base_url("api/pcare/add_tindakan_kunjungan") ?>/" + $("#noKunjungan").val(),
                dataType: "json",
                type: "POST",
                data: $("#obat_form").serialize(),
                success: (data) => {
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: "Obat berhasil ditambahkan!"
                    })
                },
                error: (jqXHR, textStatus) => {
                    Swal.fire({
                        icon: "error",
                        title: `${jqXHR.status} - ${textStatus}`,
                        text: "Obat gagal ditambahkan!"
                    })
                }
            })
        }
    </script>
</body>

</html>
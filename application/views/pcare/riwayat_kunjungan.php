<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obat - Pcare Testing Center</title>
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
        <h4>
            <strong>P-Care Bridging System - Abiyosoft | Riwayat Kunjungan</strong>
        </h4>
        <div class="table-responsive">
            <table class="table">
                <thead class="thead-info">
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">No. Kunjungan</th>
                        <th class="text-center">No. Tanggal Kunjungan</th>
                        <th class="text-center">Poli Tujuan</th>
                        <th class="text-center">Obat</th>
                        <th class="text-center">Tindakan</th>
                        <th class="text-center">Rujuk Lanjut</th>
                        <th class="text-center">Aksi</th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kunjungan as $key => $value): ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= $value->noKunjungan ?></td>
                            <td><?= $value->tglDaftar ?></td>
                            <td><?= $value->kdPoli ?></td>
                            <td><a href="<?= base_url("test/get_obat_kunjungan") . "/{$value->noKunjungan}" ?>" class="btn btn-sm btn-info">Lihat Obat</a></td>
                            <td><a href="<?= base_url("test/get_tindakan_kunjungan") . "/{$value->noKunjungan}" ?>" class="btn btn-sm btn-info">Lihat Tindakan</a></td>
                            <td><a href="<?= base_url("test/cetak_rujukan") . "/{$value->noKunjungan}" ?>" class="btn btn-sm btn-warning">Cetak Rujukan</a></td>
                            <td><a href="<?= base_url("api/pcare/delete_kunjungan") . "/{$value->id}" ?>" class="btn btn-sm btn-danger">Hapus Data Rujukan</a></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
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
</body>

</html>
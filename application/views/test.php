<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mecoba Javascript Promise</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js" integrity="sha512-n/4gHW3atM3QqRcbCn6ewmpxcLAHGaDjpEBu4xZd47N0W2oQ+6q7oc3PXstrJYXcbNU1OHdQ1T7pAP+gi5Yu8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
    <h1>Hello Mom!</h1>
    <script>
        var url = "<?= base_url() ?>", totalPage = 0

        $.ajax(url + '/piutang/transactions', {
            success: function (data) {
                totalPage = data.data
                console.log(totalPage)
            },
            data: {
                vendor: 'BPJS',
                fromdate: '2021-01-01',
                todate: '2022-08-08',
            },
            dataType: 'json'
        })
    </script>
</body>
</html>
window.history.forward();
  function noBack() { window.history.forward(); }
    
var UIIdleTimeout = function () {

    return {

        //main function to initiate the module
        init: function () {

            // cache a reference to the countdown element so we don't have to query the DOM for it on each ping.
            var $countdown;

            $('body').append('<div class="modal fade" id="idle-timeout-dialog" data-backdrop="static"><div class="modal-dialog modal-small"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">Waktu jeda aplikasi sudah berakhir.</h4></div><div class="modal-body"><p><i class="fa fa-warning"></i> Aplikasi akan terkunci <span id="idle-timeout-counter"></span> detik lagi.</p><p>Anda akan tetap menggunakan aplikasi ini ?</p></div><div class="modal-footer"><button id="idle-timeout-dialog-logout" type="button" class="btn btn-default">Tidak, Logout</button><button id="idle-timeout-dialog-keepalive" type="button" class="btn btn-primary" data-dismiss="modal">Ya, Tetap Kerja</button></div></div></div></div>');
                    
            // start the idle timer plugin
            $.idleTimeout('#idle-timeout-dialog', '.modal-content button:last', {
                idleAfter: 300, // 5 seconds
                timeout: 3000, //30 seconds to timeout
                pollingInterval: 300, // 5 seconds
                keepAliveURL: 'demo/idletimeout_keepalive.php',
                serverResponseEquals: 'OK',
                onTimeout: function(){
                    window.location = "login_lock.php";
                },
                onIdle: function(){
                    $('#idle-timeout-dialog').modal('show');
                    $countdown = $('#idle-timeout-counter');

                    $('#idle-timeout-dialog-keepalive').on('click', function () { 
                        $('#idle-timeout-dialog').modal('hide');
                    });

                    $('#idle-timeout-dialog-logout').on('click', function () { 
                        $('#idle-timeout-dialog').modal('hide');
                        $.idleTimeout.options.onTimeout.call(this);
                    });
                },
                onCountdown: function(counter){
                    $countdown.html(counter); // update the counter
                }
            });
            
        }

    };

}();

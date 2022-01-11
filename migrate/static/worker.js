/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
document.addEventListener("DOMContentLoaded", function () {
    // JQuery methods
    $.methods = {
        /**
         * Call main worker.php (backup, migrate, check, restore or update)
         * @param object data
         */
        work: function (data) {
            $.worker = $.ajax({
                type: "POST",
                url: "./worker.php",
                dataType: "json",
                async: true,
                data: data,
                success: function (data) {
                    $(".worker-content").html(data.text);
                    $.worker = null;
                    // run listener ine more time and stop repeating
                    clearInterval($.interval);
                    $.methods.listen(data);
                }
            });
        },
        /** Call main listener.php (read status every 5 seconds)
         * @param object data
         */
        listen: function (data) {
            $.listener = $.ajax({
                type: "POST",
                url: "./listener.php",
                dataType: "json",
                async: true,
                data: data,
                success: function (data) {
                    $(".listener-content").html(data.text);
                    $(".listener-content").html(data.table);
                    $.listener = null;
                }
            });
            return(data.status);
        },
    };

    // Data to send to worker process
    data = {
        filename: $("#file").val(),
        companies: companies,
    };

    // Start worker process
    var status = $.methods.work(data);

    // Start repeating listener process
    // Use clearInterval($.interval) to stop
    $.interval = setInterval(function () {
        // method to be executed;
        $.methods.listen(data);
    }, 5000);
    // run once
    $.methods.listen(data);
});
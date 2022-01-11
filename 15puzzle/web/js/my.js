$(document).ready(function () {

    function setPossible() {
        // highlite possible choices
        $("#" + freeRow + ".row").find("#" + (freeCol + 1) + ".col").addClass('possible');
        $("#" + freeRow + ".row").find("#" + (freeCol - 1) + ".col").addClass('possible');
        $("#" + (freeRow + 1) + ".row").find("#" + freeCol + ".col").addClass('possible');
        $("#" + (freeRow - 1) + ".row").find("#" + freeCol + ".col").addClass('possible');
    }

    setPossible();

    function fillBoard(state) {
        for (var i in state) {
            for (var j in state[i]) {
                $("#" + i + ".row").find("#" + j + ".col").html(state[i][j]).removeClass('possible');
            }
        }
    }

    $(".col").click(function () {
        if ($(this).hasClass('possible')) {
            var request = {
                pid: $(this).parent().parent().attr('id').replace('play-', ''),
                row: $(this).parent().attr('id'),
                col: $(this).attr('id')
            };
            $.postsResponse = $.ajax({

                type: "POST",
                url: "/click",
                dataType: "json",
                async: true,

                data: {
                    'request': request,
                },

                success: function (data) {
                    if (data.status === 'ok') {
                        // update data on page
                        $('.steps').html(data.data.steps);
                        fillBoard(data.data.state);
                        freeRow = data.data.free[0];
                        freeCol = data.data.free[1];
                        setPossible();
                        // you won
                        if (data.data.status == false) {
                            // lock board
                            $(".col").unbind('click') ;
                            // show message
                            $('#won').css('visibility', 'visible');
                        }
                    }
                    $.postsResponse = null;
                }
            });
        }
    })
})
$(function () {
    $('input').iCheck();
    $('input.all').on('ifChecked ifUnchecked', function(event) {
        if (event.type == 'ifChecked') {
            $('input.check').iCheck('check');
        } else {
            $('input.check').iCheck('uncheck');
        }
    });
    $('input.check').on('ifUnchecked', function(event) {
        $('input.all').iCheck('uncheck');
    });
});
function show_dlg_msg(title, msg)
{
    $('#dlg_title').html(title);
    $('#dlg_body').html(msg);
    $('#frmDlg').modal('show');
}

function logout()
{
    bootbox.confirm("Are you sure to logout ?", function(result) {
        if(result) {
            $.post(base_url+'api/auth/logout', {}, function(result){
                if (result.success) window.location.href = base_url + 'login'; 
                else {
                    show_dlg_msg('Exception', result.msg);
                    setTimeout(function(){ $('#frmDlg').modal('toggle'); }, 2000);
                }
            },'json');
        }
    }); 
}

function request_get(load_url, load_to)
{
    $.ajax({
        url: load_url,
        cache: false,
        method: 'GET',
        beforeSend: function(jqXHR, settings ) {
            NProgress.start();
            intervalProgress = setInterval(function() { NProgress.inc(); }, 1000);
        },        
        statusCode: {
            404: function(){ show_dlg_msg('Exception', 'Page Not Found !'); }
        },
        complete: function(jqXHR, textStatus ) {
            NProgress.done();
            clearInterval(intervalProgress);
        },
        error: function( jqXHR, textStatus, errorThrown ) {
            show_dlg_msg(textStatus, jqXHR.responseText);
        }
    })
    .done(function(data) {
        NProgress.done();
        $( "#"+load_to).html(data);
    });
}

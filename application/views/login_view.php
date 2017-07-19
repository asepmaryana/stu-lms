<?php $this->load->view('header_view'); ?>

<script>
    nextfield = "username"; // name of first field on page

    function onKeyPress(e) {
        var keycode;
        if (window.event) keycode = window.event.keyCode;
        else if (e) keycode = e.keyCode;
        else return true;

        if (keycode == 13) {  //enter key pressed
            if (nextfield == 'done'){ return true;  }//submit, we finished all fields
            else {//  we're not done yet, send focus to next field
                eval('document.yourform.' + nextfield + '.focus()');
                return false;
            }
        }
    }

    document.onkeypress = onKeyPress;
</script>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="alert alert-danger <?php if ($this->session->flashdata('error') == '') echo 'hide';; ?>">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>Error!</strong> <?php echo $this->session->flashdata('error'); ?>
            </div>

        </div>
    </div>

</div>

<h1>Lighting Monitoring Systems</h1>
<form class="form-horizontal" role="form" method="post" name="yourform" id="form_login">
    <div class="flip">
        <div class="content">
            <ul>
                <li>
                    <input type="text" name="username" id="username" placeholder="Username"  autofocus onFocus="nextfield ='password' "/>
                </li>
                <li>
                    <!--<input type="text" name="password" placeholder="Password"onFocus="nextfield='done'" />-->
                    <input type="password" name="password" id="password" placeholder="Password" autofocus onFocus="nextfield ='submit' " />
                </li>
                <!--  <li>
                     <input type="text" placeholder="Repeat password" />
                 </li> -->
            </ul>
        </div>
        <ul class="button">
            <li class="front">
                Login
            </li>
            <li class="back">
                <button class="btn btn-link btn-lg pull-xs-right btn-close">&times;</button>
                <!--  <button class="btn btn-primary btn-lg">Sign up</button> -->
                <button class="btn btn-primary btn-lg" type="button" name="submit" onFocus="nextfield='done'" onclick="login()">Login</button>
            </li>
        </ul>
    </div>
</form>

<div class="modal fade" id="frmDlg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">                    
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">                        
				<h4 class="modal-title" id="dlg_title"></h4>
			</div>
			<div class="modal-body" id="dlg_body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>                                        
			</div>
		</div>
	</div>
</div>

<link rel="stylesheet" href="<?php echo base_url('assets/css/login.css') ?>"/>
<script type="text/javascript">var BASE_URL = '<?php echo base_url(); ?>';</script>
<script src="<?php echo base_url();?>assets/js/app/app.core.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/app/app.login.js" type="text/javascript"></script>
<script>
    $('.button .front').click(function () {
        $(this).parents('.flip').toggleClass('flipped');
        if (document.location.pathname.indexOf('fullcpgrid') == -1) {
            $(this).parents('.flip').find('input:eq(0)').focus();
        }
        return false;
    });
    $('.btn-close').click(function () {
        $(this).parents('.flip').toggleClass('flipped');
        return false;
    });

    function demo() {
        /***
         Add your demo script here...
         ***/
        setTimeout(function () {
            $('.button .front').click();
        }, 2000);
    }
</script>
<?php $this->load->view('footer_view'); ?>
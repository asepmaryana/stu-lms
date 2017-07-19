<?php $this->load->view('v_Head'); ?>

<body id="app-layout">
<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#spark-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="#">
                CodeIgniter
            </a>
        </div>

        <div class="collapse navbar-collapse" id="spark-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                <li><a href="<?php echo site_url('welcome')?>">Welcome</a></li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                <li><a href="<?php echo site_url('login')?>">Login</a></li>
                <li><a href="<?php echo site_url('register')?>">Register</a></li>

            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="alert alert-danger <?php if($this->session->flashdata('error') =='') echo  'hide'; ;?>">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>Error!</strong> <?php echo $this->session->flashdata('error');?>
            </div>
            <div class="panel panel-default">

                <div class="panel-heading">Validation</div>
                <br/>
                <form class="form-horizontal" role="form" method="post" action="<?php echo site_url('license/validate')?>">

                    <div class="form-group ">
                        <label class="col-md-4 control-label">License Key</label>

                        <div class="col-md-6">
                            <textarea name="license" class="form-control"></textarea>

                        </div>
                    </div>





                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-btn fa-paper-plane"></i>Submit
                            </button>

                        </div>
                    </div>
                </form>
                <div class="panel-body"></div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('v_Foot'); ?>
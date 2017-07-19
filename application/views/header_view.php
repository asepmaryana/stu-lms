<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>LMS 2.0 - Beta</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-touch-fullscreen" content="yes"/>
    <meta name="author" content="Asep Maryana"/>

    <link href='http://fonts.googleapis.com/css?family=RobotoDraft:300,400,400italic,500,700' rel='stylesheet' type='text/css'/>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,400italic,600,700' rel='stylesheet' type='text/css'/>

    <!--[if lt IE 10]>
    <script src="<?php echo base_url(); ?>assets/js/media.match.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/placeholder.min.js"></script>
    <![endif]-->

    <link href="<?php echo base_url(); ?>assets/fonts/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet"/>        <!-- Font Awesome -->
    <link href="<?php echo base_url(); ?>assets/css/styles.css" type="text/css" rel="stylesheet"/>
    <link href="<?php echo base_url(); ?>assets/css/app.map.css" type="text/css" media="all" rel="stylesheet" />                        <!-- Core CSS with all styles -->
    <link href="<?php echo base_url(); ?>assets/plugins/jstree/dist/themes/avenger/style.min.css" type="text/css" rel="stylesheet"/>    <!-- jsTree -->
    <link href="<?php echo base_url(); ?>assets/plugins/codeprettifier/prettify.css" type="text/css" rel="stylesheet"/>                <!-- Code Prettifier -->
    <link href="<?php echo base_url(); ?>assets/plugins/iCheck/skins/minimal/blue.css" type="text/css" rel="stylesheet"/>              <!-- iCheck -->
    <link href="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.bootstrap.css" type="text/css" rel="stylesheet"/>
    <link href="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.fontAwesome.css" type="text/css" rel="stylesheet"/>
    <link href="<?php echo base_url(); ?>assets/plugins/iCheck/skins/minimal/blue.css" type="text/css" rel="stylesheet"/>
    <link href="<?php echo base_url(); ?>assets/plugins/iCheck/skins/minimal/_all.css" type="text/css" rel="stylesheet"/>                   <!-- Custom Checkboxes / iCheck -->
    <link href="<?php echo base_url(); ?>assets/plugins/iCheck/skins/flat/_all.css" type="text/css" rel="stylesheet"/>
    <link href="<?php echo base_url(); ?>assets/plugins/iCheck/skins/square/_all.css" type="text/css" rel="stylesheet"/>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries. Placeholdr.js enables the placeholder attribute -->
    <!--[if lt IE 9]>
    <link href="<?php echo base_url(); ?>assets/css/ie8.css" type="text/css" rel="stylesheet">
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.1.0/respond.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/charts-flot/excanvas.min.js"></script>
    <script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
    <script src="<?php echo base_url() ?>assets/js/jquery-1.10.2.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/jqueryui-1.9.2.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/sparklines/jquery.sparklines.min.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/codeprettifier/prettify.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/bootstrap-switch/bootstrap-switch.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/iCheck/icheck.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/enquire.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/jquery.form.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/bootbox/bootbox.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>assets/plugins/bootstrap-timepicker/bootstrap-timepicker.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>assets/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>assets/plugins/nanoScroller/js/jquery.nanoscroller.min.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/jquery-mousewheel/jquery.mousewheel.min.js"></script>
    <script src="<?php echo base_url();?>assets/plugins/amcharts/dist/amcharts/amcharts.js"></script>
    <script src="<?php echo base_url();?>assets/plugins/amcharts/dist/amcharts/serial.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/plugins/amcharts/dist/amcharts/gauge.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/plugins/amcharts/dist/amcharts/themes/dark.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/plugins/amcharts/dist/amcharts/themes/light.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/plugins/amcharts/dist/amcharts/plugins/export/export.js"></script>
    <script src="<?php echo base_url();?>assets/js/application.js"></script>
</head>

<body class="infobar-overlay sidebar-hideon-collpase sidebar-scroll" ng-app="pjuApp">

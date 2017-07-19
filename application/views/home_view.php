<?php $this->load->view('header_view'); ?>

<?php $this->load->view('header_info_view'); ?>

<?php $this->load->view('top_menu_view'); ?>

<link href="<?php echo base_url(); ?>assets/plugins/amcharts/amcharts/plugins/export/export.css" type="text/css" media="all" rel="stylesheet"/>
<link href="<?php echo base_url(); ?>assets/css/app/app.map.css" type="text/css" rel="stylesheet"/>

<div id="wrapper">
    <div id="layout-static">

        <!--LeftSideBar-->
        <?php $this->load->view('left_menu_view'); ?>
        <!--LeftSideBar-->

        <!--Content Page-->
        <div class="static-content-wrapper">
            <div class="static-content">
                <div class="page-content">
                    
                    <br /><br />
                    
                    <div ui-view></div>
                    
                </div> <!-- #page-content -->
            </div>
        </div>
        <!--Content Page-->
    </div>
</div>

<div class="modal fade modal-wide" id="frmConsenDlg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">                    
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="title_consen">PJU Summary</h4>
			</div>
			<div class="modal-body">
                <div class="row">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tableconsen">
                        <thead>
                            <tr>
                                <th>Lamp</th>
                                <th>Status</th>
                                <th>V Batt (V)</th>
                                <th>I Batt (A)</th>
                                <th>I Load (A)</th>
                                <th>Temp Ctrl <sup>o</sup>C</th>
                                <th>Temp Batt <sup>o</sup>C</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="frmSiteDlg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">                    
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title" id="dlg_title_site">Add Site</h4>
			</div>
			<div class="modal-body" id="dlg_body_site">
                <form class="form-horizontal" role="form" method="post" name="yourform" id="form_site">
                    <input type="hidden" name="id" id="id" value="" />
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Name </label>
                            <div class="col-md-5">
                                <input type="text" class="form-control" placeholder="Enter name" name="name" id="name"/>
                            </div>
                            <label class="col-md-2 control-label">Pole </label>
                            <div class="col-md-2">
                                <input type="text" class="form-control" placeholder="Pole number" name="pole" id="pole"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Address </label>
                            <div class="col-md-5">
                                <input type="text" class="form-control" placeholder="" name="address" id="address"/>
                            </div>
                            <label class="col-md-2 control-label">Channel </label>
                            <div class="col-md-2">
                                <input type="text" class="form-control" placeholder="Chanel" name="chanel" id="chanel"/>
                            </div>
                        </div>                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">Position </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" placeholder="Latitude" name="latitude" id="latitude"/>
                            </div>
                            <label class="col-md-1 control-label"> </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" placeholder="Longitude" name="longitude" id="longitude"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">As Master ? </label>
                            <div class="col-md-3">
                                <input type="radio" name="master" value="1" /> Yes 
                                <input type="radio" name="master" value="0" /> No
                            </div>
                            <label class="col-md-2 control-label">IMEI</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" placeholder="IMEI number" name="imei" id="imei"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Master References </label>
                            <div class="col-md-9">
                                <select name="consent_id" id="consent_id" class="form-control">
                                    <option value="">- Select -</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Network Group </label>
                            <div class="col-md-9">
                                <select name="subnet_id" id="subnet_id" class="form-control">
                                    <option value="">- Select -</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
			<div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="node_save()">Save</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade modal-medium" id="frmDataDlg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">                    
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="dlg_title_site_data">Site Data History</h4>
			</div>
			<div class="modal-body" id="dlg_body_site_data">
                <form class="form-horizontal" role="form" method="post" name="yourform" id="form_site_data">
                    <input type="hidden" name="id" id="id" value="" />
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Date Periode </label>
                            <div class="col-md-3">
                                <input type="text" class="form-control datetimepicker" placeholder="Start Date" name="from" id="from"/>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control datetimepicker" placeholder="End Date" name="to" id="to"/>
                            </div>
                            <div class="col-md-3">
                                <div class="btn-group">
                                    <button id="bgAction" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                    <ul class="dropdown-menu" aria-labelledby="bgAction">
                                        <li><a href="#" class="btn" onclick="node_datalog_show('tbl')"><i class="fa fa-table"></i> View Table</a></li>
                                        <li><a href="#" class="btn" onclick="node_datalog_show('xls')">Export Excel</a></li>
                                        <li><a href="#" class="btn" onclick="node_datalog_show('img')"><i class="fa fa-line-chart"></i> View Chart</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row" id="table_out">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tabledatalog">
                        <thead>
                            <tr>
                                <th rowspan="2" class="text-center">Date Time</th>
                                <th rowspan="2" class="text-center">Status</th>
                                <th colspan="2" class="text-center">Batt</th>
                                <th rowspan="2" class="text-center">I Load <br />(A)</th>
                                <th colspan="2" class="text-center">Temp <sup>o</sup>C</th>
                                <th colspan="8" class="text-center">Cell</th>
                                <th rowspan="2" class="text-center">SoC</th>
                                <th rowspan="2" class="text-center">Pack<br />Volt</th>
                                <th rowspan="2" class="text-center">BMS<br />Curr</th>
                            </tr>
                            <tr>
                                <th class="text-center">V</th>
                                <th class="text-center">I</th>
                                <th class="text-center">Ctrl</th>
                                <th class="text-center">Batt</th>
                                <th class="text-center">1</th>
                                <th class="text-center">2</th>
                                <th class="text-center">3</th>
                                <th class="text-center">4</th>
                                <th class="text-center">5</th>
                                <th class="text-center">6</th>
                                <th class="text-center">7</th>
                                <th class="text-center">8</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                
                <div class="row" id="chart_out" style="height: 500px;">
                    
                </div>
                
            </div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade modal-medium" id="frmAreaDlg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">                    
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Area</h4>
			</div>
			<div class="modal-body">
                <div class="row">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tablearea">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Parent</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
			<div class="modal-footer">
                <button type="button" class="btn btn-primary">New</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade modal-medium" id="frmCustDlg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">                    
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Customer</h4>
			</div>
			<div class="modal-body">
                <div class="row">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tablecust">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
			<div class="modal-footer">
                <button type="button" class="btn btn-primary">New</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>assets/js/angular/angular.js"></script>
<script src="<?php echo base_url(); ?>assets/js/angular-ui-router/release/angular-ui-router.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/angular-ng-table/ng-table.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/angular-route/angular-route.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/angular-animate/angular-animate.js"></script>
<script src="<?php echo base_url(); ?>assets/js/angular-resource/angular-resource.js"></script>
<script src="<?php echo base_url(); ?>assets/js/angular-loading-bar/build/loading-bar.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/angular-bootstrap/ui-bootstrap-tpls.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/underscore-min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/amcharts/dist/amcharts/amcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/js/amcharts/dist/amcharts/serial.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/amcharts/dist/amcharts/themes/dark.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/amcharts/dist/amcharts/themes/light.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/amcharts/dist/amcharts/plugins/export/export.js"></script>
<script src="<?php echo base_url(); ?>assets/js/amcharts-angular/dist/amChartsDirective.js"></script>
<script src="<?php echo base_url(); ?>assets/js/angular-recursion.js" type="text/javascript"></script>
<script type="text/javascript">var BASE_URL = '<?php echo base_url(); ?>';</script>
<script type="text/javascript">var ROLE_ID  = '<?php echo $this->session->userdata('roles_id'); ?>';</script>
<script type="text/javascript">var CUST_ID  = '<?php echo $this->session->userdata('customers_id'); ?>';</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBbl5r5Vr7-fzlvNqsIpCQiiF8Ojo738ww"></script>
<script src="<?php echo base_url(); ?>assets/js/app.context-menu.js" type="text/javascript"></script>    
<script src="<?php echo base_url(); ?>assets/js/app.js"></script>
<script src="<?php echo base_url(); ?>assets/js/app.directive.js"></script>
<script src="<?php echo base_url(); ?>assets/js/app.service.js"></script>
<script src="<?php echo base_url(); ?>assets/js/app.controller.js"></script>

<?php $this->load->view('footer_view');?>

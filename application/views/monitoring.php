<?php $this->load->view('template/v_Head'); ?>

<?php $this->load->view('template/_HeaderInfo'); ?>

<?php $this->load->view('template/_TopNavBar'); ?>

<div id="wrapper">
    <div id="layout-static">

        <!--LeftSideBar-->
        <?php $this->load->view('template/_LeftBarMenu'); ?>
        <!--LeftSideBar-->

        <!--Content Page-->
        <div class="static-content-wrapper">
            <div class="static-content">
                <div class="page-content">
                    <div class="page-heading hide">
                        <h1>Scroll Sidebar</h1>
                        <div class="options">
                            <div class="btn-toolbar">
                                <a href="#" class="btn btn-default"><i class="fa fa-fw fa-wrench"></i></a>
                            </div>
                        </div>
                    </div>
                    <ol class="breadcrumb hide">
                        <li><a href="<?php echo base_url(); ?>dashboard">Home</a></li>
                        <li class="active"><a href="<?php echo base_url(); ?>monitoring">Monitoring</a></li>
                    </ol>
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row" style="margin-top: 25px">
                                    <div class="col-xs-12">
                                        <div class="panel">
                                            <div class="panel-heading">Monitoring</div>
                                            <div class="panel-body">
                                                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tablemonitoring">
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="2">Lamp</th>
                                                            <th rowspan="2" class="text-center">Status</th>
                                                            <th rowspan="2" class="text-center">V Batt (V)</th>
                                                            <th rowspan="2" class="text-center">I Batt (A)</th>
                                                            <th rowspan="2" class="text-center">I Load (A)</th>
                                                            <th colspan="2" class="text-center">Temp <sup>o</sup>C</th>
                                                            <th colspan="8" class="text-center">Cell Volt</th>
                                                            <th rowspan="2" class="text-center">SoC</th>
                                                            <th rowspan="2" class="text-center">Pack<br />Volt</th>
                                                            <th rowspan="2" class="text-center">BMS<br />Curr</th>
                                                            <th rowspan="2" class="text-center">Last Updated</th>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-center">Ctrl</th>
                                                            <th class="text-center">Batt</th>
                                                            <th>1</th>
                                                            <th>2</th>
                                                            <th>3</th>
                                                            <th>4</th>
                                                            <th>5</th>
                                                            <th>6</th>
                                                            <th>7</th>
                                                            <th>8</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div> <!-- .container-fluid -->
                </div> <!-- #page-content -->
            </div>

            <?php $this->load->view('template/_Footer');?>

        </div>
        <!--Content Page-->


    </div>
</div>

<?php $this->load->view('template/_RightBarMenu');?>

<?php $this->load->view('template/v_Foot'); ?>

<script src="<?php echo base_url() ?>assets/js/app/app.core.js"></script>

<script type="text/javascript">
    function node_reload_all()
    {
        $.get(base_url+'api/node/all', {}, function(msg){
                var table = $('#tablemonitoring').DataTable();
                table.clear().draw();                            
                for(var i=0; i<msg.data.length; i++)
                {
                    status = 'OFF';
                    if(msg.data[i].status == '1' && parseFloat(msg.data[i].iload) > 1) status = 'ON';
                    else if(msg.data[i].status == '1' && parseFloat(msg.data[i].iload) < 1) status = 'STANDBY';
                    
                    table.row.add([
                        msg.data[i].subnet+ ' - '+ msg.data[i].name,
                        status,
                        msg.data[i].vbatt,
                        msg.data[i].ibatt,
                        msg.data[i].iload,
                        msg.data[i].temperature_ctrl,
                        msg.data[i].temperature_batt,
                        msg.data[i].cell_1_volt,
                        msg.data[i].cell_2_volt,
                        msg.data[i].cell_3_volt,
                        msg.data[i].cell_4_volt,
                        msg.data[i].cell_5_volt,
                        msg.data[i].cell_6_volt,
                        msg.data[i].cell_7_volt,
                        msg.data[i].cell_8_volt,
                        msg.data[i].soc,
                        msg.data[i].pack_volt,
                        msg.data[i].bms_curr,
                        msg.data[i].updated_at
                    ]).draw();
                }
        },'json');
    }
    
    $(document).ready(function(){ 
        //$('#tabledatalog').dataTable({"paging": false, "info": false});
        $('#tablemonitoring').dataTable();
        node_reload_all();
        setInterval(function(){ node_reload_all(); }, 30 * 1000);
    });
</script>
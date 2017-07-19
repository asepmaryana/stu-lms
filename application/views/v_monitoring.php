<?php $this->load->view('template/v_Head'); ?>
<link href="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.bootstrap.css" type="text/css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.fontAwesome.css" type="text/css" rel="stylesheet">

<?php $this->load->view('template/_HeaderInfo'); ?>

<?php $this->load->view('template/_TopNavBar'); ?>

<div id="wrapper">
    <div id="layout-static">

        <!--LeftSideBar-->
        <?php $this->load->view('template/_LeftBarMenu'); ?>
        <!--LeftSideBar-->

        <div class="static-content-wrapper">
            <div class="static-content">
                <div class="page-content">


                    <div class="container-fluid">



                        <div class="row" style="margin-top: 25px">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h2>Monitoring Data</h2>
                                        <div class="panel-ctrls">
                                        </div>
                                    </div>
                                    <div class="panel-body panel-no-padding">
                                        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Site</th>
                                                <th>Date/Time</th>
                                                <th>pVoltage</th>
                                                <th>VBatt</th>
                                                <th>IBatt</th>
                                                <th>ILoad</th>
                                                <th>T Controll</th>
                                                <th>T Batt</th>
                                                <th>Status</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $x =1; foreach ($polling as $dt): ?>
                                                <tr>
                                                    <td><?php echo $x++?></td>
                                                    <td><?php echo $dt->site_id?></td>
                                                    <td><?php echo $dt->dtime ;?></td>
                                                    <td><?php echo $dt->pvoltage; ?>V</td>
                                                    <td><?php echo $dt->vbatt; ?>V</td>
                                                    <td><?php echo $dt->ibatt; ?>A</td>
                                                    <td><?php echo $dt->iload; ?>A</td>
                                                    <td><?php echo $dt->temperature_ctrl; ?> &deg;C </td>
                                                    <td><?php echo $dt->temperature_batt ?>&deg;C </td>
                                                    <td><?php echo ($dt->status == 1)?'ON':'OFF' ; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                        <div class="panel-footer"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div> <!-- .container-fluid -->
                </div> <!-- #page-content -->
            </div>
            <?php $this->load->view('template/_Footer');?>
        </div>
    </div>
</div>


<<?php $this->load->view('template/_RightBarMenu');?>

<?php $this->load->view('template/v_Foot'); ?>

<script src="<?php echo base_url(); ?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url(); ?>assets/demo/demo-datatables.js"></script>

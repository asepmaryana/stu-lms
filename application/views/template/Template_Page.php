<?php $this->load->view('template/v_Head'); ?>

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

                                        <!--start content here-->

                                        <!--/start content here-->

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

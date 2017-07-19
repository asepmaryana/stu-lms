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
                        <li class="active"><a href="<?php echo base_url(); ?>customer">Customer</a></li>
                    </ol>
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row" style="margin-top: 25px">
                                    <div class="col-xs-12">
                                        <div class="panel">
                                            <div class="panel-heading">Customer</div>
                                            <div class="panel-body">
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
    function cust_reload_all()
    {
        $.get(base_url+'api/customer/all', {}, function(msg){
                var table = $('#tablecust').DataTable();
                table.clear().draw();                            
                for(var i=0; i<msg.data.length; i++)
                {
                    table.row.add([
                        msg.data[i].name,
                        status
                    ]).draw();
                }
        },'json');
    }
    
    $(document).ready(function(){ 
        //$('#tabledatalog').dataTable({"paging": false, "info": false});
        $('#tablecust').dataTable();
        cust_reload_all();
    });
</script>
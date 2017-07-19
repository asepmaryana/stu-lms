
<script src="<?php echo base_url() ?>assets/js/jquery-1.10.2.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/jqueryui-1.9.2.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script>

<!--<script src="--><?php //echo base_url() ?><!--assets/plugins/easypiechart/jquery.easypiechart.js"></script>-->
<script src="<?php echo base_url() ?>assets/plugins/sparklines/jquery.sparklines.min.js"></script>
<!--<script src="--><?php //echo base_url() ?><!--assets/plugins/jstree/dist/jstree.min.js"></script>-->

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


<!--<script src="--><?php //echo base_url() ?><!--assets/plugins/simpleWeather/jquery.simpleWeather.min.js"></script> -->

<script src="<?php echo base_url() ?>assets/plugins/nanoScroller/js/jquery.nanoscroller.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/jquery-mousewheel/jquery.mousewheel.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/application.js"></script>
<script src="<?php echo base_url() ?>assets/demo/demo.js"></script>
<script src="<?php echo base_url() ?>assets/demo/demo-switcher.js"></script>

<!-- Load page level scripts-->

<script src="<?php echo base_url() ?>assets/plugins/jstree/dist/jstree.min.js"></script>
<!-- End loading page level scripts-->

<script type="text/javascript">
    $('#tree-default')
        .on("changed.jstree", function (e, data) {
			if(data.selected.length) {
                idx = data.instance.get_node(data.selected[0]).id;
                //lbl = data.instance.get_node(data.selected[0]).text;
                if(idx.indexOf('_') > -1) {
                    ids = idx.split('_');
                    subnet_id = ids[0];
                    node_id = ids[1];
                    //alert('The selected node_id is: ' + node_id);
                    //node_master_show(node_id);
                }
			}
		})
        .jstree({
        'core': {
            'data': {
                'url' : base_url+'api/subnet/tree',
                'dataType' : 'json',
                'data' : function (node) {
                    return { 'id' : node.id };
                }
            },
            'check_callback': true
        },
        'types': {
            'subnet': {
                'icon': 'fa fa-folder'
            },
            'node': {
                'icon': 'fa fa-folder'
            }
        },
        'plugins': [
            "contextmenu", "dnd",
            "state", "types", "wholerow"
        ]
    });

    $('#tree-default').on("select_node.jstree", function (e, data) {
        //var href = data.node.a_attr.href;
        //document.location.href = href;
        
    });
</script>

</body>

</html>
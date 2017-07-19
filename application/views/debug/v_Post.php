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

                        <li><a href="index.html">Home</a></li>
                        <li><a href="#">Layout</a></li>
                        <li class="active"><a href="layout-scroll-sidebar.html">Scroll Sidebar</a></li>

                    </ol>
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row" style="margin-top: 25px">
                                    <div class="col-xs-12">
                                        <div class="panel">
                                            <div class="panel-body" id="basic-map" style="height:500px;">
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

<script    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBbl5r5Vr7-fzlvNqsIpCQiiF8Ojo738ww&callback=initMap"></script>
<script>
    /* function initialize() {
     var mapProp = {
     center:new google.maps.LatLng(51.508742,-0.120850),
     zoom:5,
     mapTypeId:google.maps.MapTypeId.ROADMAP
     };
     var map=new google.maps.Map(document.getElementById("basic-map"),mapProp);
     }
     google.maps.event.addDomListener(window, 'load', initialize);*/
</script>
<script>
    /*var locations = [
        ['Bandung: MSTR1', -6.921776, 107.61121, 1],
        ['Bandung: MSTR2', -6.921776, 107.61125, 2],
        ['Bandung: MSTR3', -6.921776, 107.61129, 3],
        //['Jakarta', -6.174444, 106.827102, 5],
        //['Cronulla Beach', -34.028249, 151.157507, 3],
        //['Manly Beach', -33.80010128657071, 151.28747820854187, 2],
        //['Maroubra Beach', -33.950198, 151.259302, 1]
    ];

    var map = new google.maps.Map(document.getElementById('basic-map'), {
        zoom: 17,
        center: new google.maps.LatLng(-6.921776,107.611279),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) {
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map
        });

        google.maps.event.addListener(marker, 'click', (function (marker, i) {
            return function () {
                infowindow.setContent(locations[i][0]);
                infowindow.open(map, marker);
            }
        })(marker, i));


    }*/

</script>
<script>
    function initMap() {
        //var myLatLng = {lat: -25.363, lng: 131.044};
        var myLatLng = {lat: -6.921776,lng: 107.61121};

        var map = new google.maps.Map(document.getElementById('basic-map'), {
            zoom: 4,
            center: myLatLng
        });

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: 'Hello World!'
        });
    }

</script>


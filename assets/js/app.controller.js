'use strict';
/**
 * @ngdoc overview
 * @name cdcApp
 * @description
 * # cdcApp
 *
 * Controller module of the application.
 */
 angular.module('pjuApp')
    .controller('TopMenuController', ['$scope', '$modal', '$http', function($scope, $modal, $http){                
        
        $scope.userinfo = {};
        
        $http.get(BASE_URL+'api/user/info').success(function(data){
            $scope.userinfo = data;
        });
        
        $scope.password = {};
        
        $scope.openpwd  = function(o, s) {
            /*
            alert('open pwd');
            var modalInstance = $modal.open({
                templateUrl: BASE_URL+'assets/pages/setPassword.html',
                controller: 'SetPasswordController',
                size: s,
                resolve: {
                    item: function() {
                        return o;
                    }
                }
            });
            
            modalInstance.result.then(function(selectedObject) {
                //$scope.reloadPage();
            });
            */
        }
        
        $scope.logout   = function() {
            bootbox.confirm("Are you sure to logout ?", function(result) {
                if(result) {
                    $http.get(BASE_URL+'api/auth/logout').success(function(data){
                        window.location.href = BASE_URL + 'login';
                    });
                }
            });
        }
    }])
    .controller('SetPasswordController', function($scope, $modalInstance, $http, item){

        $scope.cancel = function () {
            $modalInstance.dismiss('Close');
        };
        
        $scope.title = 'Change Password';
        $scope.buttonText = 'Save';
        
        var original = item;
        $scope.isClean = function() {
            return angular.equals(original, $scope.password);
        }
        
        $scope.save = function (o) {
            $http.post(BASE_URL+'api/account/password', o).then(function (result) {
                var x = angular.copy(o);
                $modalInstance.close(x);
            });
        };
    })
    .controller('LeftMenuController', function($scope, $http, $interval, $location){
        $scope.open     = function(id) {
            $location.path('/node/view/'+id);
        }
    })
    .controller('MapController', function($rootScope, $interval, $scope, $http, $location, $timeout) {
        if (angular.isDefined($rootScope.alarmTimer)) $interval.cancel($rootScope.alarmTimer);
        if (angular.isDefined($rootScope.nodeTimer)) $interval.cancel($rootScope.nodeTimer);
        
        $scope.map;
        $scope.markers = [];
        $scope.infoWindow = new google.maps.InfoWindow();
        $scope.lat;
        $scope.lng;          
        $scope.contextMenuOptions={};
        $scope.contextMenuOptions.classNames={menu:'context_menu', menuSeparator:'context_menu_separator'};                
        $scope.menuItems=[];
        if(ROLE_ID == '1' || ROLE_ID == '2')
        {
            $scope.menuItems.push({className:'context_menu_item', eventName:'node_add', label:'New Site'});
            $scope.menuItems.push({className:'context_menu_item', eventName:'node_clear', label:'Remove All'});
            $scope.menuItems.push({});
        }
        $scope.menuItems.push({className:'context_menu_item', eventName:'zoom_in', label:'Zoom In'});
        $scope.menuItems.push({className:'context_menu_item', eventName:'zoom_out', label:'Zoom Out'});
        $scope.menuItems.push({className:'context_menu_item', eventName:'center_map', label:'Center Map Here'});
        $scope.contextMenuOptions.menuItems = $scope.menuItems;
        
        $scope.contextMenu;
        
        $scope.nodeLoad= function(url) {
            $http.get(url).success(function(response){
				var data = response.data;
				//alert(data.length);
                for(var i=0;i<data.length;i++)
                {
                    var site = data[i];
                    var tanda= {};
                    var node = {};
                    var point = new google.maps.LatLng(parseFloat(site.latitude), parseFloat(site.longitude));
                    
					// off = grey, on = green
					if(site.status == '1' && parseFloat(site.iload) > 1) tanda = new google.maps.Marker({position: point, map: $scope.map, draggable: true, icon: BASE_URL+'assets/img/lamp_green.png' });
					else if(site.status == '1' && parseFloat(site.iload) < 1) tanda = new google.maps.Marker({position: point, map: $scope.map, draggable: true, icon: BASE_URL+'assets/img/lamp_yellow.png' });
					else tanda = new google.maps.Marker({position: point, map: $scope.map, draggable: true, icon: BASE_URL+'assets/img/lamp_grey.png' });
                    
                    // remove marker previous
                    if( $scope.markers[site.id] != null ) {
                        node = $scope.markers[site.id];
                        node.setMap(null);
                    }

                    // register marker to array markers
                    $scope.markers[site.id] = tanda;
                    $scope.nodeCreate(tanda, site);
                }
            });
        }
        
        $scope.nodeCreate   = function(tanda, site)
        {
            google.maps.event.addListener(tanda, 'mouseover', function(event) {
				
				var label = '<table width="100%">';
				label   += '<tr><td colspan="2"><strong>'+site.subnet+' - '+site.name+'</strong></td></tr>';
				
				if(site.status == '1' && parseFloat(site.iload) > 1) label   += '<tr><td>Status</td> <td>: ON</td></tr>';
				else if(site.status == '1' && parseFloat(site.iload) < 1) label   += '<tr><td>Status</td> <td>: STANDBY</td></tr>';
				else label   += '<tr><td>Status</td> <td>: OFF</td></tr>';
				
				label   += '<tr><td>VBatt</td> <td>: ' + site.vbatt + ' V</td></tr>';
				label   += '<tr><td>IBatt</td> <td>: ' + site.ibatt + ' A</td></tr>';
				label   += '<tr><td>ILoad</td> <td>: ' + site.iload + ' A</td></tr>';
				label   += '<tr><td>Temp Ctrl</td> <td>: ' + site.temperature_ctrl + ' <sup>o</sup>C</td></tr>';
				label   += '<tr><td>Temp Batt</td> <td>: ' + site.temperature_batt + ' <sup>o</sup>C</td></tr>';
                /*
				if(site.protocol == 'A1')
				{
					label   += '<tr><td>Pack</td> <td>: ' + site.pack_volt + '  V</td></tr>';
					label   += '<tr><td>Cell 1</td> <td>: ' + site.cell_1_volt + '  V</td></tr>';
					label   += '<tr><td>Cell 2</td> <td>: ' + site.cell_2_volt + '  V</td></tr>';
					label   += '<tr><td>Cell 3</td> <td>: ' + site.cell_3_volt + '  V</td></tr>';
					label   += '<tr><td>Cell 4</td> <td>: ' + site.cell_4_volt + '  V</td></tr>';
					label   += '<tr><td>Cell 5</td> <td>: ' + site.cell_5_volt + '  V</td></tr>';
					label   += '<tr><td>Cell 6</td> <td>: ' + site.cell_6_volt + '  V</td></tr>';
					label   += '<tr><td>Cell 7</td> <td>: ' + site.cell_7_volt + '  V</td></tr>';
					label   += '<tr><td>Cell 8</td> <td>: ' + site.cell_8_volt + '  V</td></tr>';
					label   += '<tr><td>BMS Curr</td> <td>: ' + site.bms_curr + ' A</td></tr>';
					label   += '<tr><td>SOC</td> <td>: ' + site.soc + ' %</td></tr>';
					
					var scode = parseInt(site.bms_status,10).toString(2);
					var prefix= '';
					for(var i=0; i<8-scode.length; i++) prefix += '0';
					scode = prefix+scode;
					
					var bms_status = '';
					
					if(scode.substr(7,1) == '1') bms_status += 'Mosfet Charge Status ON <br/>';
					//else bms_status += 'Mosfet Charge Status OFF <br/>';
					if(scode.substr(6,1) == '1') bms_status += 'Mosfet Discharge Status ON <br/>';
					//else bms_status += 'Mosfet Discharge Status OFF <br/>';
					if(scode.substr(5,1) == '1') bms_status += 'Charge Off Over Cell <br/>';
					if(scode.substr(4,1) == '1') bms_status += 'Charge Off Over Pack <br/>';
					if(scode.substr(3,1) == '1') bms_status += 'Charge Off Soc Full <br/>';
					if(scode.substr(2,1) == '1') bms_status += 'Discharge Off Under Cell <br/>';
					if(scode.substr(1,1) == '1') bms_status += 'Discharge Off Under Pack <br/>';
					
					label   += '<tr><td>BMS Status</td> <td>: '+bms_status+' </td></tr>';
				}
				label   += '<tr><td>Latitude</td> <td>: ' + site.latitude + '  </td></tr>';
				label   += '<tr><td>Longitude</td> <td>: ' + site.longitude + ' </td></tr>';
                */
				label   += '<tr><td>Updated</td> <td>: ' + site.updated_at + ' WIB</td></tr>';
				label   += '</table>';
				
                $scope.infoWindow.setContent(label);
                $scope.infoWindow.open($scope.map, tanda);
            });
            google.maps.event.addListener(tanda, 'mouseout', function(event) { $scope.infoWindow.close(); });
            google.maps.event.addListener(tanda, 'click', function(event){ 
                //$scope.$apply(function(){ $location.path('/node/view/'+site.id); });
                $scope.infoWindow.close();
                $scope.contextMenu[site.id].show(event.latLng);
            });
            
            $scope.menuItems[site.id] = [];
            if(ROLE_ID == '1' || ROLE_ID == '2')
            {
                if(site.status == '0') $scope.menuItems[site.id].push({className:'context_menu_item', eventName:'node_on', label:'Turn ON'});
                else $scope.menuItems[site.id].push({className:'context_menu_item', eventName:'node_off', label:'Turn OFF'});
                $scope.menuItems[site.id].push({});
            }
            $scope.menuItems[site.id].push({className:'context_menu_item', eventName:'node_view', label:'View '+site.name});
            $scope.contextMenuOptions[site.id]={};
            $scope.contextMenuOptions[site.id].classNames={menu:'context_menu', menuSeparator:'context_menu_separator'};
            $scope.contextMenuOptions[site.id].menuItems = $scope.menuItems[site.id];
            $scope.contextMenu[site.id] = new ContextMenu($scope.map, $scope.contextMenuOptions[site.id]);
                
            google.maps.event.addListener(tanda, 'rightclick', function(event) {
                $scope.infoWindow.close();                
                //console.log("Lat=" + event.latLng.lat() + "; Lng=" + event.latLng.lng());
                $scope.contextMenu[site.id].show(event.latLng);
            });
            
            google.maps.event.addListener($scope.contextMenu[site.id], 'menu_item_selected', function(latLng, eventName){
                switch(eventName) {
                    case 'node_on':
                        $scope.nodeTurn(site, 'ON');
                        break;
                    case 'node_off':
                        $scope.nodeTurn(site, 'OFF');
                        break;
                    case 'node_view':
                        $location.path('/node/view/'+site.id);
                        break;
                }
            });
            
            google.maps.event.addListener(tanda, 'dragend', function(event){ 
                $.post(BASE_URL+'api/site/latlng', {id:site.id, lat:event.latLng.lat(), lng:event.latLng.lng()}, function(result){
                    //console.log('move to : '+ event.latLng.lat()+ " : " +event.latLng.lng() + " --> " +result.success);
                },'json');
            });
        }
        
        $scope.nodeTurn = function(site, cmd) {
            bootbox.confirm("Do you want to turn "+cmd+" "+site.name+" ?", function(res) {
                if(res) {
                    $.post(BASE_URL+'api/node/cmd', {site_id:site.id, command:cmd}, function(result){
                        bootbox.alert({
                            message: result.msg,
                            timeOut: 3000
                        });
                    },'json');
                }
            });
        }
        
        $scope.site = null;
        
        $scope.nodeAdd = function() {
            $scope.site = null;
            $('#dlg_title_site').html('Add New Site');
            $("input[name=master]:radio").click(function() {
                if($(this).attr("value")=="1") {
                    $("#imei").attr("disabled",false);
                }
                if($(this).attr("value")=="0") {
                    $("#imei").attr("disabled",true);
                }
            });
            
            $http.get(BASE_URL+'api/node/master').success(function(response){
                if(response.success) $scope.masters = response.data;
            });
            
            $http.get(BASE_URL+'api/subnet/all').success(function(response){
                if(response.success) $scope.subnets = response.data;
            });
            
            $scope.site = {latitude: $scope.lat, longitude: $scope.lng};
            $('#frmSiteDlg').modal('show');
        }
        
        $scope.nodeSave = function(s) {
            $http.post(BASE_URL+'api/node/save', s)
            .success(function (data, status, headers, config) {
                //bootbox.alert(data.msg);
                if(data.success) {
                    var id = parseInt(data.id);
                    var point = new google.maps.LatLng(parseFloat(s.latitude), parseFloat(s.longitude));
                    var tanda = new google.maps.Marker({position: point, map: $scope.map, draggable: true, icon: BASE_URL+'assets/img/lamp_grey.png' });
                    $scope.markers[id] = tanda;
                    $('#frmSiteDlg').modal('hide');
                }
                else bootbox.alert(data.msg);
            });
        }
        
        $scope.nodeClear = function() {
            bootbox.confirm("Do you want to remove all site ?", function(res) {
                if(res) {
                    $.post(BASE_URL+'api/node/clear', {}, function(result){
                        bootbox.alert(result.msg);
                    },'json');
                }
            });
        }
        
        $timeout(function(){
            var mapDiv  = document.getElementById('map');
            var mapOptions  = {
				center: new google.maps.LatLng(-6.44203733876653, 106.951118409633),
				zoom: 9,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            $scope.map = new google.maps.Map(mapDiv, mapOptions);
            $scope.contextMenu = new ContextMenu($scope.map, $scope.contextMenuOptions);
            google.maps.event.addListener($scope.map, "click", function(event){ $('.contextmenu').remove(); });
            google.maps.event.addListener($scope.map, "rightclick", function(event){
                $scope.lat = event.latLng.lat();
                $scope.lng = event.latLng.lng();
                //console.log("Lat=" + lat + "; Lng=" + lng);                
                $scope.contextMenu.show(event.latLng);
            });
            google.maps.event.addListener($scope.contextMenu, 'menu_item_selected', function(latLng, eventName){
                switch(eventName) {
                    case 'node_add':
                        $scope.nodeAdd();
                        break;
                    case 'node_clear':
                        $scope.nodeClear();
                        break;
                    case 'zoom_in':
                        $scope.map.setZoom($scope.map.getZoom()+1);
                        break;
        			case 'zoom_out':
                        $scope.map.setZoom($scope.map.getZoom()-1);
                        break;
        			case 'center_map':
                        $scope.map.panTo(latLng);
                        break;
                }
            });
            
            $scope.nodeLoad(BASE_URL+'api/node/all');
            
            $rootScope.Timer = $interval(function () {
                $scope.nodeLoad(BASE_URL+'api/node/all');
            }, 15*1000);
            
        },100);
    })
    .controller('SummariesController', function($rootScope, $interval, $scope, $http, $location, $timeout){
        if (angular.isDefined($rootScope.Timer)) $interval.cancel($rootScope.Timer);
        if (angular.isDefined($rootScope.alarmTimer)) $interval.cancel($rootScope.alarmTimer);
        if (angular.isDefined($rootScope.nodeTimer)) $interval.cancel($rootScope.nodeTimer);
        $scope.statistics   = {};
        
        $http.get(BASE_URL+'api/node/statistic').success(function(response){
            if(response.success) $scope.statistics = response.data;
        });
        
    })
    .controller('SurveillanceController', function($rootScope, $interval, $scope, $http, $location, $timeout){
        if (angular.isDefined($rootScope.Timer)) $interval.cancel($rootScope.Timer);
        if (angular.isDefined($rootScope.nodeTimer)) $interval.cancel($rootScope.nodeTimer);
        
        $("#startDate").datepicker({todayHighlight: true, format:'yyyy-mm-dd'});
        $("#stopDate").datepicker({todayHighlight: true, format:'yyyy-mm-dd'});
        $("#startDate").val(new Date().toJSON().slice(0,10));
        $("#stopDate").val(new Date().toJSON().slice(0,10));
        
        $scope.regions          = [];
        $scope.areas            = [];
        $scope.sites            = [];
        $scope.alarmLists       = [];        
        $scope.selectedRegion   = [];        
        $scope.selectedArea     = [];
        $scope.selectedSite     = [];        
        $scope.selectedAlarm    = [];
        $scope.actives          = {};
        $scope.actives.pages    = [];
        $scope.itemPerPages     = [10, 20, 30];
        $scope.itemPerPage      = 10;
        
        $http.get(BASE_URL+'api/subnet/region').success(function(response){
            if(response.success) $scope.regions = response.data;
        });
        
        $http.get(BASE_URL+'api/alarmList/all').success(function(response){
            if(response.success) $scope.alarmLists = response.data;
        });
        
        $scope.updateSelectedRegion = function($event, id){
            var checkbox = $event.target;
            if(checkbox.checked  && $scope.selectedRegion.indexOf(id) < 0){
                $scope.selectedRegion.push(id);
            } else {
                $scope.selectedRegion.splice($scope.selectedRegion.indexOf(id), 1);                
            }
            var region_id = '_';
            for(var i=0; i<$scope.selectedRegion.length; i++) {
                region_id += $scope.selectedRegion[i] + '_';                
            }
            if(region_id.length > 1) {
                region_id = region_id.substring(1, region_id.length - 1);
                $http.get(BASE_URL+'api/subnet/area/'+region_id).success(function(response){
                    if(response.success) $scope.areas = response.data;
                });
            }
            else {
                $scope.areas = [];
                $scope.sites = [];
            }          
        }
        
        $scope.updateSelectedArea = function($event, id){
            var checkbox = $event.target;
            if(checkbox.checked  && $scope.selectedArea.indexOf(id) < 0){
                $scope.selectedArea.push(id);
            } else {
                $scope.selectedArea.splice($scope.selectedArea.indexOf(id), 1);                
            }
            var area_id = '_';
            for(var i=0; i<$scope.selectedArea.length; i++) {
                area_id += $scope.selectedArea[i] + '_';                
            }
            if(area_id.length > 1) {
                area_id = area_id.substring(1, area_id.length - 1);
                $http.get(BASE_URL+'api/node/site/'+area_id).success(function(response){
                    if(response.success) $scope.sites = response.data;
                });
            }
            else {
                $scope.sites = [];
            }          
        }
        
        $scope.updateSelectedSite = function($event, id){
            var checkbox = $event.target;
            if(checkbox.checked  && $scope.selectedSite.indexOf(id) < 0){
                $scope.selectedSite.push(id);
            } else {
                $scope.selectedSite.splice($scope.selectedSite.indexOf(id), 1);                
            }
            var node_id = '_';
            for(var i=0; i<$scope.selectedSite.length; i++) {
                node_id += $scope.selectedSite[i] + '_';                
            }
            if(node_id.length > 1) node_id = node_id.substring(1, node_id.length - 1);
            else $scope.nodes = [];     
        }
        
        $scope.updateSelectedAlarm = function($event, id){
            var checkbox = $event.target;
            if(checkbox.checked  && $scope.selectedAlarm.indexOf(id) < 0){
                $scope.selectedAlarm.push(id);
            } else {
                $scope.selectedAlarm.splice($scope.selectedAlarm.indexOf(id), 1);                
            }
        }
        
        $scope.buildAlarmUrl    = function() {
            var from    = $("#startDate").val();
            var to      = $("#stopDate").val();
            var alarm_id  = '_';
            for(var i=0; i<$scope.selectedAlarm.length; i++) alarm_id += $scope.selectedAlarm[i]+'_';
            if(alarm_id.length > 1) alarm_id = alarm_id.substring(1, alarm_id.length - 1);
            
            var node_id = '_';
            for(var i=0; i<$scope.selectedSite.length; i++) node_id += $scope.selectedSite[i]+'_';
            if(node_id.length > 1) node_id = node_id.substring(1, node_id.length - 1);
            
            var area_id = '_';
            for(var i=0; i<$scope.selectedArea.length; i++) area_id += $scope.selectedArea[i]+'_';
            if(area_id.length > 1) area_id = area_id.substring(1, area_id.length - 1);
            
            var region_id = '_';
            for(var i=0; i<$scope.selectedRegion.length; i++) region_id += $scope.selectedRegion[i]+'_';
            if(region_id.length > 1) region_id = region_id.substring(1, region_id.length - 1);
            
            if(node_id != '_') return BASE_URL+'api/alarm/fetch/site/'+node_id+'/'+alarm_id+'/'+from+'/'+to;
            else if(area_id != '_') return BASE_URL+'api/alarm/fetch/area/'+area_id+'/'+alarm_id+'/'+from+'/'+to;
            else if(region_id != '_') return BASE_URL+'api/alarm/fetch/region/'+region_id+'/'+alarm_id+'/'+from+'/'+to;
            else return BASE_URL+'api/alarm/fetch/all/_/'+alarm_id+'/'+from+'/'+to;
        }

        $scope.viewAlarm    = function(doc) {
            if(doc == 'xls') window.open($scope.buildAlarmUrl()+'/1/100/xls');
            else $scope.reloadAlamPage();
            //bootbox.alert($scope.buildAlarmUrl());            
        }
        
        $scope.reloadAlamPage = function(page){
            if(!page || page < 1) {
                page = 1;
            }
            $http.get($scope.buildAlarmUrl()+'/'+page+'/'+$scope.itemPerPage).success(function(response){
                $scope.actives = response;
                $scope.actives.pages = [];
                for(var i=0; i<$scope.actives.totalPage; i++) $scope.actives.pages[i] = i+1;
            });
        }
        
        $scope.reloadAlamPage();
        
        $rootScope.alarmTimer = $interval(function () {
            $scope.reloadAlamPage();
        }, 15*1000);
        
    })
    .controller('DatalogController', function($rootScope, $scope, $filter, $interval, $http, $timeout, $q){
        if (angular.isDefined($rootScope.Timer)) $interval.cancel($rootScope.Timer);
        if (angular.isDefined($rootScope.alarmTimer)) $interval.cancel($rootScope.alarmTimer);
        if (angular.isDefined($rootScope.nodeTimer)) $interval.cancel($rootScope.nodeTimer);
        
        $("#startDate").datepicker({todayHighlight: true, format:'yyyy-mm-dd'});
        $("#stopDate").datepicker({todayHighlight: true, format:'yyyy-mm-dd'});
        $("#startDate").val(new Date().toJSON().slice(0,10));
        $("#stopDate").val(new Date().toJSON().slice(0,10));
        
        $scope.regions          = [];
        $scope.areas            = [];
        $scope.sites            = [];
        $scope.selectedRegion   = [];        
        $scope.selectedArea     = [];
        $scope.selectedSite     = [];
        $scope.datalogs         = [];
        $scope.site             = {};
        $scope.mode             = 'table';
        
        $http.get(BASE_URL+'api/subnet/region').success(function(response){
            if(response.success) $scope.regions = response.data;
        });
        
        $scope.updateSelectedRegion = function($event, id){
            var checkbox = $event.target;
            if(checkbox.checked  && $scope.selectedRegion.indexOf(id) < 0){
                $scope.selectedRegion.push(id);
            } else {
                $scope.selectedRegion.splice($scope.selectedRegion.indexOf(id), 1);                
            }
            var region_id = '_';
            for(var i=0; i<$scope.selectedRegion.length; i++) {
                region_id += $scope.selectedRegion[i] + '_';                
            }
            if(region_id.length > 1) {
                region_id = region_id.substring(1, region_id.length - 1);
                $http.get(BASE_URL+'api/subnet/area/'+region_id).success(function(response){
                    if(response.success) $scope.areas = response.data;
                });
            }
            else {
                $scope.areas = [];
                $scope.sites = [];
            }
            $scope.selectedArea     = [];
            $scope.selectedSite     = [];
        }
        
        $scope.updateSelectedArea = function($event, id){
            var checkbox = $event.target;
            if(checkbox.checked  && $scope.selectedArea.indexOf(id) < 0){
                $scope.selectedArea.push(id);
            } else {
                $scope.selectedArea.splice($scope.selectedArea.indexOf(id), 1);                
            }
            var area_id = '_';
            for(var i=0; i<$scope.selectedArea.length; i++) {
                area_id += $scope.selectedArea[i] + '_';                
            }
            if(area_id.length > 1) {
                area_id = area_id.substring(1, area_id.length - 1);
                $http.get(BASE_URL+'api/node/site/'+area_id).success(function(response){
                    if(response.success) $scope.sites = response.data;
                });
            }
            else {
                $scope.sites = [];
            }
            $scope.selectedSite     = [];
        }
        
        $scope.updateSelectedSite = function($event, id){
            var checkbox = $event.target;
            if(checkbox.checked  && $scope.selectedSite.indexOf(id) < 0){
                $scope.selectedSite.push(id);
            } else {
                $scope.selectedSite.splice($scope.selectedSite.indexOf(id), 1);                
            }
            var node_id = '_';
            for(var i=0; i<$scope.selectedSite.length; i++) {
                node_id += $scope.selectedSite[i] + '_';                
            }
            if(node_id.length > 1) node_id = node_id.substring(1, node_id.length - 1);
            else $scope.nodes = [];     
        }
        
        $scope.buildDataUrl    = function() {
            var from    = $("#startDate").val();
            var to      = $("#stopDate").val();
            
            var node_id = '_';
            for(var i=0; i<$scope.selectedSite.length; i++) node_id += $scope.selectedSite[i]+'_';
            if(node_id.length > 1) node_id = node_id.substring(1, node_id.length - 1);
            
            var area_id = '_';
            for(var i=0; i<$scope.selectedArea.length; i++) area_id += $scope.selectedArea[i]+'_';
            if(area_id.length > 1) area_id = area_id.substring(1, area_id.length - 1);
            
            var region_id = '_';
            for(var i=0; i<$scope.selectedRegion.length; i++) region_id += $scope.selectedRegion[i]+'_';
            if(region_id.length > 1) region_id = region_id.substring(1, region_id.length - 1);
            
            if(node_id != '_') return BASE_URL+'api/datalog/fetch/site/'+node_id+'/'+from+'/'+to;
            else if(area_id != '_') return BASE_URL+'api/datalog/fetch/area/'+site_id+'/'+from+'/'+to;
            else if(region_id != '_') return BASE_URL+'api/datalog/fetch/region/'+region_id+'/'+from+'/'+to;
            else return BASE_URL+'api/datalog/fetch/all/_/'+from+'/'+to;
        }
        
        $scope.viewData    = function(doc) {
            if($scope.selectedRegion.length == 0) bootbox.alert("Please select region !");
            else if($scope.selectedArea.length == 0) bootbox.alert("Please select area !");
            else if($scope.selectedSite.length == 0) bootbox.alert("Please select site !");
            else {
                //bootbox.alert($scope.buildDataUrl()+'/'+doc);
                $scope.mode = doc;
                if(doc == 'xls') window.open($scope.buildDataUrl()+'/xls');
                else {
                    $http.get($scope.buildDataUrl()+'/'+doc).success(function(resp){
                        if(resp.success) $scope.datalogs = resp.data;
                    });
                    
                    if(doc == 'chart') {
                        for(var i=0; i<$scope.datalogs.length; i++) {
                            $scope.datalogs[i].jsdate = new Date($scope.datalogs[i].jsdate);
                        }
                        
                        $scope.dataFromPromise = function(){
                            var deferred = $q.defer();
                            var data = $scope.datalogs;
                            deferred.resolve(data)
                            return deferred.promise;
                        };
                        
                        $scope.amChartOptions = $timeout(function(){
                            return {
                                data: $scope.dataFromPromise(),
                                type: "serial",
                                theme: "light",
                                categoryField: "jsdate",                        
                                pathToImages: BASE_URL+'assets/plugins/amcharts/dist/images/',
                                legend: {
                                    enabled: true
                                },
                                chartScrollbar: {
                                    enabled: true,
                                },
                                categoryAxis: {
                                    minPeriod: "mm",                            
                                    parseDates: true
                                },
                                valueAxes: [{
                                    position: "left",
                                    title: "Value"
                                }],
                                graphs: [{
                                    type: "smoothedLine",
                                    title: "P Voltage",
                                    valueField: "pvoltage",
                                    lineThickness: 1,
                                    bullet: "round",
                                    bulletSize: 3,
                                    negativeLineColor: "#637bb6"
                                },{
                                    type: "smoothedLine",
                                    title: "Batt Volt",
                                    valueField: "vbatt",
                                    lineThickness: 1,
                                    bullet: "round",
                                    bulletSize: 3,
                                    negativeLineColor: "#637bb6"
                                },{
                                    type: "smoothedLine",
                                    title: "Batt Curr",
                                    valueField: "ibatt",
                                    lineThickness: 1,
                                    bullet: "round",
                                    bulletSize: 3,
                                    negativeLineColor: "#637bb6"
                                },{
                                    type: "smoothedLine",
                                    title: "Load Curr",
                                    valueField: "iload",
                                    lineThickness: 1,
                                    bullet: "round",
                                    bulletSize: 3,
                                    negativeLineColor: "#637bb6"
                                },{
                                    type: "smoothedLine",
                                    title: "Temp Ctrl",
                                    valueField: "temperature_ctrl",
                                    lineThickness: 1,
                                    bullet: "round",
                                    bulletSize: 3,
                                    negativeLineColor: "#637bb6"
                                },{
                                    type: "smoothedLine",
                                    title: "Temp Batt",
                                    valueField: "temperature_batt",
                                    lineThickness: 1,
                                    bullet: "round",
                                    bulletSize: 3,
                                    negativeLineColor: "#637bb6"
                                },{
                                    type: "smoothedLine",
                                    title: "Pack Volt",
                                    valueField: "pack_volt",
                                    lineThickness: 1,
                                    bullet: "round",
                                    bulletSize: 3,
                                    negativeLineColor: "#637bb6"
                                }]
                            }
                        }, 1000);                    
                    }
                
                }
            }
        }
    })
    .controller('AlarmlogController', function($rootScope, $scope, $filter, $interval, $http){
        if (angular.isDefined($rootScope.Timer)) $interval.cancel($rootScope.Timer);
        if (angular.isDefined($rootScope.alarmTimer)) $interval.cancel($rootScope.alarmTimer);
        if (angular.isDefined($rootScope.nodeTimer)) $interval.cancel($rootScope.nodeTimer);
        
        $("#startDate").datepicker({todayHighlight: true, format:'yyyy-mm-dd'});
        $("#stopDate").datepicker({todayHighlight: true, format:'yyyy-mm-dd'});
        $("#startDate").val(new Date().toJSON().slice(0,10));
        $("#stopDate").val(new Date().toJSON().slice(0,10));
        
        $scope.regions          = [];
        $scope.areas            = [];
        $scope.sites            = [];
        $scope.alarmLists       = [];        
        $scope.selectedRegion   = [];        
        $scope.selectedArea     = [];
        $scope.selectedSite     = [];
        $scope.selectedAlarm    = [];
        $scope.alarmlogs        = {};
        
        $http.get(BASE_URL+'api/subnet/region').success(function(response){
            if(response.success) $scope.regions = response.data;
        });
        $http.get(BASE_URL+'api/alarmList/all').success(function(response){
            if(response.success) $scope.alarmLists = response.data;
        });
        
        $scope.updateSelectedRegion = function($event, id){
            var checkbox = $event.target;
            if(checkbox.checked  && $scope.selectedRegion.indexOf(id) < 0){
                $scope.selectedRegion.push(id);
            } else {
                $scope.selectedRegion.splice($scope.selectedRegion.indexOf(id), 1);                
            }
            var region_id = '_';
            for(var i=0; i<$scope.selectedRegion.length; i++) {
                region_id += $scope.selectedRegion[i] + '_';                
            }
            if(region_id.length > 1) {
                region_id = region_id.substring(1, region_id.length - 1);
                $http.get(BASE_URL+'api/subnet/area/'+region_id).success(function(response){
                    if(response.success) $scope.areas = response.data;
                });
            }
            else {
                $scope.areas = [];
                $scope.sites = [];
            }          
        }
        
        $scope.updateSelectedArea = function($event, id){
            var checkbox = $event.target;
            if(checkbox.checked  && $scope.selectedArea.indexOf(id) < 0){
                $scope.selectedArea.push(id);
            } else {
                $scope.selectedArea.splice($scope.selectedArea.indexOf(id), 1);                
            }
            var area_id = '_';
            for(var i=0; i<$scope.selectedArea.length; i++) {
                area_id += $scope.selectedArea[i] + '_';                
            }
            if(area_id.length > 1) {
                area_id = area_id.substring(1, area_id.length - 1);
                $http.get(BASE_URL+'api/node/site/'+area_id).success(function(response){
                    if(response.success) $scope.sites = response.data;
                });
            }
            else {
                $scope.sites = [];
            }          
        }
        
        $scope.updateSelectedSite = function($event, id){
            var checkbox = $event.target;
            if(checkbox.checked  && $scope.selectedSite.indexOf(id) < 0){
                $scope.selectedSite.push(id);
            } else {
                $scope.selectedSite.splice($scope.selectedSite.indexOf(id), 1);                
            }
            var node_id = '_';
            for(var i=0; i<$scope.selectedSite.length; i++) {
                node_id += $scope.selectedSite[i] + '_';                
            }
            if(node_id.length > 1) node_id = node_id.substring(1, node_id.length - 1);
            else $scope.nodes = [];     
        }
        
        $scope.updateSelectedAlarm = function($event, id){
            var checkbox = $event.target;
            if(checkbox.checked  && $scope.selectedAlarm.indexOf(id) < 0){
                $scope.selectedAlarm.push(id);
            } else {
                $scope.selectedAlarm.splice($scope.selectedAlarm.indexOf(id), 1);                
            }
        }
        
        $scope.buildAlarmUrl    = function() {
            var from    = $("#startDate").val();
            var to      = $("#stopDate").val();
            
            var alarm_id  = '_';
            for(var i=0; i<$scope.selectedAlarm.length; i++) alarm_id += $scope.selectedAlarm[i]+'_';
            if(alarm_id.length > 1) alarm_id = alarm_id.substring(1, alarm_id.length - 1);
            
            var node_id = '_';
            for(var i=0; i<$scope.selectedSite.length; i++) node_id += $scope.selectedSite[i]+'_';
            if(node_id.length > 1) node_id = node_id.substring(1, node_id.length - 1);
            
            var area_id = '_';
            for(var i=0; i<$scope.selectedArea.length; i++) area_id += $scope.selectedArea[i]+'_';
            if(area_id.length > 1) area_id = area_id.substring(1, area_id.length - 1);
            
            var region_id = '_';
            for(var i=0; i<$scope.selectedRegion.length; i++) region_id += $scope.selectedRegion[i]+'_';
            if(region_id.length > 1) region_id = region_id.substring(1, region_id.length - 1);
            
            if(node_id != '_') return BASE_URL+'api/alarmlog/fetch/site/'+node_id+'/'+alarm_id+'/'+from+'/'+to;
            else if(area_id != '_') return BASE_URL+'api/alarmlog/fetch/area/'+site_id+'/'+alarm_id+'/'+from+'/'+to;
            else if(region_id != '_') return BASE_URL+'api/alarmlog/fetch/region/'+region_id+'/'+alarm_id+'/'+from+'/'+to;
            else return BASE_URL+'api/alarmlog/fetch/all/_/'+alarm_id+'/'+from+'/'+to;
        }
        
        $scope.viewAlarm    = function(doc) {
            if($scope.selectedRegion.length == 0) bootbox.alert("Please select region !");
            else if($scope.selectedArea.length == 0) bootbox.alert("Please select area !");
            else if($scope.selectedSite.length == 0) bootbox.alert("Please select site !");
            else {
                //bootbox.alert($scope.buildAlarmUrl()+'/'+doc);            
                if(doc == 'xls') window.open($scope.buildAlarmUrl()+'/xls');
                else {
                    $http.get($scope.buildAlarmUrl()+'/'+doc).success(function(response){
                        if(response.success) $scope.alarmlogs = response.data;
                    });
                }                
            }
        }
    })
    .controller('CustomerController', function($rootScope, $scope, $modal, $filter, $interval, $http){
        if (angular.isDefined($rootScope.Timer)) $interval.cancel($rootScope.Timer);
        if (angular.isDefined($rootScope.alarmTimer)) $interval.cancel($rootScope.alarmTimer);
        if (angular.isDefined($rootScope.nodeTimer)) $interval.cancel($rootScope.nodeTimer);
        
        $scope.customer         = {};
        $scope.customers        = {};
        $scope.customers.pages  = [];
        $scope.itemPerPages     = [10, 20, 30];
        $scope.itemPerPage      = 10;
        
        $scope.open = function (p, s) {
            var modalInstance = $modal.open({
                templateUrl: BASE_URL+'assets/pages/customerEdit.html',
                controller: 'CustomerEditController',
                size: s,
                resolve: {
                    item: function() {
                        return p;
                    }
                }
            });
            
            modalInstance.result.then(function(selectedObject) {
                if(selectedObject.save == "insert"){
                    $scope.customers.content.push(selectedObject);
                    $scope.customers.content = $filter('orderBy')($scope.customers.content, 'id', 'reverse');
                } else if(selectedObject.save == "update"){
                    p.name = selectedObject.name;
                    p.email = selectedObject.email;
                    p.phone = selectedObject.phone;
                }
            });
        }            
        
        $scope.remove = function(c) {
            bootbox.confirm("Are you sure to delete "+c.name+" ?", function(result) {
                if(result) {
                    $http.delete(BASE_URL+'api/customer/remove/'+c.id).success(function(data){
                        $scope.customers.content = _.without($scope.customers.content, _.findWhere($scope.customers.content, {id:c.id}));
                    });
                }
            });
        }
        
        $scope.reloadPage = function(page){
            if(!page || page < 1) {
                page = 1;
            }
            $http.get(BASE_URL+'api/customer/fetch/'+page+'/'+$scope.itemPerPage).success(function(response){
                $scope.customers = response;
                $scope.customers.pages = [];
                for(var i=0; i<$scope.customers.totalPage; i++) $scope.customers.pages[i] = i+1;
            });
        }
        
        $scope.reloadPage();
    })
    .controller('CustomerEditController', function($scope, $modalInstance, $http, item){
        $scope.customer = angular.copy(item);
        
        $scope.cancel = function () {
            $modalInstance.dismiss('Close');
        };
        
        $scope.title = (angular.isDefined(item.id)) ? 'Edit Customer' : 'Add Customer';
        $scope.buttonText = (angular.isDefined(item.id)) ? 'Update' : 'Save';
        
        var original = item;
        $scope.isClean = function() {
            return angular.equals(original, $scope.customer);
        }
        
        $scope.save = function (c) {
            if(angular.isDefined(c.id)){
                $http.post(BASE_URL+'api/customer/update/'+c.id, c).then(function (result) {
                    var x = angular.copy(c);
                    x.save = 'update';
                    $modalInstance.close(x);
                });
            } else{
                $http.post(BASE_URL+'api/customer/save', c).then(function (result) {
                    var x = angular.copy(c);
                    x.save = 'insert';
                    x.id = result.id;                    
                    $modalInstance.close(x);
                });
            }
        };        
    })
    .controller('SeverityController', function($rootScope, $scope, $modal, $filter, $interval, $http){
        if (angular.isDefined($rootScope.Timer)) $interval.cancel($rootScope.Timer);
        if (angular.isDefined($rootScope.alarmTimer)) $interval.cancel($rootScope.alarmTimer);
        if (angular.isDefined($rootScope.nodeTimer)) $interval.cancel($rootScope.nodeTimer);
        
        $scope.severity         = {};
        $scope.severities       = {};
        $scope.severities.pages  = [];
        $scope.itemPerPage      = 10;
        
        $scope.open = function (o, s) {
            var modalInstance = $modal.open({
                templateUrl: BASE_URL+'assets/pages/severityEdit.html',
                controller: 'SeverityEditController',
                size: s,
                resolve: {
                    item: function() {
                        return o;
                    }
                }
            });
            
            modalInstance.result.then(function(selectedObject) {
                if(selectedObject.save == "insert"){
                    $scope.severities.content.push(selectedObject);
                    $scope.severities.content = $filter('orderBy')($scope.severities.content, 'id', 'reverse');
                } else if(selectedObject.save == "update"){
                    o.name = selectedObject.name;
                    o.color = selectedObject.color;
                }
            });
        }            
        
        $scope.remove = function(o) {
            bootbox.confirm("Are you sure to delete "+o.name+" ?", function(result) {
                if(result) {
                    $http.delete(BASE_URL+'api/severity/remove/'+o.id).success(function(data){
                        $scope.severities.content = _.without($scope.severities.content, _.findWhere($scope.severities.content, {id:o.id}));
                    });
                }
            });
        }
        
        $scope.reloadPage = function(page){
            if(!page || page < 1) {
                page = 1;
            }
            $http.get(BASE_URL+'api/severity/fetch/'+page+'/'+$scope.itemPerPage).success(function(response){
                $scope.severities = response;
                $scope.severities.pages = [];
                for(var i=0; i<$scope.severities.totalPage; i++) $scope.severities.pages[i] = i+1;
            });
        }
        
        $scope.reloadPage();
    })
    .controller('SeverityEditController', function($scope, $modalInstance, $http, item){
        $scope.severity = angular.copy(item);
        
        $scope.cancel = function () {
            $modalInstance.dismiss('Close');
        };
        
        $scope.title = (angular.isDefined(item.id)) ? 'Edit Severity' : 'Add Severity';
        $scope.buttonText = (angular.isDefined(item.id)) ? 'Update' : 'Save';
        
        var original = item;
        $scope.isClean = function() {
            return angular.equals(original, $scope.severity);
        }
        
        $scope.save = function (o) {
            if(angular.isDefined(o.id)){
                $http.post(BASE_URL+'api/severity/update/'+o.id, o).then(function (result) {
                    var x = angular.copy(o);
                    x.save = 'update';
                    $modalInstance.close(x);
                });
            } else{
                $http.post(BASE_URL+'api/severity/save', o).then(function (result) {
                    var x = angular.copy(o);
                    x.save = 'insert';
                    x.id = result.id;                    
                    $modalInstance.close(x);
                });
            }
        };
        
    })
    .controller('AlarmListController', function($rootScope, $scope, $modal, $filter, $interval, $http){
        if (angular.isDefined($rootScope.Timer)) $interval.cancel($rootScope.Timer);
        if (angular.isDefined($rootScope.alarmTimer)) $interval.cancel($rootScope.alarmTimer);
        if (angular.isDefined($rootScope.nodeTimer)) $interval.cancel($rootScope.nodeTimer);
        
        $scope.alarmlist            = {};
        $scope.alarmlists           = {};
        $scope.alarmlists.pages     = [];
        $scope.itemPerPage          = 10;
        
        $scope.open = function (o, s) {
            var modalInstance = $modal.open({
                templateUrl: BASE_URL+'assets/pages/alarmListEdit.html',
                controller: 'AlarmListEditController',
                size: s,
                resolve: {
                    item: function() {
                        return o;
                    }
                }
            });
            
            modalInstance.result.then(function(selectedObject) {
                $scope.reloadPage();            
            });
        }
        
        $scope.remove = function(o) {
            bootbox.confirm("Are you sure to delete "+o.name+" ?", function(result) {
                if(result) {
                    $http.delete(BASE_URL+'api/alarmList/remove/'+o.id).success(function(data){
                        $scope.alarmlists.content = _.without($scope.alarmlists.content, _.findWhere($scope.alarmlists.content, {id:o.id}));
                    });
                }
            });
        }
        
        $scope.reloadPage = function(page){
            if(!page || page < 1) {
                page = 1;
            }
            $http.get(BASE_URL+'api/alarmList/fetch/'+page+'/'+$scope.itemPerPage).success(function(response){
                $scope.alarmlists = response;
                $scope.alarmlists.pages = [];
                for(var i=0; i<$scope.alarmlists.totalPage; i++) $scope.alarmlists.pages[i] = i+1;
            });
        }
        
        $scope.reloadPage();
    })
    .controller('AlarmListEditController', function($scope, $modalInstance, $http, item){
        $scope.severities = [];
        $scope.alarmlist = angular.copy(item);
        
        $http.get(BASE_URL+'api/severity/all').success(function(data){
            $scope.severities = data;
        });
        
        $scope.cancel = function () {
            $modalInstance.dismiss('Close');
        };
        
        $scope.title = (angular.isDefined(item.id)) ? 'Edit Alarm List' : 'Add Alarm List';
        $scope.buttonText = (angular.isDefined(item.id)) ? 'Update' : 'Save';
        
        var original = item;
        $scope.isClean = function() {
            return angular.equals(original, $scope.alarmlist);
        }
        
        $scope.save = function (o) {
            if(angular.isDefined(o.id)){
                $http.post(BASE_URL+'api/alarmList/update/'+o.id, o).then(function (result) {
                    var x = angular.copy(o);
                    x.save = 'update';
                    $modalInstance.close(x);
                });
            } else{
                $http.post(BASE_URL+'api/alarmList/save', o).then(function (result) {
                    var x = angular.copy(o);
                    x.save = 'insert';
                    x.id = result.id;                    
                    $modalInstance.close(x);
                });
            }
        };
    })
    .controller('RegionController', function($rootScope, $scope, $modal, $filter, $interval, $http){
        if (angular.isDefined($rootScope.Timer)) $interval.cancel($rootScope.Timer);
        if (angular.isDefined($rootScope.alarmTimer)) $interval.cancel($rootScope.alarmTimer);
        if (angular.isDefined($rootScope.nodeTimer)) $interval.cancel($rootScope.nodeTimer);
        
        $scope.region            = {};
        $scope.regions           = {};
        $scope.regions.pages     = [];
        $scope.itemPerPage       = 10;
        
        $scope.open = function (o, s) {
            var modalInstance = $modal.open({
                templateUrl: BASE_URL+'assets/pages/regionEdit.html',
                controller: 'RegionEditController',
                size: s,
                resolve: {
                    item: function() {
                        return o;
                    }
                }
            });
            
            modalInstance.result.then(function(selectedObject) {
                $scope.reloadPage();
            });
        }
        
        $scope.remove = function(o) {
            bootbox.confirm("Are you sure to delete "+o.name+" ?", function(result) {
                if(result) {
                    $http.delete(BASE_URL+'api/region/remove/'+o.id).success(function(data){
                        $scope.regions.content = _.without($scope.regions.content, _.findWhere($scope.regions.content, {id:o.id}));
                    });
                }
            });
        }
        
        $scope.reloadPage = function(page){
            if(!page || page < 1) {
                page = 1;
            }
            $http.get(BASE_URL+'api/region/fetch/'+page+'/'+$scope.itemPerPage).success(function(response){
                $scope.regions = response;
                $scope.regions.pages = [];
                for(var i=0; i<$scope.regions.totalPage; i++) $scope.regions.pages[i] = i+1;
            });
        }
        
        $scope.reloadPage();
    })
    .controller('RegionEditController', function($scope, $modalInstance, $http, item){
        $scope.customers = [];
        $scope.region    = angular.copy(item);
        
        $http.get(BASE_URL+'api/customer/all').success(function(response){
            $scope.customers = response.data;
        });
        
        $scope.cancel = function () {
            $modalInstance.dismiss('Close');
        };
        
        $scope.title = (angular.isDefined(item.id)) ? 'Edit Region' : 'Add Region';
        $scope.buttonText = (angular.isDefined(item.id)) ? 'Update' : 'Save';
        
        var original = item;
        $scope.isClean = function() {
            return angular.equals(original, $scope.region);
        }
        
        $scope.save = function (o) {
            if(angular.isDefined(o.id)){
                $http.post(BASE_URL+'api/region/update/'+o.id, o).then(function (result) {
                    var x = angular.copy(o);
                    x.save = 'update';
                    $modalInstance.close(x);
                });
            } else{
                $http.post(BASE_URL+'api/region/save', o).then(function (result) {
                    var x = angular.copy(o);
                    x.save = 'insert';
                    x.id = result.id;                    
                    $modalInstance.close(x);
                });
            }
        };
    })
    .controller('AreaController', function($rootScope, $scope, $modal, $filter, $interval, $http){
        if (angular.isDefined($rootScope.Timer)) $interval.cancel($rootScope.Timer);
        if (angular.isDefined($rootScope.alarmTimer)) $interval.cancel($rootScope.alarmTimer);
        if (angular.isDefined($rootScope.nodeTimer)) $interval.cancel($rootScope.nodeTimer);
        
        $scope.area            = {};
        $scope.areas           = {};
        $scope.areas.pages     = [];
        $scope.itemPerPage     = 10;
        
        $scope.open = function (o, s) {
            var modalInstance = $modal.open({
                templateUrl: BASE_URL+'assets/pages/areaEdit.html',
                controller: 'AreaEditController',
                size: s,
                resolve: {
                    item: function() {
                        return o;
                    }
                }
            });
            
            modalInstance.result.then(function(selectedObject) {
                $scope.reloadPage();
            });
        }
        
        $scope.remove = function(o) {
            bootbox.confirm("Are you sure to delete "+o.name+" ?", function(result) {
                if(result) {
                    $http.delete(BASE_URL+'api/area/remove/'+o.id).success(function(data){
                        $scope.areas.content = _.without($scope.areas.content, _.findWhere($scope.areas.content, {id:o.id}));
                    });
                }
            });
        }
        
        $scope.reloadPage = function(page){
            if(!page || page < 1) {
                page = 1;
            }
            $http.get(BASE_URL+'api/area/fetch/'+page+'/'+$scope.itemPerPage).success(function(response){
                $scope.areas = response;
                $scope.areas.pages = [];
                for(var i=0; i<$scope.areas.totalPage; i++) $scope.areas.pages[i] = i+1;
            });
        }
        
        $scope.reloadPage();
    })
    .controller('AreaEditController', function($scope, $modalInstance, $http, item){
        $scope.regions  = [];
        $scope.area     = angular.copy(item);
        
        $http.get(BASE_URL+'api/region/all').success(function(data){
            $scope.regions = data;
        });
        
        $scope.cancel = function () {
            $modalInstance.dismiss('Close');
        };
        
        $scope.title = (angular.isDefined(item.id)) ? 'Edit Area' : 'Add Area';
        $scope.buttonText = (angular.isDefined(item.id)) ? 'Update' : 'Save';
        
        var original = item;
        $scope.isClean = function() {
            return angular.equals(original, $scope.area);
        }
        
        $scope.save = function (o) {
            if(angular.isDefined(o.id)){
                $http.post(BASE_URL+'api/area/update/'+o.id, o).then(function (result) {
                    var x = angular.copy(o);
                    x.save = 'update';
                    $modalInstance.close(x);
                });
            } else{
                $http.post(BASE_URL+'api/area/save', o).then(function (result) {
                    var x = angular.copy(o);
                    x.save = 'insert';
                    x.id = result.id;                    
                    $modalInstance.close(x);
                });
            }
        };
    })
    .controller('SiteController', function($rootScope, $scope, $modal, $filter, $interval, $http){
        if (angular.isDefined($rootScope.Timer)) $interval.cancel($rootScope.Timer);
        if (angular.isDefined($rootScope.alarmTimer)) $interval.cancel($rootScope.alarmTimer);
        if (angular.isDefined($rootScope.nodeTimer)) $interval.cancel($rootScope.nodeTimer);
        
        $scope.site            = {};
        $scope.sites           = {};
        $scope.sites.pages     = [];
        $scope.itemPerPage     = 10;
        
        $scope.open = function (o, s) {
            var modalInstance = $modal.open({
                templateUrl: BASE_URL+'assets/pages/siteEdit.html',
                controller: 'SiteEditController',
                size: s,
                resolve: {
                    item: function() {
                        return o;
                    }
                }
            });
            
            modalInstance.result.then(function(selectedObject) {
                $scope.reloadPage();
            });
        }
        
        $scope.remove = function(o) {
            bootbox.confirm("Are you sure to delete "+o.name+" ?", function(result) {
                if(result) {
                    $http.delete(BASE_URL+'api/site/remove/'+o.id).success(function(data){
                        $scope.sites.content = _.without($scope.sites.content, _.findWhere($scope.sites.content, {id:o.id}));
                    });
                }
            });
        }
        
        $scope.reloadPage = function(page){
            if(!page || page < 1) {
                page = 1;
            }
            $http.get(BASE_URL+'api/site/fetch/'+page+'/'+$scope.itemPerPage).success(function(response){
                $scope.sites = response;
                $scope.sites.pages = [];
                for(var i=0; i<$scope.sites.totalPage; i++) $scope.sites.pages[i] = i+1;
            });
        }
        
        $scope.reloadPage();
    })
    .controller('SiteEditController', function($scope, $modalInstance, $http, item){
        $scope.areas    = [];
        $scope.masters  = [];
        $scope.site     = angular.copy(item);
        $scope.site.pos = (angular.isDefined(item.consent_id) && item.consent_id == null) ? 'master' : 'slave';
        
        $http.get(BASE_URL+'api/area/all').success(function(data){
            $scope.areas = data;
        });
        
        $http.get(BASE_URL+'api/node/master').success(function(response){
            $scope.masters = response.data;
        });
        
        $scope.cancel = function () {
            $modalInstance.dismiss('Close');
        };
        
        $scope.title = (angular.isDefined(item.id)) ? 'Edit Site' : 'Add Site';
        $scope.buttonText = (angular.isDefined(item.id)) ? 'Update' : 'Save';
        
        var original = item;
        $scope.isClean = function() {
            return angular.equals(original, $scope.site);
        }
        
        $scope.save = function (o) {
            if(angular.isDefined(o.id)){
                $http.post(BASE_URL+'api/site/update/'+o.id, o).then(function (result) {
                    var x = angular.copy(o);
                    //x.save = 'update';
                    $modalInstance.close(x);
                });
            } else{
                $http.post(BASE_URL+'api/site/save', o).then(function (result) {
                    var x = angular.copy(o);
                    //x.save = 'insert';
                    //x.id = result.id;                    
                    $modalInstance.close(x);                    
                });
            }
        };
    })
    .controller('UserController', function($rootScope, $scope, $modal, $filter, $interval, $http){
        if (angular.isDefined($rootScope.Timer)) $interval.cancel($rootScope.Timer);
        if (angular.isDefined($rootScope.alarmTimer)) $interval.cancel($rootScope.alarmTimer);
        if (angular.isDefined($rootScope.nodeTimer)) $interval.cancel($rootScope.nodeTimer);
        
        $scope.user            = {};
        $scope.users           = {};
        $scope.users.pages     = [];
        $scope.itemPerPage     = 10;
        
        $scope.open = function (o, s) {
            var modalInstance = $modal.open({
                templateUrl: BASE_URL+'assets/pages/userEdit.html',
                controller: 'UserEditController',
                size: s,
                resolve: {
                    item: function() {
                        return o;
                    }
                }
            });
            
            modalInstance.result.then(function(selectedObject) {
                $scope.reloadPage();
            });
        }
        
        $scope.remove = function(o) {
            bootbox.confirm("Are you sure to delete "+o.name+" ?", function(result) {
                if(result) {
                    $http.delete(BASE_URL+'api/user/remove/'+o.id).success(function(data){
                        $scope.users.content = _.without($scope.users.content, _.findWhere($scope.users.content, {id:o.id}));
                    });
                }
            });
        }
        
        $scope.reloadPage = function(page){
            if(!page || page < 1) {
                page = 1;
            }
            $http.get(BASE_URL+'api/user/fetch/'+page+'/'+$scope.itemPerPage).success(function(response){
                $scope.users = response;
                $scope.users.pages = [];
                for(var i=0; i<$scope.users.totalPage; i++) $scope.users.pages[i] = i+1;
            });
        }
        
        $scope.reloadPage();
    })
    .controller('UserEditController', function($scope, $modalInstance, $http, item){
        $scope.roles        = [];
        $scope.customers    = [];
        $scope.user         = angular.copy(item);
        
        $http.get(BASE_URL+'api/role/all').success(function(response){
            if(response.success) $scope.roles = response.data;
        });
        
        $http.get(BASE_URL+'api/customer/all').success(function(response){
            if(response.success) $scope.customers = response.data;
        });
        
        $scope.cancel = function () {
            $modalInstance.dismiss('Close');
        };
        
        $scope.title = (angular.isDefined(item.id)) ? 'Edit User' : 'Add User';
        $scope.buttonText = (angular.isDefined(item.id)) ? 'Update' : 'Save';
        
        var original = item;
        $scope.isClean = function() {
            return angular.equals(original, $scope.site);
        }
        
        $scope.save = function (o) {
            if(angular.isDefined(o.id)){
                $http.post(BASE_URL+'api/user/update/'+o.id, o).then(function (result) {
                    var x = angular.copy(o);
                    //x.save = 'update';
                    $modalInstance.close(x);
                });
            } else{
                $http.post(BASE_URL+'api/user/save', o).then(function (result) {
                    var x = angular.copy(o);
                    //x.save = 'insert';
                    //x.id = result.id;                    
                    $modalInstance.close(x);                    
                });
            }
        };
    })
    .controller('NodeController', function($rootScope, $interval, $scope, $timeout, $q, $http, $stateParams){
        if (angular.isDefined($rootScope.Timer)) $interval.cancel($rootScope.Timer);
        if (angular.isDefined($rootScope.alarmTimer)) $interval.cancel($rootScope.alarmTimer);
        if (angular.isDefined($rootScope.nodeTimer)) $interval.cancel($rootScope.nodeTimer);
        
        $scope.node = {};
        $http.get(BASE_URL+'api/node/info/'+$stateParams.id).success(function(response){
            if(response.success) $scope.node = response.data;
        });
        
        $timeout(function(){
            var chart = AmCharts.makeChart("pvchart", {
                "type": "gauge",
                "theme": "light",
                "axes": [ {
                    "axisThickness": 1,
                    "axisAlpha": 0.2,
                    "tickAlpha": 0.2,
                    "valueInterval": 10,
                    "startAngle": -100,
                    "endAngle": 100,
                    "bottomText": $scope.node.pack_volt+" V",
                    "bottomTextYOffset": -20,
                    "endValue": 30
                } ],
              "arrows": [ {} ],
              "export": {
                "enabled": true
              }
            });
            
            chart.arrows[0].setValue($scope.node.pack_volt);
            chart.axes[0].bands[1].setEndValue($scope.node.pack_volt);
        }, 1000);
        
        $timeout(function(){
            var chart = AmCharts.makeChart("vbchart", {
                "type": "gauge",
                "theme": "light",
                "axes": [ {
                    "axisThickness": 1,
                    "axisAlpha": 0.2,
                    "tickAlpha": 0.2,
                    "valueInterval": 10,
                    "startAngle": -100,
                    "endAngle": 100,
                    "bottomText": $scope.node.vbatt+" V",
                    "bottomTextYOffset": -20,
                    "endValue": 30
                } ],
              "arrows": [ {} ],
              "export": {
                "enabled": true
              }
            });
            
            chart.arrows[0].setValue($scope.node.vbatt);
            chart.axes[0].bands[1].setEndValue($scope.node.vbatt);
        }, 1000);
        
        $timeout(function(){
            var chart = AmCharts.makeChart("bcchart", {
                "type": "gauge",
                "theme": "light",
                "axes": [ {
                    "axisThickness": 1,
                    "axisAlpha": 0.2,
                    "tickAlpha": 0.2,
                    "valueInterval": 2,
                    "startAngle": -100,
                    "endAngle": 100,
                    "bottomText": $scope.node.ibatt+" A",
                    "bottomTextYOffset": -20,
                    "startValue": -5,
                    "endValue": 5
                } ],
              "arrows": [ {} ],
              "export": {
                "enabled": true
              }
            });
            
            chart.arrows[0].setValue($scope.node.ibatt);
            chart.axes[0].bands[1].setEndValue($scope.node.ibatt);
        }, 1000);
        
        $timeout(function(){
            var chart = AmCharts.makeChart("lcchart", {
                "type": "gauge",
                "theme": "light",
                "axes": [ {
                    "axisThickness": 1,
                    "axisAlpha": 0.2,
                    "tickAlpha": 0.2,
                    "valueInterval": 2,
                    "startAngle": -100,
                    "endAngle": 100,
                    "bottomText": $scope.node.iload+" A",
                    "bottomTextYOffset": -20,
                    "startValue": -5,
                    "endValue": 5
                } ],
              "arrows": [ {} ],
              "export": {
                "enabled": true
              }
            });
            
            chart.arrows[0].setValue($scope.node.iload);
            chart.axes[0].bands[1].setEndValue($scope.node.iload);
        }, 1000);
        
        $timeout(function(){
            var chart = AmCharts.makeChart("tcchart", {
                "type": "gauge",
                "theme": "light",
                "axes": [ {
                    "axisThickness": 1,
                    "axisAlpha": 0.2,
                    "tickAlpha": 0.2,
                    "valueInterval": 20,
                    "startAngle": -100,
                    "endAngle": 100,
                    "bands": [ {
                        "color": "#84b761",
                        "startValue": 0,
                        "endValue": 30
                    }, {
                        "color": "#fdd400",
                        "startValue": 30,
                        "endValue": 60
                    }, {
                        "color": "#cc4748",
                        "startValue": 60,
                        "endValue": 100
                    } ],
                    "bottomText": $scope.node.temperature_ctrl+" C",
                    "bottomTextYOffset": -20,
                    "endValue": 100
                } ],
              "arrows": [ {} ],
              "export": {
                "enabled": true
              }
            });
            
            chart.arrows[0].setValue($scope.node.temperature_ctrl);
            //chart.axes[0].setTopText(value + " %");
            // adjust darker band to new value
            chart.axes[0].bands[1].setEndValue($scope.node.temperature_ctrl);
        }, 1000);
        
        $timeout(function(){
            var chart = AmCharts.makeChart("tbchart", {
                "type": "gauge",
                "theme": "light",
                "axes": [ {
                    "axisThickness": 1,
                    "axisAlpha": 0.2,
                    "tickAlpha": 0.2,
                    "valueInterval": 20,
                    "startAngle": -100,
                    "endAngle": 100,
                    "bands": [ {
                        "color": "#84b761",
                        "startValue": 0,
                        "endValue": 30
                    }, {
                        "color": "#fdd400",
                        "startValue": 30,
                        "endValue": 60
                    }, {
                        "color": "#cc4748",
                        "startValue": 60,
                        "endValue": 100
                    } ],
                    "bottomText": $scope.node.temperature_batt+" C",
                    "bottomTextYOffset": -20,
                    "endValue": 100
                } ],
              "arrows": [ {} ],
              "export": {
                "enabled": true
              }
            });
            
            chart.arrows[0].setValue($scope.node.temperature_batt);
            //chart.axes[0].setTopText(value + " %");
            // adjust darker band to new value
            chart.axes[0].bands[1].setEndValue($scope.node.temperature_batt);
        }, 1000);
        
    })
    ;
    
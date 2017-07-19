'use strict';
/**
 * @ngdoc overview
 * @name pjuApp
 * @description
 * # sbAdminApp
 *
 * Main module of the application.
 */
 angular
    .module('pjuApp', [
        'ui.router',
        'ui.bootstrap',
        'angular-loading-bar',
        'ngAnimate',
        'ngResource',
        'ngTable',
        'amChartsDirective'
    ])
    .config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider){
        
        $urlRouterProvider.otherwise('/map');
        
        $stateProvider
            .state('map', {
                url: '/map',
                templateUrl: BASE_URL+'assets/pages/map.html',                
                controller: 'MapController'                
            })
            .state('summaries', {
                url: '/summaries',
                templateUrl: BASE_URL+'assets/pages/summaries.html',                
                controller: 'SummariesController'                
            })
            .state('surveillance', {
                url: '/surveillance',
                templateUrl: BASE_URL+'assets/pages/surveillance.html',                
                controller: 'SurveillanceController'                
            })
            .state('datalog', {
                url: '/datalog',
                templateUrl: BASE_URL+'assets/pages/datalog.html',                
                controller: 'DatalogController'                
            })
            .state('alarmlog', {
                url: '/alarmlog',
                templateUrl: BASE_URL+'assets/pages/alarmlog.html',                
                controller: 'AlarmlogController'                
            })
            .state('customer', {
                url: '/admin/customer',
                templateUrl: BASE_URL+'assets/pages/customer.html',                
                controller: 'CustomerController'                
            })
            .state('severity', {
                url: '/admin/severity',
                templateUrl: BASE_URL+'assets/pages/severity.html',                
                controller: 'SeverityController'                
            })
            .state('alarm', {
                url: '/admin/alarm',
                templateUrl: BASE_URL+'assets/pages/alarmList.html',                
                controller: 'AlarmListController'                
            })
            .state('region', {
                url: '/admin/region',
                templateUrl: BASE_URL+'assets/pages/region.html',                
                controller: 'RegionController'                
            })
            .state('area', {
                url: '/admin/area',
                templateUrl: BASE_URL+'assets/pages/area.html',                
                controller: 'AreaController'                
            })
            .state('site', {
                url: '/admin/site',
                templateUrl: BASE_URL+'assets/pages/site.html',                
                controller: 'SiteController'                
            })
            .state('user', {
                url: '/admin/user',
                templateUrl: BASE_URL+'assets/pages/user.html',                
                controller: 'UserController'                
            })
            .state('nodeview', {
                url: '/node/view/:id',
                templateUrl: BASE_URL+'assets/pages/node.html',
                controller: 'NodeController'
            })
            /*
            .state('summary', {
                url: '/summary',
                templateUrl: BASE_URL+'assets/pages/summary.html',
                controller: 'SummaryController'                
            })
            
            .state('surveillance', {
                url: '/surveillance',
                templateUrl: BASE_URL+'assets/pages/surveillance.html',
                controller: 'SurveillanceController'
            })
            .state('alarmLog', {
                url: '/alarmlog',
                templateUrl: BASE_URL+'assets/pages/alarmLog.html',
                controller: 'AlarmLogController'
            })
            .state('dataLog', {
                url: '/datalog',
                templateUrl: BASE_URL+'assets/pages/dataLog.html',
                controller: 'DataLogController'
            })
            .state('profile', {
                url: '/profile',
                templateUrl: BASE_URL+'assets/pages/profile.html',
                controller: 'ProfileController'
            })
            .state('setting', {
                url: '/setting',
                templateUrl: BASE_URL+'assets/pages/setting.html',
                controller: 'SettingController'
            })
            .state('adminCustomer', {
                url: '/admin/customer',
                templateUrl: BASE_URL+'assets/pages/customer.html',
                controller: 'CustomerController'
            })
            .state('adminSeverity', {
                url: '/admin/severity',
                templateUrl: BASE_URL+'assets/pages/severity.html',
                controller: 'SeverityController'
            })
            .state('adminList', {
                url: '/admin/alarm',
                templateUrl: BASE_URL+'assets/pages/alarmList.html',
                controller: 'AlarmListController'
            })
            .state('adminRegion', {
                url: '/admin/region',
                templateUrl: BASE_URL+'assets/pages/region.html',
                controller: 'RegionController'
            })
            .state('adminArea', {
                url: '/admin/area',
                templateUrl: BASE_URL+'assets/pages/area.html',
                controller: 'AreaController'
            })
            .state('adminSite', {
                url: '/admin/site',
                templateUrl: BASE_URL+'assets/pages/site.html',
                controller: 'SiteController'
            })
            .state('adminUser', {
                url: '/admin/user',
                templateUrl: BASE_URL+'assets/pages/user.html',
                controller: 'UserController'
            })
            */
            ;
            
    }])
    .run(function($rootScope) {
        $rootScope.Timer = null;
        $rootScope.alarmTimer = null;
        $rootScope.nodeTimer = null;
    });
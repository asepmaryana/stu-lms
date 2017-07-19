angular.module('pjuApp')
    .directive('icheck', function ($timeout, $parse) {
        return {
            require: '?ngModel',
            link: function ($scope, element, $attrs, ngModel) {
                return $timeout(function () {
                    var value;
                    value = $attrs['value'];
    
                    $scope.$watch($attrs['ngModel'], function (newValue) {
                        $(element).iCheck('update');
                    });
    
                    $scope.$watch($attrs['ngDisabled'], function (newValue) {
                        $(element).iCheck(newValue ? 'disable' : 'enable');
                        $(element).iCheck('update');
                    })
    
                    return $(element).iCheck({
                        checkboxClass: 'icheckbox_square-blue',
                        radioClass: 'iradio_square-blue'
    
                    }).on('ifChanged', function (event) {
                        if ($(element).attr('type') === 'checkbox' && $attrs['ngModel']) {
                            $scope.$apply(function () {
                                return ngModel.$setViewValue(event.target.checked);
                            })
                        }
                        
                        var checkToggle = $parse($attrs['ngChange']);
                        
                        //$(element).triggerHandler('click');
                        $(element).triggerHandler('change');

                        // Can be used for modelless inputs, otherwise use ngChange
                        $scope.$apply(function () {
                            event.isChecked = element[0].checked;
                            checkToggle($scope, {$event:event});
                        });
                        
                    }).on('ifClicked', function (event) {
                        if ($(element).attr('type') === 'radio' && $attrs['ngModel']) {
                            return $scope.$apply(function () {
                                //set up for radio buttons to be de-selectable
                                if (ngModel.$viewValue != value)
                                    return ngModel.$setViewValue(value);
                                else
                                    ngModel.$setViewValue(null);
                                ngModel.$render();
                                return
                            });
                        }
                    });
                });
            }
        };
    })
    .directive('showtab', function(){
        return {
            link: function(scope, element, attrs){
                element.click(function(e){
                    e.preventDefault();
                    $(element).tab('show');
                });
            }
        };
    })
    .directive('onlyNumbers', function() {
        return function(scope, element, attrs) {
            var keyCode = [8,9,13,37,39,45,46,48,49,50,51,52,53,54,55,56,57,96,97,98,99,100,101,102,103,104,105,110,190];
            element.bind("keydown", function(event) {
                if($.inArray(event.which,keyCode) == -1) {
                    scope.$apply(function(){
                        scope.$eval(attrs.onlyNum);
                        event.preventDefault();
                    });
                    event.preventDefault();
                }
    
            });
        };
    })
    .directive('focus', function() {
        return function(scope, element) {
            element[0].focus();
        }      
    })
    ;
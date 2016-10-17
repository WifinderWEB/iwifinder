(function () {
    'use strict';
    angular.module('PiZoneAuth', [])
        .service('SessionService', ['$injector', '$rootScope', '$sessionStorage', '$http', '$cookies', function ($injector, $rootScope, $sessionStorage, $http, $cookies) {
            var service = {};
            service.CheckAccess = CheckAccess;
            service.SetCredentials = SetCredentials;
            service.ClearCredentials = ClearCredentials;

            return service;

            function CheckAccess(event, toState, toParams, fromState, fromParams) {
                var $scope = $injector.get('$rootScope');

                if (toState.data !== undefined) {
                    if (toState.data.noLogin !== undefined && toState.data.noLogin) {
                        // если нужно, выполняйте здесь какие-то действия
                        // перед входом без авторизации
                    }
                }
                else {
                    if (!$sessionStorage.isAuthorized) {
                        // если пользователь не авторизован - отправляем на страницу авторизации
                        event.preventDefault();
                        $scope.$state.$last = $scope.$state.current;
                        $scope.$state.go('login');
                    }
                }
            };

            function SetCredentials(username, password) {
                var authdata = Base64.Encode(username + ':' + password);
                $sessionStorage.isAuthorized = true;
                $http.defaults.headers.common['Authorization'] = 'Basic ' + authdata; // jshint ignore:line
                $cookies.put('token', authdata);
            }

            function ClearCredentials() {
                $rootScope.globals = {};
                $sessionStorage.isAuthorized = false;
                $cookies.remove('token');
                $http.defaults.headers.common.Authorization = 'Basic ';
            }
        }]
    );
})();
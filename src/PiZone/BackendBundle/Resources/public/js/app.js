(function () {
    'use strict';
    var path = ConfigPiZone.pathToTmpl;
    angular.module('backend', ['ui.router', 'ngCookies', 'ngStorage', 'ngSanitize', 'PiZoneControllers', 'PiZoneAuth', 'PiZoneModalService'])
        .config(['$stateProvider', '$urlRouterProvider', '$httpProvider', function ($stateProvider, $urlRouterProvider, $httpProvider) {
                $urlRouterProvider
                    .otherwise('/content/');

                $stateProvider
                    .state('main', {
                        "abstract": true,
                        "url": "",
                        "views": {
                            '@': {
                                templateUrl: '/admin/tmpl/content.html',
                                controller: 'MainCtrl'
                            }
                        },

                    })
                    .state('content', {
                        "url": '/content/:pageId?sort&order_by',
                        "parent": 'main',
                        "views": {
                            'content': {
                                templateUrl: path + 'content/list.html',
                                controller: 'ContentListCtrl'
                            }
                        },

                    })
                    .state('content.new', {
                        "url": '/content/new/',
                        "parent": 'main',
                        "views": {
                            'content': {
                                templateUrl: path + 'content/new.html',
                                controller: 'ContentNewCtrl'
                            }
                        }
                    })
                    .state('content.edit', {
                        "url": '/content/:id/edit/',
                        "parent": 'main',
                        "views": {
                            'content': {
                                templateUrl: path + 'content/edit.html',
                                controller: 'ContentEditCtrl'
                            }
                        }
                    })
                    .state('layout', {
                        "url": '/layout/:pageId?sort&order_by',
                        "parent": 'main',
                        "views": {
                            'content': {
                                templateUrl: path + 'layout/list.html',
                                controller: 'LayoutListCtrl'
                            }
                        }
                    })
                    .state('layout.new', {
                        "url": '/layout/new/',
                        "parent": 'main',
                        "views": {
                            'content': {
                                templateUrl: path + 'layout/new.html',
                                controller: 'LayoutNewCtrl'
                            }
                        }
                    })
                    .state('layout.edit',{
                        "url": '/layout/:id/edit/',
                        "parent": 'main',
                        "views": {
                            'content': {
                                templateUrl: path + 'layout/edit.html',
                                controller: 'LayoutEditCtrl'
                            }
                        }
                    })
                    .state('web_item.new', {
                        "url": '/web_item/new/',
                        "parent": 'main',
                        "views": {
                            'content': {
                                templateUrl: path + 'webitem/new.html',
                                controller: 'WebItemNewCtrl'
                            }
                        }
                    })
                    .state('web_item', {
                        "url": '/web_item/:pageId?sort&order_by',
                        "parent": 'main',
                        "views": {
                            'content': {
                                templateUrl: path + 'webitem/list.html',
                                controller: 'WebItemListCtrl'
                            }
                        }
                    })
                    .state('web_item.edit', {
                        "url": '/web_item/:id/edit/',
                        "parent": 'main',
                        "views": {
                            'content': {
                                templateUrl: path + 'webitem/edit.html',
                                controller: 'WebItemEditCtrl'
                            }
                        }
                    })
                    .state('menu', {
                        "url": '/menu/:pageId?sort&order_by',
                        "parent": 'main',
                        "views": {
                            'content': {
                                templateUrl: path + 'menu/list.html',
                                controller: 'MenuListCtrl'
                            }
                        }
                    })
                    .state('menu.new', {
                        "url": '/menu/new/',
                        "parent": 'main',
                        "views": {
                            'content': {
                                templateUrl: path + 'menu/new.html',
                                controller: 'MenuNewCtrl'
                            }
                        }
                    })
                    .state('menu.edit',{
                        "url": '/menu/:id/edit/',
                        "parent": 'main',
                        "views": {
                            'content': {
                                templateUrl: path + 'menu/edit.html',
                                controller: 'MenuEditCtrl'
                            }
                        }
                    })
                    .state('login', {
                        "url": '/login',
                        "views": {
                            '@':{
                                templateUrl: path + 'security/login.html',
                                controller: 'LoginCtrl'
                            }
                        },
                        "data": {
                            'noLogin': true
                        }
                    });

                $httpProvider.interceptors.push('authInterceptor');
        }])
        .directive('tabPizone', TabPizone)
        .directive('buttonActivePizone', ButtonActivePizone)
        .directive('listButtonGroupPizone', ListButtonGroupPizone)
        .directive('perPagePizone', PerPagePizone)
        .directive('fieldSortPizone', FieldSortPizone)
        .factory('authInterceptor', AuthInterceptor)
        .run([
            '$rootScope', '$state', '$stateParams', 'SessionService',
            function ($rootScope, $state, $stateParams, SessionService) {
                $rootScope.$state = $state;
                $rootScope.$stateParams = $stateParams;

                $rootScope.user = null;

                // Здесь мы будем проверять авторизацию
                $rootScope.$on('$stateChangeStart',
                    function (event, toState, toParams, fromState, fromParams) {
                        SessionService.CheckAccess(event, toState, toParams, fromState, fromParams);
                    }
                );
            }
        ])
    ;
})();

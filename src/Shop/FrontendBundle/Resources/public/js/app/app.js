(function () {
    'use strict';
    var path = ConfigPiZone.pathToTmpl;
    angular.module('shop', ['ui.router', 'ngCookies', 'ngStorage', 'ngSanitize', 'PiZoneControllers'])
        .config(['$stateProvider', '$urlRouterProvider', '$httpProvider', function ($stateProvider, $urlRouterProvider, $httpProvider) {
            //$urlRouterProvider
            //    .otherwise('/');

            $stateProvider
                //.state('main', {
                //    abstract: true,
                //    url: "",
                //    views: {
                //        '@': {
                //            templateUrl: '/admin/tmpl/content.html',
                //            controller: 'MainCtrl'
                //        }
                //    },
                //
                //})
                .state('cart', {
                    url: '/cart/',
                    views: {
                        'cart': {
                            templateUrl: path + 'cart/index.html',
                            controller: 'CartCtrl'
                        }
                    }
                })
                .state('order', {
                    url: '/order/',
                    params: {
                        cart: {}
                    },
                    views: {
                        'cart': {
                            templateUrl: path + 'order/index.html',
                            controller: 'OrderCtrl'
                        }
                    }
                })
                .state('orderCreated', {
                    url: '/order/created/',
                    params: {
                        order: {}
                    },
                    views: {
                        'cart': {
                            templateUrl: path + 'order/created.html',
                            controller: 'OrderCreatedCtrl'
                        }
                    }
                })
        }]);
})();

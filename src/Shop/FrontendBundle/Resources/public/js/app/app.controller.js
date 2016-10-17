(function() {
    'use strict';
    angular.module('PiZoneControllers', [])
        .controller('MainCtrl', MainCtrl)
        .controller('CartCtrl', CartCtrl)
        .controller('CartInfoCtrl', CartInfoCtrl)
        .controller('CartInfoBottomCtrl', CartInfoBottomCtrl)
        .controller('OrderCtrl', OrderCtrl)
        .controller('OrderCreatedCtrl', OrderCreatedCtrl)
        .controller('GoodsCtrl', GoodsCtrl)
        .controller('CatalogCtrl', CatalogCtrl)
        .controller('GoodsListCtrl', GoodsListCtrl)
    ;
})();
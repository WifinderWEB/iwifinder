(function() {
    'use strict';
    angular.module('PiZoneControllers', [])
        .controller('MainCtrl', MainCtrl)
        .controller('BreadcrumbsCtrl', BreadcrumbsCtrl)
        .controller('UserInfoCtrl', UserInfoCtrl)
        .controller('LoginCtrl', LoginCtrl)
        .controller('ModalCtrl', ModalCtrl)
        .controller('FiltersCtrl', FiltersCtrl)
        .controller('LayoutListCtrl', LayoutListCtrl)
        .controller('LayoutNewCtrl', LayoutNewCtrl)
        .controller('LayoutEditCtrl', LayoutEditCtrl)
        .controller('ContentListCtrl', ContentListCtrl)
        .controller('ContentNewCtrl', ContentNewCtrl)
        .controller('ContentEditCtrl', ContentEditCtrl)
        .controller('WebItemListCtrl', WebItemListCtrl)
        .controller('WebItemNewCtrl', WebItemNewCtrl)
        .controller('WebItemEditCtrl', WebItemEditCtrl)
        .controller('MenuListCtrl', MenuListCtrl)
        .controller('MenuNewCtrl', MenuNewCtrl)
        .controller('MenuEditCtrl', MenuEditCtrl)
        ;
})();
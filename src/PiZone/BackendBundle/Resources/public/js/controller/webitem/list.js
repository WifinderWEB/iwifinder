function WebItemListCtrl($scope, $http, $stateParams, ModalService){
    angular.extend($scope, new ListCtrl($scope, $http, $stateParams, ModalService));
    $scope.prefix = 'web_item';
    $scope.model = WebItem;
    $scope.breadcrumbs = [
        {
            title: 'Элементы'
        }
    ];
    $scope.Init();
}
WebItemListCtrl.$inject = ['$scope', '$http', '$stateParams', 'ModalService'];
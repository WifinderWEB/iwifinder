function MenuListCtrl($scope, $http, $stateParams, ModalService){
    angular.extend($scope, new ListCtrl($scope, $http, $stateParams, ModalService));
    $scope.prefix = 'menu';
    $scope.model = Menu;
    $scope.breadcrumbs = [
        {
            title: 'Меню'
        }
    ];
    $scope.Init();
}
MenuListCtrl.$inject = ['$scope', '$http', '$stateParams', 'ModalService'];
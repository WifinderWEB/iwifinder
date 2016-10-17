function LayoutListCtrl($scope, $http, $stateParams, ModalService){
    angular.extend($scope, new ListCtrl($scope, $http, $stateParams, ModalService));
    $scope.prefix = 'layout';
    $scope.model = Layout;
    $scope.breadcrumbs = [
        {
            title: 'Шаблоны'
        }
    ];
    $scope.Init();
}
LayoutListCtrl.$inject = ['$scope', '$http', '$stateParams', 'ModalService'];
function ContentListCtrl($scope, $http, $stateParams, ModalService){
    angular.extend($scope, new ListCtrl($scope, $http, $stateParams, ModalService));
    $scope.prefix = 'content';
    $scope.model = Content;
    $scope.breadcrumbs = [
        {
            title: 'Контент'
        }
    ];
    $scope.Init();
}
ContentListCtrl.$inject = ['$scope', '$http', '$stateParams', 'ModalService'];
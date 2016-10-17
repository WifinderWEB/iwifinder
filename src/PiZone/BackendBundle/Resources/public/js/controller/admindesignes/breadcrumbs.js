function BreadcrumbsCtrl($scope){
    $scope.items = [];

    function Init(){
        EventDispatcher.AddEventListener('change-route', function(data){
            $scope.items = data;
        })
    }

    Init();
}
BreadcrumbsCtrl.$inject = ['$scope'];
function CartInfoBottomCtrl($scope){
    $scope.count = 0;

    function Init(){
        EventDispatcher.AddEventListener('update-cart-bottom', function(data){
            $scope.count = data.count;
        });
    }

    Init();
}
CartInfoBottomCtrl.$inject = ['$scope'];
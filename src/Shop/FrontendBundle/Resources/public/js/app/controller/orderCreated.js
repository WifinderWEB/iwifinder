function OrderCreatedCtrl($scope, $stateParams) {
    $scope.orderCreatedModalId = 'orderCreatedModal';
    $scope.orderId = $stateParams.order;

    $scope.backToHome = backToHome;
    $scope.closeOrderCreated = closeOrderCreated;
    $scope.backToCatalog = backToCatalog;

    function backToHome(){
        $('body').removeClass('modal-open');
        window.location.href = '/';
    }

    function backToCatalog(){
        $('body').removeClass('modal-open');
        window.location.href = '/catalog/';
    }

    function closeOrderCreated(){
        $('body').removeClass('modal-open');
        window.location.hash = '/';
    }

    $('body').addClass('modal-open');
}
OrderCreatedCtrl.$inject = ['$scope', '$stateParams'];
function ModalCtrl($scope, title, message, $sce, close){
    $scope.title = title;
    $scope.message = message;
    $scope.info = '';
    $scope.infoBlockId = 'infoBlockId';
    $scope.Click = {
        Confirm: function(){
            $.magnificPopup.close();
            $scope.Callback.Confirm();
            close(null, 500);
        },
        Cancel: function(){
            $.magnificPopup.close();
            close(null, 500);
        }
    }
    $scope.Callback = {
        Confirm: function(){},
        Init: function(){}
    };

    function Init(){
        $scope.Callback.Init();
    }

    $sce.trustAsHtml($scope.title)
    $sce.trustAsHtml($scope.message);
    $sce.trustAsHtml($scope.info);

    Init();
}
ModalCtrl.$inject = ['$scope', 'title', 'message', '$sce', 'close'];
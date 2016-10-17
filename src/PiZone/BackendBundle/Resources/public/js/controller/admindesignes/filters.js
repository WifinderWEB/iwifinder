function FiltersCtrl($scope, title, fields, $sce, close){
    $scope.title = title;
    $scope.fields = fields;
    $scope.Click = {
        Submit: function(){
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

    Init();
}
FiltersCtrl.$inject = ['$scope', 'title', 'fields', '$sce', 'close'];
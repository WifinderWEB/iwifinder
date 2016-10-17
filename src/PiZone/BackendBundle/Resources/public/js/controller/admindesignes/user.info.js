function UserInfoCtrl($scope, SessionService){
    $scope.logoutButtonId = 'logoutButtonId';
    $scope.Click = {
        Logout: Logout
    }

    function Logout(){
        var container = $('#' + $scope.logoutButtonId);
        var loader = new Loader(container);
        $.ajax({
            url: ConfigPiZone.apiPrefix + '/logout',
            type: 'post',
            dataType: 'json',
            beforeSend: function(){
                if(loader.ready == false)
                    loader.Create();
            },
            error: function(message) {
                PiZoneException.AjaxError(message, loader)
            },
            success: function(message){
                loader.Delete();
                if(message.result == 'ok') {
                    SessionService.ClearCredentials();
                    $scope.$state.go('login');
                }
                else{

                }

            }
        })
    }
}
UserInfoCtrl.$inject = ['$scope', 'SessionService'];
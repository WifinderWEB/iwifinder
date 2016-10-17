function LoginCtrl($scope, SessionService, $sessionStorage){
    $scope.username = null;
    $scope.password = null;
    $scope.remember = null;
    $scope.formId = 'loginForm';
    $scope.Click = {
        Submit: Submit,
        ForgotPassword: ForgotPassword
    }

    function Init(){
        if($sessionStorage.isAuthorized)
            $scope.$state.go('content');
        $('body').attr('class', 'external-page external-alt sb-l-c sb-r-c');
    }

    function Submit(){
        var container = $('#' + $scope.formId);
        var loader = new Loader(container);
        $.ajax({
            url: ConfigPiZone.apiPrefix + '/login',
            type: 'post',
            data: getValues(),
            dataType: 'json',
            beforeSend: function(){
                if(loader.ready == false)
                    loader.Create();
            },
            error: function(message) {
                PiZoneException.AjaxError(message, loader)
                loader.Delete();
            },
            success: function(message){
                if(message.result == 'ok') {
                    SessionService.SetCredentials($scope.username, $scope.password);
                    $scope.$state.go('content');
                }
                else{
                    loader.Delete();
                }
            }
        })
    }

    function getValues(){
        return $('#' + $scope.formId).serialize();
    }

    function ForgotPassword(){

    }

    Init();
}
LoginCtrl.$inject = ['$scope', 'SessionService', '$sessionStorage'];
function NewCtrl($scope, $http){
    $scope.prefix = '';
    $scope.fields = {};
    $scope.tabs = {};
    $scope.action = null;
    $scope.formId = '';
    $scope.pathToTmpl = ConfigPiZone.pathToTmpl;
    $scope.breadcrumbs = [];
    $scope.messages = {
        submit: {
            success: {
                title: 'Сохранение',
                message: 'Успешно создан новый контент'
            },
            danger: {
                title: 'Ошибка',
                message: 'Исправте ошибки возникшие при заполнении формы и попробуйте сохранить её еще раз'
            }
        }
    }
    $scope.Click = {
        ToList: ToList,
        Submit: Submit
    }
    $scope.Init = Init;
    $scope.Get = {
        Tabs: GetTabs,
        Fields: GetFields,
        Values: GetValues,
        Layout: GetLayout
    }
    $scope.Callback = {
        Init: function(){},
        Success: CallbackSuccess,
        Error: CallbackError
    }
    $scope.ParseData = ParseData;
    $scope.ToList = ToList;

    function Init(){
        $scope.Get.Layout(function(){
            $scope.Callback.Init();
            EventDispatcher.DispatchEvent('change-route', $scope.breadcrumbs);
        });
    }

    function ToList(){
        window.location.hash = '/' + $scope.prefix +'/';
    }

    function Submit(){
        var container = $('#' + $scope.formId);
        var loader = new Loader(container);
        $.ajax({
            url: $scope.action,
            type: 'post',
            data: $scope.Get.Values(),
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
                    $scope.Callback.Success(message);
                    ViewNotify('submit', 'success');
                }
                else{
                    $scope.Callback.Error(message);
                    ViewNotify('submit', 'danger');
                }

            }
        })
    }

    function ViewNotify(action, res){
        new Notify({
            type: res,
            title: $scope.messages[action][res].title,
            text:  $scope.messages[action][res].message
        });
    }

    function ParseData(message){
        var data = DataProcessing.Normalize(message.fields);
        $scope.Get.Tabs(data);
        $scope.Get.Fields(data);
        $scope.action = message.action;
    }

    function GetValues(){
        return $('#' + $scope.formId).serialize();
    }

    function GetLayout(callback){
        $http.get(ConfigPiZone.apiPrefix + '/' + $scope.prefix + '/new').success(function(message) {
            $scope.ParseData(message);
            if(callback)
                callback();
        });
    }

    function GetFields(data){
        $scope.fields = []
    }

    function GetTabs(data){
        $scope.tabs = []
    }

    function CallbackSuccess(message){
        $scope.ToList();
    }

    function CallbackError(message){
        $scope.ParseData(message);
        $scope.$apply();
    }
}
NewCtrl.$inject = ['$scope', '$http'];
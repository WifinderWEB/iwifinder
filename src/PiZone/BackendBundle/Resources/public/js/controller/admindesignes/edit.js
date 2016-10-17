function EditCtrl($scope, $http, $stateParams, ModalService){
    $scope.id = $stateParams.id;
    $scope.prefix = '';
    $scope.title = null;
    $scope._delete_token = '';
    $scope.fields = {};
    $scope.tabs = {};
    $scope.action = null;
    $scope.formId = '';
    $scope.pathToTmpl = ConfigPiZone.pathToTmpl;
    $scope.Click = {
        ToList: ToList,
        Submit: Submit,
        Delete: Delete
    }
    $scope.breadcrumbs = [];
    $scope.messages = {
        submit: {
            success: {
                title: 'Обновление',
                message: 'Контент успешно обновлен'
            },
            danger: {
                title: 'Ошибка обновления',
                message: 'Исправте ошибки возникшие при заполнении формы и попробуйте сохранить её еще раз'
            }
        },
        remove: {
            confirm: {
                title: "Удаление элемента",
                message: "Вы уверенны, что хотите удалить элемент?",
                info: ""
            },
            success: {
                title: 'Удаление',
                message: 'Элемент успешно удален'
            },
            danger: {
                title: 'Ошибка удаления',
                message: 'Возникла ошибка при удалении элемента.'
            }
        }
    }
    $scope.Get = {
        Tabs: GetTabs,
        Fields: GetFields,
        Values: GetValues,
        Layout: GetLayout
    }
    $scope.Callback = {
        Init: function(){},
        Submit: {
            Success: CallbackSuccess,
            Error: CallbackError,
        },
        Delete: {
            Before: function(){},
            Success: function(){$scope.$state.go($scope.prefix);},
            Error: function(){}
        }
    }
    $scope.ParseData = ParseData;
    $scope.ToList = ToList;
    $scope.Init = Init;

    function Init(){
        $scope.Get.Layout(function(){
            $scope.Callback.Init();
            EventDispatcher.DispatchEvent('change-route', $scope.breadcrumbs);
        });
    }

    function ToList(){
        window.location.hash = '/' + $scope.prefix + '/';
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
            error: function(message){
                PiZoneException.AjaxError(message, loader)
            },
            success: function(message){
                loader.Delete();
                if (message.result == 'ok') {
                    $scope.Callback.Submit.Success(message);
                    ViewNotify('submit', 'success');
                }
                else {
                    $scope.Callback.Submit.Error(message);
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

    function Delete(){
        ModalService.showModal({
            templateUrl: ConfigPiZone.pathToTmpl + 'admindesignes/confirm.html',
            controller: "ModalCtrl",
            id: "modalConfirm",
            inputs: {
                title: $scope.messages.remove.confirm.title,
                message: $scope.messages.remove.confirm.message
            }
        }).then(function(modal) {
            modal.scope.Callback.Confirm = ConfirmDelete;
            $scope.Callback.Delete.Before(modal.scope);
        });
    }

    function ConfirmDelete() {
        var url = ConfigPiZone.apiPrefix + '/' + $scope.prefix + '/' + $scope.id + '/delete';
        $.ajax({
            url: url,
            dataType: 'json',
            type: 'post',
            data: {_token: $scope._delete_token},
            error: function (jqXHR) {
                if (jqXHR.status == 404)
                    $scope.messages.remove.danger.message = 'Элемент с [id] = ' + $scope.list[i].id + ' не существует!';
                if (jqXHR.status == 400)
                    $scope.messages.remove.danger.message = 'Неверные параметры запроса!';
                if (jqXHR.status == 500)
                    $scope.messages.remove.danger.message = 'Возникла непредвиденная ошибка сервера!';
                $scope.Callback.Delete.Error();
                ViewNotify('delete', 'danger');
            },
            success: function (message) {
                if (message.result == 'ok') {
                    $scope.Callback.Delete.Success();
                    ViewNotify('delete', 'success');
                }
                else {
                    $scope.Callback.Delete.Error();
                    ViewNotify('delete', 'danger');
                }

                $scope.$apply();
            }
        })
    }

    function GetValues(){
        return $('#' + $scope.formId).serialize();
    }

    function GetLayout(callback){
        $http.get(ConfigPiZone.apiPrefix + '/' + $scope.prefix + '/' + $scope.id + '/edit').success(function(message) {
            $scope.ParseData(message);
            if(callback)
                callback();
        });
    }

    function ApplyEditor(data){
        if(data.show_editor_anons.checked) {
            data.anons.type = 'editor';
            data.anons.editorConfig = ConfigPiZone.editorFull;

            //data.anons.imageUpload = function(files, editor){
            //    $.each(files, function(i){
            //        sendFile(files[i], editor);
            //    });
            //}
        }
        if(data.show_editor_content.checked) {
            data.content.type = 'editor';
            data.content.editorConfig = ConfigPiZone.editorFull;
        }

        return data;
    }

    //function SendFile(file) {
    //    editor = $.summernote.eventHandler.getEditor();
    //    console.log(editor);
    //    var data = new FormData();
    //    data.append("file", file);
    //    var url = ConfigPiZone.apiPrefix + "/api/upload_file";
    //    $.ajax({
    //        data: data,
    //        type: "POST",
    //        url: url,
    //        cache: false,
    //        contentType: false,
    //        processData: false,
    //        success: function (message) {
    //            if(message.result == 'ok')
    //                editor.insertImage($scope.fields.anons.editable, message.path);
    //        }
    //    });
    //}

    function ParseData(message){
        var data = DataProcessing.Normalize(message.fields);
        $scope.Get.Tabs(data);
        $scope.Get.Fields(data);
        $scope.action = message.action;
        $scope._delete_token = message._delete_token;
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
EditCtrl.$inject = ['$scope', '$http', '$stateParams', 'ModalService'];
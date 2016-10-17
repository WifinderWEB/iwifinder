function LayoutEditCtrl($scope, $http, $stateParams, ModalService){
    angular.extend($scope, new EditCtrl($scope, $http, $stateParams, ModalService));
    $scope.prefix = 'layout';
    $scope.formId = 'editLayoutForm';

    $scope.Get.Fields = GetFields;
    $scope.Callback.Init = CallbackInit;
    $scope.Callback.Delete.Before = CallbackBeforeDelete;

    function CallbackInit(){
        $scope.messages.submit.success.title = 'Шаблон успешно изменен';
        $scope.messages.submit.success.message = 'Успешно изменен шаблон "' + $scope.title + '"';
        $scope.messages.submit.danger.title = 'Ошибка редактирования шаблона';
        $scope.messages.remove.confirm.title = "Удаление шаблона";
        $scope.messages.remove.confirm.message = '<h3 class="mt5">Вы уверены, что хотите удалить шаблон?</h3>' +
            '<p>Шаблон "' + $scope.title + '" будет полностью удален из системы. ' +
            'Совершайте данное действие только в полной уверенности, что это не приведет к ошибкам в отображении страниц!</p>'
        $scope.breadcrumbs = [
            {
                title: 'Шаблоны',
                link: '#/layout/'
            },
            {
                title: 'Редактировать шаблон "' + $scope.title + '"'
            }
        ];
    }

    function CallbackBeforeDelete(scopeModal){
        var container = $('#' + $scope.infoBlockId);
        var loader = new Loader(container);
        $.ajax({
            url: ConfigPiZone.apiPrefix + '/layout/' + $scope.id + '/checkuse',
            dataType: 'json',
            type: 'post',
            beforeSend: function(){
                if(loader.ready == false)
                    loader.Create();
            },
            error: function(message){
                loader.Delete();
                PiZoneException.AjaxError(message, loader)
            },
            success: function(message){
                loader.Delete();
                if(message.result == 'ok' && message.list.length > 0){
                    var ul = '<p>Страницы использующие этот шаблон:</p><ul class="pl15">';
                    $.each(message.list, function(i, one){
                        ul = ul + '<li><a href="#/content/' + one.id + '/edit/" target="_blank">[' + one.id + '] ' + one.title + '</a></li>';
                    });
                    ul = ul + '</ul>';
                    scopeModal.info = ul;
                    scopeModal.$apply();
                }
            }
        })
    }

    function GetFields(data){
        $scope.title = data.name.value;
        $scope.fields = [
            data._token,
            data.is_active,
            data.name,
            data.description,
            data.file
        ]
    }

    $scope.Init();
}
LayoutEditCtrl.$inject = ['$scope', '$http', '$stateParams', 'ModalService'];
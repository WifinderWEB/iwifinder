function MenuEditCtrl($scope, $http, $stateParams, ModalService){
    angular.extend($scope, new EditCtrl($scope, $http, $stateParams, ModalService));
    $scope.prefix = 'menu';
    $scope.formId = 'editMenuForm';

    $scope.Get.Fields = GetFields;
    $scope.Callback.Init = CallbackInit;

    function CallbackInit(){
        $scope.messages.submit.success.title = 'Меню успешно изменено';
        $scope.messages.submit.success.message = 'Успешно изменено меню "' + $scope.title + '"';
        $scope.messages.submit.danger.title = 'Ошибка редактирования меню';
        $scope.messages.remove.confirm.title = "Удаление меню";
        $scope.messages.remove.confirm.message = '<h3 class="mt5">Вы уверены, что хотите удалить меню?</h3>' +
            '<p>Меню "' + $scope.title + '" будет полностью удалено из системы. ' +
            'Совершайте данное действие только в полной уверенности, что это не приведет к ошибкам в отображении страниц!</p>'
        $scope.breadcrumbs = [
            {
                title: 'Меню',
                link: '#/menu/'
            },
            {
                title: 'Редактировать меню "' + $scope.title + '"'
            }
        ];
    }

    function GetFields(data){
        $scope.title = data.name.value;
        $scope.fields = [
            data._token,
            data.is_active,
            data.name,
            data.alias
        ]
    }

    $scope.Init();
}
MenuEditCtrl.$inject = ['$scope', '$http', '$stateParams', 'ModalService'];
function WebItemEditCtrl($scope, $http, $stateParams, ModalService){
    angular.extend($scope, new EditCtrl($scope, $http, $stateParams, ModalService));
    $scope.prefix = 'web_item';
    $scope.formId = 'editWebItemForm';
    $scope.pathToTmpl = ConfigPiZone.pathToTmpl;

    $scope.Get.Tabs = GetTabs;
    $scope.Callback.Init = CallbackInit;

    function CallbackInit(){
        $scope.messages.submit.success.title = 'Элемент успешно изменен';
        $scope.messages.submit.success.message = 'Успешно изменен элемент "' + $scope.title + '"';
        $scope.messages.submit.danger.title = 'Ошибка редактирования элемента';
        $scope.breadcrumbs = [
            {
                title: 'Элементы',
                link: '#/web_item/'
            },
            {
                title: 'Редактировать элемент - "' + $scope.title + '"'
            }
        ];
    }

    function GetTabs(data){
        $scope.title = data.alias.value;
        $scope.tabs = [
            {
                title: 'Элемент',
                fields: [
                    data._token,
                    data.is_active,
                    data.alias,
                    data.description
                ]
            },
            {
                title: 'Контент',
                fields: [
                    data.content
                ]
            }
        ]
    }

    $scope.Init();
}
WebItemEditCtrl.$inject = ['$scope', '$http', '$stateParams', 'ModalService'];
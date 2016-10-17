function WebItemNewCtrl($scope, $http){
    angular.extend($scope, new NewCtrl($scope, $http));
    $scope.prefix = 'web_item';
    $scope.formId = 'newWebItemForm';
    $scope.breadcrumbs = [
        {
            title: 'Элемент',
            link: '#/web_item/'
        },
        {
            title: 'Новый элемент'
        }
    ];
    $scope.messages.submit.success.title = 'Новый элемент';
    $scope.messages.submit.danger.title = 'Ошибка создания элемента';
    $scope.Get.Tabs = GetTabs;
    $scope.Callback.Success = CallbackSuccess;

    function CallbackSuccess(){
        $scope.messages.submit.success.message = 'Успешно создан новы элемент "' + $scope.tabs[0].fields[2].value + '"';
        $scope.ToList();
    }
    function GetTabs(data) {
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
WebItemNewCtrl.$inject = ['$scope', '$http'];
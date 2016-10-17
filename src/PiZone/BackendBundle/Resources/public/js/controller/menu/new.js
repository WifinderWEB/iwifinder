function MenuNewCtrl($scope, $http){
    angular.extend($scope, new NewCtrl($scope, $http));
    $scope.prefix = 'menu';
    $scope.formId = 'newMenuForm';
    $scope.breadcrumbs = [
        {
            title: 'Меню',
            link: '#/menu/'
        },
        {
            title: 'Новое меню'
        }
    ];
    $scope.messages.submit.success.title = 'Новое меню';
    $scope.messages.submit.danger.title = 'Ошибка создания меню';
    $scope.Get.Fields = GetFields;
    $scope.Callback.Success = CallbackSuccess;

    function CallbackSuccess(){
        $scope.messages.submit.success.message = 'Успешно создано новое меню "' + $scope.fields[2].value + '"';
        $scope.ToList();
    }

    function GetFields(data){
        $scope.fields = [
            data._token,
            data.is_active,
            data.name,
            data.alias,
            data.file
        ]
    }

    $scope.Init();
}
MenuNewCtrl.$inject = ['$scope', '$http'];
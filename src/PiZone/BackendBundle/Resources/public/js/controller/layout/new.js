function LayoutNewCtrl($scope, $http){
    angular.extend($scope, new NewCtrl($scope, $http));
    $scope.prefix = 'layout';
    $scope.formId = 'newLayoutForm';
    $scope.breadcrumbs = [
        {
            title: 'Шаблоны',
            link: '#/layout/'
        },
        {
            title: 'Новый шаблон'
        }
    ];
    $scope.messages.submit.success.title = 'Новый шаблон';
    $scope.messages.submit.danger.title = 'Ошибка создания шаблона';
    $scope.Get.Fields = GetFields;
    $scope.Callback.Success = CallbackSuccess;

    function CallbackSuccess(){
        $scope.messages.submit.success.message = 'Успешно создан новы шаблон "' + $scope.fields[2].value + '"';
        $scope.ToList();
    }

    function GetFields(data){
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
LayoutNewCtrl.$inject = ['$scope', '$http'];
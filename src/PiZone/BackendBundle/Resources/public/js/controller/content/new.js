function ContentNewCtrl($scope, $http){
    angular.extend($scope, new NewCtrl($scope, $http));
    $scope.prefix = 'content';
    $scope.formId = 'newContentForm';
    $scope.breadcrumbs = [
        {
            title: 'Контент',
            link: '#/content/'
        },
        {
            title: 'Новый контент'
        }
    ];
    $scope.messages.submit.success.title = 'Новый контент';
    $scope.messages.submit.danger.title = 'Ошибка создания контента';
    $scope.Get.Tabs = GetTabs;
    $scope.Callback.Success = CallbackSuccess;

    function CallbackSuccess(){
        $scope.messages.submit.success.message = 'Успешно создан новы контент "' + $scope.tabs[0].fields[2].value + '"';
        $scope.ToList();
    }

    function GetTabs(data){
        $scope.tabs = [
            {
                title: 'Элемент',
                fields: [
                    data._token,
                    data.is_active,
                    data.title,
                    data.alias,
                    data.layout
                ]
            },
            {
                title: 'Анонс',
                fields: [
                    data.anons,
                    data.show_editor_anons
                ]
            },
            {
                title: 'Подробно',
                fields: [
                    data.content,
                    data.show_editor_content
                ]
            },
            {
                title: 'SEO',
                fields: [
                    data.meta.form.meta_title,
                    data.meta.form.meta_keywords,
                    data.meta.form.meta_description,
                    data.meta.form.more_scripts,
                    data.meta.form.in_breadcrumbs,
                    data.meta.form.in_site_map,
                    data.meta.form.in_robots
                ]
            }
        ]
    }

    $scope.Init();
}
ContentNewCtrl.$inject = ['$scope', '$http'];
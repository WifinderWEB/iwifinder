function ContentEditCtrl($scope, $http, $stateParams, ModalService){
    angular.extend($scope, new EditCtrl($scope, $http, $stateParams, ModalService));
    $scope.prefix = 'content';
    $scope.formId = 'editContentForm';
    $scope.Get.Tabs = GetTabs;
    $scope.Callback.Init = CallbackInit;
    $scope.Callback.Success = CallbackSuccess;

    function CallbackSuccess(){
        $scope.messages.submit.success.message = 'Успешно изменен контент "' + $scope.title + '"';
        $scope.ToList();
    }

    function Watch(){
        $scope.$watch(
            function() {
                return $scope.tabs[1].fields[1].checked;
            },
            function(newValue, oldValue) {
                if (newValue)
                    $scope.tabs[1].fields[0].type = 'editor';
                else
                    $scope.tabs[1].fields[0].type = 'textarea';
                $scope.tabs[1].fields[0].template = FieldDispatcher.GetLayout($scope.tabs[1].fields[0].type);
            }
        );

        $scope.$watch(
            function() {
                return $scope.tabs[2].fields[1].checked;
            },
            function(newValue, oldValue) {
                if (newValue)
                    $scope.tabs[2].fields[0].type = 'editor';
                else
                    $scope.tabs[2].fields[0].type = 'textarea';
                $scope.tabs[2].fields[0].template = FieldDispatcher.GetLayout($scope.tabs[2].fields[0].type);
            }
        );
    }

    function CallbackInit(){
        $scope.messages.submit.success.title = 'Контент успешно изменен';
        $scope.messages.submit.success.message = 'Успешно изменен контент "' + $scope.title + '"';
        $scope.messages.submit.danger.title = 'Ошибка редактирования контента';
        $scope.messages.remove.confirm.title = "Удаление контента";
        $scope.messages.remove.confirm.message = '<h3 class="mt5">Вы уверены, что хотите удалить контент?</h3>' +
            '<p>Контент "' + $scope.title + '" будет полностью удален из системы. ' +
            'Совершайте данное действие только в полной уверенности, что это не приведет к ошибкам в отображении страниц!</p>'

        $scope.breadcrumbs = [
            {
                title: 'Контент',
                link: '#/content/'
            },
            {
                title: 'Редактировать контент - "' + $scope.title + '"'
            }
        ];
        Watch();
    }

    function GetTabs(data){
        $scope.title = data.title.value;
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
ContentEditCtrl.$inject = ['$scope', '$http', '$stateParams', 'ModalService'];
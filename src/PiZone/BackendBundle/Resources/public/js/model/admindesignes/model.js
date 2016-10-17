function Model($scope, i, obj, ModalService){
    var self = this;
    self.prefix = '';
    self.isActive = null;
    self._token = null;
    self.Click = {
        Active: Active
    }
    self.messages = {
        active: {
            success: {
                title: 'Активация',
                message: 'Элемент активирован'
            },
            warning: {
                title: 'Деактивация',
                message: 'Элемент деактивирован'
            },
            danger: {
                title: 'Ошибка активации',
                message: 'Элемент не был активирован.'
            },
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
    };
    self.Callback = {
        Active: {
            Success: function(){},
            Warning: function(){},
            Error: function(){}
        },
        Delete: {
            Before: function(){},
            Success: function(){},
            Error: function(){}
        }
    }
    self.Delete = Delete;

    function Active(){
        var url = ConfigPiZone.apiPrefix + '/' + $scope.list[i].prefix + '/' + $scope.list[i].id + '/set/' + ($scope.list[i].isActive ? 'inactive' : 'active');
        $.ajax({
            url: url,
            dataType: 'json',
            success: function (message) {
                if (message.hasOwnProperty('active'))
                    $scope.list[i].isActive = message.active;
                if (message.result == 'ok') {
                    if ($scope.list[i].isActive) {
                        $scope.list[i].Callback.Active.Success(message);
                        ViewNotify('active', 'success');
                    }
                    else {
                        $scope.list[i].Callback.Active.Warning(message);
                        ViewNotify('active', 'warning');
                    }
                }
                else {
                    $scope.list[i].Callback.Active.Error(message);
                    ViewNotify('active', 'danger');
                }
                $scope.$apply();
            }
        })
    }

    function Delete(){
        ModalService.showModal({
            templateUrl: ConfigPiZone.pathToTmpl + 'admindesignes/confirm.html',
            controller: "ModalCtrl",
            id: "modalConfirm",
            inputs: {
                title: self.messages.remove.confirm.title,
                message: self.messages.remove.confirm.message
            }
        }).then(function(modal) {
            modal.scope.Callback.Confirm = ConfirmDelete;
            self.Callback.Delete.Before(modal.scope);
        });
    }

    function ConfirmDelete() {
        var url = ConfigPiZone.apiPrefix + '/' + $scope.list[i].prefix + '/' + $scope.list[i].id + '/delete';
        $.ajax({
            url: url,
            dataType: 'json',
            type: 'post',
            data: {_token: $scope.list[i]._token},
            error: function (jqXHR) {
                if (jqXHR.status == 404)
                    $scope.list[i].message.remove.danger.message = 'Элемент с [id] = ' + $scope.list[i].id + ' не существует!';
                if (jqXHR.status == 400)
                    $scope.list[i].message.remove.danger.message = 'Неверные параметры запроса!';
                if (jqXHR.status == 500)
                    $scope.list[i].message.remove.danger.message = 'Возникла непредвиденная ошибка сервера!';
                self.Callback.Delete.Error();
                ViewNotify('remove', 'danger');
            },
            success: function (message) {
                if (message.result == 'ok') {
                    ViewNotify('remove', 'success');
                    self.Callback.Delete.Success();
                    EventDispatcher.DispatchEvent('update_page_' + $scope.list[i].prefix);
                }
                else {
                    self.Callback.Delete.Error();
                    ViewNotify('remove', 'danger');
                }

                $scope.$apply();
            }
        })
    }

    function ViewNotify(action, res){
        new Notify({
            type: res,
            title: $scope.list[i].messages[action][res].title,
            text:  $scope.list[i].messages[action][res].message
        });
    }
}
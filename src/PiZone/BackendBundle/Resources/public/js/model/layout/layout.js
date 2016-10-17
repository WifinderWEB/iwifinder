function Layout($scope, i, data, ModalService){
    angular.extend(this, new Model($scope, i, data, ModalService));
    var self = this;
    self.prefix = 'layout';
    self.id = data.id;
    self.name = data.name;
    self.description = data.description;
    self.isActive = data.isActive;
    self._token = data._token;
    self.messages.active.success.title = 'Шаблон активирован';
    self.messages.active.success.message = 'Успешно активирован шаблон - "' + self.name + '"';
    self.messages.active.warning.title = 'Шаблон деактивирован';
    self.messages.active.warning.message = 'Успешно деактивирован шаблон - "' + self.name + '"';
    self.messages.remove.confirm.title = "Удаление шаблона";
    self.messages.remove.confirm.message = '<h3 class="mt5">Вы уверены, что хотите удалить шаблон?</h3>' +
        '<p>Шаблон "' + self.name + '" будет полностью удален из системы. ' +
        'Совершайте данное действие только в полной уверенности, что это не приведет к ошибкам в отображении страниц!</p>'
    self.Callback.Active.Error = function(data){
        self.messages.active.danger.message = 'Шаблон - "' + self.name + '" не был активирован.<br/><b>Ошибка:</b> ' + data.message + '.<br/>Обратитесь к администратору';
    }
    self.Callback.Delete.Before = function(scopeModal){
        var container = $('#' + $scope.infoBlockId);
        var loader = new Loader(container);
        $.ajax({
            url: ConfigPiZone.apiPrefix + '/layout/' + self.id + '/checkuse',
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
}
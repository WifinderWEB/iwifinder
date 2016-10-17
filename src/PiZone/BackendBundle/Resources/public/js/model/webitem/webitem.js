function WebItem($scope, i, data, ModalService){
    angular.extend(this, new Model($scope, i, data, ModalService));
    var self = this;
    self.prefix = 'web_item';
    self.id = data.id;
    self.alias = data.alias;
    self.description = data.description;
    self.content = data.content;
    self.isActive = data.isActive;
    self._token = data._token;
    self.messages.active.success.title = 'Элемент активирован';
    self.messages.active.success.message = 'Успешно активирован элемент - "' + self.alias + '"';
    self.messages.active.warning.title = 'Элемент деактивирован';
    self.messages.active.warning.message = 'Успешно деактивирован элемент - "' + self.alias + '"';
    self.messages.remove.confirm.message = '<h3 class="mt5">Вы уверены, что хотите удалить элемент?</h3>' +
        '<p>Элемент "' + self.alias + '" будет полностью удален из системы. ' +
        'Совершайте данное действие только в полной уверенности, что это не приведет к ошибкам в отображении страниц!</p>'
    self.Callback.Active.Error = function(data){
        self.messages.active.danger.message = 'Элемент - "' + self.alias + '" не был активирован.<br/><b>Ошибка:</b> ' + data.message + '.<br/>Обратитесь к администратору';
    }
}
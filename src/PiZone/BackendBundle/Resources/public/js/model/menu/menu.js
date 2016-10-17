function Menu($scope, i, data, ModalService){
    angular.extend(this, new Model($scope, i, data, ModalService));
    var self = this;
    self.prefix = 'menu';
    self.id = data.id;
    self.name = data.name;
    self.alias = data.alias;
    self.isActive = data.isActive;
    self._token = data._token;
    self.messages.active.success.title = 'Меню активировано';
    self.messages.active.success.message = 'Успешно активировано меню - "' + self.name + '"';
    self.messages.active.warning.title = 'Меню деактивировано';
    self.messages.active.warning.message = 'Успешно деактивировано меню - "' + self.name + '"';
    self.messages.remove.confirm.title = "Удаление меню";
    self.messages.remove.confirm.message = '<h3 class="mt5">Вы уверены, что хотите удалить меню?</h3>' +
        '<p>Меню "' + self.name + '" будет полностью удалено из системы. ' +
        'Совершайте данное действие только в полной уверенности, что это не приведет к ошибкам в отображении страниц!</p>'
    self.Callback.Active.Error = function(data){
        self.messages.active.danger.message = 'Меню - "' + self.name + '" не был активировано.<br/><b>Ошибка:</b> ' + data.message + '.<br/>Обратитесь к администратору';
    }
}
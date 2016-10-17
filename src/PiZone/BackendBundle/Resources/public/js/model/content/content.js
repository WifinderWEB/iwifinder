function Content($scope, i, data, ModalService){
    angular.extend(this, new Model($scope, i, data, ModalService));
    var self = this;
    self.prefix = 'content';
    self.id = data.id;
    self.title = data.title;
    self.alias = data.alias;
    self.anons = data.anons;
    self.showEditorAnons = data.show_editor_anons;
    self.content = data.content;
    self.showEditorContent = data.show_editor_content;
    self.meta = data.meta;
    self.isActive = data.isActive;
    self._token = data._token;
    self.messages.active.success.title = 'Контент активирован';
    self.messages.active.success.message = 'Успешно активирован контент - "' + self.title + '"';
    self.messages.active.warning.title = 'Контент деактивирован';
    self.messages.active.warning.message = 'Успешно деактивирован контент - "' + self.title + '"';
    self.Callback.Active.Error = function(data){
        self.messages.active.danger.message = 'Контент - "' + self.title + '" не был активирован.<br/><b>Ошибка:</b> ' + data.message + '.<br/>Обратитесь к администратору';
    }
}
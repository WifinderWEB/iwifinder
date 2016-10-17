var FieldDispatcher = {
    path: ConfigPiZone.pathToTmpl + 'form/',
    GetLayout: function(type){
        if(type == 'text')
            return this.path + '_text_field.html';
        if(type == 'textarea')
            return this.path + '_textarea_field.html';
        if(type == 'hidden')
            return this.path + '_hidden_field.html';
        if(type == 'checkbox')
            return this.path + '_checkbox_field.html';
        if(type == 'choice')
            return this.path + '_choice_field.html';
        if(type == 'editor')
            return this.path + '_editor_field.html';
    }
}
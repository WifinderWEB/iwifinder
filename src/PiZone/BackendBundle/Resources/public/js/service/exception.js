var PiZoneException = {
    AjaxError: function(message, loader, callback){
        if(loader)
            loader.Delete();
        if(message.hasOwnProperty('responseJSON')){
            new Notify({
                type: 'danger',
                title: 'Ошибка ' + message.responseJSON.code,
                text: message.responseJSON.message
            });
        }
        if(callback)
            callback();
    }
}
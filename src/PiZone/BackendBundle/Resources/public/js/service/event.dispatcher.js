var EventDispatcher = {
    events : [],

    AddEventListener : function (event, callback, widgetName) {
        if(!widgetName)
            widgetName = '';
        this.events[event] = this.events[event] || [];
        if (this.events[event]) {
            var callbackHash = EventDispatcher.HashCode(callback.toString() + widgetName);
            this.RemoveEventListener(event, callback, widgetName, callbackHash);
            this.events[event].push(
                {
                    hash: callbackHash,
                    callback : callback
                }
            );
        }
    },

    RemoveEventListener : function (event, callback, widgetName, callbackHash) {
        if (this.events[event]) {
            var listeners = this.events[event];
            for (var i = listeners.length - 1; i >= 0; --i) {
                if (listeners[i].hash === callbackHash) {
                    listeners.splice(i, 1);
                    return true;
                }
            }
        }
        return false;
    },

    DispatchEvent: function (event, data) {
        if (this.events[event]) {
            var listeners = this.events[event], len = listeners.length;
            while (len--) {
                listeners[len].callback(data);   //callback with self
            }
        }
    },

    HashCode : function(str){
        var hash = 0, i, ch;
        if (str.length === 0) return hash;
        for (i = 0; i < str.length; i++) {
            ch = str.charCodeAt(i);
            hash = ((hash<<5)-hash)+ch;
            hash = hash & hash; // Convert to 32bit integer
        }
        return hash;
    },
    GetUUID : function(){
        var t = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
            return v.toString(16);
        });
        return t;
    }
}
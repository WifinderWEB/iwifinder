var Loader = function(idContainer, style){
    var self = this;
    self.content = idContainer;
    self.imgSrc = ConfigPiZone.pathToImg + 'ajax-loader.gif';
    self.loader = null;
    self.width = 0;
    self.height = 0;
    self.ready = false;
    self.Create = function(){
        self.ready = true;
        self.width = self.content.width() ? self.content.width() : '100%';
        self.height = self.content.height() ? self.content.height() : 32;
        self.content.hide();
        self.loader = self.GetHtmlLoader();
        self.content.after(self.loader);
    };
    self.GetHtmlLoader = function(){
        var box = $('<div></div>').addClass('loader');
        if(style)
            box.addClass(style); 
        var img = $('<img>').attr({src: self.imgSrc}).css({marginTop: (self.height/2)-16});
        box.html(img).width(self.width).height(self.height);
        return box;
    };
    self.Delete = function(){
        $('.loader').remove();
        self.content.show();
    };
};


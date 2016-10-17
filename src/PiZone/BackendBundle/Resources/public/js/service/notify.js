function Notify(data){
    var self = this;
    self.title = null;
    self.text = null;
    self.type = null;
    self.shadow = null;
    self.opacity = null;
    self.type = null;
    self.noteStack = null;
    self.delay = null;
    self.stacks = {
        stack_top_right: {
            "dir1": "down",
            "dir2": "left",
            "push": "top",
            "spacing1": 10,
            "spacing2": 10
        },
        stack_top_left: {
            "dir1": "down",
            "dir2": "right",
            "push": "top",
            "spacing1": 10,
            "spacing2": 10
        },
        stack_bottom_left: {
            "dir1": "right",
            "dir2": "up",
            "push": "top",
            "spacing1": 10,
            "spacing2": 10
        },
        stack_bottom_right: {
            "dir1": "left",
            "dir2": "up",
            "push": "top",
            "spacing1": 10,
            "spacing2": 10
        },
        stack_bar_top: {
            "dir1": "down",
            "dir2": "right",
            "push": "top",
            "spacing1": 0,
            "spacing2": 0
        },
        stack_bar_bottom: {
            "dir1": "up",
            "dir2": "right",
            "spacing1": 0,
            "spacing2": 0
        },
        stack_context: {
            "dir1": "down",
            "dir2": "left",
            "context": $("#stack-context")
        }
    }

    function Init(){
        setOption();
        createNotify();
    }

    function setOption(){
        self.title = data.hasOwnProperty('title') ? data.title : '';
        self.text = data.hasOwnProperty('text') ? data.text : '';
        self.type = data.hasOwnProperty('type') ? data.type : 'info';
        self.shadow = data.hasOwnProperty('shadow') ? data.shadow : true;
        self.opacity = data.hasOwnProperty('opacity') ? data.opacity : 1;
        self.noteStack = data.hasOwnProperty('noteStack') ? data.noteStack : "stack_top_right";
        self.delay = data.hasOwnProperty('delay') ? data.delay : '2000' + '';
   }

    function createNotify(){
        new PNotify({
            title: self.title,
            text: self.text,
            shadow: self.shadow,
            opacity: self.opacity,
            addclass: self.noteStack,
            type: self.type,
            stack: self.stacks[self.noteStack],
            width: findWidth(),
            delay: self.delay
        });
    }

    function findWidth() {
        if (self.noteStack == "stack_bar_top") {
            return "100%";
        }
        if (self.noteStack == "stack_bar_bottom") {
            return "70%";
        } else {
            return "290px";
        }
    }

    Init();
}
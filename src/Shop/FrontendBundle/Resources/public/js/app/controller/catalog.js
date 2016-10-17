function CatalogCtrl($scope){
   // $scope.goods = {};

    $scope.removeItem = removeItem;
    $scope.inCart = inCart;

    function init(){
        if(!window.cart)
            executeQuery('/getCartInfo/');
    }

    function inCart(id){
        console.log(id);
        if(window.cart && window.cart.goods.hasOwnProperty(id))
            return true;
        return false;
    }

    function removeItem(id){
        executeQuery('/removeFromCart/' + id, function(message){
            EventDispatcher.DispatchEvent('change-cart', message);
            new Notify({
                type: 'info',
                title: 'Карзина',
                text: 'Товар успешно удален из карзины'
            });
        });
    }

    function updateData(data){
        console.log('update');
        if(data)
            window.cart = data;
    }

    function executeQuery(query, callback){
        var container = $('#' + $scope.formId);
        var loader = new Loader(container);
        $.ajax({
            url: ConfigPiZone.apiPrefix + query,
            dataType: 'json',
            beforeSend: function(){
                if(loader.ready == false)
                    loader.Create();
            },
            error: function(message){
                PiZoneException.AjaxError(message, loader)
                $scope.$apply();
            },
            success: function(message){
                loader.Delete();
                updateData(message);
                if(callback)
                    callback(message);
            }
        })
    }

    init();
}
CatalogCtrl.$inject = ['$scope'];
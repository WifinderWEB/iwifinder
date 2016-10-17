function GoodsCtrl($scope){
    $scope.goodsId = 0;
    $scope.inCart = false;
    $scope.count = 0;
    $scope.formId = 'goodsInfo';

    $scope.minusGoods = minusGoods;
    $scope.plusGoods = plusGoods;
    $scope.removeItem = removeItem;

    function init(){
        if(window.cart)
            updateData();
        else
            executeQuery('/getCartInfo/');

        EventDispatcher.AddEventListener('update-cart', function(){
            updateData();
        });
    }

    function plusGoods(){
        executeQuery('/setCountGoods/' + $scope.goodsId + '/' + (parseInt($scope.count)+1), function(message){
            EventDispatcher.DispatchEvent('change-cart', message);
        });
    }

    function minusGoods(){
        if($scope.count > 0) {

            executeQuery('/setCountGoods/' + $scope.goodsId + '/' + (parseInt($scope.count) - 1), function(message){
                EventDispatcher.DispatchEvent('change-cart', message);
            });
        }
        else{
            new Notify({
                type: 'warning',
                title: 'Карзина',
                text: 'достигнуто минимальное значение'
            });
        }
    }

    function removeItem(){
        executeQuery('/removeFromCart/' + $scope.goodsId, function(message){
            EventDispatcher.DispatchEvent('change-cart', message);
            new Notify({
                type: 'info',
                title: 'Карзина',
                text: 'Товар успешно удален из карзины'
            });
        });
    }

    function updateData(data){
        if(data)
            window.cart = data
        var goods = window.cart.goods;

        $scope.count = 0;
        $scope.inCart = false;
        if(window.cart.count > 0){
            $.each(goods, function (i){
                if(parseInt($scope.goodsId) == parseInt(goods[i].id)) {
                    $scope.count = goods[i].count;
                    $scope.inCart = true;
                    return false;
                }
            })
        }

        //$scope.$apply();
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
GoodsCtrl.$inject = ['$scope'];
function CartInfoCtrl($scope, $timeout){
    $scope.formId = 'cartInfo';
    $scope.cartModalId = 'cartModal';
    $scope.cartContentId = 'cartContent'
    $scope.count = 0;
    $scope.goods = null;
    $scope.discount = 0;
    $scope.itog = 0;
    $scope.selectAll = false;

    $scope.clear = clear;
    $scope.refresh = getCart;
    $scope.removeItem = removeItem;
    $scope.open = open;
    $scope.closeCart = closeCart;
    $scope.minusGoods = minusGoods;
    $scope.plusGoods = plusGoods;
    $scope.removeSelected = removeSelected;
    $scope.order = order;

    function watch() {
        $('#selectAll').change(function(){
            var test = $(this)[0].checked;
            $.each($scope.goods, function(i){
                $('#goods' + $scope.goods[i].id)[0].checked = test;
            })
        })

        $.each($scope.goods, function(i){
            $('#goods' + $scope.goods[i].id).change(function(){
                var checkedGoods = [];

                $.each($scope.goods, function(j){
                    if($scope.goods[j].checked)
                        checkedGoods.push($scope.goods[j].id);
                })

                if(Object.keys($scope.goods).length == checkedGoods.length)
                    $scope.selectAll = true;
                else
                    $scope.selectAll = false;
                $scope.$apply();
            })
        })
    }

    function Init(){
        EventDispatcher.AddEventListener('update-cart', function(data){
            console.log(data);
            if(data)
                updateData(data);
            // else
            //     getCart();
        });
        EventDispatcher.AddEventListener('add-to-cart', function(data){
            addToCart(data);
        });
        getCart();
    }

    function getCart(){
        executeQuery('/getCartInfo/');
    }

    function addToCart(data){
        executeQuery('/addToCart/' + data.alias, function(){
            new Notify({
                type: 'success',
                title: 'Карзина',
                text: 'Товар успешно добавлен в карзину'
            });
        })
    }

    function clear(){
        executeQuery('/clearCart/', function(){
            new Notify({
                type: 'info',
                title: 'Карзина',
                text: 'Карзина успешно очищена'
            });
        })
    }

    function removeItem(data){
        executeQuery('/removeFromCart/' + data.id, function(){
            new Notify({
                type: 'info',
                title: 'Карзина',
                text: 'Товар успешно удален из карзины'
            });
        });
    }

    function plusGoods(data){
        executeQuery('/setCountGoods/' + data.id + '/' + (parseInt(data.count)+1));
    }

    function minusGoods(data){
        if(data.count > 0) {
            executeQuery('/setCountGoods/' + data.id + '/' + (parseInt(data.count) - 1));
        }
        else{
            new Notify({
                type: 'warning',
                title: 'Карзина',
                text: 'достигнуто минимальное значение'
            });
        }
    }

    function open(){
        EventDispatcher.DispatchEvent('open-cart');
        window.location.hash = '/cart/';
        $('#' + $scope.cartModalId).show();
        $('body').addClass('modal-open');
    }

    function closeCart(){
        $('#' + $scope.cartModalId).hide();
        $('body').removeClass('modal-open');
        window.location.hash = '/';
    }

    function order(){
        $('#' + $scope.cartModalId).hide();
        window.location.hash = '/order/';
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
                    callback();
            }
        })
    }

    function updateData(data){
        window.cart = data;
        // EventDispatcher.DispatchEvent('update-cart');
        $scope.count = data.count;
        $scope.goods = data.goods;
        $scope.discount = 0;
        $scope.itog = 0;
        $.each($scope.goods, function(i) {
            $scope.goods[i].checked = false;
            var discount = 0;

            if(!$scope.goods[i].price)
                $scope.goods[i].price = 0;

            if($scope.goods[i].discount){
                discount = ($scope.goods[i].price * $scope.goods[i].discount)/100 * $scope.goods[i].count;
                $scope.discount =  $scope.discount + discount;
            }
            else{
                $scope.goods[i].discount = 0;
            }

            $scope.itog = $scope.itog + ($scope.goods[i].price * $scope.goods[i].count - discount);
        });

        EventDispatcher.DispatchEvent('update-cart-bottom', {count: $scope.count});

        $scope.$apply();

        watch();
    }

    function removeSelected(){
        var selected = [];
        $.each($scope.goods, function(i) {
            if($scope.goods[i].checked)
                selected.push($scope.goods[i].id);
        });

        $.ajax({
            url: ConfigPiZone.apiPrefix + '/removeFromCart/' + selected.join(','),
            dataType: 'json',
            error: function(message){
                $scope.$apply();
            },
            success: function(message){
                updateData(message);
                new Notify({
                    type: 'info',
                    title: 'Карзина',
                    text: 'Товар успешно удален из карзины'
                });
            }
        })
    }

    Init();
}
CartInfoCtrl.$inject = ['$scope', '$timeout'];
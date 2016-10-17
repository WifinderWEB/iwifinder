function OrderCtrl($scope, $state, $stateParams){
    $scope.orderModalId = 'orderModal';
    $scope.orderInfo = {
        firstName: '',
        lastName: '',
        middleName: '',
        email: '',
        phone: '',
        country: '',
        region: '',
        city: '',
        street: '',
        house: '',
        room: '',
        postcode: '',
        _token: '',
        goods: null,
        discount: '',
        itog: ''
    }
    $scope.goodsInCart = null;
    $scope.itog = 0;
    $scope.discount = 0;

    $scope.closeOrder = closeOrder;
    $scope.submit = submit;
    $scope.back = back;

    function init(){
        getForm(function(data){
            if($.isEmptyObject($stateParams.cart)) {
                getCart(data);
            }
            else {
                updateData({goods:  $stateParams.cart}, data);
            }
        });
        $("#shop_apibundle_order_phone").mask("+7 (999) 999-9999");
        $("#shop_apibundle_order_postcode").mask("999999");
        $('body').addClass('modal-open');
    }

    function closeOrder(){
        $('#' + $scope.orderModalId).hide();
        $('body').removeClass('modal-open');
        window.location.hash = '/';
    }

    function back(){
        $state.go('cart');
    }

    function getCart(data){
        $.ajax({
            url: ConfigPiZone.apiPrefix + '/getCartInfo/',
            dataType: 'json',
            error: function(message){
                $scope.$apply();
            },
            success: function(message){
                updateData(message, data);
            }
        })
    }

    function getForm(callback){
        $.ajax({
            url: ConfigPiZone.apiPrefix + '/getFormOrder/',
            dataType: 'json',
            error: function(message){
                console.log(message);
            },
            success: function(message){
                if(callback)
                    callback(message);
            }
        })
    }

    function updateData(data, form){
        $.each(form.fields, function(i){
            if(form.fields.hasOwnProperty(i))
                $scope.orderInfo[i] = form.fields[i];
        })

        $scope.goodsInCart = data.goods;
        $scope.discount = 0;
        $scope.itog = 0;
        $.each($scope.goodsInCart, function(i) {
            $scope.goodsInCart[i].checked = false;
            var discount = 0;

            if(!$scope.goodsInCart[i].price)
                $scope.goodsInCart[i].price = 0;

            if($scope.goodsInCart[i].discount){
                discount = ($scope.goodsInCart[i].price * $scope.goodsInCart[i].discount)/100 * $scope.goodsInCart[i].count;
                $scope.discount =  $scope.discount + discount;
            }
            else{
                $scope.goodsInCart[i].discount = 0;
            }

            $scope.itog = $scope.itog + ($scope.goodsInCart[i].price * $scope.goodsInCart[i].count - discount);
        });
        $scope.$apply();
    }

    function submit(){
        $.ajax({
            url: ConfigPiZone.apiPrefix + '/order/',
            data: $('#' + $scope.orderModalId + ' form').serialize(),
            method: 'post',
            dataType: 'json',
            error: function(message){
                var result = message.responseJSON;
                if(result.result == 'error' && result.fields){
                    updateData({goods:  $scope.goodsInCart}, result);
                    new Notify({
                        type: 'warning',
                        title: 'Оформление заказа',
                        text: 'Проверте правильность заполнения формы и попробуйте еще раз.'
                    });
                }
                else{
                    new Notify({
                        type: 'error',
                        title: message.statusText + ' Error: ' + result.code,
                        text: result.message + '<br/> Обратитесь к администратору.'
                    });
                }
            },
            success: function(message){
                if(message.result == 'ok') {
                    $('#' + $scope.orderModalId).hide();
                    $state.go('orderCreated', {order: message.order});
                }
            }
        })
    }

    init();
}
OrderCtrl.$inject = ['$scope', '$state', '$stateParams'];
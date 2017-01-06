function GoodsListCtrl($scope, $http){
    $scope.goodsList = [];
    $scope.alias = '';
    $scope.filters  = {};
    $scope.sortBy = '';
    var popup = $('<div class="resultFind"><img src="/bundles/pizonebackend/img/ajax-loader.gif"> Ищем ...</div>');
    $scope.lastPopup= null;
    $scope.filterForm = null;
    $scope.testInitFilters = false;
    $scope.testInitSortBy = false;
    $scope.count = '';

    $scope.LinkGoods = LinkGoods;
    $scope.Click = {
        ClearFilter: ClickClearFilter,
        AddToCart: ClickAddToCart
    };

    $scope.$watch('filters', function(oldVal, newVal){
        if($scope.testInitFilters) {
            var diff = DeepDiffMapper.map(oldVal, newVal);

            function GetDifKey(diff) {
                for (var key in diff) {
                    if (diff[key].type != "unchanged")
                        return key;
                }
            }

            var id = GetDifKey(diff);
            var target = $('#params_' + id);
            Filter(target);
        }
        else
            $scope.testInitFilters = true;
    }, true);
    $scope.$watch('sortBy', function(oldVal, newVal){
        if($scope.testInitSortBy)
            GetData();
        else
            $scope.testInitSortBy = true;
    }, true);

    function Filter($target){
        hidePopUp();
        $scope.lastPopup = popup;
        $scope.filterForm = $('#filterform');
        $scope.filterForm.append($scope.lastPopup);

        if($('body').hasClass('mobile'))
            $('.resultFind').css('margin-top', 0);

        if($target)
            filterPopUpRender($target);


        if($('body').hasClass('mobile'))
            $('.resultFind').css('margin-top', '20px');

        $.ajax({
            url: ConfigPiZone.apiPrefix + '/filter',
            type: "POST",
            data: GetFilterParameters(),
            // beforeSend: function(req){ _this._beforeAjax.forEach(function(c){ c.call(_this,'filter',req) }) },
        })
            .done(function(data,req){
                $scope.lastPopup.html("Найдено: " + data.count + ". <span>Показать</span>")
                $scope.lastPopup.click(function(){
                    $scope.goodsList = data.content;
                    $scope.count = data.count;
                    $($scope.filterForm).find('.filter').each(function(){
                        var li = $(this).find('li');
                        $.each(li, function(i){
                            $(li[i]).data('count',0).attr('data-count',0);
                            $(li[i]).find(">span").text(0);
                        });

                    });
                    if(data.filters)
                        $scope.dataFilter = data.filters;
                    $.each(data.filters, function(i, group){
                        $.each(group.parameters, function(j, param){
                            $('#params_' + param.id).closest('label').next('span').text(param.count);
                            if(param.count > 0){
                                var li = $('#params_' + param.id).closest('li');
                                li.data('count',param.count).attr('data-count',param.count);
                                li.removeClass('disabled');
                            }
                        });
                    });
                    $($scope.filterForm).find('.filter').each(function(){
                        var li = $(this).find('li');
                        $.each(li, function(i){
                            if($(li[i]).data('count') == 0)
                                $(li[i]).addClass('disabled');
                        });

                    });
                    hidePopUp();
                    $scope.$apply();
                })
            });
    }

    function hidePopUp() {
        if ($scope.lastPopup)
            $scope.lastPopup.remove();
    }

    function filterPopUpRender($target) {
        var $label  = $($target.closest('label'));
        $scope.lastPopup.offset($.extend($label.position(), {left: '50%'}, {}))
    }


    function ClickClearFilter(){
        $scope.testInitFilters = false;
        $scope.testInitSortBy = false;
        $.each($scope.filters, function(i){
            var check = $('#params_' + i);
            check[0].checked = false;
            check.next('span').removeClass('checked');
        });
        $scope.sortBy = '';
        $scope.filters = {};
        GetData();
    }

    function ClickAddToCart(item){
        EventDispatcher.DispatchEvent('add-to-cart', item);
    }

    function LinkGoods(item){
        var link = window.location.href;

        if(item.category)
            link  = '/catalog/' + item.category.alias;
        return link + '/' + item.alias;
    }

    function GetData(){
        $.ajax({
            url: ConfigPiZone.apiPrefix + '/filter',
            type: "POST",
            data: GetFilterParameters(),
            // beforeSend: function(req){ _this._beforeAjax.forEach(function(c){ c.call(_this,'filter',req) }) },
            success: function(data){
                $scope.goodsList = data.content;
                $scope.count = data.count;
                if(data.filters)
                    $scope.dataFilter = data.filters;
                $scope.$apply();
            }
        })
    }

    function GetFilterParameters(){
        var params = [],
            result = {},
            strParam = {};

        $.each($scope.filters, function(i, param){
            if(param)
                params.push(parseInt(i));
        });

        for(var key in $scope.dataFilter){
            var resultParams = [];
            for(var key2 in $scope.dataFilter[key].parameters){
                var obj =  $scope.dataFilter[key].parameters[key2];
                if($.inArray(obj.id, params) >= 0)
                    resultParams.push(obj.id);
            }

            if(resultParams.length > 0)
                strParam[key] = resultParams;
        }

        result['group_parameters'] = JSON.stringify(strParam);

        if($scope.sortBy)
             result['sort_by'] = $scope.sortBy;

        result['alias'] = $scope.alias;

        return result;
    }

    function Init(){
        if(goodsList && goodsList.length > 0)
            $scope.goodsList = goodsList;

        $scope.alias = alias;
        $scope.count = count ? count : 0;
        if($scope.goodsList.length > 0)
            $scope.issetGoods = true;

        if(dataFilters)
            $scope.dataFilter = dataFilters;

        $('#filterform').on('click','.disabled label',function(e){ e.preventDefault(); e.stopPropagation(); return false; });
    }

    Init();
}

GoodsListCtrl.$inject = ['$scope', '$http'];
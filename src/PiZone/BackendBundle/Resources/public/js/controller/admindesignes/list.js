function ListCtrl($scope, $http, $stateParams, ModalService){
    $scope.prefix = '';
    $scope.model = {};
    $scope.pageId = $stateParams.pageId;
    $scope.order = {
        sort: $stateParams.sort,
        order_by: $stateParams.order_by
    }
    $scope.perPage = 10;
    $scope.list = [];
    $scope.pager = {};
    $scope.filters = {};
    $scope.pathToTmpl = ConfigPiZone.pathToTmpl;
    $scope.breadcrumbs = [];
    $scope.route = '';
    $scope.Click = {
        Add: ClickAdd,
        Page: ClickPage,
        ChangePerPage: ChangePerPage,
        Filters: ClickFilters
    }
    $scope.Init = Init;

    function Init(){

        $scope.route = '/'+ $scope.prefix +'/';
        var perPage = $.cookie('pizone_' + $scope.prefix + '_max_per_page');
        if(perPage)
            $scope.perPage = perPage;
        GetLayout();
        EventDispatcher.DispatchEvent('change-route', $scope.breadcrumbs);
        EventDispatcher.AddEventListener('update_page_' + $scope.prefix, function(){
            GetLayout();
            $scope.$apply();
        });
    }

    function ClickAdd(){
        window.location.hash = '/' + $scope.prefix + '/new/';
    }

    function ClickPage(page){
        window.location.hash = '/' + $scope.prefix + '/'+ page;
    }

    function ChangePerPage(perPage){
        if(perPage != $scope.perPage) {
            $.cookie('pizone_' + $scope.prefix + '_max_per_page', perPage);
            window.location.hash = '/' + $scope.prefix + '';
            $scope.perPage = perPage;
            GetLayout();
        }
    }

    function GetLayout(){
        var query = ConfigPiZone.apiPrefix + '/' + $scope.prefix + '';
        var params = [];
        if($scope.pageId)
            params.push('page=' + $scope.pageId);
        if($scope.perPage)
            params.push('perPage=' + $scope.perPage);
        if($scope.order.sort && $scope.order.order_by)
            params.push('sort=' + $scope.order.sort + '&order_by=' + $scope.order.order_by);
        if(params.length > 0)
            query = query + '?' + params.join('&');
        $scope.list = [];
        $http.get(query).success(function(data) {
            $scope.pager = data.pager;
            $scope.filters = DataProcessing.Normalize(data.filters);
            $.each(data.list, function(i, obj){
                $scope.list.push(new $scope.model($scope, i, obj, ModalService));
            });
        });
    }

    function ClickFilters(){
        ModalService.showModal({
            templateUrl: ConfigPiZone.pathToTmpl + 'admindesignes/filters.html',
            controller: "FiltersCtrl",
            id: 'filterModal',
            inputs: {
                title: "Фильтры",
                fields: $scope.filters
            }
        }).then(function(modal) {

        });
    }
}
ListCtrl.$inject = ['$scope', '$http', '$stateParams', 'ModalService'];
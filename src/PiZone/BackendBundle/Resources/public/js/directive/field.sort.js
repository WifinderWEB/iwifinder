function FieldSortPizone() {

    var template = '{{fieldLabel}} ' +
        '<a ng-click="ClickSort()" ng-show="sort != fieldAlias">' +
        '<i class="glyphicon glyphicon-sort"></i>' +
        '</a>' +
        '<a ng-click="ClickSort()" ng-show="sort == fieldAlias && orderBy == \'asc\'">' +
        '<i class="glyphicon" ng-class="{true: \'glyphicon-sort-by-order\', false: \'glyphicon-sort-by-alphabet\' }[fieldType == \'int\']"></i>' +
        '</a>' +
        '<a ng-click="ClickSort()" ng-show="sort == fieldAlias && orderBy == \'desc\'">' +
        '<i class="glyphicon" ng-class="{true: \'glyphicon-sort-by-order-alt\', false: \'glyphicon-sort-by-alphabet-alt\' }[fieldType == \'int\']"></i>' +
        '</a>';

    var controller = ['$scope', '$state', function ($scope, $state) {
        $scope.sort = $state.params.sort;
        $scope.orderBy = $state.params.order_by;
        if($scope.fieldAlias != $scope.sort)
            $scope.orderBy = '';
        $scope.newOrderBy = ($scope.orderBy == 'asc' || self.sort == '') ? 'desc' : 'asc';

        $scope.ClickSort = ClickSort;

        function ClickSort(){
            $state.go($state.current.name, {pageId: $state.params.pageId, sort: $scope.fieldAlias, order_by: $scope.newOrderBy});
        }
    }];

    return {
        restrict: 'A',
        scope: {
            fieldAlias: '=',
            fieldLabel: '=',
            fieldType: '='
        },
        template: template,
        controller: controller
    }
}
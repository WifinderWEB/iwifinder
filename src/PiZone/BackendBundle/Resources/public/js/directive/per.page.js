function PerPagePizone() {
    return {
        restrict: 'A',
        scope: {
            listLength: '=',
            changePerPage: '=',
            perPagePizone: '='
        },
        template: '<span>Результатов ' +
            '<a href="" class="btn btn-xs btn-default btn-gradient dropdown-toggle" data-toggle="dropdown" aria-expanded="false">' +
                '<i class="glyphicon glyphicon-cog fs13"></i> {{listLength}} ' +
            '</a>' +
            '<ul class="dropdown-menu checkbox-persist pull-right text-left" role="menu">' +
                '<li ng-repeat="item in paginate" ng-class="{true: \'active\'}[item == perPagePizone.max_per_page]">' +
                    '<a ng-click="changePerPage(item)">показать {{item}}</a>' +
                '</li>' +
            '</ul>' +
            '</span> из <span class="badge">{{perPagePizone.nb_result}}</span>',
        link: function(scope, el, attr){
            scope.paginate = [10, 20, 50, 100];
        }
    }
}
function ButtonActivePizone() {
    return {
        restrict: 'A',
        scope: {
            ngModel: '='
        },
        template: '<button type="button" class="btn btn-xs btn-success" ng-if="ngModel.isActive" ng-click="ngModel.Click.Active()">' +
                      '<i class="fa fa-check"></i>' +
                  '</button>' +
                  '<button type="button" class="btn btn-xs btn-warning" ng-if="!ngModel.isActive" ng-click="ngModel.Click.Active()">' +
                      '<i class="fa fa-minus"></i>' +
                  '</button>'
    }
}
function ListButtonGroupPizone() {
    var controller = ['$scope', function ($scope) {
        function init() {
            $scope.model = angular.copy($scope.ngModel);
        }

        init();

        $scope.Edit = function(){
            window.location.hash = '/' + $scope.listButtonGroupPizone + '/'+ $scope.model.id +'/edit/';
        }
    }];

    var template = '<div class="bs-component">' +
            '<div class="btn-group">' +
                '<button type="button" class="btn btn-xs btn-dark light" ng-click="Edit()">' +
                    '<i class="fa fa-edit"></i>' +
                '</button>' +
                '<button type="button" class="btn btn-xs btn-danger" ng-click="model.Delete()">' +
                    '<i class="fa fa-trash"></i>' +
                '</button>' +
            '</div>' +
        '</div>';
    return {
        restrict: 'A',
        scope: {
            ngModel: '=',
            listButtonGroupPizone: '='
        },
        controller: controller,
        template: template
    }
}
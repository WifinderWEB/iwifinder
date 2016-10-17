function MainCtrl($scope){
    $('body').attr('class', 'ng-scope sb-l-o sb-r-c');
    Core.init();
}
MainCtrl.$inject = ['$scope'];
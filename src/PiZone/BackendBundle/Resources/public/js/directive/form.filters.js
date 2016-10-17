function FormFiltersPizone() {

    var link = function ($scope, $element, $attr) {
        ModalService.showModal({
            templateUrl: ConfigPiZone.pathToTmpl + 'admindesignes/filters.html',
            controller: "FiltersCtrl",
            inputs: {

            }
        }).then(function(modal) {

        });
    };

    return {
        restrict: 'A',
        link: link
    }
}
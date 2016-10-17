function AuthInterceptor($rootScope, $q, $cookies , $location, $sessionStorage) {
    return {
        // Add authorization token to headers
        request: function (config) {
            config.headers = config.headers || {};
            if ($cookies.get('token')) {
                $sessionStorage.isAuthorized = true;
                config.headers.Authorization = 'Basic ' + $cookies.get('token');
            }
            return config;
        },

        // Intercept 401s and redirect you to login
        responseError: function(response) {
            if(response.status === 401 || response.status === 403) {
                $location.path('/login');
                // remove any stale tokens
                $cookies.remove('token');
                return $q.reject(response);
            }
            else {
                return $q.reject(response);
            }
        }
    };
}
AuthInterceptor.$inject = ['$rootScope', '$q', '$cookies', '$location', '$sessionStorage'];
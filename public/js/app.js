var app = angular.module('authApp', []);

app.controller('RegisterController', function ($scope, $http) {

    $scope.registerUser = function () {
        $http.post('/api/register', $scope.user)
            .then(function (response) {
                $scope.message = response.data.message;
            }, function (error) {
                $scope.message = error.data.message || 'Registration failed';
            });
    };

});

app.controller('LoginController', function ($scope, $http) {

    $scope.loginUser = function () {
        $http.post('/api/login', $scope.user)
            .then(function (response) {
                $scope.message = response.data.message;
            }, function () {
                $scope.message = 'Login failed';
            });
    };

});

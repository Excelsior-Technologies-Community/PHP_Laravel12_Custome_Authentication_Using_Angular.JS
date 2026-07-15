var app = angular.module('authApp', []);

/* ==========================================
   REGISTER CONTROLLER
========================================== */

app.controller('RegisterController', function ($scope, $http) {

    $scope.user = {};

    $scope.registerUser = function () {

        $http.post('/api/register', $scope.user)

            .then(function (response) {

                alert(response.data.message);

                window.location.href = "login.html";

            })

            .catch(function (error) {

                if (error.data && error.data.message) {
                    alert(error.data.message);
                } else {
                    alert("Registration failed.");
                }

            });

    };

}); 


/* ==========================================
   LOGIN CONTROLLER
========================================== */

app.controller('LoginController', function ($scope, $http) {

    $scope.user = {};

    $scope.loginUser = function () {

        $http.post('/api/login', $scope.user)

            .then(function (response) {

                if (response.data.status) {

                    localStorage.setItem(
                        "loggedUser",
                        JSON.stringify(response.data.user)
                    );

                    alert(response.data.message);

                    window.location.href = "profile.html";

                } else {

                    alert(response.data.message);

                }

            })

            .catch(function (error) {

                if (error.data && error.data.message) {
                    alert(error.data.message);
                } else {
                    alert("Login failed.");
                }

            });

    };

});


/* ==========================================
   PROFILE CONTROLLER
========================================== */

app.controller('ProfileController', function ($scope, $http) {

    var user = JSON.parse(localStorage.getItem("loggedUser"));

    if (!user) {

        window.location.href = "login.html";
        return;

    }

    $scope.user = {};

    $http.get('/api/profile/' + user.id)

        .then(function (response) {

            if (response.data.status) {

                $scope.user = response.data.user;

            }

        });

    $scope.logout = function () {

        localStorage.removeItem("loggedUser");

        alert("Logged out successfully.");

        window.location.href = "login.html";

    };

});


/* ==========================================
   CHANGE PASSWORD CONTROLLER
========================================== */

app.controller('ChangePasswordController', function ($scope, $http) {

    var user = JSON.parse(localStorage.getItem("loggedUser"));

    if (!user) {

        window.location.href = "login.html";
        return;

    }

    $scope.password = {};

    $scope.changePassword = function () {

        var data = {

            user_id: user.id,

            current_password: $scope.password.current_password,

            new_password: $scope.password.new_password,

            new_password_confirmation: $scope.password.new_password_confirmation

        };

        $http.post('/api/change-password', data)

            .then(function (response) {

                alert(response.data.message);

                $scope.password = {};

            })

            .catch(function (error) {

                if (error.data && error.data.message) {
                    alert(error.data.message);
                } else {
                    alert("Unable to change password.");
                }

            });

    };

});
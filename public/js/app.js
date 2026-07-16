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

                    window.location.href = "dashboard.html";

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
   DASHBOARD CONTROLLER
========================================== */

app.controller('DashboardController', function ($scope, $http) {

    var user = JSON.parse(localStorage.getItem("loggedUser"));

    if (!user) {

        window.location.href = "login.html";
        return;

    }

    $scope.dashboard = {};
    $scope.users = {};
    $scope.search = "";
    $scope.page = 1;

    /* Dashboard Statistics */

    $scope.loadDashboard = function () {

        $http.get('/api/dashboard')

            .then(function (response) {

                if (response.data.status) {

                    $scope.dashboard = response.data;

                }

            });

    };

    /* User List */

    $scope.loadUsers = function () {

        $http.get('/api/users?page=' + $scope.page +
            '&search=' + $scope.search)

            .then(function (response) {

                $scope.users = response.data;

            });

    };

    /* Pagination */

    $scope.changePage = function (page) {

        if (page < 1 || page > $scope.users.last_page)
            return;

        $scope.page = page;

        $scope.loadUsers();

    };

    /* Delete User */

    $scope.deleteUser = function (id) {

        if (confirm("Are you sure you want to delete this user?")) {

            $http.delete('/api/users/' + id)

                .then(function (response) {

                    alert(response.data.message);

                    $scope.loadDashboard();

                    $scope.loadUsers();

                });

        }

    };

    /* Open Profile */

    $scope.goProfile = function () {

        window.location.href = "profile.html";

    };

    /* Logout */

    $scope.logout = function () {

        localStorage.removeItem("loggedUser");

        alert("Logged out successfully.");

        window.location.href = "login.html";

    };

    /* Initial Load */

    $scope.loadDashboard();

    $scope.loadUsers();

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
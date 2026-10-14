<!DOCTYPE html>
<html lang="en" ng-app="authApp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel 12 & AngularJS Custom Authentication Studio</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --sidebar-bg: #0f172a;
            --surface-bg: #f8fafc;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--surface-bg);
            color: #1e293b;
            min-height: 100vh;
        }
        .navbar-brand-badge {
            background: var(--primary-gradient);
            color: white;
            padding: 6px 14px;
            border-radius: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }
        .auth-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.08), 0 5px 15px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            background: #ffffff;
        }
        .auth-header {
            background: var(--primary-gradient);
            padding: 30px;
            color: white;
            text-align: center;
        }
        .form-control, .form-select {
            padding: 12px 16px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        }
        .btn-gradient {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: 0.3px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.45);
            color: white;
        }
        .stat-card {
            border: none;
            border-radius: 18px;
            background: white;
            padding: 22px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }
        .strength-bar-wrapper {
            height: 8px;
            border-radius: 6px;
            background-color: #e2e8f0;
            overflow: hidden;
            margin-top: 8px;
        }
        .strength-bar {
            height: 100%;
            transition: width 0.3s ease, background-color 0.3s ease;
        }
        .checkpoint-item {
            font-size: 0.82rem;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s ease;
        }
        .checkpoint-pass {
            color: #10b981;
            font-weight: 600;
        }
        .checkpoint-fail {
            color: #94a3b8;
        }
        .nav-pill-btn {
            border: none;
            background: #f1f5f9;
            color: #475569;
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        .nav-pill-btn.active, .nav-pill-btn:hover {
            background: #4f46e5;
            color: white;
        }
        .badge-active {
            background-color: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }
        .badge-suspended {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        .user-avatar-circle {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e0e7ff;
        }
        [ng-cloak] {
            display: none !important;
        }
    </style>
</head>
<body ng-controller="AuthController" ng-cloak>

    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3 px-3 shadow-sm sticky-top">
        <div class="container-fluid max-w-7xl">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#" ng-click="switchView(currentUser ? 'dashboard' : 'login')">
                <span class="navbar-brand-badge"><i class="fa-solid fa-shield-halved me-1"></i> AUTH STUDIO</span>
                <span class="fw-semibold text-white fs-6 d-none d-sm-inline">Laravel 12 & AngularJS</span>
            </a>

            <div class="d-flex align-items-center gap-2 ms-auto">
                <!-- Logged Out Nav Controls -->
                <div ng-if="!currentUser" class="d-flex gap-2">
                    <button class="nav-pill-btn" ng-class="{'active': currentView === 'login'}" ng-click="switchView('login')">
                        <i class="fa-solid fa-right-to-bracket me-1"></i> Sign In
                    </button>
                    <button class="nav-pill-btn" ng-class="{'active': currentView === 'register'}" ng-click="switchView('register')">
                        <i class="fa-solid fa-user-plus me-1"></i> Sign Up
                    </button>
                </div>

                <!-- Logged In Nav Controls -->
                <div ng-if="currentUser" class="d-flex align-items-center gap-2">
                    <button class="nav-pill-btn" ng-class="{'active': currentView === 'dashboard'}" ng-click="switchView('dashboard')">
                        <i class="fa-solid fa-chart-pie me-1"></i> Dashboard
                    </button>
                    <button class="nav-pill-btn" ng-class="{'active': currentView === 'profile'}" ng-click="switchView('profile')">
                        <i class="fa-solid fa-id-badge me-1"></i> My Profile
                    </button>

                    <!-- User Pill Dropdown -->
                    <div class="dropdown ms-2">
                        <button class="btn btn-outline-light btn-sm rounded-pill d-flex align-items-center gap-2 py-1 px-3 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <img ng-src="<% currentUser.avatar || 'https://ui-avatars.com/api/?name=' + currentUser.name + '&background=4f46e5&color=fff' %>" class="rounded-circle" width="24" height="24">
                            <span class="small fw-semibold"><% currentUser.name %></span>
                            <span class="badge bg-primary text-uppercase" style="font-size: 0.65rem;"><% currentUser.role || 'User' %></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3">
                            <li class="px-3 py-2 border-bottom">
                                <div class="fw-bold small text-truncate" style="max-width: 180px;"><% currentUser.name %></div>
                                <div class="text-muted text-xs text-truncate" style="font-size: 0.75rem;"><% currentUser.email %></div>
                            </li>
                            <li><a class="dropdown-item py-2 small" href="#" ng-click="switchView('profile')"><i class="fa-solid fa-user-gear me-2 text-primary"></i> Profile Settings</a></li>
                            <li><a class="dropdown-item py-2 small" href="#" ng-click="switchView('dashboard')"><i class="fa-solid fa-chart-line me-2 text-info"></i> Analytics</a></li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li><a class="dropdown-item py-2 small text-danger" href="#" ng-click="logout()"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Log Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Dynamic Content Container -->
    <div class="container py-4">

        <!-- ========================================== -->
        <!-- VIEW 1: LOGIN FORM                         -->
        <!-- ========================================== -->
        <div class="row justify-content-center" ng-if="currentView === 'login'">
            <div class="col-md-6 col-lg-5">
                <div class="auth-card">
                    <div class="auth-header">
                        <div class="display-6 mb-2"><i class="fa-solid fa-user-lock"></i></div>
                        <h4 class="fw-bold mb-1">Welcome Back!</h4>
                        <p class="mb-0 opacity-75 small">Enter your credentials to access your account</p>
                    </div>

                    <div class="p-4 p-md-5">
                        <form name="loginForm" ng-submit="handleLogin(loginForm)" novalidate>
                            <!-- Email Input -->
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="name@domain.com"
                                           ng-model="loginData.email" required
                                           ng-class="{'is-invalid': loginForm.email.$dirty && loginForm.email.$invalid}">
                                </div>
                                <div class="text-danger small mt-1" ng-if="loginForm.email.$dirty && loginForm.email.$invalid">
                                    <span ng-if="loginForm.email.$error.required"><i class="fa-solid fa-circle-exclamation me-1"></i> Email is required.</span>
                                    <span ng-if="loginForm.email.$error.email"><i class="fa-solid fa-circle-exclamation me-1"></i> Please enter a valid email address.</span>
                                </div>
                            </div>

                            <!-- Password Input -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label small fw-semibold text-secondary mb-0">Password</label>
                                    <a href="#" class="small text-decoration-none text-primary" ng-click="quickFillAdmin()">Fill Admin Demo</a>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
                                    <input type="<% showLoginPassword ? 'text' : 'password' %>" name="password" class="form-control border-start-0 border-end-0 ps-0" placeholder="••••••••"
                                           ng-model="loginData.password" required
                                           ng-class="{'is-invalid': loginForm.password.$dirty && loginForm.password.$invalid}">
                                    <button class="btn btn-outline-secondary border-start-0 border-1 bg-white text-muted" type="button" ng-click="showLoginPassword = !showLoginPassword">
                                        <i class="fa-solid" ng-class="showLoginPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                                <div class="text-danger small mt-1" ng-if="loginForm.password.$dirty && loginForm.password.$invalid">
                                    <span ng-if="loginForm.password.$error.required"><i class="fa-solid fa-circle-exclamation me-1"></i> Password is required.</span>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-gradient w-full w-100 mt-3" ng-disabled="loginLoading">
                                <span ng-if="!loginLoading"><i class="fa-solid fa-arrow-right-to-bracket me-2"></i> Sign In to Dashboard</span>
                                <span ng-if="loginLoading"><i class="fa-solid fa-circle-notch fa-spin me-2"></i> Verifying...</span>
                            </button>

                            <div class="text-center mt-4 pt-2 border-top">
                                <span class="text-muted small">Don't have an account? </span>
                                <a href="#" class="fw-semibold text-primary small text-decoration-none" ng-click="switchView('register')">Create an account</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- ========================================== -->
        <!-- VIEW 2: REGISTER FORM WITH STRENGTH METER   -->
        <!-- ========================================== -->
        <div class="row justify-content-center" ng-if="currentView === 'register'">
            <div class="col-md-7 col-lg-6">
                <div class="auth-card">
                    <div class="auth-header">
                        <div class="display-6 mb-2"><i class="fa-solid fa-user-shield"></i></div>
                        <h4 class="fw-bold mb-1">Create Your Account</h4>
                        <p class="mb-0 opacity-75 small">Full AngularJS Reactive Validation & Strength Meter</p>
                    </div>

                    <div class="p-4 p-md-5">
                        <form name="regForm" ng-submit="handleRegister(regForm)" novalidate>
                            <!-- Full Name -->
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-id-card"></i></span>
                                    <input type="text" name="name" class="form-control border-start-0 ps-0" placeholder="Jane Doe"
                                           ng-model="regData.name" required
                                           ng-class="{'is-invalid': regForm.name.$dirty && regForm.name.$invalid}">
                                </div>
                                <div class="text-danger small mt-1" ng-if="regForm.name.$dirty && regForm.name.$invalid">
                                    <span ng-if="regForm.name.$error.required"><i class="fa-solid fa-circle-exclamation me-1"></i> Full Name is required.</span>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="jane@example.com"
                                           ng-model="regData.email" required
                                           ng-class="{'is-invalid': regForm.email.$dirty && regForm.email.$invalid}">
                                </div>
                                <div class="text-danger small mt-1" ng-if="regForm.email.$dirty && regForm.email.$invalid">
                                    <span ng-if="regForm.email.$error.required"><i class="fa-solid fa-circle-exclamation me-1"></i> Email is required.</span>
                                    <span ng-if="regForm.email.$error.email"><i class="fa-solid fa-circle-exclamation me-1"></i> Enter a valid email format.</span>
                                </div>
                            </div>

                            <!-- Role Selection -->
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary">Account Role</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-shield"></i></span>
                                    <select class="form-select border-start-0 ps-0" ng-model="regData.role" required>
                                        <option value="User">Standard User</option>
                                        <option value="Admin">System Administrator</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Password with LIVE STRENGTH METER -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label small fw-semibold text-secondary mb-0">Password</label>
                                    <span class="badge" ng-class="strengthBadgeClass" style="font-size: 0.72rem;">
                                        <% strengthLabel %>
                                    </span>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-key"></i></span>
                                    <input type="<% showRegPassword ? 'text' : 'password' %>" name="password" class="form-control border-start-0 border-end-0 ps-0" placeholder="Create strong password"
                                           ng-model="regData.password" ng-change="calculatePasswordStrength(regData.password)" required minlength="6"
                                           ng-class="{'is-invalid': regForm.password.$dirty && regForm.password.$invalid}">
                                    <button class="btn btn-outline-secondary border-start-0 border-1 bg-white text-muted" type="button" ng-click="showRegPassword = !showRegPassword">
                                        <i class="fa-solid" ng-class="showRegPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>

                                <!-- Animated Strength Bar -->
                                <div class="strength-bar-wrapper">
                                    <div class="strength-bar" ng-style="{'width': strengthScore + '%', 'background-color': strengthColor}"></div>
                                </div>

                                <!-- 4 Interactive Real-time Checkpoints -->
                                <div class="card bg-light border-0 p-3 mt-2 rounded-3">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="checkpoint-item" ng-class="checkPoints.length ? 'checkpoint-pass' : 'checkpoint-fail'">
                                                <i class="fa-solid" ng-class="checkPoints.length ? 'fa-circle-check' : 'fa-circle-xmark'"></i>
                                                <span>Min 8 characters</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="checkpoint-item" ng-class="checkPoints.uppercase ? 'checkpoint-pass' : 'checkpoint-fail'">
                                                <i class="fa-solid" ng-class="checkPoints.uppercase ? 'fa-circle-check' : 'fa-circle-xmark'"></i>
                                                <span>1 Uppercase (A-Z)</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="checkpoint-item" ng-class="checkPoints.number ? 'checkpoint-pass' : 'checkpoint-fail'">
                                                <i class="fa-solid" ng-class="checkPoints.number ? 'fa-circle-check' : 'fa-circle-xmark'"></i>
                                                <span>1 Number (0-9)</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="checkpoint-item" ng-class="checkPoints.special ? 'checkpoint-pass' : 'checkpoint-fail'">
                                                <i class="fa-solid" ng-class="checkPoints.special ? 'fa-circle-check' : 'fa-circle-xmark'"></i>
                                                <span>1 Special (@, #, $, %)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-danger small mt-1" ng-if="regForm.password.$dirty && regForm.password.$invalid">
                                    <span ng-if="regForm.password.$error.required"><i class="fa-solid fa-circle-exclamation me-1"></i> Password is required.</span>
                                    <span ng-if="regForm.password.$error.minlength"><i class="fa-solid fa-circle-exclamation me-1"></i> Minimum 6 characters required.</span>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-4">
                                <label class="form-label small fw-semibold text-secondary">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-lock-open"></i></span>
                                    <input type="password" name="password_confirmation" class="form-control border-start-0 ps-0" placeholder="Re-type password"
                                           ng-model="regData.password_confirmation" required
                                           ng-class="{'is-invalid': regForm.password_confirmation.$dirty && (regData.password !== regData.password_confirmation)}">
                                </div>
                                <div class="text-danger small mt-1" ng-if="regForm.password_confirmation.$dirty && (regData.password !== regData.password_confirmation)">
                                    <i class="fa-solid fa-triangle-exclamation me-1"></i> Passwords do not match.
                                </div>
                                <div class="text-success small mt-1" ng-if="regData.password && regData.password === regData.password_confirmation">
                                    <i class="fa-solid fa-circle-check me-1"></i> Passwords match!
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-gradient w-100" ng-disabled="regLoading || (regData.password !== regData.password_confirmation)">
                                <span ng-if="!regLoading"><i class="fa-solid fa-user-check me-2"></i> Register Account</span>
                                <span ng-if="regLoading"><i class="fa-solid fa-circle-notch fa-spin me-2"></i> Registering...</span>
                            </button>

                            <div class="text-center mt-4 pt-2 border-top">
                                <span class="text-muted small">Already have an account? </span>
                                <a href="#" class="fw-semibold text-primary small text-decoration-none" ng-click="switchView('login')">Sign In</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- ============================================================== -->
        <!-- VIEW 3: ADMIN DASHBOARD & CHART.JS ANALYTICS + USER MANAGEMENT -->
        <!-- ============================================================== -->
        <div ng-if="currentView === 'dashboard'">
            <!-- Welcome Header -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3 bg-white p-4 rounded-4 shadow-sm">
                <div>
                    <h3 class="fw-bold mb-1 text-dark">
                        <i class="fa-solid fa-chart-line text-primary me-2"></i> System Analytics & Users
                    </h3>
                    <p class="text-muted mb-0 small">
                        Real-time authentication KPIs and registration velocities powered by Chart.js & Laravel 12
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-light border btn-sm px-3 rounded-pill" ng-click="loadDashboardData()">
                        <i class="fa-solid fa-arrows-rotate me-1 text-primary"></i> Refresh Metrics
                    </button>
                    <button class="btn btn-primary btn-sm px-3 rounded-pill" ng-click="switchView('register')">
                        <i class="fa-solid fa-user-plus me-1"></i> New User
                    </button>
                </div>
            </div>

            <!-- KPI Cards Row -->
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-lg-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase">Total Users</span>
                            <h3 class="fw-bold mt-1 mb-0 text-dark"><% dashData.total_users || 0 %></h3>
                            <small class="text-success"><i class="fa-solid fa-arrow-trend-up"></i> Registered</small>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase">Active Accounts</span>
                            <h3 class="fw-bold mt-1 mb-0 text-success"><% dashData.active_users || 0 %></h3>
                            <small class="text-muted">Verified & Active</small>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="fa-solid fa-user-check"></i>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase">Suspended</span>
                            <h3 class="fw-bold mt-1 mb-0 text-danger"><% dashData.suspended_users || 0 %></h3>
                            <small class="text-danger">Restricted Access</small>
                        </div>
                        <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                            <i class="fa-solid fa-user-slash"></i>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase">System Admins</span>
                            <h3 class="fw-bold mt-1 mb-0 text-indigo"><% dashData.admin_count || 0 %></h3>
                            <small class="text-primary">Super Powers</small>
                        </div>
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row (Feature 3) -->
            <div class="row g-4 mb-4">
                <!-- 7-Day Trend Chart -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">
                                <i class="fa-solid fa-chart-area text-primary me-2"></i> 7-Day Registration Velocity
                            </h5>
                            <span class="badge bg-light text-secondary border">Real-Time Daily Count</span>
                        </div>
                        <div style="height: 280px; position: relative;">
                            <canvas id="registrationTrendChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Roles & Status Doughnut Chart -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">
                                <i class="fa-solid fa-chart-pie text-success me-2"></i> Account Distribution
                            </h5>
                            <span class="badge bg-light text-secondary border">Ratio</span>
                        </div>
                        <div style="height: 280px; position: relative;">
                            <canvas id="accountRatioChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Directory & Live Management Table -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <h5 class="fw-bold mb-0"><i class="fa-solid fa-address-book text-primary me-2"></i> User Directory</h5>
                        <small class="text-muted">Instant Search, Status Toggling & Account Actions</small>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <!-- Search Box -->
                        <div class="input-group input-group-sm" style="width: 240px;">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-search text-muted"></i></span>
                            <input type="text" class="form-control bg-light border-start-0" placeholder="Search name or email..."
                                   ng-model="userFilter.search" ng-change="fetchUsers(1)">
                        </div>

                        <!-- Status Filter -->
                        <select class="form-select form-select-sm bg-light" style="width: 130px;"
                                ng-model="userFilter.status" ng-change="fetchUsers(1)">
                            <option value="">All Statuses</option>
                            <option value="Active">Active</option>
                            <option value="Suspended">Suspended</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover">
                        <thead class="table-light text-secondary small text-uppercase">
                            <tr>
                                <th class="ps-4">User</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Registered</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="user in usersList.data">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <img ng-src="<% user.avatar || 'https://ui-avatars.com/api/?name=' + user.name + '&background=4f46e5&color=fff' %>" class="user-avatar-circle">
                                        <div>
                                            <div class="fw-bold text-dark"><% user.name %></div>
                                            <div class="text-muted small"><% user.email %></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge" ng-class="user.role === 'Admin' ? 'bg-primary' : 'bg-secondary bg-opacity-75'">
                                        <% user.role || 'User' %>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-1" ng-class="user.status === 'Active' ? 'badge-active' : 'badge-suspended'">
                                        <i class="fa-solid" ng-class="user.status === 'Active' ? 'fa-circle-check' : 'fa-circle-xmark'"></i>
                                        <% user.status || 'Active' %>
                                    </span>
                                </td>
                                <td class="small text-muted">
                                    <% user.created_at | date:'dd MMM yyyy' %>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <!-- Toggle Status Button -->
                                        <button class="btn btn-outline-secondary" ng-click="toggleUserStatus(user)" title="Toggle Status">
                                            <i class="fa-solid" ng-class="user.status === 'Active' ? 'fa-ban text-danger' : 'fa-check text-success'"></i>
                                        </button>
                                        <!-- Delete Button -->
                                        <button class="btn btn-outline-danger" ng-click="confirmDeleteUser(user)" title="Delete User">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr ng-if="!usersList.data || usersList.data.length === 0">
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-user-xmark fs-2 mb-2 d-block"></i>
                                    No users found matching your filters.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div class="card-footer bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center" ng-if="usersList.last_page > 1">
                    <span class="small text-muted">
                        Page <% usersList.current_page %> of <% usersList.last_page %> (<% usersList.total %> total users)
                    </span>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item" ng-class="{'disabled': usersList.current_page === 1}">
                                <a class="page-link" href="#" ng-click="fetchUsers(usersList.current_page - 1)">Previous</a>
                            </li>
                            <li class="page-item" ng-repeat="p in getPageNumbers(usersList.last_page)" ng-class="{'active': p === usersList.current_page}">
                                <a class="page-link" href="#" ng-click="fetchUsers(p)"><% p %></a>
                            </li>
                            <li class="page-item" ng-class="{'disabled': usersList.current_page === usersList.last_page}">
                                <a class="page-link" href="#" ng-click="fetchUsers(usersList.current_page + 1)">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>


        <!-- ========================================== -->
        <!-- VIEW 4: USER PROFILE & CHANGE PASSWORD    -->
        <!-- ========================================== -->
        <div ng-if="currentView === 'profile'">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Profile Header Card -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                        <div class="d-flex flex-wrap align-items-center gap-4">
                            <img ng-src="<% currentUser.avatar || 'https://ui-avatars.com/api/?name=' + currentUser.name + '&background=4f46e5&color=fff' %>"
                                 class="rounded-circle shadow-sm" width="90" height="90" style="border: 3px solid #6366f1;">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <h4 class="fw-bold mb-0 text-dark"><% currentUser.name %></h4>
                                    <span class="badge bg-primary"><% currentUser.role || 'User' %></span>
                                    <span class="badge rounded-pill" ng-class="currentUser.status === 'Active' ? 'badge-active' : 'badge-suspended'">
                                        <% currentUser.status || 'Active' %>
                                    </span>
                                </div>
                                <div class="text-muted small"><i class="fa-solid fa-envelope me-1"></i> <% currentUser.email %></div>
                                <div class="text-muted small mt-1" ng-if="currentUser.created_at">
                                    <i class="fa-solid fa-calendar me-1"></i> Member since <% currentUser.created_at | date:'mediumDate' %>
                                </div>
                            </div>
                            <div>
                                <button class="btn btn-outline-secondary btn-sm rounded-pill" ng-click="switchView('dashboard')">
                                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Change Password Card -->
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                        <div class="card-header bg-light border-0 py-3 px-4">
                            <h5 class="fw-bold mb-0"><i class="fa-solid fa-shield-keyhole text-primary me-2"></i> Security & Password Change</h5>
                            <small class="text-muted">Ensure your account uses a strong, unique password</small>
                        </div>
                        <div class="card-body p-4">
                            <form name="pwForm" ng-submit="handleChangePassword(pwForm)" novalidate>
                                <!-- Current Password -->
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold text-secondary">Current Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
                                        <input type="password" name="current_password" class="form-control border-start-0 ps-0" placeholder="Enter current password"
                                               ng-model="pwData.current_password" required
                                               ng-class="{'is-invalid': pwForm.current_password.$dirty && pwForm.current_password.$invalid}">
                                    </div>
                                    <div class="text-danger small mt-1" ng-if="pwForm.current_password.$dirty && pwForm.current_password.$invalid">
                                        <span ng-if="pwForm.current_password.$error.required">Current password is required.</span>
                                    </div>
                                </div>

                                <!-- New Password -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label small fw-semibold text-secondary mb-0">New Password</label>
                                        <span class="badge" ng-class="pwStrengthBadgeClass" style="font-size: 0.72rem;"><% pwStrengthLabel %></span>
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-key"></i></span>
                                        <input type="password" name="new_password" class="form-control border-start-0 ps-0" placeholder="Minimum 6 characters"
                                               ng-model="pwData.new_password" ng-change="calculateNewPwStrength(pwData.new_password)" required minlength="6"
                                               ng-class="{'is-invalid': pwForm.new_password.$dirty && pwForm.new_password.$invalid}">
                                    </div>
                                    <div class="strength-bar-wrapper">
                                        <div class="strength-bar" ng-style="{'width': pwStrengthScore + '%', 'background-color': pwStrengthColor}"></div>
                                    </div>
                                    <div class="text-danger small mt-1" ng-if="pwForm.new_password.$dirty && pwForm.new_password.$invalid">
                                        <span ng-if="pwForm.new_password.$error.required">New password is required.</span>
                                        <span ng-if="pwForm.new_password.$error.minlength">Must be at least 6 characters.</span>
                                    </div>
                                </div>

                                <!-- Confirm New Password -->
                                <div class="mb-4">
                                    <label class="form-label small fw-semibold text-secondary">Confirm New Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-lock-open"></i></span>
                                        <input type="password" name="new_password_confirmation" class="form-control border-start-0 ps-0" placeholder="Re-type new password"
                                               ng-model="pwData.new_password_confirmation" required
                                               ng-class="{'is-invalid': pwForm.new_password_confirmation.$dirty && (pwData.new_password !== pwData.new_password_confirmation)}">
                                    </div>
                                    <div class="text-danger small mt-1" ng-if="pwForm.new_password_confirmation.$dirty && (pwData.new_password !== pwData.new_password_confirmation)">
                                        Passwords do not match.
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-gradient px-4" ng-disabled="pwLoading || (pwData.new_password !== pwData.new_password_confirmation)">
                                        <span ng-if="!pwLoading"><i class="fa-solid fa-key me-2"></i> Update Password</span>
                                        <span ng-if="pwLoading"><i class="fa-solid fa-circle-notch fa-spin me-2"></i> Updating...</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Scripts: Bootstrap, SweetAlert2, Chart.js, AngularJS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script>

    <script>
        var app = angular.module('authApp', [], function($interpolateProvider) {
            $interpolateProvider.startSymbol('<%');
            $interpolateProvider.endSymbol('%>');
        });

        app.controller('AuthController', function($scope, $http, $timeout) {
            // View State: 'login', 'register', 'dashboard', 'profile'
            $scope.currentView = 'login';
            $scope.currentUser = null;
            $scope.authToken = null;

            // Forms Data
            $scope.loginData = { email: '', password: '' };
            $scope.regData = { name: '', email: '', password: '', password_confirmation: '', role: 'User' };
            $scope.pwData = { current_password: '', new_password: '', new_password_confirmation: '' };

            // User Filter & Pagination
            $scope.userFilter = { search: '', status: '' };
            $scope.usersList = { data: [], current_page: 1, last_page: 1, total: 0 };
            $scope.dashData = {};

            // Strength Meter for Registration
            $scope.strengthScore = 0;
            $scope.strengthLabel = 'Empty';
            $scope.strengthColor = '#e2e8f0';
            $scope.strengthBadgeClass = 'bg-secondary';
            $scope.checkPoints = { length: false, uppercase: false, number: false, special: false };

            // Strength Meter for Password Change
            $scope.pwStrengthScore = 0;
            $scope.pwStrengthLabel = 'Empty';
            $scope.pwStrengthColor = '#e2e8f0';
            $scope.pwStrengthBadgeClass = 'bg-secondary';

            // Chart instances
            var trendChartInstance = null;
            var ratioChartInstance = null;

            // Initialize Application & Restore Session from LocalStorage
            $scope.init = function() {
                var savedToken = localStorage.getItem('auth_token');
                var savedUser = localStorage.getItem('auth_user');

                if (savedToken && savedUser) {
                    try {
                        $scope.authToken = savedToken;
                        $scope.currentUser = JSON.parse(savedUser);
                        $scope.currentView = 'dashboard';
                        $scope.loadDashboardData();
                        $scope.fetchUsers(1);
                    } catch (e) {
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_user');
                    }
                }
            };

            // Switch Active View
            $scope.switchView = function(view) {
                $scope.currentView = view;
                if (view === 'dashboard') {
                    $scope.loadDashboardData();
                    $scope.fetchUsers(1);
                }
            };

            // Quick Demo Admin Fill
            $scope.quickFillAdmin = function() {
                $scope.loginData.email = 'admin@example.com';
                $scope.loginData.password = 'Admin@1234';
            };

            // ==============================================================
            // FEATURE 2: PASSWORD STRENGTH METER & 4 REAL-TIME CHECKPOINTS
            // ==============================================================
            $scope.calculatePasswordStrength = function(pwd) {
                if (!pwd) {
                    $scope.strengthScore = 0;
                    $scope.strengthLabel = 'Empty';
                    $scope.strengthColor = '#e2e8f0';
                    $scope.strengthBadgeClass = 'bg-secondary';
                    $scope.checkPoints = { length: false, uppercase: false, number: false, special: false };
                    return;
                }

                var points = 0;
                $scope.checkPoints.length = pwd.length >= 8;
                $scope.checkPoints.uppercase = /[A-Z]/.test(pwd);
                $scope.checkPoints.number = /[0-9]/.test(pwd);
                $scope.checkPoints.special = /[@$!%*?&#^~]/.test(pwd);

                if ($scope.checkPoints.length) points += 25;
                if ($scope.checkPoints.uppercase) points += 25;
                if ($scope.checkPoints.number) points += 25;
                if ($scope.checkPoints.special) points += 25;

                $scope.strengthScore = points;

                if (points <= 25) {
                    $scope.strengthLabel = 'Weak 🔴';
                    $scope.strengthColor = '#ef4444';
                    $scope.strengthBadgeClass = 'bg-danger';
                } else if (points <= 75) {
                    $scope.strengthLabel = 'Medium 🟡';
                    $scope.strengthColor = '#f59e0b';
                    $scope.strengthBadgeClass = 'bg-warning text-dark';
                } else {
                    $scope.strengthLabel = 'Strong 🟢';
                    $scope.strengthColor = '#10b981';
                    $scope.strengthBadgeClass = 'bg-success';
                }
            };

            // Password strength calculation for profile change password
            $scope.calculateNewPwStrength = function(pwd) {
                if (!pwd) {
                    $scope.pwStrengthScore = 0;
                    $scope.pwStrengthLabel = 'Empty';
                    $scope.pwStrengthColor = '#e2e8f0';
                    $scope.pwStrengthBadgeClass = 'bg-secondary';
                    return;
                }
                var pts = 0;
                if (pwd.length >= 8) pts += 25;
                if (/[A-Z]/.test(pwd)) pts += 25;
                if (/[0-9]/.test(pwd)) pts += 25;
                if (/[@$!%*?&#^~]/.test(pwd)) pts += 25;
                $scope.pwStrengthScore = pts;

                if (pts <= 25) {
                    $scope.pwStrengthLabel = 'Weak 🔴';
                    $scope.pwStrengthColor = '#ef4444';
                    $scope.pwStrengthBadgeClass = 'bg-danger';
                } else if (pts <= 75) {
                    $scope.pwStrengthLabel = 'Medium 🟡';
                    $scope.pwStrengthColor = '#f59e0b';
                    $scope.pwStrengthBadgeClass = 'bg-warning text-dark';
                } else {
                    $scope.pwStrengthLabel = 'Strong 🟢';
                    $scope.pwStrengthColor = '#10b981';
                    $scope.pwStrengthBadgeClass = 'bg-success';
                }
            };

            // ==============================================================
            // FEATURE 1: USER REGISTRATION (SPA Transition)
            // ==============================================================
            $scope.handleRegister = function(form) {
                if (form.$invalid || $scope.regData.password !== $scope.regData.password_confirmation) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validation Error',
                        text: 'Please ensure all fields are valid and passwords match.'
                    });
                    return;
                }

                $scope.regLoading = true;
                $http.post('/api/register', $scope.regData)
                    .then(function(res) {
                        $scope.regLoading = false;
                        if (res.data.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Registration Successful!',
                                text: 'Your account has been created. You can now login.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            // Reset form & Switch to login
                            $scope.regData = { name: '', email: '', password: '', password_confirmation: '', role: 'User' };
                            $scope.calculatePasswordStrength('');
                            $scope.switchView('login');
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: res.data.message || 'Registration failed' });
                        }
                    })
                    .catch(function(err) {
                        $scope.regLoading = false;
                        var msg = (err.data && err.data.message) ? err.data.message : 'Server error during registration.';
                        if (err.data && err.data.errors) {
                            msg = Object.values(err.data.errors).flat().join('<br>');
                        }
                        Swal.fire({ icon: 'error', title: 'Registration Failed', html: msg });
                    });
            };

            // ==============================================================
            // FEATURE 1: USER LOGIN & LOCALSTORAGE SESSION
            // ==============================================================
            $scope.handleLogin = function(form) {
                if (form.$invalid) {
                    Swal.fire({ icon: 'warning', title: 'Validation', text: 'Please fill in valid credentials.' });
                    return;
                }

                $scope.loginLoading = true;
                $http.post('/api/login', $scope.loginData)
                    .then(function(res) {
                        $scope.loginLoading = false;
                        if (res.data.status) {
                            $scope.currentUser = res.data.user;
                            $scope.authToken = res.data.token;

                            // Save session to LocalStorage
                            localStorage.setItem('auth_token', res.data.token);
                            localStorage.setItem('auth_user', JSON.stringify(res.data.user));

                            Swal.fire({
                                icon: 'success',
                                title: 'Welcome Back, ' + res.data.user.name + '!',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            $scope.switchView('dashboard');
                        } else {
                            Swal.fire({ icon: 'error', title: 'Authentication Failed', text: res.data.message });
                        }
                    })
                    .catch(function(err) {
                        $scope.loginLoading = false;
                        var msg = (err.data && err.data.message) ? err.data.message : 'Invalid credentials or account issue.';
                        Swal.fire({ icon: 'error', title: 'Login Error', text: msg });
                    });
            };

            // Logout
            $scope.logout = function() {
                localStorage.removeItem('auth_token');
                localStorage.removeItem('auth_user');
                $scope.currentUser = null;
                $scope.authToken = null;
                $scope.loginData = { email: '', password: '' };
                $scope.switchView('login');

                Swal.fire({
                    icon: 'info',
                    title: 'Logged Out',
                    text: 'You have been successfully signed out.',
                    timer: 1500,
                    showConfirmButton: false
                });
            };

            // ==============================================================
            // FEATURE 3: DASHBOARD & CHART.JS VISUAL ANALYTICS
            // ==============================================================
            $scope.loadDashboardData = function() {
                $http.get('/api/dashboard')
                    .then(function(res) {
                        if (res.data.status) {
                            $scope.dashData = res.data;
                            $timeout(function() {
                                $scope.renderCharts(res.data.chart_data);
                            }, 100);
                        }
                    })
                    .catch(function(err) {
                        console.error('Failed to load dashboard statistics', err);
                    });
            };

            $scope.renderCharts = function(chartData) {
                if (!chartData) return;

                // 1. Line Chart: 7-Day Velocity
                var trendCanvas = document.getElementById('registrationTrendChart');
                if (trendCanvas) {
                    if (trendChartInstance) {
                        trendChartInstance.destroy();
                    }
                    var ctx1 = trendCanvas.getContext('2d');
                    trendChartInstance = new Chart(ctx1, {
                        type: 'line',
                        data: {
                            labels: chartData.labels,
                            datasets: [{
                                label: 'New Registrations',
                                data: chartData.trend,
                                borderColor: '#4f46e5',
                                backgroundColor: 'rgba(79, 70, 229, 0.12)',
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#4f46e5',
                                pointBorderColor: '#fff',
                                pointHoverRadius: 6,
                                borderWidth: 3
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { stepSize: 1 }
                                }
                            }
                        }
                    });
                }

                // 2. Doughnut Chart: Account Status / Roles
                var ratioCanvas = document.getElementById('accountRatioChart');
                if (ratioCanvas) {
                    if (ratioChartInstance) {
                        ratioChartInstance.destroy();
                    }
                    var ctx2 = ratioCanvas.getContext('2d');
                    ratioChartInstance = new Chart(ctx2, {
                        type: 'doughnut',
                        data: {
                            labels: ['Active Users', 'Suspended', 'Admins'],
                            datasets: [{
                                data: [
                                    chartData.statuses.Active || 0,
                                    chartData.statuses.Suspended || 0,
                                    chartData.roles.Admin || 0
                                ],
                                backgroundColor: ['#10b981', '#ef4444', '#6366f1'],
                                borderWidth: 2,
                                borderColor: '#ffffff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            },
                            cutout: '65%'
                        }
                    });
                }
            };

            // ==============================================================
            // USER DIRECTORY: PAGINATION, SEARCH, TOGGLE STATUS, DELETE
            // ==============================================================
            $scope.fetchUsers = function(page) {
                var params = {
                    page: page || 1,
                    search: $scope.userFilter.search,
                    status: $scope.userFilter.status,
                    per_page: 5
                };

                $http.get('/api/users', { params: params })
                    .then(function(res) {
                        $scope.usersList = res.data;
                    })
                    .catch(function(err) {
                        console.error('Error fetching users', err);
                    });
            };

            $scope.getPageNumbers = function(totalPages) {
                var pages = [];
                for (var i = 1; i <= totalPages; i++) {
                    pages.push(i);
                }
                return pages;
            };

            // Toggle User Status (Active <-> Suspended)
            $scope.toggleUserStatus = function(user) {
                $http.post('/api/users/' + user.id + '/toggle-status')
                    .then(function(res) {
                        if (res.data.status) {
                            user.status = res.data.user.status;
                            Swal.fire({
                                icon: 'success',
                                title: 'Status Updated',
                                text: res.data.message,
                                timer: 1200,
                                showConfirmButton: false
                            });
                            $scope.loadDashboardData();
                        }
                    })
                    .catch(function(err) {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Could not update user status.' });
                    });
            };

            // Delete User with SweetAlert confirmation
            $scope.confirmDeleteUser = function(user) {
                Swal.fire({
                    title: 'Delete ' + user.name + '?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Delete!'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        $http.delete('/api/users/' + user.id)
                            .then(function(res) {
                                if (res.data.status) {
                                    Swal.fire({ icon: 'success', title: 'Deleted!', text: res.data.message, timer: 1200, showConfirmButton: false });
                                    $scope.fetchUsers($scope.usersList.current_page);
                                    $scope.loadDashboardData();
                                }
                            })
                            .catch(function(err) {
                                Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to delete user.' });
                            });
                    }
                });
            };

            // ==============================================================
            // CHANGE PASSWORD (Profile View)
            // ==============================================================
            $scope.handleChangePassword = function(form) {
                if (form.$invalid || $scope.pwData.new_password !== $scope.pwData.new_password_confirmation) {
                    Swal.fire({ icon: 'warning', title: 'Validation', text: 'Check password fields and matching.' });
                    return;
                }

                $scope.pwLoading = true;
                var payload = {
                    user_id: $scope.currentUser.id,
                    current_password: $scope.pwData.current_password,
                    new_password: $scope.pwData.new_password,
                    new_password_confirmation: $scope.pwData.new_password_confirmation
                };

                $http.post('/api/change-password', payload)
                    .then(function(res) {
                        $scope.pwLoading = false;
                        if (res.data.status) {
                            Swal.fire({ icon: 'success', title: 'Success', text: res.data.message });
                            $scope.pwData = { current_password: '', new_password: '', new_password_confirmation: '' };
                            $scope.calculateNewPwStrength('');
                        } else {
                            Swal.fire({ icon: 'error', title: 'Failed', text: res.data.message });
                        }
                    })
                    .catch(function(err) {
                        $scope.pwLoading = false;
                        var msg = (err.data && err.data.message) ? err.data.message : 'Error changing password.';
                        Swal.fire({ icon: 'error', title: 'Failed', text: msg });
                    });
            };

            // Run Init
            $scope.init();
        });
    </script>
</body>
</html>

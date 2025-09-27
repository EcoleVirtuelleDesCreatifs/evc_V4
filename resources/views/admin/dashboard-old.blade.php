@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('styles')
    <!-- Admin Menu CSS - Module dédié -->
    <link href="{{ asset('css/admin-menu.css') }}" rel="stylesheet">
    <!-- Admin Statistics CSS - Système modulaire -->
    <link href="{{ asset('css/admin-statistics.css') }}" rel="stylesheet">
    <!-- Admin 3D Interface CSS - Interface révolutionnaire -->
    <link href="{{ asset('css/admin-3d-interface.css') }}" rel="stylesheet">
    <!-- Holographic Stats CSS - Interface holographique révolutionnaire -->
    <link href="{{ asset('css/holographic-stats.css') }}" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary-color: #003366;
            --secondary-color: #3399ff;
            --accent-color: #ff6633;
            --warning-color: #FF9900;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --dark-color: #2c3e50;
            --light-color: #f8f9fa;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8f9fa;
            color: #333;
            line-height: 1.6;
            height: 100%;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 320px;
            height: 100vh;
            background: linear-gradient(135deg, #1a1d29 0%, #2c3e50 100%);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Admin Topbar */
        .admin-topbar {
            position: fixed;
            top: 0;
            left: 320px;
            right: 0;
            height: 70px;
            background: linear-gradient(135deg, #1a1d29 0%, #2c3e50 100%);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            z-index: 999;
            transition: all 0.3s ease;
        }

        /* Responsive Sidebar */
        @media (max-width: 768px) {
            .main-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
            }

            .admin-topbar {
                left: 0;
            }
        }

        /* Main Content Area */
        .main-wrapper {
            margin-left: 320px !important;
            min-height: 100vh;
            background: #f8f9fa;
            transition: all 0.3s ease;
            width: calc(100% - 320px) !important;
            position: relative;
        }

        .admin-main {
            padding: 0 !important;
            min-height: 100vh;
            width: 100% !important;
            position: relative;
        }

        .admin-main .container-fluid {
            padding: 2rem !important;
            margin-top: 70px !important; /* Hauteur de la topbar */
            width: 100% !important;
        }

        /* Sidebar States */
        .admin-sidebar.collapsed {
            width: 80px;
        }

        .main-wrapper.collapsed {
            margin-left: 80px !important;
            width: calc(100% - 80px) !important;
        }

        /* Force visibility of main content */
        body {
            overflow-x: hidden;
        }

        .main-wrapper, .admin-main {
            display: block !important;
            visibility: visible !important;
        }

        .admin-sidebar.collapsed .sidebar-header h4,
        .admin-sidebar.collapsed .sidebar-header small,
        .admin-sidebar.collapsed .nav-text,
        .admin-sidebar.collapsed .nav-badge,
        .admin-sidebar.collapsed .nav-arrow {
            opacity: 0;
            transform: translateX(-20px);
        }

        .admin-sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 1rem;
        }

        /* Revolutionary Sidebar Header */
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            text-align: center;
            background: rgba(255, 255, 255, 0.02);
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 120px;
        }

        .sidebar-toggle {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
            border: none;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            z-index: 10;
        }

        .sidebar-toggle:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(255, 102, 51, 0.4);
        }

        .logo {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
            font-size: 1.3rem;
            font-weight: bold;
            box-shadow: 0 8px 25px rgba(255, 102, 51, 0.3);
            transition: all 0.3s ease;
        }

        .admin-sidebar.collapsed .logo {
            width: 35px;
            height: 35px;
            font-size: 1.1rem;
        }

        /* Custom Scrollbar */
        .sidebar-content {
            height: calc(100vh - 120px);
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: 2rem;
        }

        .sidebar-content::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-content::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-content::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--accent-color), var(--secondary-color));
            border-radius: 2px;
            box-shadow: 0 0 10px rgba(255, 102, 51, 0.3);
        }

        .sidebar-content::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #ff8a50, #66b3ff);
            box-shadow: 0 0 15px rgba(255, 102, 51, 0.5);
        }

        /* Revolutionary Nav Links */
        .admin-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 0.875rem 1.25rem;
            border-radius: 14px;
            margin: 0.15rem 0.75rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.03);
            font-weight: 500;
            font-size: 0.85rem;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .nav-text {
            display: flex;
            align-items: center;
            flex: 1;
            transition: all 0.3s ease;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        /* Compact spacing for many menu items */
        .nav-item {
            margin-bottom: 0.1rem;
        }

        .admin-sidebar .nav-link:hover {
            color: white;
            background: linear-gradient(135deg, rgba(255, 102, 51, 0.2), rgba(51, 153, 255, 0.15));
            border-color: rgba(255, 102, 51, 0.4);
            text-decoration: none;
            transform: translateX(8px) scale(1.02);
            box-shadow: 0 6px 25px rgba(255, 102, 51, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .admin-sidebar .nav-link.active {
            color: white;
            background: linear-gradient(135deg, var(--accent-color), rgba(51, 153, 255, 0.9));
            border-color: var(--accent-color);
            text-decoration: none;
            box-shadow: 0 8px 30px rgba(255, 102, 51, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            transform: translateX(5px) scale(1.02);
        }

        /* Dropdown Menu Styles */
        .nav-item.dropdown {
            position: relative;
        }

        .nav-item.dropdown .dropdown-toggle::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            border: none;
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .nav-item.dropdown.show .dropdown-toggle::after {
            transform: rotate(180deg);
        }

        .dropdown-item {
            color: rgba(255, 255, 255, 0.75);
            padding: 0.6rem 1rem 0.6rem 2rem;
            background: transparent;
            border: none;
            border-radius: 10px;
            margin: 0.1rem 0.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: calc(100% - 1rem);
            position: relative;
        }

        .dropdown-item:hover {
            color: white;
            background: linear-gradient(135deg, rgba(255, 102, 51, 0.15), rgba(51, 153, 255, 0.1));
            transform: translateX(6px) scale(1.02);
            box-shadow: 0 4px 15px rgba(255, 102, 51, 0.2);
        }

        .dropdown-item.active {
            color: white;
            background: linear-gradient(135deg, rgba(255, 102, 51, 0.3), rgba(51, 153, 255, 0.2));
            transform: translateX(4px);
            box-shadow: 0 4px 15px rgba(255, 102, 51, 0.3);
        }

        .dropdown-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: linear-gradient(180deg, var(--accent-color), var(--secondary-color));
            border-radius: 0 2px 2px 0;
            transition: height 0.3s ease;
        }

        .dropdown-item:hover::before,
        .dropdown-item.active::before {
            height: 60%;
        }

        .dropdown-item i {
            width: 16px;
            font-size: 0.7rem;
            margin-right: 0.5rem;
            opacity: 0.8;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover i {
            opacity: 1;
            transform: scale(1.1);
        }

        /* Ancien code CSS des menus supprimé - Maintenant géré par admin-menu.css */

        /* Main Wrapper */
        .main-wrapper {
            margin-left: 320px;
            margin-top: 70px;
            min-height: calc(100vh - 70px);
            background: #f8f9fa;
            transition: margin-left 0.3s ease;
            position: relative;
        }

        .main-wrapper.collapsed {
            margin-left: 80px;
        }

        /* Main Content */
        .admin-main {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: calc(100vh - 70px);
            padding: 2rem;
            box-sizing: border-box;
            position: relative;
        }

        /* Compact Revolutionary Topbar */
        .admin-topbar {
            background: linear-gradient(145deg, #0a0e1a 0%, #1a1d29 25%, #16213e 75%, #0f1419 100%);
            backdrop-filter: blur(25px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 0.75rem 1.5rem;
            position: fixed;
            top: 0;
            left: 320px;
            right: 0;
            height: 70px;
            z-index: 1500;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow:
                0 4px 20px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.05);
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            animation: topbarSlideIn 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            color: white;
        }

        .admin-topbar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg,
                transparent 0%,
                var(--primary-color) 20%,
                var(--accent-color) 50%,
                var(--primary-color) 80%,
                transparent 100%);
            animation: topbarGlow 3s ease-in-out infinite;
        }

        .admin-topbar.scrolled {
            padding: 0.625rem 2rem;
            box-shadow:
                0 8px 40px rgba(51, 153, 255, 0.12),
                0 4px 20px rgba(0, 0, 0, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
        }

        @keyframes topbarSlideIn {
            0% {
                transform: translateY(-100%);
                opacity: 0;
            }
            70% {
                transform: translateY(5px);
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes topbarGlow {
            0%, 100% {
                opacity: 0.3;
                transform: scaleX(0.8);
            }
            50% {
                opacity: 0.8;
                transform: scaleX(1.2);
            }
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: slideInLeft 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) 0.1s both;
        }

        .topbar-title {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            color: white;
            position: relative;
        }

        .topbar-breadcrumb {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
            margin: 0;
            opacity: 0.9;
            font-weight: 500;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: slideInRight 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) 0.3s both;
        }

        @keyframes slideInLeft {
            0% {
                transform: translateX(-50px);
                opacity: 0;
            }
            100% {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideInRight {
            0% {
                transform: translateX(50px);
                opacity: 0;
            }
            100% {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            0% {
                transform: translateY(20px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 0.8;
            }
        }

        @keyframes titlePulse {
            0%, 100% {
                filter: brightness(1);
            }
            50% {
                filter: brightness(1.1);
            }
        }

        /* Compact Search Bar */
        .topbar-search {
            position: relative;
            width: 280px;
            animation: searchBarFloat 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) 0.2s both;
        }

        .topbar-search input {
            width: 100%;
            padding: 0.6rem 1rem 0.6rem 2.5rem;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            font-size: 0.85rem;
            font-weight: 400;
            color: white;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            box-shadow:
                0 2px 12px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .topbar-search input:focus {
            outline: none;
            border-color: var(--primary-color);
            background: rgba(255, 255, 255, 0.15);
            box-shadow:
                0 4px 20px rgba(51, 153, 255, 0.2),
                0 0 0 3px rgba(51, 153, 255, 0.15);
        }

        .topbar-search input::placeholder {
            color: rgba(255, 255, 255, 0.6);
            font-weight: 400;
        }

        .topbar-search .search-icon {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .topbar-search:focus-within .search-icon {
            color: var(--primary-color);
        }

        @keyframes searchBarFloat {
            0% {
                transform: translateY(-20px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes searchGlow {
            0%, 100% {
                background-size: 200% 200%;
                background-position: 0% 50%;
            }
            50% {
                background-size: 200% 200%;
                background-position: 100% 50%;
            }
        }

        @keyframes searchIconPulse {
            0%, 100% {
                opacity: 0.7;
            }
            50% {
                opacity: 1;
            }
        }

        /* Compact Notification Bell */
        .notification-bell {
            position: relative;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            box-shadow:
                0 2px 12px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.8);
        }

        .notification-bell {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            box-shadow:
                0 2px 12px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.8);
        }

        .notification-bell:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-1px);
            box-shadow:
                0 4px 20px rgba(51, 153, 255, 0.3),
                0 2px 8px rgba(0, 0, 0, 0.2);
            border-color: var(--primary-color);
        }

        .notification-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: var(--accent-color);
            color: white;
            border: 2px solid white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(255, 102, 51, 0.3);
        }

        @keyframes notificationFloat {
            0% {
                transform: translateY(-30px) rotate(-10deg);
                opacity: 0;
            }
            60% {
                transform: translateY(5px) rotate(2deg);
            }
            100% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
            }
        }

        @keyframes bellRing {
            0%, 100% { transform: translateY(0) scale(1.02) rotate(0deg); }
            10% { transform: translateY(0) scale(1.02) rotate(-10deg); }
            20% { transform: translateY(0) scale(1.02) rotate(10deg); }
            30% { transform: translateY(0) scale(1.02) rotate(-10deg); }
            40% { transform: translateY(0) scale(1.02) rotate(10deg); }
            50% { transform: translateY(0) scale(1.02) rotate(-5deg); }
            60% { transform: translateY(0) scale(1.02) rotate(5deg); }
            70% { transform: translateY(0) scale(1.02) rotate(-2deg); }
            80% { transform: translateY(0) scale(1.02) rotate(2deg); }
            90% { transform: translateY(0) scale(1.02) rotate(-1deg); }
        }

        @keyframes badgePulse {
            0%, 100% {
                transform: scale(1);
                box-shadow:
                    0 4px 16px rgba(255, 102, 51, 0.3),
                    0 2px 8px rgba(0, 0, 0, 0.1);
            }
            50% {
                transform: scale(1.15);
                box-shadow:
                    0 6px 24px rgba(255, 102, 51, 0.5),
                    0 3px 12px rgba(0, 0, 0, 0.15);
            }
        }

        /* Compact Quick Actions */
        .quick-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quick-action-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            color: rgba(255, 255, 255, 0.8);
            box-shadow:
                0 2px 8px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .quick-action-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-1px);
            box-shadow:
                0 4px 16px rgba(51, 153, 255, 0.3),
                0 2px 8px rgba(0, 0, 0, 0.2);
            border-color: var(--primary-color);
        }

        @keyframes actionsSlideIn {
            0% {
                transform: translateX(100px);
                opacity: 0;
            }
            100% {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes rippleEffect {
            0% {
                box-shadow:
                    0 8px 32px rgba(51, 153, 255, 0.25),
                    0 4px 16px rgba(255, 102, 51, 0.15),
                    0 0 0 0 rgba(51, 153, 255, 0.4);
            }
            100% {
                box-shadow:
                    0 8px 32px rgba(51, 153, 255, 0.25),
                    0 4px 16px rgba(255, 102, 51, 0.15),
                    0 0 0 20px rgba(51, 153, 255, 0);
            }
        }

        /* Compact Profile Dropdown */
        .profile-dropdown {
            position: relative;
        }

        .profile-trigger {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            box-shadow:
                0 2px 12px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .profile-trigger:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--primary-color);
            transform: translateY(-1px);
            box-shadow:
                0 4px 20px rgba(51, 153, 255, 0.2),
                0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-info h6 {
            margin: 0;
            font-size: 0.875rem;
            font-weight: 600;
            color: white;
        }

        .profile-info span {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
        }

        .profile-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg,
                var(--primary-color) 0%,
                var(--accent-color) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
            box-shadow: 0 2px 8px rgba(51, 153, 255, 0.2);
        }

        .profile-info h6 {
            margin: 0;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .profile-info span {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
        }

        @keyframes profileSlideIn {
            0% {
                transform: translateX(50px) scale(0.8);
                opacity: 0;
            }
            70% {
                transform: translateX(-5px) scale(1.02);
            }
            100% {
                transform: translateX(0) scale(1);
                opacity: 1;
            }
        }

        /* Profile Dropdown */
        .profile-dropdown {
            position: relative;
        }

        .profile-trigger {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem;
            border-radius: 25px;
            background: rgba(248, 249, 250, 0.6);
            border: 1px solid rgba(0, 0, 0, 0.08);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .profile-trigger:hover {
            background: rgba(51, 153, 255, 0.1);
            border-color: var(--primary-color);
        }

        .profile-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .profile-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--dark-color);
            margin: 0;
        }

        .profile-role {
            font-size: 0.75rem;
            color: #6c757d;
            margin: 0;
        }

        .profile-chevron {
            color: #6c757d;
            font-size: 0.75rem;
            transition: transform 0.3s ease;
        }

        .profile-dropdown.show .profile-chevron {
            transform: rotate(180deg);
        }

        /* Profile Dropdown Menu */
        .profile-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(0, 0, 0, 0.08);
            min-width: 220px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            margin-top: 0.5rem;
        }

        .profile-dropdown.show .profile-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .profile-menu-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.25rem;
            color: #495057;
            text-decoration: none;
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .profile-menu-item:last-child {
            border-bottom: none;
        }

        .profile-menu-item:hover {
            background: rgba(51, 153, 255, 0.05);
            color: var(--primary-color);
        }

        .profile-menu-item i {
            width: 16px;
            font-size: 0.875rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-topbar {
                padding: 0.75rem 1rem;
            }

            .topbar-search {
                width: 200px;
            }

            .topbar-title {
                font-size: 1.25rem;
            }

            .profile-info {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .topbar-search {
                display: none;
            }

            .quick-actions {
                gap: 0.25rem;
            }
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary-color);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .stats-card.users { border-left-color: var(--secondary-color); }
        .stats-card.documents { border-left-color: var(--warning-color); }
        .stats-card.tps { border-left-color: var(--success-color); }
        .stats-card.sessions { border-left-color: var(--accent-color); }

        .stats-card .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1rem;
        }

        .stats-card.users .stats-icon { background: linear-gradient(135deg, var(--secondary-color), #66b3ff); }
        .stats-card.documents .stats-icon { background: linear-gradient(135deg, var(--warning-color), #ffb84d); }
        .stats-card.tps .stats-icon { background: linear-gradient(135deg, var(--success-color), #5cbf60); }
        .stats-card.sessions .stats-icon { background: linear-gradient(135deg, var(--accent-color), #ff8c66); }

        .stats-card h3 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            color: var(--dark-color);
        }

        .stats-card p {
            margin: 0;
            color: #6c757d;
            font-weight: 500;
        }

        .stats-card .growth {
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .growth.positive { color: var(--success-color); }
        .growth.negative { color: var(--danger-color); }

        /* Menu Statistics Cards */
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .menu-stats-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .menu-stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .menu-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f8f9fa;
        }

        .header-left {
            display: flex;
            align-items: center;
        }

        .header-left i {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-right: 0.75rem;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 12px;
        }

        .header-left h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
        }

        .header-actions {
            display: flex;
            gap: 0.5rem;
        }

        .detail-btn, .action-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-btn:hover, .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(51, 153, 255, 0.3);
        }

        .action-btn {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .action-btn:hover {
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        .stat-item {
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .stat-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Interactive Elements */
        .stat-item.clickable {
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .stat-item.clickable:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .hover-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(51, 153, 255, 0.9), rgba(40, 167, 69, 0.9));
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            border-radius: 15px;
        }

        .hover-overlay i {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .stat-item.clickable:hover .hover-overlay {
            opacity: 1;
            visibility: visible;
        }

        /* Progress Bars */
        .card-progress {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 3px;
            transition: width 1s ease;
            animation: progressAnimation 2s ease-in-out;
        }

        .progress-text {
            font-size: 0.8rem;
            color: #6c757d;
            font-weight: 500;
        }

        @keyframes progressAnimation {
            0% { width: 0; }
            100% { width: var(--target-width); }
        }

        /* Interactive Card Effects */
        .interactive-card {
            position: relative;
            overflow: visible;
        }

        .interactive-card::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color), var(--success-color));
            border-radius: 22px;
            opacity: 0;
            z-index: -1;
            transition: opacity 0.3s ease;
        }

        .interactive-card:hover::before {
            opacity: 0.1;
        }

        .stat-item .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--secondary-color), #66b3ff);
        }

        .stat-item.students .stat-icon { background: linear-gradient(135deg, #007bff, #0056b3); }
        .stat-item.design .stat-icon { background: linear-gradient(135deg, #e83e8c, #c2185b); }
        .stat-item.community .stat-icon { background: linear-gradient(135deg, #20c997, #17a2b8); }
        .stat-item.active .stat-icon { background: linear-gradient(135deg, #28a745, #20c997); }
        .stat-item.formations .stat-icon { background: linear-gradient(135deg, #6f42c1, #5a32a3); }
        .stat-item.modules .stat-icon { background: linear-gradient(135deg, #fd7e14, #e55a00); }
        .stat-item.tp-total .stat-icon { background: linear-gradient(135deg, #17a2b8, #138496); }
        .stat-item.tp-pending .stat-icon { background: linear-gradient(135deg, #ffc107, #e0a800); }
        .stat-item.tp-validated .stat-icon { background: linear-gradient(135deg, #28a745, #1e7e34); }
        .stat-item.tp-rejected .stat-icon { background: linear-gradient(135deg, #dc3545, #c82333); }
        .stat-item.projects-total .stat-icon { background: linear-gradient(135deg, #6610f2, #520dc2); }
        .stat-item.projects-progress .stat-icon { background: linear-gradient(135deg, #17a2b8, #138496); }
        .stat-item.projects-done .stat-icon { background: linear-gradient(135deg, #28a745, #1e7e34); }
        .stat-item.articles-total .stat-icon { background: linear-gradient(135deg, #fd7e14, #e55a00); }
        .stat-item.articles-recent .stat-icon { background: linear-gradient(135deg, #007bff, #0056b3); }
        .stat-item.articles-categories .stat-icon { background: linear-gradient(135deg, #6c757d, #5a6268); }
        .stat-item.docs-total .stat-icon { background: linear-gradient(135deg, #343a40, #23272b); }
        .stat-item.docs-pending .stat-icon { background: linear-gradient(135deg, #ffc107, #e0a800); }
        .stat-item.docs-validated .stat-icon { background: linear-gradient(135deg, #28a745, #1e7e34); }
        .stat-item.docs-rejected .stat-icon { background: linear-gradient(135deg, #dc3545, #c82333); }
        .stat-item.payments-total .stat-icon { background: linear-gradient(135deg, #28a745, #1e7e34); }
        .stat-item.payments-uptodate .stat-icon { background: linear-gradient(135deg, #20c997, #17a2b8); }
        .stat-item.payments-pending .stat-icon { background: linear-gradient(135deg, #ffc107, #e0a800); }
        .stat-item.payments-late .stat-icon { background: linear-gradient(135deg, #dc3545, #c82333); }
        .stat-item.applications-total .stat-icon { background: linear-gradient(135deg, #6f42c1, #5a32a3); }
        .stat-item.applications-accepted .stat-icon { background: linear-gradient(135deg, #28a745, #1e7e34); }
        .stat-item.applications-pending .stat-icon { background: linear-gradient(135deg, #ffc107, #e0a800); }
        .stat-item.applications-rejected .stat-icon { background: linear-gradient(135deg, #dc3545, #c82333); }
        .stat-item.reports-total .stat-icon { background: linear-gradient(135deg, #17a2b8, #138496); }
        .stat-item.reports-monthly .stat-icon { background: linear-gradient(135deg, #007bff, #0056b3); }
        .stat-item.reports-yearly .stat-icon { background: linear-gradient(135deg, #6f42c1, #5a32a3); }
        .stat-item.admin-total .stat-icon { background: linear-gradient(135deg, #343a40, #23272b); }
        .stat-item.admin-active .stat-icon { background: linear-gradient(135deg, #28a745, #1e7e34); }
        .stat-item.admin-super .stat-icon { background: linear-gradient(135deg, #ffc107, #e0a800); }

        .stat-content h4 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
            color: var(--dark-color);
        }

        .stat-content p {
            margin: 0 0 0.5rem 0;
            color: #6c757d;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .stat-content .growth {
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
        }

        /* Completion Rate Bar */
        .completion-rate {
            margin-top: 1rem;
        }

        .rate-label {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .rate-bar {
            width: 100%;
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .rate-fill {
            height: 100%;
            background: linear-gradient(135deg, var(--success-color), #20c997);
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .rate-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--success-color);
            text-align: center;
        }

        /* INTERFACE RÉVOLUTIONNAIRE - STYLES AVANCÉS */

        /* Dashboard Container */
        .dashboard-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero-metrics-section {
            position: relative;
            background: linear-gradient(135deg, #1a1d29 0%, #2c3e50 50%, #34495e 100%);
            border-radius: 0 0 30px 30px;
            padding: 2rem 0;
            margin: -2rem -2rem 0 -2rem;
            color: white;
            overflow: hidden;
        }

        .hero-metrics-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .hero-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
            margin-bottom: 2rem;
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin: 0;
            position: relative;
        }

        .gradient-text {
            background: linear-gradient(135deg, #3399ff, #00ff88, #ff6b6b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 3s ease-in-out infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .title-decoration {
            height: 4px;
            background: linear-gradient(135deg, #3399ff, #00ff88);
            border-radius: 2px;
            margin-top: 0.5rem;
            animation: decorationGlow 2s ease-in-out infinite;
        }

        @keyframes decorationGlow {
            0%, 100% { box-shadow: 0 0 10px rgba(51, 153, 255, 0.5); }
            50% { box-shadow: 0 0 20px rgba(0, 255, 136, 0.8); }
        }

        .hero-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin: 0.5rem 0 0 0;
            font-weight: 400;
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
        }

        .hero-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            backdrop-filter: blur(10px);
        }

        .hero-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .hero-btn.primary {
            background: linear-gradient(135deg, #3399ff, #0066cc);
            border: none;
        }

        .hero-btn.secondary {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
        }

        .hero-btn.tertiary {
            background: linear-gradient(135deg, #6f42c1, #5a32a3);
            border: none;
        }

        /* Métriques Principales Ultra-Modernes */
        .main-metrics {
            padding: 0 2rem;
            position: relative;
            z-index: 2;
        }

        .metric-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            position: relative;
            overflow: visible;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            min-height: 280px;
            height: auto;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .metric-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }

        .metric-background {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0.1;
            overflow: hidden;
        }

        .metric-pattern {
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, currentColor 1px, transparent 1px);
            background-size: 20px 20px;
            animation: patternMove 20s linear infinite;
        }

        @keyframes patternMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(20px, 20px); }
        }

        .metric-content {
            position: relative;
            z-index: 2;
        }

        .metric-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .metric-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .metric-card.primary .metric-icon {
            background: linear-gradient(135deg, #3399ff, #0066cc);
        }

        .metric-card.success .metric-icon {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .metric-card.warning .metric-icon {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
        }

        .metric-card.info .metric-icon {
            background: linear-gradient(135deg, #17a2b8, #138496);
        }

        .metric-trend {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.875rem;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
        }

        .metric-trend.positive {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .metric-trend.negative {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .metric-trend.neutral {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }

        .metric-value {
            font-size: 3rem;
            font-weight: 800;
            color: #2c3e50;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .metric-value .unit {
            font-size: 1.5rem;
            opacity: 0.7;
        }

        .metric-label {
            font-size: 1rem;
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .metric-details {
            font-size: 0.875rem;
            color: #8a8a8a;
        }

        .metric-chart {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50px;
            opacity: 0.3;
        }

        /* Navigation Rapide */
        .quick-nav-section {
            padding: 0 2rem;
        }

        .quick-nav-container {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-filters {
            display: flex;
            gap: 0.5rem;
        }

        .filter-btn {
            background: transparent;
            border: 2px solid #e9ecef;
            color: #6c757d;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-btn:hover, .filter-btn.active {
            background: linear-gradient(135deg, #3399ff, #0066cc);
            border-color: #3399ff;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(51, 153, 255, 0.3);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .search-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-container i {
            position: absolute;
            left: 1rem;
            color: #6c757d;
            z-index: 2;
        }

        .search-container input {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            padding: 0.75rem 1rem 0.75rem 3rem;
            width: 300px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .search-container input:focus {
            outline: none;
            border-color: #3399ff;
            background: white;
            box-shadow: 0 0 0 3px rgba(51, 153, 255, 0.1);
        }

        .action-btn {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            color: #6c757d;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: #3399ff;
            border-color: #3399ff;
            color: white;
            transform: scale(1.1);
        }

        /* Section Title Avancée */
        .section-title.advanced {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .title-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #3399ff, #0066cc);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-right: 1.5rem;
        }

        .title-content {
            flex: 1;
        }

        .title-main {
            font-size: 1.75rem;
            font-weight: 800;
            color: #2c3e50;
            display: block;
        }

        .title-sub {
            font-size: 1rem;
            color: #6c757d;
            font-weight: 500;
        }

        .title-actions {
            display: flex;
            gap: 1rem;
        }

        .title-btn {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .title-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
        }

        /* Animations de Compteur */
        .counter {
            display: inline-block;
            transition: all 0.3s ease;
        }

        /* STYLES POUR LES SECTIONS DÉTAILLÉES */

        /* Analytics Cards */
        .analytics-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .analytics-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .analytics-card .card-header {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .analytics-card .card-title {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            display: flex;
            align-items: center;
        }

        .analytics-card .card-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .analytics-card .card-body {
            padding: 1.5rem;
        }

        /* Formation Legend */
        .formation-legend {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9rem;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* Student Info */
        .student-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .student-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3399ff, #0066cc);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .student-details {
            flex: 1;
        }

        .student-name {
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.9rem;
        }

        .student-email {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .progress-sm {
            height: 6px;
            margin-bottom: 0.25rem;
        }

        /* Activity Timeline */
        .activity-timeline {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem;
            border-radius: 12px;
            background: rgba(248, 249, 250, 0.5);
            transition: all 0.3s ease;
        }

        .activity-item:hover {
            background: rgba(248, 249, 250, 0.8);
            transform: translateX(5px);
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .activity-desc {
            color: #6c757d;
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }

        .activity-time {
            color: #adb5bd;
            font-size: 0.8rem;
        }

        /* Performance Metrics */
        .performance-metric {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            background: rgba(248, 249, 250, 0.5);
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .performance-metric:hover {
            background: rgba(248, 249, 250, 0.8);
            transform: translateY(-2px);
        }

        .performance-metric .metric-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .performance-metric .metric-data {
            flex: 1;
        }

        .performance-metric .metric-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.25rem;
        }

        .performance-metric .metric-label {
            color: #6c757d;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }

        .performance-metric .metric-change {
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .performance-metric .metric-change.positive {
            color: #28a745;
        }

        .performance-metric .metric-change.negative {
            color: #dc3545;
        }

        /* Alert System */
        .alert-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .alert-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.5rem;
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .alert-item.critical {
            background: rgba(220, 53, 69, 0.1);
            border-left: 4px solid #dc3545;
        }

        .alert-item.warning {
            background: rgba(255, 193, 7, 0.1);
            border-left: 4px solid #ffc107;
        }

        .alert-item.info {
            background: rgba(23, 162, 184, 0.1);
            border-left: 4px solid #17a2b8;
        }

        .alert-item:hover {
            transform: translateX(5px);
        }

        .alert-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .alert-item.critical .alert-icon {
            background: #dc3545;
            color: white;
        }

        .alert-item.warning .alert-icon {
            background: #ffc107;
            color: white;
        }

        .alert-item.info .alert-icon {
            background: #17a2b8;
            color: white;
        }

        .alert-content {
            flex: 1;
        }

        .alert-title {
            font-weight: 600;
            color: #2c3e50;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .alert-desc {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .alert-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        /* Schedule List */
        .schedule-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .schedule-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 12px;
            background: rgba(248, 249, 250, 0.5);
            transition: all 0.3s ease;
        }

        .schedule-item:hover {
            background: rgba(248, 249, 250, 0.8);
            transform: translateX(5px);
        }

        .schedule-item.upcoming {
            background: rgba(51, 153, 255, 0.1);
            border-left: 4px solid #3399ff;
        }

        .schedule-time {
            font-weight: 600;
            color: #3399ff;
            font-size: 0.9rem;
            min-width: 60px;
        }

        .schedule-content {
            flex: 1;
        }

        .schedule-title {
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .schedule-desc {
            color: #6c757d;
            font-size: 0.8rem;
        }

        /* Responsive Design Avancé et Complet */
        @media (max-width: 1400px) {
            .metric-card {
                min-height: 280px;
                padding: 1.5rem;
            }

            .metric-value {
                font-size: 2.8rem;
            }

            .metric-chart {
                height: 120px !important;
            }
        }

        @media (max-width: 1200px) {
            .hero-title {
                font-size: 2rem;
            }

            .metric-value {
                font-size: 2.5rem;
            }

            .metric-card {
                min-height: 300px;
                padding: 1.5rem;
            }

            .metric-info {
                margin-top: 1rem;
            }

            .trend-indicator {
                font-size: 0.9rem;
                margin-top: 0.75rem;
            }
        }

        @media (max-width: 992px) {
            .hero-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .hero-actions {
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.75rem;
            }

            .hero-btn {
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
            }

            .quick-nav-container {
                flex-direction: column;
                gap: 1rem;
            }

            .nav-filters {
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.5rem;
            }

            .filter-btn {
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
            }

            .search-container input {
                width: 100%;
                max-width: 300px;
            }

            .metric-card {
                height: auto;
                min-height: 320px;
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .metric-value {
                font-size: 2.2rem;
            }

            .metric-chart {
                height: 140px !important;
                margin: 1rem 0;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }

            .hero-metrics-section {
                padding: 2rem 1rem;
            }

            .metric-card {
                min-height: 280px;
                padding: 1.25rem;
                margin-bottom: 1rem;
            }

            .metric-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .metric-icon {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }

            .metric-value {
                font-size: 2rem;
                margin: 0.5rem 0;
            }

            .metric-label {
                font-size: 1rem;
            }

            .metric-chart {
                height: 120px !important;
                margin: 0.75rem 0;
            }

            .trend-indicator {
                font-size: 0.85rem;
                padding: 0.4rem 0.8rem;
            }

            .quick-nav-section {
                padding: 1.5rem 1rem;
            }

            .search-container {
                width: 100%;
                margin-bottom: 1rem;
            }

            .search-container input {
                width: 100%;
                padding: 0.75rem 1rem;
            }
        }

        @media (max-width: 576px) {
            .hero-metrics-section {
                margin: -1rem -0.5rem 0 -0.5rem;
                border-radius: 0 0 20px 20px;
                padding: 1.5rem 0.5rem;
            }

            .main-metrics, .quick-nav-section {
                padding: 0 0.5rem;
            }

            .metric-card {
                min-height: 260px;
                padding: 1rem;
                border-radius: 15px;
            }

            .metric-value {
                font-size: 1.8rem;
            }

            .metric-chart {
                height: 100px !important;
            }

            .hero-btn {
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
            }

            .filter-btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }

            .trend-indicator {
                font-size: 0.8rem;
                padding: 0.3rem 0.6rem;
            }
        }

        @media (max-width: 480px) {
            .metric-card {
                min-height: 240px;
                padding: 0.875rem;
            }

            .metric-value {
                font-size: 1.6rem;
            }

            .metric-chart {
                height: 80px !important;
            }

            .hero-title {
                font-size: 1.5rem;
            }

            .hero-subtitle {
                font-size: 0.9rem;
            }
        }

        /* Content Cards */
        .content-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .content-card .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.25rem 1.5rem;
            border: none;
        }

        .content-card .card-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .content-card .card-body {
            padding: 1.5rem;
        }

        /* Tables */
        .table {
            margin: 0;
        }

        .table th {
            border: none;
            font-weight: 600;
            color: var(--dark-color);
            background: var(--light-color);
        }

        .table td {
            border: none;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        /* Badges */
        .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
        }

        .badge.bg-warning { background-color: var(--warning-color) !important; }
        .badge.bg-success { background-color: var(--success-color) !important; }
        .badge.bg-danger { background-color: var(--danger-color) !important; }

        /* Buttons */
        .btn-admin {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 51, 102, 0.3);
            color: white;
        }

        /* Activity Timeline */
        .activity-item {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #e9ecef;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 0.875rem;
        }

        .activity-icon.user { background: linear-gradient(135deg, var(--secondary-color), #66b3ff); color: white; }
        .activity-icon.document { background: linear-gradient(135deg, var(--warning-color), #ffb84d); color: white; }
        .activity-icon.tp { background: linear-gradient(135deg, var(--success-color), #5cbf60); color: white; }

        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.875rem;
            font-size: 1.1rem;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(255, 102, 51, 0.4);
            transition: all 0.3s ease;
        }

        .mobile-menu-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(255, 102, 51, 0.5);
        }

        .mobile-menu-btn.active {
            background: linear-gradient(135deg, var(--accent-color), var(--warning-color));
        }

        /* Sidebar Overlay for Mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
        }

        /* Responsive Breakpoints */

        /* Large screens (1200px+) */
        @media (min-width: 1200px) {
            .admin-main {
                padding: 2.5rem;
            }

            .stats-card h3 {
                font-size: 2.5rem;
            }
        }

        /* Medium screens (992px - 1199px) */
        @media (max-width: 1199px) and (min-width: 992px) {
            .admin-sidebar {
                width: 250px;
            }

            .admin-main {
                margin-left: 250px;
                padding: 1.5rem;
            }

            .stats-card h3 {
                font-size: 1.8rem;
            }
        }

        /* Small screens (768px - 991px) */
        @media (max-width: 991px) and (min-width: 768px) {
            .admin-sidebar {
                width: 220px;
            }

            .admin-main {
                margin-left: 220px;
                padding: 1.5rem;
            }

            .admin-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .stats-card {
                margin-bottom: 1rem;
            }

            .stats-card h3 {
                font-size: 1.6rem;
            }
        }

        /* Mobile & Responsive */
        @media (max-width: 1024px) {
            .admin-sidebar {
                width: 300px;
            }
        }

        @media (max-width: 767px) {
            .mobile-menu-btn {
                display: block;
            }

            .sidebar-overlay {
                display: block;
            }

            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
                width: 280px;
                z-index: 1000;
                box-shadow: 8px 0 40px rgba(0, 0, 0, 0.5);
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .dropdown-item {
                padding: 0.5rem 0.75rem 0.5rem 1.75rem;
                font-size: 0.75rem;
            }

            .nav-link {
                padding: 0.75rem 1rem;
                font-size: 0.8rem;
            }

            .admin-main {
                margin-left: 0;
            }

            .admin-main.collapsed {
                margin-left: 0;
            }
        }

            /* FORCER L'AFFICHAGE DES SOUS-MENUS SUR MOBILE */
            .nav-item.dropdown .dropdown-menu {
                max-height: none !important;
                display: block !important;
                position: static !important;
                opacity: 1 !important;
                visibility: visible !important;
                transform: none !important;
            }
            
            .nav-item.dropdown:not(.show) .dropdown-menu {
                display: none !important;
            }

            .nav-badge {
                font-size: 0.6rem;
                padding: 0.1rem 0.3rem;
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
                padding: 1rem;
                padding-top: 5rem; /* Space for mobile menu button */
            }

            .admin-header {
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .admin-header h1 {
                font-size: 1.5rem;
            }

            .admin-info {
                justify-content: center;
            }

            .stats-card {
                margin-bottom: 1rem;
                padding: 1rem;
            }

            .stats-card h3 {
                font-size: 1.5rem;
            }

            .stats-card .stats-icon {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }

            .content-card .card-body {
                padding: 1rem;
            }

            .table-responsive {
                font-size: 0.875rem;
            }

            .activity-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .activity-icon {
                align-self: flex-start;
            }
        }

        /* Extra small screens (up to 480px) */
        @media (max-width: 480px) {
            .admin-main {
                padding: 0.5rem;
                padding-top: 4.5rem;
            }

            .admin-header {
                padding: 0.75rem;
                margin-bottom: 1rem;
            }

            .admin-header h1 {
                font-size: 1.25rem;
            }

            .stats-card {
                padding: 0.75rem;
            }

            .stats-card h3 {
                font-size: 1.25rem;
            }

            .stats-card p {
                font-size: 0.875rem;
            }

            .content-card .card-header {
                padding: 1rem;
            }

            .content-card .card-header h5 {
                font-size: 1rem;
            }

            .btn-admin {
                font-size: 0.875rem;
                padding: 0.5rem 1rem;
            }

            .table {
                font-size: 0.75rem;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }

        /* Chart Container */
        .chart-container {
            position: relative;
            height: 300px;
            margin: 1rem 0;
        }

        /* Badge Styles */
        .nav-badge {
            background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
            color: white;
            font-size: 0.65rem;
            padding: 0.15rem 0.4rem;
            border-radius: 8px;
            margin-left: auto;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(255, 102, 51, 0.3);
        }

        .nav-badge.warning {
            background: linear-gradient(135deg, var(--warning-color), #ffa726);
            box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
        }

        .nav-badge.success {
            background: linear-gradient(135deg, var(--success-color), #66bb6a);
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
        }

        /* Arrow Styles */
        .nav-arrow {
            opacity: 0.6;
            font-size: 0.7rem;
            transition: all 0.3s ease;
            transform: rotate(0deg);
        }

        .nav-link:hover .nav-arrow {
            opacity: 1;
            transform: translateX(3px) rotate(0deg);
        }

        .nav-link[aria-expanded="true"] .nav-arrow {
            transform: rotate(90deg);
        }

        /* Item Icons */
        .item-icon {
            opacity: 0.5;
            font-size: 0.6rem;
            transition: all 0.3s ease;
            flex-shrink: 0;
            margin-left: 0.25rem;
        }

        .dropdown-item:hover .item-icon {
            opacity: 1;
            transform: translateX(2px);
        }

        /* Nav Link Icons */
        .nav-link i {
            width: 18px;
            font-size: 0.9rem;
            margin-right: 0.75rem;
            opacity: 0.9;
            transition: all 0.3s ease;
        }

        .nav-link:hover i {
            opacity: 1;
            transform: scale(1.1);
        }

        .sidebar-header h4 {
            margin: 0 0 0.25rem;
            font-weight: 700;
            color: white;
            font-size: 1rem;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .sidebar-header small {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" onclick="toggleSidebar()" id="mobileMenuBtn">
        <i class="fas fa-bars" id="menuIcon"></i>
    </button>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="closeSidebar()" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <nav class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <button class="sidebar-toggle" onclick="toggleSidebar()" title="Réduire/Étendre">
                <i class="fas fa-bars" id="toggle-icon"></i>
            </button>
            <div class="logo">EVC</div>
            <h4>Admin Panel</h4>
            <small>École Virtuelle des Créatifs</small>
        </div>

        <div class="sidebar-content">
            <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}" role="button">
                    <div class="nav-text">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </div>
                </a>
            </li>

            <!-- Gestion des Étudiants -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#">
                    <div class="nav-text">
                        <i class="fas fa-users"></i>
                        Gestion des Étudiants
                    </div>
                    <div class="nav-right">
                        <span class="nav-badge">4</span>
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </div>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('admin.etudiants.design-graphique') }}">
                        <i class="fas fa-palette"></i>Design Graphique
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.etudiants.community-management') }}">
                        <i class="fas fa-share-alt"></i>Community Management
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.etudiants.intelligence-artificielle') }}">
                        <i class="fas fa-robot"></i>Intelligence Artificielle
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.etudiants.gestion-informatique') }}">
                        <i class="fas fa-server"></i>Gestion Informatique
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                </div>
            </li>

            <!-- Gestion des Formations -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" onclick="toggleDropdown(this)">
                    <div class="nav-text">
                        <i class="fas fa-chalkboard-teacher"></i>
                        Gestion des Formations
                    </div>
                    <div class="nav-right">
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </div>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('admin.formations.categories.index') }}">
                        <i class="fas fa-tags"></i>Catégories
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.formations.index') }}">
                        <i class="fas fa-book-open"></i>Formations
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                </div>
            </li>

            <!-- Bibliothèque -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" onclick="toggleDropdown(this)">
                    <div class="nav-text">
                        <i class="fas fa-book-reader"></i>
                        Bibliothèque
                    </div>
                    <div class="nav-right">
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </div>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('admin.bibliotheque.categories') }}">
                        <i class="fas fa-folder-open"></i>Catégories
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.bibliotheque.index') }}">
                        <i class="fas fa-book-open"></i>Bibliothèque
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                </div>
            </li>

            <!-- Gestion des Documents -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" onclick="toggleDropdown(this)">
                    <div class="nav-text">
                        <i class="fas fa-folder-open"></i>
                        Gestion des Documents
                    </div>
                    <div class="nav-right">
                        <span class="nav-badge warning">{{ $stats['pending_documents'] ?? 0 }}</span>
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </div>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('admin.documents.pending') }}">
                        <i class="fas fa-hourglass-half"></i>À valider
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.documents.all') }}">
                        <i class="fas fa-file-invoice"></i>Tous documents
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                </div>
            </li>

            <!-- Gestion des Programmes -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.programmes') }}">
                    <div class="nav-text">
                        <i class="fas fa-graduation-cap"></i>
                        Gestion des Programmes
                    </div>
                    <div class="nav-right">
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </div>
                </a>
            </li>

            <!-- Gestion des Travaux -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" onclick="toggleDropdown(this)">
                    <div class="nav-text">
                        <i class="fas fa-tasks"></i>
                        Gestion des Travaux
                    </div>
                    <div class="nav-right">
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </div>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('admin.travaux.pending') }}">
                        <i class="fas fa-hourglass-half"></i>À valider
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.travaux.to-send') }}">
                        <i class="fas fa-paper-plane"></i>TP à envoyer
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.travaux.all') }}">
                        <i class="fas fa-list"></i>Tous
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                </div>
            </li>

            <!-- Gestion des Projets -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" onclick="toggleDropdown(this)">
                    <div class="nav-text">
                        <i class="fas fa-project-diagram"></i>
                        Gestion des Projets
                    </div>
                    <div class="nav-right">
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </div>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('admin.projets.pending') }}">
                        <i class="fas fa-eye"></i>À valider
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.projets.to-send') }}">
                        <i class="fas fa-share"></i>À envoyer
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.projets.all') }}">
                        <i class="fas fa-folder-open"></i>Tous
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                </div>
            </li>

            <!-- Gestion des Articles -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" onclick="toggleDropdown(this)">
                    <div class="nav-text">
                        <i class="fas fa-newspaper"></i>
                        Gestion des Articles
                    </div>
                    <div class="nav-right">
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </div>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('admin.articles.evenements') }}">
                        <i class="fas fa-calendar-alt"></i>Événements
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.articles.actualites') }}">
                        <i class="fas fa-rss"></i>Actualités
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                </div>
            </li>

            <!-- Gestion des Certificats -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" onclick="toggleDropdown(this)">
                    <div class="nav-text">
                        <i class="fas fa-certificate"></i>
                        Gestion des Certificats
                    </div>
                    <div class="nav-right">
                        <span class="nav-badge success">{{ $stats['eligible_certificates'] ?? 0 }}</span>
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </div>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('admin.certificats.eligible') }}">
                        <i class="fas fa-check-circle"></i>Éligibles
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.certificats.not-eligible') }}">
                        <i class="fas fa-times-circle"></i>Pas éligibles
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                </div>
            </li>

            <!-- Gestion des Paiements -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" onclick="toggleDropdown(this)">
                    <div class="nav-text">
                        <i class="fas fa-credit-card"></i>
                        Gestion des Paiements
                    </div>
                    <div class="nav-right">
                        <span class="nav-badge warning">{{ $stats['pending_payments'] ?? 0 }}</span>
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </div>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('admin.paiements.a-jour') }}">
                        <i class="fas fa-check-double"></i>À jour
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.paiements.a-solder') }}">
                        <i class="fas fa-exclamation-triangle"></i>À solder
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.paiements.reste-a-payer') }}">
                        <i class="fas fa-clock"></i>Reste à payer
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                </div>
            </li>

            <!-- Gestion des CVthèque -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.cvtheque') }}">
                    <div class="nav-text">
                        <i class="fas fa-user-tie"></i>
                        Gestion des CVthèque
                    </div>
                    <div class="nav-right">
                        <span class="nav-badge">{{ $stats['total_cvtheque'] ?? 0 }}</span>
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </div>
                </a>
            </li>

            <!-- Gestion des Rapports -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.rapports') }}">
                    <div class="nav-text">
                        <i class="fas fa-chart-line"></i>
                        Gestion des Rapports
                    </div>
                    <div class="nav-right">
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </div>
                </a>
            </li>

            <!-- Gestion des Admins -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" onclick="toggleDropdown(this)">
                    <div class="nav-text">
                        <i class="fas fa-user-shield"></i>
                        Gestion des Admins
                    </div>
                    <div class="nav-right">
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </div>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('admin.admins.index') }}">
                        <i class="fas fa-users-cog"></i>Admins
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.admins.roles') }}">
                        <i class="fas fa-key"></i>Rôles
                        <i class="fas fa-external-link-alt item-icon"></i>
                    </a>
                </div>
            </li>

            <!-- Séparateur -->
            <li class="nav-item" style="margin-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 1rem;">
                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="background: rgba(220, 53, 69, 0.1); border-color: rgba(220, 53, 69, 0.2);">
                    <div class="nav-text">
                        <i class="fas fa-sign-out-alt"></i>
                        Déconnexion
                    </div>
                </a>
            </li>
        </ul>

        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </nav>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <main class="admin-main" id="adminMain">

        <!-- Modern Topbar -->
        <div class="admin-topbar">
            <div class="topbar-left">
                <div>
                    <h1 class="topbar-title">Dashboard Administrateur</h1>
                    <p class="topbar-breadcrumb">Vue d'ensemble de la plateforme EVC</p>
                </div>
            </div>

            <div class="topbar-right">
                <!-- Search Bar -->
                <div class="topbar-search">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Rechercher étudiants, documents..." id="globalSearch">
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <button class="quick-action-btn" title="Messages" onclick="openMessages()">
                        <i class="fas fa-envelope"></i>
                    </button>
                    <button class="quick-action-btn" title="Paramètres" onclick="openSettings()">
                        <i class="fas fa-cog"></i>
                    </button>
                    <button class="quick-action-btn" title="Mode sombre" onclick="toggleDarkMode()">
                        <i class="fas fa-moon"></i>
                    </button>
                </div>

                <!-- Notifications -->
                <button class="notification-bell" onclick="toggleNotifications()" title="Notifications">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">{{ $stats['pending_documents'] ?? 5 }}</span>
                </button>

                <!-- Profile Dropdown -->
                <div class="profile-dropdown" id="profileDropdown">
                    <div class="profile-trigger" onclick="toggleProfileMenu()">
                        <div class="profile-avatar">
                            {{ strtoupper(substr(session('admin_name', 'A'), 0, 1)) }}
                        </div>
                        <div class="profile-info">
                            <div class="profile-name">{{ session('admin_name', 'Administrateur') }}</div>
                            <div class="profile-role">{{ ucfirst(session('admin_role', 'admin')) }}</div>
                        </div>
                        <i class="fas fa-chevron-down profile-chevron"></i>
                    </div>

                    <div class="profile-menu">
                        <a href="#" class="profile-menu-item">
                            <i class="fas fa-user"></i>
                            Mon Profil
                        </a>
                        <a href="#" class="profile-menu-item">
                            <i class="fas fa-cog"></i>
                            Paramètres
                        </a>
                        <a href="#" class="profile-menu-item">
                            <i class="fas fa-bell"></i>
                            Notifications
                        </a>
                        <a href="#" class="profile-menu-item">
                            <i class="fas fa-question-circle"></i>
                            Aide & Support
                        </a>
                        <a href="#" class="profile-menu-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            Déconnexion
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Dashboard Content -->
        <div class="container-fluid dashboard-container">


                <!-- INTERFACE STATISTIQUES HOLOGRAPHIQUES RÉVOLUTIONNAIRE -->
                <div class="row">
                    <div class="col-12">
                        <div class="main-content-area">
                            <!-- Le contenu sera généré dynamiquement par holographic-stats.js -->
                        </div>
                    </div>
                </div>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Admin Menu JS - Module dédié -->
    <script src="{{ asset('js/admin-menu.js') }}"></script>
    
    <!-- Admin Statistics JS - Module dédié pour les statistiques dynamiques -->
    <script src="{{ asset('js/admin-statistics.js') }}"></script>
    
    <!-- Three.js pour l'interface 3D révolutionnaire -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
    
    <!-- Admin 3D Visualizer - Interface révolutionnaire -->
    <script src="{{ asset('js/admin-3d-visualizer.js') }}"></script>
    
    <!-- Admin 3D Initializer - Script d'initialisation révolutionnaire -->
    <script src="{{ asset('js/admin-3d-init.js') }}"></script>
    
    <!-- Holographic Stats - Interface statistiques holographiques révolutionnaire -->
    <script src="{{ asset('js/holographic-stats.js') }}"></script>
    
    <!-- Admin Statistics Demo - Script de test et démonstration (développement uniquement) -->
    @if(config('app.debug'))
    <script src="{{ asset('js/admin-statistics-demo.js') }}"></script>
    @endif

    <!-- Chart.js pour les graphiques révolutionnaires -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // SCRIPTS RÉVOLUTIONNAIRES - INTERFACE AVANCÉE

        // Variables globales pour l'interface révolutionnaire
        let autoRefreshInterval = null;
        let isAutoRefreshActive = false;
        let currentViewMode = 'grid';
        let dashboardCharts = {};
        let isFullscreen = false;

        // Animation des compteurs avec effet révolutionnaire
        function animateCounters() {
            const counters = document.querySelectorAll('.counter');

            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-target'));
                const duration = 2000;
                const increment = target / (duration / 16);
                let current = 0;

                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    counter.textContent = Math.floor(current);
                }, 16);
            });
        }

        // Création des mini-graphiques avec Chart.js
        function createMiniCharts() {
            // Graphique Utilisateurs
            const usersCtx = document.getElementById('usersChart');
            if (usersCtx) {
                dashboardCharts.users = new Chart(usersCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                        datasets: [{
                            data: [12, 19, 15, 25, 22, 30],
                            borderColor: '#3399ff',
                            backgroundColor: 'rgba(51, 153, 255, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { display: false },
                            y: { display: false }
                        },
                        elements: { point: { radius: 0 } }
                    }
                });
            }

            // Graphique Formations
            const formationsCtx = document.getElementById('formationsChart');
            if (formationsCtx) {
                dashboardCharts.formations = new Chart(formationsCtx, {
                    type: 'bar',
                    data: {
                        labels: ['DG', 'CM', 'IA', 'GI'],
                        datasets: [{
                            data: [85, 92, 78, 88],
                            backgroundColor: 'rgba(40, 167, 69, 0.8)',
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { display: false },
                            y: { display: false }
                        }
                    }
                });
            }

            // Graphique TP
            const tpCtx = document.getElementById('tpChart');
            if (tpCtx) {
                dashboardCharts.tp = new Chart(tpCtx, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [65, 25, 10],
                            backgroundColor: ['#ffc107', '#28a745', '#dc3545'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        cutout: '70%'
                    }
                });
            }

            // Graphique Revenus
            const revenueCtx = document.getElementById('revenueChart');
            if (revenueCtx) {
                dashboardCharts.revenue = new Chart(revenueCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                        datasets: [{
                            data: [15000, 18000, 22000, 25000, 28000, 32000],
                            borderColor: '#17a2b8',
                            backgroundColor: 'rgba(23, 162, 184, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { display: false },
                            y: { display: false }
                        },
                        elements: { point: { radius: 0 } }
                    }
                });
            }
        }

        // Fonction de recherche dynamique révolutionnaire
        function initDashboardSearch() {
            const searchInput = document.getElementById('dashboardSearch');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    const cards = document.querySelectorAll('.metric-card, .menu-stats-card');

                    cards.forEach(card => {
                        const text = card.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            card.style.display = 'block';
                            card.style.opacity = '1';
                            card.style.transform = 'scale(1)';
                        } else {
                            card.style.opacity = '0.3';
                            card.style.transform = 'scale(0.95)';
                        }
                    });
                });
            }
        }

        // Système de filtres révolutionnaire
        function initAdvancedFilters() {
            const filterBtns = document.querySelectorAll('.filter-btn');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Retirer la classe active des autres boutons
                    filterBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    const filter = this.getAttribute('data-filter');
                    filterDashboardContent(filter);
                });
            });
        }

        function filterDashboardContent(filter) {
            const allCards = document.querySelectorAll('.metric-card, .menu-stats-card');

            allCards.forEach(card => {
                if (filter === 'all') {
                    showCard(card);
                } else {
                    const cardType = card.getAttribute('data-metric') || card.getAttribute('data-section');
                    if (cardType === filter) {
                        showCard(card);
                    } else {
                        hideCard(card);
                    }
                }
            });
        }

        function showCard(card) {
            card.style.display = 'block';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0) scale(1)';
            }, 50);
        }

        function hideCard(card) {
            card.style.opacity = '0';
            card.style.transform = 'translateY(-20px) scale(0.9)';
            setTimeout(() => {
                card.style.display = 'none';
            }, 300);
        }

        // Fonctions révolutionnaires pour les boutons hero
        function refreshDashboard() {
            // Animation de refresh révolutionnaire
            const refreshBtn = document.querySelector('.hero-btn.primary i');
            if (refreshBtn) {
                refreshBtn.style.animation = 'spin 1s linear infinite';

                // Simuler le rechargement des données
                setTimeout(() => {
                    refreshBtn.style.animation = '';

                    // Réanimer les compteurs
                    animateCounters();

                    // Mettre à jour les graphiques
                    updateCharts();

                    // Notification de succès
                    showNotification('Dashboard actualisé avec succès !', 'success');
                }, 1500);
            }
        }

        function exportReport() {
            showNotification('Génération du rapport en cours...', 'info');

            setTimeout(() => {
                showNotification('Rapport exporté avec succès !', 'success');
            }, 2000);
        }

        function toggleFullscreen() {
            if (!isFullscreen) {
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                }
                isFullscreen = true;
                const btn = document.querySelector('.hero-btn.tertiary');
                if (btn) {
                    btn.querySelector('span').textContent = 'Quitter';
                    btn.querySelector('i').className = 'fas fa-compress';
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
                isFullscreen = false;
                const btn = document.querySelector('.hero-btn.tertiary');
                if (btn) {
                    btn.querySelector('span').textContent = 'Plein écran';
                    btn.querySelector('i').className = 'fas fa-expand';
                }
            }
        }

        function toggleAutoRefresh() {
            const icon = document.getElementById('autoRefreshIcon');
            if (!icon) return;

            const btn = icon.parentElement;

            if (!isAutoRefreshActive) {
                isAutoRefreshActive = true;
                icon.style.animation = 'spin 2s linear infinite';
                btn.style.background = 'linear-gradient(135deg, #28a745, #20c997)';

                autoRefreshInterval = setInterval(() => {
                    updateCharts();
                    animateCounters();
                }, 30000); // Refresh toutes les 30 secondes

                showNotification('Auto-refresh activé', 'success');
            } else {
                isAutoRefreshActive = false;
                icon.style.animation = '';
                btn.style.background = 'linear-gradient(135deg, #6c757d, #5a6268)';

                if (autoRefreshInterval) {
                    clearInterval(autoRefreshInterval);
                }

                showNotification('Auto-refresh désactivé', 'info');
            }
        }

        function toggleViewMode() {
            const icon = document.getElementById('viewModeIcon');
            if (!icon) return;

            const statsSection = document.querySelector('.stats-grid');

            if (currentViewMode === 'grid') {
                currentViewMode = 'list';
                icon.className = 'fas fa-th-list';
                if (statsSection) {
                    statsSection.classList.add('list-view');
                }
            } else {
                currentViewMode = 'grid';
                icon.className = 'fas fa-th-large';
                if (statsSection) {
                    statsSection.classList.remove('list-view');
                }
            }
        }

        // Mise à jour des graphiques
        function updateCharts() {
            Object.keys(dashboardCharts).forEach(chartKey => {
                const chart = dashboardCharts[chartKey];
                if (chart && chart.data && chart.data.datasets) {
                    // Générer de nouvelles données aléatoirement
                    chart.data.datasets.forEach(dataset => {
                        dataset.data = dataset.data.map(() =>
                            Math.floor(Math.random() * 100) + 10
                        );
                    });
                    chart.update('none');
                }
            });
        }

        // Système de notifications révolutionnaire
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'}-circle"></i>
                <span>${message}</span>
            `;

            // Styles pour la notification
            notification.style.cssText = `
                position: fixed;
                top: 100px;
                right: 20px;
                background: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8'};
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 12px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.3);
                z-index: 10000;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                font-weight: 600;
                transform: translateX(400px);
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            `;

            document.body.appendChild(notification);

            // Animation d'entrée
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);

            // Animation de sortie
            setTimeout(() => {
                notification.style.transform = 'translateX(400px)';
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 400);
            }, 3000);
        }

        // Animation révolutionnaire au chargement
        document.addEventListener('DOMContentLoaded', function() {
            // Animation des métriques principales
            const metricCards = document.querySelectorAll('.metric-card');
            metricCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(50px) scale(0.9)';
                card.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';

                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0) scale(1)';
                }, index * 200);
            });

            // Animation des cartes de statistiques
            const statsCards = document.querySelectorAll('.menu-stats-card');
            statsCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(30px)';
                    card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';

                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 100);
                }, index * 150);
            });

            // Initialiser les compteurs après un délai
            setTimeout(() => {
                animateCounters();
// ========================================
// INITIALISATION PRINCIPALE DU DASHBOARD
// ========================================

// Initialize on page load - VERSION CORRIGÉE
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard initialization starting...');
    
    // 1. Initialiser la navigation sidebar EN PREMIER
    initializeSidebarNavigation();
    
    // 2. Initialiser les graphiques
    setTimeout(() => {
        initializeCharts();
    }, 500);
    
    // 3. Initialiser les fonctionnalités dynamiques
    setTimeout(() => {
        initializeDynamicFeatures();
    }, 1000);
    
    // 4. Initialiser les animations des cartes
    setTimeout(() => {
        initializeCardAnimations();
    }, 1500);
    
    // 5. Message de bienvenue
    setTimeout(() => {
        if (typeof showNotification === 'function') {
            showNotification('Dashboard admin chargé avec succès !', 'success');
        }
    }, 2000);
    
    console.log('Dashboard initialization complete');
});

function initializeCardAnimations() {
    const cards = document.querySelectorAll('.analytics-card, .stats-card, .metric-card');
    console.log('Initializing animations for', cards.length, 'cards');
    
    cards.forEach((card, index) => {
        card.style.animationDelay = (index * 0.1) + 's';
        card.classList.add('fade-in-up');
        
        // Ajouter les styles d'animation si pas déjà présents
        if (!document.querySelector('#card-animations')) {
            const style = document.createElement('style');
            style.id = 'card-animations';
            style.textContent = `
                .fade-in-up {
                    animation: fadeInUp 0.6s ease-out forwards;
                    opacity: 0;
                    transform: translateY(30px);
                }
            
            cards.forEach((card, index) => {
                card.style.animationDelay = (index * 0.1) + 's';
                card.classList.add('fade-in-up');
                
                // Ajouter les styles d'animation si pas déjà présents
                if (!document.querySelector('#card-animations')) {
                    const style = document.createElement('style');
                    style.id = 'card-animations';
                    style.textContent = `
                        .fade-in-up {
                            animation: fadeInUp 0.6s ease-out forwards;
                            opacity: 0;
                            transform: translateY(30px);
                        }
                        
                        @keyframes fadeInUp {
                            to {
                                opacity: 1;
                                transform: translateY(0);
                            }
                        }
                    `;
                    document.head.appendChild(style);
                }
            });
        }

        // Animation CSS pour le spin
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);

        // Données des statistiques pour les détails
        const statsData = {
            students: {
                title: 'Statistiques Étudiants',
                icon: 'fas fa-users',
                data: {
                    total: {{ $stats['total_users'] ?? 0 }},
                    today: {{ $stats['new_users_today'] ?? 0 }},
                    design: {{ $stats['users_by_formation']['design_graphique'] ?? 0 }},
                    community: {{ $stats['users_by_formation']['community_management'] ?? 0 }},
                    ia: {{ $stats['users_by_formation']['intelligence_artificielle'] ?? 0 }},
                    gestion: {{ $stats['users_by_formation']['gestion_informatique'] ?? 0 }},
                    active: {{ $stats['active_sessions'] ?? 0 }}
                }
            }
        };

        // Ancienne fonction toggleDropdown supprimée - Maintenant gérée par AdminMenuManager

        // ========================================
        // ANCIEN CODE SIDEBAR SUPPRIMÉ
        // Maintenant géré par AdminMenuManager (admin-menu.js)
        // ========================================

            // Configurer les événements de toggle
            document.querySelectorAll('.dropdown-toggle').forEach((toggle, index) => {
                console.log(`Setting up toggle ${index}`);
                
                // Supprimer les anciens événements
                toggle.removeEventListener('click', toggleDropdown);
                
                // Ajouter le nouvel événement
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('🖱️ Dropdown toggle clicked');
                    toggleDropdown(this);
                });
            });

            // Mobile responsive
            if (window.innerWidth <= 767) {
                document.querySelectorAll('.dropdown-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const sidebar = document.getElementById('adminSidebar');
                        if (sidebar) sidebar.classList.remove('show');
                    });
                });
            }

            console.log('✅ Sidebar navigation setup complete');
        }

        // Fonction pour corriger la structure des statistiques - VERSION AMÉLIORÉE
        function fixStatisticsStructure() {
            console.log('🔧 Correction avancée de la structure des statistiques...');
            
            // Corriger les conteneurs de cartes avec flexbox amélioré
            const cardContainers = document.querySelectorAll('.row .col-xl-3, .row .col-lg-6, .row .col-md-6, .row .col-sm-6, .row .col-12');
            cardContainers.forEach((container, index) => {
                container.style.display = 'flex';
                container.style.flexDirection = 'column';
                container.style.marginBottom = '1.5rem';
                container.style.minHeight = '140px';
                
                // Animation d'apparition échelonnée
                container.style.animation = `fadeInUp 0.6s ease-out ${index * 0.1}s both`;
            });

            // Corriger les cartes statistiques avec design moderne
            const statCards = document.querySelectorAll('.card');
            statCards.forEach((card, index) => {
                // Structure de base
                card.style.minHeight = '130px';
                card.style.display = 'flex';
                card.style.flexDirection = 'column';
                card.style.justifyContent = 'space-between';
                card.style.height = '100%';
                card.style.borderRadius = '12px';
                card.style.border = '1px solid rgba(255, 255, 255, 0.1)';
                card.style.background = 'linear-gradient(135deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.02))';
                card.style.backdropFilter = 'blur(10px)';
                card.style.transition = 'all 0.3s ease';
                card.style.position = 'relative';
                card.style.overflow = 'hidden';
                
                // Effet hover
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px) scale(1.02)';
                    this.style.boxShadow = '0 15px 35px rgba(0, 0, 0, 0.2)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                    this.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.1)';
                });
                
                // Corriger le card-body
                const cardBody = card.querySelector('.card-body');
                if (cardBody) {
                    cardBody.style.padding = '1.5rem';
                    cardBody.style.display = 'flex';
                    cardBody.style.flexDirection = 'column';
                    cardBody.style.justifyContent = 'space-between';
                    cardBody.style.flex = '1';
                    cardBody.style.position = 'relative';
                    cardBody.style.zIndex = '2';
                }
            });

            // Corriger les titres avec typographie améliorée
            const cardTitles = document.querySelectorAll('.card-title, .card .card-title');
            cardTitles.forEach(title => {
                title.style.fontSize = '0.85rem';
                title.style.fontWeight = '600';
                title.style.marginBottom = '0.75rem';
                title.style.lineHeight = '1.4';
                title.style.color = 'rgba(255, 255, 255, 0.9)';
                title.style.textTransform = 'uppercase';
                title.style.letterSpacing = '0.5px';
            });

            // Corriger les valeurs numériques avec style moderne
            const cardValues = document.querySelectorAll('.card h3, .card .h3, .card .display-6, .card .fs-2');
            cardValues.forEach(value => {
                value.style.fontSize = '2rem';
                value.style.fontWeight = '700';
                value.style.marginBottom = '0.5rem';
                value.style.lineHeight = '1.1';
                value.style.color = '#fff';
                value.style.textShadow = '0 2px 4px rgba(0, 0, 0, 0.3)';
            });

            // Corriger les icônes des cartes
            const cardIcons = document.querySelectorAll('.card i, .card .fas, .card .far');
            cardIcons.forEach(icon => {
                icon.style.fontSize = '2rem';
                icon.style.opacity = '0.8';
                icon.style.marginBottom = '0.5rem';
                icon.style.filter = 'drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2))';
            });

            // Corriger les badges et indicateurs
            const badges = document.querySelectorAll('.card .badge, .card .btn-sm');
            badges.forEach(badge => {
                badge.style.fontSize = '0.7rem';
                badge.style.padding = '0.25rem 0.5rem';
                badge.style.borderRadius = '6px';
                badge.style.fontWeight = '600';
            });

            // Ajouter des animations CSS si elles n'existent pas
            if (!document.querySelector('#statisticsAnimations')) {
                const style = document.createElement('style');
                style.id = 'statisticsAnimations';
                style.textContent = `
                    @keyframes fadeInUp {
                        from {
                            opacity: 0;
                            transform: translateY(30px);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }
                    
                    @keyframes pulse {
                        0%, 100% { transform: scale(1); }
                        50% { transform: scale(1.05); }
                    }
                `;
                document.head.appendChild(style);
            }

            console.log('✅ Structure des statistiques corrigée avec design moderne');
        }

        // Set active menu function
        function setActiveMenu() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link, .dropdown-item');

            navLinks.forEach(link => {
                link.classList.remove('active');
                const href = link.getAttribute('href');
                if (href && currentPath.includes(href.split('/').pop())) {
                    link.classList.add('active');
                    // If it's a dropdown item, also show the parent dropdown
                    const parentDropdown = link.closest('.nav-item.dropdown');
                    if (parentDropdown) {
                        parentDropdown.classList.add('show');
                        const dropdownMenu = parentDropdown.querySelector('.dropdown-menu');
                        if (dropdownMenu) {
                            dropdownMenu.style.maxHeight = dropdownMenu.scrollHeight + 20 + 'px';
                            dropdownMenu.style.opacity = '1';
                        }
                    }
                }
            });
        }

        // Animate menu items
        function animateMenuItems() {
            const menuItems = document.querySelectorAll('.nav-item');
            menuItems.forEach((item, index) => {
                item.style.animationDelay = (index * 0.1) + 's';
                item.classList.add('animate-in');
            });
        }

        // Create ripple effect
        function createRippleEffect(e) {
            const button = e.currentTarget;
            const ripple = document.createElement('span');
            const rect = button.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');

            button.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        }

        // Sidebar toggle functions
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const mainWrapper = document.querySelector('.main-wrapper');

            if (window.innerWidth <= 767) {
                // Mobile behavior
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            } else {
                // Desktop behavior
                sidebar.classList.toggle('collapsed');
                if (mainWrapper) {
                    mainWrapper.classList.toggle('collapsed');
                }
            }
        }

        function closeSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        }

        // ========================================
        // GESTION DES ÉTATS ACTIFS DU MENU
        // ========================================
        
        // Initialize active menu on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializeMenuStates();
        });

        function initializeMenuStates() {
            setActiveMenu();
            animateMenuItems();

            // Add ripple effect to links
            document.querySelectorAll('.nav-link, .dropdown-item').forEach(link => {
                link.addEventListener('click', createRippleEffect);
            });
        }

        // Topbar Functions
        function toggleProfileMenu() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('show');

            // Close when clicking outside
            document.addEventListener('click', function(event) {
                if (!dropdown.contains(event.target)) {
                    dropdown.classList.remove('show');
                }
            });
        }

        function toggleNotifications() {
            // Create notification panel if it doesn't exist
            let panel = document.getElementById('notificationPanel');
            if (!panel) {
                panel = createNotificationPanel();
                document.body.appendChild(panel);
            }

            panel.classList.toggle('show');
        }

        function createNotificationPanel() {
            const panel = document.createElement('div');
            panel.id = 'notificationPanel';
            panel.className = 'notification-panel';
            panel.innerHTML = `
                <div class="notification-header">
                    <h5>Notifications</h5>
                    <button onclick="markAllAsRead()" class="btn-link">Tout marquer comme lu</button>
                </div>
                <div class="notification-list">
                    <div class="notification-item unread">
                        <div class="notification-icon bg-primary">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">Nouvel étudiant inscrit</div>
                            <div class="notification-text">Marie Dubois s'est inscrite en Design Graphique</div>
                            <div class="notification-time">Il y a 5 minutes</div>
                        </div>
                    </div>
                    <div class="notification-item unread">
                        <div class="notification-icon bg-warning">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">Document à valider</div>
                            <div class="notification-text">3 nouveaux CV en attente de validation</div>
                            <div class="notification-time">Il y a 15 minutes</div>
                        </div>
                    </div>
                    <div class="notification-item">
                        <div class="notification-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">Paiement reçu</div>
                            <div class="notification-text">Paiement de 500€ confirmé pour Jean Martin</div>
                            <div class="notification-time">Il y a 1 heure</div>
                        </div>
                    </div>
                </div>
                <div class="notification-footer">
                    <a href="#" class="btn btn-sm btn-primary">Voir toutes les notifications</a>
                </div>
            `;

            // Add styles for notification panel
            const style = document.createElement('style');
            style.textContent = `
                .notification-panel {
                    position: fixed;
                    top: 70px;
                    right: 20px;
                    width: 350px;
                    max-height: 500px;
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
                    border: 1px solid rgba(0, 0, 0, 0.08);
                    opacity: 0;
                    visibility: hidden;
                    transform: translateY(-20px);
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    z-index: 1001;
                }

                .notification-panel.show {
                    opacity: 1;
                    visibility: visible;
                    transform: translateY(0);
                }

                .notification-header {
                    padding: 1rem 1.25rem;
                    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                .notification-header h5 {
                    margin: 0;
                    font-weight: 600;
                }

                .notification-list {
                    max-height: 300px;
                    overflow-y: auto;
                }

                .notification-item {
                    display: flex;
                    padding: 1rem 1.25rem;
                    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
                    transition: background 0.3s ease;
                }

                .notification-item:hover {
                    background: rgba(51, 153, 255, 0.05);
                }

                .notification-item.unread {
                    background: rgba(51, 153, 255, 0.02);
                    border-left: 3px solid var(--primary-color);
                }

                .notification-icon {
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin-right: 0.75rem;
                    color: white;
                    font-size: 0.875rem;
                }

                .notification-content {
                    flex: 1;
                }

                .notification-title {
                    font-weight: 600;
                    font-size: 0.875rem;
                    margin-bottom: 0.25rem;
                }

                .notification-text {
                    color: #6c757d;
                    font-size: 0.8rem;
                    margin-bottom: 0.25rem;
                }

                .notification-time {
                    color: #adb5bd;
                    font-size: 0.75rem;
                }

                .notification-footer {
                    padding: 1rem 1.25rem;
                    text-align: center;
                    border-top: 1px solid rgba(0, 0, 0, 0.08);
                }
            `;
            document.head.appendChild(style);

            return panel;
        }

        function markAllAsRead() {
            document.querySelectorAll('.notification-item.unread').forEach(item => {
                item.classList.remove('unread');
            });
            document.querySelector('.notification-badge').textContent = '0';
        }

        // Revolutionary Topbar Functionality with Ultra-Fluid Animations
        let isScrolled = false;
        let searchTimeout = null;

        // Dynamic topbar scroll effect
        window.addEventListener('scroll', () => {
            const topbar = document.querySelector('.admin-topbar');
            const scrolled = window.scrollY > 20;

            if (scrolled !== isScrolled) {
                isScrolled = scrolled;
                if (scrolled) {
                    topbar.classList.add('scrolled');
                } else {
                    topbar.classList.remove('scrolled');
                }
            }
        });

        // Revolutionary notification toggle with fluid animations
        function toggleNotifications() {
            const panel = document.querySelector('.notification-panel');
            const bell = document.querySelector('.notification-bell');

            if (panel) {
                const isVisible = panel.style.display === 'block';

                if (!isVisible) {
                    panel.style.display = 'block';
                    panel.style.opacity = '0';
                    panel.style.transform = 'translateY(-20px) scale(0.9)';

                    // Trigger bell ring animation
                    bell.style.animation = 'bellRing 0.6s ease-in-out';

                    requestAnimationFrame(() => {
                        panel.style.transition = 'all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                        panel.style.opacity = '1';
                        panel.style.transform = 'translateY(0) scale(1)';
                    });
                } else {
                    panel.style.transition = 'all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                    panel.style.opacity = '0';
                    panel.style.transform = 'translateY(-20px) scale(0.9)';

                    setTimeout(() => {
                        panel.style.display = 'none';
                    }, 300);
                }

                // Reset bell animation
                setTimeout(() => {
                    bell.style.animation = '';
                }, 600);
            }
        }

        // Revolutionary profile toggle with smooth animations
        function toggleProfile() {
            const menu = document.querySelector('.profile-menu');
            const trigger = document.querySelector('.profile-trigger');

            if (menu) {
                const isVisible = menu.style.display === 'block';

                if (!isVisible) {
                    menu.style.display = 'block';
                    menu.style.opacity = '0';
                    menu.style.transform = 'translateY(-15px) scale(0.95)';

                    // Add active state to trigger
                    trigger.style.transform = 'translateY(-2px) scale(1.02)';

                    requestAnimationFrame(() => {
                        menu.style.transition = 'all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                        menu.style.opacity = '1';
                        menu.style.transform = 'translateY(0) scale(1)';
                    });
                } else {
                    menu.style.transition = 'all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                    menu.style.opacity = '0';
                    menu.style.transform = 'translateY(-15px) scale(0.95)';

                    // Remove active state from trigger
                    trigger.style.transform = '';

                    setTimeout(() => {
                        menu.style.display = 'none';
                    }, 300);
                }
            }
        }

        // Revolutionary dark mode toggle with smooth transition
        function toggleDarkMode() {
            const body = document.body;
            const isDark = body.classList.contains('dark-mode');

            // Create transition overlay
            const overlay = document.createElement('div');
            overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: ${isDark ? '#ffffff' : '#1a1a1a'};
                z-index: 9999;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s ease;
            `;
            document.body.appendChild(overlay);

            // Animate overlay
            requestAnimationFrame(() => {
                overlay.style.opacity = '0.8';
            });

            setTimeout(() => {
                body.classList.toggle('dark-mode');
                localStorage.setItem('darkMode', body.classList.contains('dark-mode'));

                // Fade out overlay
                overlay.style.opacity = '0';
                setTimeout(() => {
                    document.body.removeChild(overlay);
                }, 300);
            }, 150);
        }

        // Revolutionary search with live feedback
        function handleSearch(input) {
            const query = input.value.trim();
            const searchIcon = document.querySelector('.search-icon');

            // Clear previous timeout
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }

            // Add searching state
            if (query.length > 0) {
                searchIcon.style.animation = 'searchIconPulse 1s ease-in-out infinite';
                input.style.paddingLeft = '3.2rem';
            } else {
                searchIcon.style.animation = 'searchIconPulse 2s ease-in-out infinite';
                input.style.paddingLeft = '3rem';
            }

            // Debounced search
            if (query.length > 2) {
                searchTimeout = setTimeout(() => {
                    console.log('🔍 Revolutionary search for:', query);

                    // Add search results animation
                    const searchContainer = input.parentElement;
                    searchContainer.style.transform = 'scale(1.02)';

                    setTimeout(() => {
                        searchContainer.style.transform = '';
                    }, 200);

                    // TODO: Implement actual search functionality
                    // This is where you would make AJAX calls to search
                }, 300);
            }
        }

        // Revolutionary quick action effects
        function addQuickActionEffects() {
            const quickActions = document.querySelectorAll('.quick-action-btn');

            quickActions.forEach((btn, index) => {
                btn.addEventListener('mouseenter', () => {
                    // Stagger animation for surrounding buttons
                    quickActions.forEach((otherBtn, otherIndex) => {
                        if (otherIndex !== index) {
                            otherBtn.style.transform = 'scale(0.95)';
                            otherBtn.style.opacity = '0.7';
                        }
                    });
                });

                btn.addEventListener('mouseleave', () => {
                    // Reset all buttons
                    quickActions.forEach((otherBtn) => {
                        otherBtn.style.transform = '';
                        otherBtn.style.opacity = '';
                    });
                });
            });
        }

        // Initialize revolutionary effects on page load
        document.addEventListener('DOMContentLoaded', () => {
            // Add quick action effects
            addQuickActionEffects();

            // Load dark mode preference
            if (localStorage.getItem('darkMode') === 'true') {
                document.body.classList.add('dark-mode');
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.notification-bell')) {
                    const panel = document.querySelector('.notification-panel');
                    if (panel && panel.style.display === 'block') {
                        toggleNotifications();
                    }
                }

                if (!e.target.closest('.profile-dropdown')) {
                    const menu = document.querySelector('.profile-menu');
                    if (menu && menu.style.display === 'block') {
                        toggleProfile();
                    }
                }
            });

            // Add revolutionary loading complete animation
            setTimeout(() => {
                document.querySelector('.admin-topbar').style.animation = 'none';
                document.querySelector('.admin-topbar').style.transform = 'translateY(0)';
                document.querySelector('.admin-topbar').style.opacity = '1';
            }, 1000);
        });

        // Global Search Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('globalSearch');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const query = e.target.value.toLowerCase();
                    if (query.length > 2) {
                        // Simulate search results
                        console.log('Searching for:', query);
                        // Here you would implement AJAX search
                    }
                });

                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        performGlobalSearch(e.target.value);
                    }
                });
            }
        });

        function performGlobalSearch(query) {
            if (query.trim()) {
                // Placeholder for global search functionality
                alert(`Recherche pour: "${query}" - Fonctionnalité en cours de développement`);
            }
        }

        // ========================================
        // FONCTIONNALITÉS DYNAMIQUES ET INTERACTIVES
        // ========================================

        // Variables globales pour les fonctionnalités dynamiques
        let autoRefreshEnabled = false;
        let autoRefreshInterval;
        let notificationSystem;
        let loadingOverlay;

        // Système de notifications toast
        function initializeNotificationSystem() {
            notificationSystem = document.createElement('div');
            notificationSystem.id = 'notificationSystem';
            notificationSystem.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                max-width: 350px;
            `;
            document.body.appendChild(notificationSystem);
        }

        function showNotification(message, type = 'info', duration = 4000) {
            const notification = document.createElement('div');
            notification.className = `notification-toast notification-${type}`;
            
            const icons = {
                success: 'fas fa-check-circle',
                error: 'fas fa-exclamation-circle',
                warning: 'fas fa-exclamation-triangle',
                info: 'fas fa-info-circle'
            };

            notification.innerHTML = `
                <div class="notification-content">
                    <i class="${icons[type]} me-2"></i>
                    <span>${message}</span>
                </div>
                <button class="notification-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;

            // Styles pour les notifications
            notification.style.cssText = `
                background: ${type === 'success' ? '#d4edda' : type === 'error' ? '#f8d7da' : type === 'warning' ? '#fff3cd' : '#d1ecf1'};
                border: 1px solid ${type === 'success' ? '#c3e6cb' : type === 'error' ? '#f5c6cb' : type === 'warning' ? '#ffeaa7' : '#bee5eb'};
                color: ${type === 'success' ? '#155724' : type === 'error' ? '#721c24' : type === 'warning' ? '#856404' : '#0c5460'};
                padding: 12px 16px;
                border-radius: 8px;
                margin-bottom: 10px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                transform: translateX(100%);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                animation: slideInRight 0.3s ease-out forwards;
            `;

            notificationSystem.appendChild(notification);

            // Auto-remove après la durée spécifiée
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease-in forwards';
                setTimeout(() => notification.remove(), 300);
            }, duration);
        }

        // Auto-refresh avec fonctionnalité complète
        function initializeAutoRefresh() {
            const autoRefreshToggle = document.getElementById('autoRefreshToggle');
            if (autoRefreshToggle) {
                autoRefreshToggle.addEventListener('change', function() {
                    autoRefreshEnabled = this.checked;
                    if (autoRefreshEnabled) {
                        startAutoRefresh();
                        showNotification('Auto-refresh activé - Mise à jour toutes les 30 secondes', 'success');
                    } else {
                        stopAutoRefresh();
                        showNotification('Auto-refresh désactivé', 'info');
                    }
                });
            }
        }

        function startAutoRefresh() {
            autoRefreshInterval = setInterval(() => {
                refreshDashboardData();
                updateLastRefreshTime();
            }, 30000); // Refresh toutes les 30 secondes
        }

        function stopAutoRefresh() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }
        }

        function refreshDashboardData() {
            showLoadingAnimation();
            
            // Simuler la récupération de nouvelles données avec animation
            setTimeout(() => {
                updateStatisticsCards();
                updateCharts();
                updateActivityTimeline();
                updateAlerts();
                hideLoadingAnimation();
                showNotification('Données mises à jour avec succès', 'success');
            }, 2000);
        }

        function updateLastRefreshTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('fr-FR');
            const refreshElement = document.querySelector('.last-refresh-time');
            if (refreshElement) {
                refreshElement.textContent = `Dernière mise à jour: ${timeString}`;
                refreshElement.style.animation = 'pulse 0.5s ease-in-out';
            }
        }

        // Animation de chargement globale
        function showLoadingAnimation() {
            if (!loadingOverlay) {
                loadingOverlay = document.createElement('div');
                loadingOverlay.id = 'loadingOverlay';
                loadingOverlay.innerHTML = `
                    <div class="loading-spinner">
                        <div class="spinner-ring"></div>
                        <div class="loading-text">Mise à jour des données...</div>
                    </div>
                `;
                loadingOverlay.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(255, 255, 255, 0.9);
                    backdrop-filter: blur(5px);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 9998;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                `;
                document.body.appendChild(loadingOverlay);
            }
            
            loadingOverlay.style.opacity = '1';
            loadingOverlay.style.pointerEvents = 'all';
        }

        function hideLoadingAnimation() {
            if (loadingOverlay) {
                loadingOverlay.style.opacity = '0';
                loadingOverlay.style.pointerEvents = 'none';
            }
        }

        // Mise à jour dynamique des cartes statistiques
        function updateStatisticsCards() {
            const cards = document.querySelectorAll('.stats-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.transform = 'scale(1.02)';
                    card.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
                    
                    // Simuler la mise à jour des valeurs
                    const valueElement = card.querySelector('.stats-value');
                    if (valueElement) {
                        const currentValue = parseInt(valueElement.textContent) || 0;
                        const newValue = currentValue + Math.floor(Math.random() * 5);
                        animateNumber(valueElement, currentValue, newValue);
                    }
                    
                    setTimeout(() => {
                        card.style.transform = '';
                        card.style.boxShadow = '';
                    }, 500);
                }, index * 100);
            });
        }

        // Animation des nombres
        function animateNumber(element, start, end, duration = 1000) {
            const startTime = performance.now();
            
            function update(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                const current = Math.floor(start + (end - start) * progress);
                element.textContent = current.toLocaleString();
                
                if (progress < 1) {
                    requestAnimationFrame(update);
                }
            }
            
            requestAnimationFrame(update);
        }

        // Mise à jour des graphiques avec animation
        function updateCharts() {
            // Mise à jour du graphique d'évolution
            if (window.enrollmentChart) {
                const newData = generateRandomData(7);
                window.enrollmentChart.data.datasets[0].data = newData;
                window.enrollmentChart.update('active');
            }
            
            // Mise à jour du graphique de répartition
            if (window.formationChart) {
                const newData = [
                    Math.floor(Math.random() * 50) + 30,
                    Math.floor(Math.random() * 30) + 20,
                    Math.floor(Math.random() * 25) + 15,
                    Math.floor(Math.random() * 20) + 10
                ];
                window.formationChart.data.datasets[0].data = newData;
                window.formationChart.update('active');
            }
        }

        function generateRandomData(count) {
            return Array.from({length: count}, () => Math.floor(Math.random() * 50) + 20);
        }

        // Mise à jour de la timeline d'activité
        function updateActivityTimeline() {
            const activities = [
                { icon: 'fas fa-user-plus', text: 'Nouvelle inscription: ' + generateRandomName(), time: 'Il y a ' + Math.floor(Math.random() * 10 + 1) + ' min', type: 'success' },
                { icon: 'fas fa-check-circle', text: 'TP validé: ' + generateRandomProject(), time: 'Il y a ' + Math.floor(Math.random() * 20 + 5) + ' min', type: 'primary' },
                { icon: 'fas fa-credit-card', text: 'Paiement reçu: ' + (Math.floor(Math.random() * 500) + 200) + '€', time: 'Il y a ' + Math.floor(Math.random() * 30 + 10) + ' min', type: 'success' },
                { icon: 'fas fa-file-alt', text: 'Document soumis: CV - ' + generateRandomName(), time: 'Il y a ' + Math.floor(Math.random() * 45 + 15) + ' min', type: 'info' },
                { icon: 'fas fa-graduation-cap', text: 'Formation terminée: ' + generateRandomName(), time: 'Il y a ' + Math.floor(Math.random() * 60 + 30) + ' min', type: 'warning' }
            ];
            
            const timeline = document.querySelector('.activity-timeline');
            if (timeline) {
                timeline.style.opacity = '0.5';
                timeline.style.transform = 'scale(0.98)';
                
                setTimeout(() => {
                    timeline.innerHTML = '';
                    activities.forEach((activity, index) => {
                        const item = document.createElement('div');
                        item.className = 'timeline-item';
                        item.style.animationDelay = `${index * 0.1}s`;
                        item.innerHTML = `
                            <div class="timeline-marker bg-${activity.type}">
                                <i class="${activity.icon} text-white"></i>
                            </div>
                            <div class="timeline-content">
                                <p class="mb-1">${activity.text}</p>
                                <small class="text-muted">${activity.time}</small>
                            </div>
                        `;
                        timeline.appendChild(item);
                    });
                    
                    timeline.style.opacity = '1';
                    timeline.style.transform = 'scale(1)';
                }, 500);
            }
        }

        function generateRandomName() {
            const firstNames = ['Marie', 'Jean', 'Sophie', 'Pierre', 'Emma', 'Lucas', 'Camille', 'Thomas', 'Léa', 'Nicolas'];
            const lastNames = ['Dubois', 'Martin', 'Laurent', 'Moreau', 'Bernard', 'Petit', 'Robert', 'Richard', 'Durand', 'Leroy'];
            return firstNames[Math.floor(Math.random() * firstNames.length)] + ' ' + lastNames[Math.floor(Math.random() * lastNames.length)];
        }

        function generateRandomProject() {
            const projects = ['Design Logo', 'Création Affiche', 'Site Web', 'Branding Complet', 'Illustration', 'Packaging', 'UI/UX Design', 'Motion Design'];
            return projects[Math.floor(Math.random() * projects.length)];
        }

        // Mise à jour des alertes
        function updateAlerts() {
            const alertsContainer = document.querySelector('.alerts-container');
            if (alertsContainer) {
                const alerts = alertsContainer.querySelectorAll('.alert-item');
                alerts.forEach((alert, index) => {
                    setTimeout(() => {
                        alert.style.transform = 'translateX(-10px)';
                        setTimeout(() => {
                            alert.style.transform = '';
                        }, 200);
                    }, index * 100);
                });
            }
        }

        // Export avec animation et téléchargement réel
        function exportStudentData() {
            const exportBtn = document.querySelector('.export-btn');
            if (exportBtn) {
                exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Export en cours...';
                exportBtn.disabled = true;
                exportBtn.style.transform = 'scale(0.95)';
            }
            
            setTimeout(() => {
                const csvData = generateStudentCSV();
                downloadCSV(csvData, 'etudiants_actifs_' + new Date().toISOString().split('T')[0] + '.csv');
                
                if (exportBtn) {
                    exportBtn.innerHTML = '<i class="fas fa-download me-2"></i>Exporter';
                    exportBtn.disabled = false;
                    exportBtn.style.transform = '';
                }
                
                showNotification('Export terminé avec succès', 'success');
            }, 3000);
        }

        function generateStudentCSV() {
            const headers = ['Nom', 'Formation', 'Progression', 'TP Validés', 'Statut', 'Date Inscription'];
            const data = [
                ['Marie Dubois', 'Design Graphique', '85%', '12', 'Actif', '2024-01-15'],
                ['Jean Martin', 'Community Management', '92%', '15', 'Actif', '2024-01-20'],
                ['Sophie Laurent', 'Intelligence Artificielle', '78%', '10', 'Actif', '2024-02-01'],
                ['Pierre Moreau', 'Gestion Informatique', '88%', '13', 'Actif', '2024-02-05'],
                ['Emma Bernard', 'Design Graphique', '95%', '18', 'Actif', '2024-02-10']
            ];
            
            let csv = headers.join(',') + '\n';
            data.forEach(row => {
                csv += row.join(',') + '\n';
            });
            
            return csv;
        }

        function downloadCSV(csvData, filename) {
            const blob = new Blob([csvData], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            
            if (link.download !== undefined) {
                const url = URL.createObjectURL(blob);
                link.setAttribute('href', url);
                link.setAttribute('download', filename);
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);
            }
        }

        // Refresh activity avec animation fluide
        function refreshActivity() {
            const refreshBtn = document.querySelector('.refresh-activity-btn');
            const activityTimeline = document.querySelector('.activity-timeline');
            
            if (refreshBtn) {
                refreshBtn.innerHTML = '<i class="fas fa-sync fa-spin me-2"></i>Actualisation...';
                refreshBtn.disabled = true;
            }
            
            if (activityTimeline) {
                activityTimeline.style.opacity = '0.5';
                activityTimeline.style.transform = 'scale(0.98)';
            }
            
            setTimeout(() => {
                updateActivityTimeline();
                
                if (refreshBtn) {
                    refreshBtn.innerHTML = '<i class="fas fa-sync me-2"></i>Actualiser';
                    refreshBtn.disabled = false;
                }
                
                showNotification('Activité mise à jour', 'success');
            }, 2000);
        }

        // Filtres de période avec animation
        function changePeriod(period) {
            const buttons = document.querySelectorAll('.period-btn');
            buttons.forEach(btn => {
                btn.classList.remove('active');
                if (btn.textContent.toLowerCase().includes(period.toLowerCase())) {
                    btn.classList.add('active');
                }
            });
            
            showLoadingAnimation();
            
            setTimeout(() => {
                updateCharts();
                updateStatisticsCards();
                hideLoadingAnimation();
                showNotification(`Période mise à jour: ${period}`, 'info');
            }, 1500);
        }

        // Initialisation complète des fonctionnalités dynamiques
        function initializeDynamicFeatures() {
            initializeNotificationSystem();
            initializeAutoRefresh();
            
            // Ajouter les styles CSS pour les animations
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideInRight {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                
                @keyframes slideOutRight {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
                
                @keyframes pulse {
                    0%, 100% { transform: scale(1); }
                    50% { transform: scale(1.05); }
                }
                
                .loading-spinner {
                    text-align: center;
                }
                
                .spinner-ring {
                    width: 50px;
                    height: 50px;
                    border: 4px solid #f3f3f3;
                    border-top: 4px solid #3399ff;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                    margin: 0 auto 20px;
                }
                
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
                
                .loading-text {
                    color: #666;
                    font-weight: 500;
                }
                
                .timeline-item {
                    animation: fadeInUp 0.5s ease-out forwards;
                    opacity: 0;
                    transform: translateY(20px);
                }
                
                @keyframes fadeInUp {
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                
                .notification-close {
                    background: none;
                    border: none;
                    cursor: pointer;
                    opacity: 0.7;
                    transition: opacity 0.2s;
                }
                
                .notification-close:hover {
                    opacity: 1;
                }
                
                .stats-card {
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                }
                
                .period-btn {
                    transition: all 0.2s ease;
                }
                
                .period-btn.active {
                    background: var(--primary-color) !important;
                    color: white !important;
                    transform: scale(1.05);
                }
            `;
            document.head.appendChild(style);
            
            // Ajouter un indicateur de dernière mise à jour
            const refreshIndicator = document.createElement('div');
            refreshIndicator.className = 'last-refresh-time';
            refreshIndicator.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: rgba(0,0,0,0.8);
                color: white;
                padding: 8px 12px;
                border-radius: 20px;
                font-size: 12px;
                z-index: 1000;
                opacity: 0.7;
            `;
            refreshIndicator.textContent = 'Dernière mise à jour: ' + new Date().toLocaleTimeString('fr-FR');
            document.body.appendChild(refreshIndicator);
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            // Close profile dropdown
            const profileDropdown = document.getElementById('profileDropdown');
            if (profileDropdown && !profileDropdown.contains(event.target)) {
                profileDropdown.classList.remove('show');
            }

            // Close notification panel
            const notificationPanel = document.getElementById('notificationPanel');
            if (notificationPanel && !notificationPanel.contains(event.target) &&
                !event.target.closest('.notification-bell')) {
                notificationPanel.classList.remove('show');
            }
        });

        // Auto-refresh stats every 30 seconds
        setInterval(function() {
            // You can implement AJAX refresh here if needed
        }, 30000);

        // Add CSS for ripple effect
        const rippleStyle = document.createElement('style');
        rippleStyle.textContent = `
            .nav-link, .dropdown-item {
                position: relative;
                overflow: hidden;
            }

            .ripple {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.3);
                transform: scale(0);
                animation: ripple-animation 0.6s linear;
                pointer-events: none;
            }

            @keyframes ripple-animation {
                to {
                    transform: scale(2);
                    opacity: 0;
                }
            }

            .animate-in {
                animation: slideInLeft 0.5s ease forwards;
            }

            @keyframes slideInLeft {
                from {
                    opacity: 0;
                    transform: translateX(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
        `;
        document.head.appendChild(rippleStyle);

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        // ✅ MENU MANAGEMENT - Utilisation du module AdminMenuManager
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Initialisation du système de menus modulaire...');
            
            // Le gestionnaire de menus est automatiquement initialisé par admin-menu.js
            // Écouter les événements personnalisés du gestionnaire
            document.addEventListener('dropdown:opened', function(e) {
                console.log('✅ Menu ouvert:', e.detail.dropdown.id);
            });
            
            document.addEventListener('dropdown:closed', function(e) {
                console.log('✅ Menu fermé:', e.detail.dropdown.id);
            });
            
            document.addEventListener('dropdown:item-clicked', function(e) {
                console.log('✅ Item cliqué:', e.detail.href);
            });
            
            console.log('✅ Système de menus modulaire initialisé');
        });
    </script>

    <!-- Les styles des menus sont maintenant gérés par le module CSS dédié: admin-menu.css -->
</body>
</html>

# TimeWise Employee Management System

## Overview

TimeWise is a comprehensive employee management application designed to streamline workplace operations. It integrates real-time chat, advanced scheduling, accurate time tracking, and efficient department management. The system is built on a PHP backend with MySQL for robust user authentication and features a sophisticated 5-tier Role-Based Access Control (RBAC) system. Its core purpose is to enhance organizational efficiency and communication within a structured employee management framework.

## User Preferences

Preferred communication style: Simple, everyday language.

## System Architecture

### UI/UX Decisions
- **Access Level Map**: Features a visual access matrix with color-coded icons (✓ Full Access, 👁️ View Only, ✗ No Access) for clear permission understanding.
- **Department-Grouped Schedule**: Employees are grouped by department with unique color coding (10-color palette) for visual distinction in schedule views.
- **Responsive Design**: Grid layouts and UI elements are adapted for various breakpoints (1024px, 768px, 480px).
- **Loading Indicators**: Global Spinner component (twSpinner) provides consistent loading feedback across the application. Time Clock page shows clear loading states during initial data fetch and user actions (clock in/out, breaks).
- **Error Handling**: User-friendly error messages with toast notifications differentiate between network errors, API failures, and connection issues.

### Technical Implementations
- **Role-Based Access Control (RBAC)**: A 5-tier `access_level` system (0-4) governs feature access. Access Control is unified using `AccessControl::enforceAccess()` and rules like `auth`, `level:N`, `exact:N`, `role:name`, and `dept:Name`.
- **Department Scoping**: All users (Level 1+) have department-scoped access to the Schedule feature, meaning they can only view and manage schedules for employees in their assigned departments. Levels 3 (Team Lead) and 4 (Department Admin) also have department-scoped access to other features like Departments & Roles.
- **Single Department Model**: Employees are assigned to a single department and a single role.
- **PHP Backend**: Core application logic and authentication.
- **Node.js Chat Server**: Built with ES modules, using Socket.io for real-time WebSocket communication, operating on a configurable port (default 3001).
- **Dual Database Approach**: SQLite for chat-specific data (messages, rooms) and MySQL for user authentication and core application data.
- **Authentication**: Integrates with the existing PHP-based MySQL user system, utilizing `$_SESSION['access_level']` for session management.
- **Database Schema**: Includes tables for users, employees, shifts, departments, roles, and chat-related entities. `users.access_level` stores the RBAC level. `employee_department` links employees to their single department.
- **Configuration**: Uses environment variables for database credentials, hosts, and ports, with fallback defaults.

### Feature Specifications
- **Real-time Chat**: Provided by the Node.js server with Socket.io.
- **Scheduling**: Supports department-grouped employee schedules with color-coding. All users (Level 1+) have department-scoped access, meaning they can only view and manage schedules for employees in their assigned departments.
- **Time Tracking**: Enhanced Time Clock page with global Spinner loading indicators, robust error handling with clear user feedback, network error detection, and seamless integration with the global loading state system. Includes break timer tracking and satisfaction surveys on clock out.
- **Department Management**: Creation, assignment of roles, and user department changes (restricted by access level).
- **Team Roster**: Displays employee information, including department assignments, with administrative actions hidden for view-only users.
- **Access Level Map**: A dedicated page displaying a comprehensive matrix of permissions per access level.

## External Dependencies

- **Socket.io**: For real-time, bidirectional event-based communication in the chat module.
- **SQLite3**: Embedded database used for storing chat-specific data (messages, rooms, participants).
- **MySQL2**: Node.js driver with promise support for interacting with the MySQL database.
- **Node.js Crypto**: Built-in module for cryptographic functions, used for security features.
- **MySQL Database**: External service (e7eh7.h.filess.io:3305) for user authentication and core application data.
- **HTTP Server**: Node.js's built-in HTTP server, primarily for Socket.io transport.
- **WebSocket Protocol**: The underlying real-time communication layer, facilitated by Socket.io.
- **Cross-Origin Resource Sharing (CORS)**: Configured to allow connections from web clients to the chat server.
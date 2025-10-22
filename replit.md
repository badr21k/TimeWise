# TimeWise Employee Management System

## Overview

TimeWise is a comprehensive employee management application that includes real-time chat, scheduling, time tracking, and department management features. The system uses PHP-based authentication with MySQL for user management and implements a robust role-based access control (RBAC) system with 5 access levels.

## User Preferences

Preferred communication style: Simple, everyday language.

## Recent Changes (October 2025)

### Team Roster UI - Level 2 View-Only Mode (October 22, 2025)
- **Department Loading**: Updated to use bootstrap data (matches role loading pattern)
- **Level 2 Action Hiding**: All admin actions now hidden for Level 2 (Power User) users:
  - "+ Add Team Member" button hidden (header and empty state)
  - "Show Terminated" checkbox hidden
  - "Actions" column completely hidden (header + all cells)
  - Table colspan adjusted from 7 to 6 when Actions column is hidden
- **Implementation**: Uses "admin-action" CSS class with display:none for ACCESS_LEVEL === 2
- **Add Team Member Form**:
  - Changed Department field from multi-select to single dropdown
  - Roles now filter based on selected department
  - Department selection is required before saving
  - Roles display message "— Select a department first —" when no department selected

### Database Migration & Access Control Fixes (October 22, 2025)
- **SSL Connection**: Updated database.php to support TiDB Cloud's required SSL/TLS encryption
- **Complete Schema Creation**: Generated all 12 tables with proper relationships and indexes
- **Schema Fixes**: 
  - Fixed role creation to require department_id (roles are now scoped to departments)
  - Fixed members.list API query to use proper column aliasing for user labels
  - Updated Department model's ensureRole method to include department_id parameter
- **Default Data**: Pre-loaded departments, roles, and admin accounts
- **Login Credentials**:
  - Admin: username=admin, password=admin123, access_level=1 (Full Admin)
  - Manager: username=manager, password=manager123, access_level=3 (Team Lead)

### Access Control & Form Improvements (October 22, 2025)
- **Exact Level Matching**: Added new `exact:N` rule to AccessControl for precise level matching (vs. minimum level with `level:N`)
- **Departments & Roles Access**: Fixed to only show for Level 1 (Full Admin) and Level 4 (Department Admin) - blocked for Level 2 and 3
- **Team Roster Access**: Extended to all users (Level 1+) - all authenticated users can now view Team Roster
- **Add Team Member Form**:
  - Made Role field required (added asterisk, required attribute, and validation)
  - Updated Departments label to remove "(optional)" text
  - All departments now load in the form for selection

## Recent Changes (October 2025)

### Access Control Redesign (October 21, 2025)
- **Level 1 Full Admin Access**: Level 1 users now have FULL access to all departments and roles (no restrictions)
- **Level 4 Scoped Admin Access**: Level 4 users have FULL EDIT access scoped to their assigned departments only
- **Single Department Model**: Changed from multi-department to single department + single role per employee
- **Department Scoping Security**: 
  - `departments.php`: guardDepartmentAccess() enforces Level 4 can only manage assigned departments
  - `team.php`: All hire/update operations validate department access; roster filtered for Level 4
  - Level 4 cannot view or modify employees outside assigned departments
- **Optimized Bootstrap**: Single preloaded query for departments+roles in departments controller
- **Security Audited**: All Level 4 privilege escalation bypasses identified and fixed
- **Architect Approved**: Complete security review passed for all access control changes

### Complete is_admin to access_level Migration
- **Database Cleanup**: Removed legacy `is_admin` column from users table
- **Access Control Unified**: All controllers now exclusively use `AccessControl::enforceAccess()` with `access_level` (0-4) checks
- **Session Management Updated**: Login process now sets `$_SESSION['access_level']` instead of `$_SESSION['is_admin']`
- **Frontend Updated**: JavaScript views (team, schedule) now use `accessLevel >= 3` checks for admin features
- **API Responses**: All API endpoints return `access_level` for client-side authorization
- **Database Optimization**: Added missing `updated_at` timestamps to employees, shifts, departments, roles, and users tables
- **Performance Indexes**: Created indexes on frequently queried columns (access_level, is_active, employee_id, etc.)
- **Security Hardened**: Removed all mixed is_admin/access_level logic; system now uses single, consistent access control mechanism

## Role-Based Access Control (RBAC)

### Access Level System (0-4)

The application implements a 5-tier access level system stored in `users.access_level`:

- **Level 0 - Inactive**: Cannot login (disabled account)
- **Level 1 - Full Admin**: Full access to all features, all departments, all roles (unrestricted) - includes Team Roster, Departments & Roles
- **Level 2 - Power User**: Dashboard, Chat, Time Clock, My Shifts, Reminders, Team Roster (view only)
- **Level 3 - Team Lead**: Dashboard, Chat, Team Roster, Schedule Management, Reminders, Admin Reports
- **Level 4 - Department Admin**: Dashboard, Chat, Team Roster (scoped), Departments & Roles (scoped with full edit access), Admin Reports (scoped) - can only manage assigned departments

### Access Control Implementation

- **Configuration File**: `app/config/access.php` defines all navigation and controller access rules
- **Access Control Class**: `app/core/AccessControl.php` handles permission checking
- **Rule Syntax**: 
  - `auth`: Any logged-in user
  - `level:N`: Minimum access level N (e.g., level:3 means levels 3 and 4 can access)
  - `exact:N`: Exact access level N (e.g., exact:1 means only level 1)
  - `role:name`: Specific role name (legacy support)
  - `dept:Name`: Department-scoped access
  - Combine rules with `&` for AND logic
  - Combine rules with `|` for OR logic (e.g., exact:1 | exact:4)

### Department Scoping

Level 4 (Department Admin) users have department-scoped access across the application:

- **Departments View**: 
  - Can only view departments they're assigned to
  - **FULL EDIT ACCESS** to assigned departments (rename, delete, manage roles, assign managers)
  - guardDepartmentAccess() validates all mutation operations
  - Server-side enforcement prevents access to unauthorized departments

- **Team Roster View**:
  - Employee list filtered to only show employees in assigned departments
  - Can hire new employees (only into assigned departments)
  - Can update employees (only in assigned departments)
  - Cannot view or modify employees in other departments
  - All operations validate current department ownership

- **Schedule View**:
  - Employee list filtered to only show employees in assigned departments
  - Shift calendar filtered to only show shifts for employees in assigned departments
  - Cannot view or manage schedules for other departments

- **Reports View**:
  - User satisfaction reports: Only shows users from assigned departments
  - Department satisfaction reports: Only shows assigned departments
  - Hours reports: Only shows employees from assigned departments
  - Employee detail reports: Blocked for employees outside assigned departments
  - All queries use secure parameterized SQL with department filtering

- **Implementation**: 
  - employee_department junction table links employees to departments (single department per employee)
  - guardDepartmentAccess() method enforces scoping in all controllers
  - All read operations filter by department; all write operations validate department ownership

### Database Schema

- **users.access_level**: INT (0-4) - User's access level
- **employee_department**: (employee_id, department_id) - Department assignments (single department per employee)
- **employees.role_title**: VARCHAR - Single role title per employee
- **department_managers**: (department_id, user_id) - Legacy manager assignments

## System Architecture

### Backend Architecture
- **Node.js Server**: Built using ES modules with Socket.io for real-time WebSocket communication
- **Dual Database Approach**: 
  - SQLite for chat-specific data (messages, rooms, participants)
  - MySQL for user authentication (shared with existing PHP application)
- **Port Configuration**: Configurable chat server port (default 3001) separate from main application

### Database Design
- **SQLite Schema**: Handles chat rooms, messages, and participant relationships with foreign key constraints
- **MySQL Integration**: Connects to existing user authentication system using environment-based configuration
- **Promise Wrappers**: Custom promise-based helpers for SQLite operations to maintain consistency with async/await patterns

### Real-time Communication
- **Socket.io Server**: Handles WebSocket connections with CORS enabled for cross-origin requests
- **Event-driven Architecture**: Message broadcasting and user connection management through socket events
- **Connection Management**: Automatic handling of user connections and disconnections

### Authentication Strategy
- **Shared Authentication**: Leverages existing MySQL-based user system from the main TimeWise application
- **Session Management**: Designed to integrate with PHP-based authentication tokens and user sessions

### Configuration Management
- **Environment Variables**: Database credentials, hosts, ports, and connection timeouts configurable via environment
- **Fallback Defaults**: Sensible defaults for development while supporting production configuration
- **Connection Pooling**: MySQL connection management with timeout configurations for reliability

## External Dependencies

### Core Dependencies
- **Socket.io**: Real-time bidirectional event-based communication
- **SQLite3**: Embedded database for chat data persistence
- **MySQL2**: MySQL database driver with promise support for authentication integration
- **Node.js Crypto**: Built-in cryptographic functionality for security features

### Database Services
- **MySQL Database**: External MySQL service hosted at e7eh7.h.filess.io (port 3305) for user authentication
- **SQLite Database**: Local file-based database (database.db) for chat message storage

### Infrastructure
- **HTTP Server**: Node.js built-in HTTP server for Socket.io transport
- **WebSocket Protocol**: Real-time communication layer provided by Socket.io
- **Cross-Origin Resource Sharing**: Configured to allow connections from web clients
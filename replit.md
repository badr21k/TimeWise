# TimeWise Employee Management System

## Overview

TimeWise is a comprehensive employee management application that includes real-time chat, scheduling, time tracking, and department management features. The system uses PHP-based authentication with MySQL for user management and implements a robust role-based access control (RBAC) system with 5 access levels.

## User Preferences

Preferred communication style: Simple, everyday language.

## Recent Changes (October 2025)

### Access Level Map (October 22, 2025)
- **Visual Access Matrix**: Added new Access Level Map page showing comprehensive feature-by-level permission matrix
- **Navigation**: Accessible from Reports dropdown menu (separated by divider)
- **Access Control**: Available to users with access level 3+ (Team Lead, Department Admin, Full Admin)
- **Features**:
  - Visual table displaying all features (Dashboard, Chat, Time Clock, Schedule, etc.)
  - Column for each access level (0-4) showing what each level can access
  - Color-coded icons: ✓ (green) = Full Access, 👁️ (blue) = View Only, ✗ (gray) = No Access
  - Special annotations for department-scoped features (Level 3 & 4)
  - Helps administrators understand the complete permission structure at a glance
- **Implementation**: 
  - Controller: `app/controllers/access_map.php`
  - View: `app/views/access_map/index.php`
  - Route: `/access-map` with `level:3` access requirement

### Department Scoping Reintroduced (October 22, 2025)
- **Department-Scoped Access**: Level 3 and 4 users now have department-scoped access to Schedule and Departments & Roles
- **Access Matrix**:
  - Level 1 (Full Admin): Full access to all features, departments, and data (no restrictions)
  - Level 3 (Team Lead): Department-scoped access to Schedule and Departments & Roles (can only manage their assigned departments)
  - Level 4 (Department Admin): Department-scoped access to Schedule and Departments & Roles (can only manage their assigned departments)
  - Level 2 (Power User): View-only access where applicable
- **Department Change Button**: Now only visible to Level 1 and Level 4 users (removed from Level 3)

### Schedule View - Department-Grouped Display (October 22, 2025)
- **Department Grouping**: Employees now grouped by department with visual headers
- **Color Coding**: Each department has a unique color (10-color palette)
  - Department headers show colored dot and left border
  - Employee rows have colored left border (4px)
  - Shift blocks have colored left border (3px) and tinted background
  - Day cells have subtle colored left border
- **Access Control**: 
  - Level 1: Full edit access to all departments
  - Level 3 & 4: Edit access only to their assigned departments (department-scoped)
  - Level 2: View-only access
- **API Changes**:
  - `employees.list` now returns department info (department_id, department_name)
  - Returns `user_editable_dept_ids` array (all departments for Level 3+)
  - Returns `access_level` for frontend permission checks

### Team Roster - Department Management (October 22, 2025)
- **Department Column**: Added Department column to roster table displaying each user's department assignment
- **Change Department**: Level 1 and Level 4 users can change user departments directly from roster
  - "Change" button appears next to department name for Level 1 and Level 4 only (not Level 3)
  - Opens modal with department dropdown selector
  - New API endpoint: `change_department` action in team.php
  - Auto-creates employee records if they don't exist when assigning departments
- **Department Loading**: Updated to use bootstrap data (matches role loading pattern)
- **Level 2 View-Only Mode**: All admin actions now hidden for Level 2 (Power User) users:
  - "+ Add Team Member" button hidden (header and empty state)
  - "Show Terminated" checkbox hidden
  - "Actions" column completely hidden (header + all cells)
  - Table colspan adjusted from 8 to 7 when Actions column is hidden (now includes Department column)
- **Implementation**: Uses "admin-action" CSS class with display:none for ACCESS_LEVEL === 2
- **Add Team Member Form**:
  - Changed Department field from multi-select to single dropdown
  - Department selection is required before saving
  - Fixed: Frontend now sends `department_id` (singular) instead of `departments` array to match backend expectations
  - Roles show all available options (can be used across departments)
  - Form displays helpful validation messages for required fields

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

### Access Control Redesign (October 21-22, 2025)
- **Level 1 Full Admin Access**: Level 1 users have FULL access to all departments and roles (no restrictions)
- **Department Scoping for Level 3 & 4**: Level 3 and 4 users have department-scoped access (can only manage their assigned departments in Schedule and Departments & Roles)
- **Single Department Model**: Changed from multi-department to single department + single role per employee
- **Department Change Permission**: Only Level 1 and Level 4 can change user departments (Level 3 cannot)
- **Optimized Bootstrap**: Single preloaded query for departments+roles in departments controller

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
- **Level 3 - Team Lead**: Dashboard, Chat, Team Roster, Schedule Management (department-scoped), Reminders, Admin Reports
- **Level 4 - Department Admin**: Dashboard, Chat, Team Roster, Schedule Management (department-scoped), Departments & Roles (department-scoped), Reminders, Admin Reports, Department Change capability

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

### Department Access

Department access varies by user level:

- **Level 1 (Full Admin)**:
  - Full access to all departments across all features
  - Can view, edit, rename, and delete any department
  - Can manage roles, assign managers, and change user departments
  - No restrictions or scoping limitations

- **Level 3 (Team Lead)**:
  - **Department-Scoped Access**: Can only access their assigned departments in:
    - Schedule Management: View and manage shifts only for employees in assigned departments
    - Departments & Roles: View and manage only assigned departments
  - **Full Access (No Scoping)**: Team Roster view and manage all employees
  - **Cannot**: Change user departments (no "Change" button)

- **Level 4 (Department Admin)**:
  - **Department-Scoped Access**: Can only access their assigned departments in:
    - Schedule Management: View and manage shifts only for employees in assigned departments
    - Departments & Roles: View and manage only assigned departments
  - **Full Access (No Scoping)**: Team Roster view and manage all employees
  - **Can**: Change user departments for any user ("Change" button available)

- **Level 2 (Power User)**:
  - View-only access to Team Roster
  - View-only access to Schedule (if granted)
  - No administrative capabilities

- **Implementation**: 
  - `guardDepartmentAccess()` method enforces department scoping for Level 3 & 4
  - `employees.list` API filters employees by department for Level 3 & 4
  - `listDepartmentsWithRolesOptimized()` filters departments by user assignment
  - Employee department junction table links employees to departments (single department per employee)
  - Level 2 users have view-only access where applicable

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
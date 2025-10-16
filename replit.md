# TimeWise Employee Management System

## Overview

TimeWise is a comprehensive employee management application that includes real-time chat, scheduling, time tracking, and department management features. The system uses PHP-based authentication with MySQL for user management and implements a robust role-based access control (RBAC) system with 5 access levels.

## User Preferences

Preferred communication style: Simple, everyday language.

## Role-Based Access Control (RBAC)

### Access Level System (0-4)

The application implements a 5-tier access level system stored in `users.access_level`:

- **Level 0 - Inactive**: Cannot login (disabled account)
- **Level 1 - Regular User**: Dashboard, Chat, Time Clock, My Shifts, Reminders
- **Level 2 - Power User**: Dashboard, Chat, Time Clock, My Shifts, Reminders
- **Level 3 - Team Lead**: Dashboard, Chat, Team Roster, Schedule Management, Reminders, Admin Reports
- **Level 4 - Department Admin**: Dashboard, Chat, Time Clock, My Shifts, Reminders, Admin Reports, Departments & Roles (View Only - department scoped)

### Access Control Implementation

- **Configuration File**: `app/config/access.php` defines all navigation and controller access rules
- **Access Control Class**: `app/core/AccessControl.php` handles permission checking
- **Rule Syntax**: 
  - `auth`: Any logged-in user
  - `level:N`: Minimum access level N (e.g., level:3 means levels 3 and 4 can access)
  - `role:name`: Specific role name (legacy support)
  - `dept:Name`: Department-scoped access
  - Combine rules with `&` for AND logic

### Department Scoping

- **employee_department table**: Junction table linking employees to departments
- **Level 4 Behavior**: Department Admins only see departments they're assigned to
- **View-Only**: Level 4 users can view departments/roles but cannot create, modify, or delete

### Database Schema

- **users.access_level**: INT (0-4) - User's access level
- **employee_department**: (employee_id, department_id) - Department assignments
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
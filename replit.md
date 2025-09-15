# TimeWise Chat

## Overview

TimeWise Chat is a real-time messaging application built with Node.js and Socket.io. The system provides WebSocket-based chat functionality with persistent message storage and user authentication. It supports both individual and group chat capabilities, integrating with an existing TimeWise ecosystem that uses PHP-based authentication.

## User Preferences

Preferred communication style: Simple, everyday language.

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
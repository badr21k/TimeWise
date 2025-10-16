<?php

/**
 * Centralized Access Control Configuration
 * 
 * This file defines all access rules for tabs/features in the TimeWise application.
 * 
 * Access Level System (0-4):
 * - 0 = Inactive (cannot login)
 * - 1 = Regular User - Dashboard, Chat, Time Clock, My Shifts, Reminders
 * - 2 = Power User - Dashboard, Chat, Time Clock, My Shifts, Reminders
 * - 3 = Team Lead - Dashboard, Chat, Team, Schedule, Reminders, Admin Reports
 * - 4 = Department Admin - Dashboard, Chat, Time Clock, My Shifts, Reminders, Admin Reports, Departments & Roles (View Only)
 * 
 * Rules support:
 * - auth: any logged-in user
 * - level:N: minimum access level (e.g., level:3 means level 3 or higher)
 * - role:manager,admin: specific roles (comma-separated) [legacy support]
 * - dept:Department Name: department-based access
 * - Combine with & (AND): level:3 & dept:Food Pantry
 * 
 * To change access rules, simply edit this file - no need to modify controllers or views.
 */

return [
    // Navigation tabs and their access rules
    'navigation' => [
        'dashboard' => 'level:1',                // Level 1+ (Regular User and above)
        'chat' => 'level:1',                    // Level 1+ (Regular User and above)
        'reminders' => 'level:1',               // Level 1+ (Regular User and above)
        
        // Team & Schedule dropdown items
        'team_roster' => 'level:3',             // Level 3+ (Team Lead and above)
        'departments_roles' => 'level:4',       // Level 4 only (Department Admin - view only)
        'schedule' => 'level:3',                // Level 3+ (Team Lead and above)
        'my_shifts' => 'level:1',               // Level 1+ (Regular User and above)
        'time_clock' => 'level:1',              // Level 1+ (Regular User and above)
        
        // Reports dropdown - Team Leads and above
        'reports' => 'level:3',
    ],
    
    // Controller-level access rules (server-side enforcement)
    'controllers' => [
        'reports' => 'level:3',                 // Team Leads and above
        'departments' => 'level:4',             // Department Admins only (view only)
        'team' => 'level:3',                    // Team Leads and above
        'schedule' => [
            'index' => 'level:3',               // Schedule management - Team Leads and above
            'my' => 'level:1',                  // My shifts - available to all
        ],
        'notes' => 'level:1',                   // Reminders available to all
        'chat' => 'level:1',                    // Chat available to all
        'home' => 'level:1',                    // Dashboard available to all
        'timeclock' => 'level:1',               // Time Clock controller
    ],
    
    // Specific action-level rules (if needed for finer control)
    'actions' => [
        'reports.index' => 'level:3',
        'reports.allReminders' => 'level:3',
        'reports.userStats' => 'level:3',
        'reports.loginReport' => 'level:3',
        'reports.hours' => 'level:3',
        'reports.hoursEmployee' => 'level:3',
        'team.roster' => 'level:3',
        'departments.index' => 'level:4',
        'schedule.index' => 'level:3',
        'timeclock.index' => 'level:1',
        'timeclock.api' => 'level:1',
    ],
];

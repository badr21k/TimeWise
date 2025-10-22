<?php

/**
 * Centralized Access Control Configuration
 * 
 * This file defines all access rules for tabs/features in the TimeWise application.
 * 
 * Access Level System (0-4):
 * - 0 = Inactive (cannot login)
 * - 1 = Full Admin - Dashboard, Chat, Time Clock, My Shifts, Reminders, Departments & Roles (FULL ACCESS)
 * - 2 = Power User - Dashboard, Chat, Time Clock, My Shifts, Reminders
 * - 3 = Team Lead - Dashboard, Chat, Team, Schedule, Reminders, Admin Reports
 * - 4 = Department Admin - Dashboard, Chat, Time Clock, My Shifts, Reminders, Admin Reports, Departments & Roles (FULL EDIT - scoped to own departments)
 * 
 * Rules support:
 * - auth: any logged-in user
 * - level:N: minimum access level (e.g., level:3 means level 3 or higher)
 * - exact:N: exact access level (e.g., exact:1 means only level 1)
 * - role:manager,admin: specific roles (comma-separated) [legacy support]
 * - dept:Department Name: department-based access
 * - Combine with & (AND): level:3 & dept:Food Pantry
 * - Combine with | (OR): exact:1 | exact:4
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
        'team_roster' => 'level:1',             // Level 1+ (All users can view team roster)
        'departments_roles' => 'exact:1 | exact:4',  // Only Level 1 (Full Admin) and Level 4 (Dept Admin)
        'schedule' => 'level:1',                // Level 1+ (All users - department-scoped)
        'my_shifts' => 'level:1',               // Level 1+ (Regular User and above)
        'time_clock' => 'level:1',              // Level 1+ (Regular User and above)
        
        // Reports dropdown - Team Leads and above
        'reports' => 'level:3',
        'access_map' => 'level:3',              // Level 3+ (Team Lead and above)
    ],
    
    // Controller-level access rules (server-side enforcement)
    'controllers' => [
        'reports' => 'level:3',                 // Team Leads and above
        'access_map' => 'level:3',              // Access Level Map - Team Leads and above
        'departments' => 'exact:1 | exact:4',   // Only Level 1 (Full Admin) and Level 4 (Dept Admin)
        'team' => 'level:1',                    // All users can access team roster
        'schedule' => [
            'index' => 'level:1',               // Schedule management - All users (department-scoped)
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
        'team.roster' => 'level:1',             // All users can access team roster
        'departments.index' => 'exact:1 | exact:4',  // Only Level 1 (Full Admin) and Level 4 (Dept Admin)
        'schedule.index' => 'level:1',
        'timeclock.index' => 'level:1',
        'timeclock.api' => 'level:1',
    ],
];

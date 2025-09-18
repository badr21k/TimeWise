<?php

/**
 * Centralized Access Control Configuration
 * 
 * This file defines all access rules for tabs/features in the TimeWise application.
 * Rules support:
 * - auth: any logged-in user
 * - role:manager,admin: specific roles (comma-separated)
 * - dept:Food Pantry: department-based access
 * - Combine with & (AND): role:manager & dept:Food Pantry
 * 
 * To change access rules, simply edit this file - no need to modify controllers or views.
 */

return [
    // Navigation tabs and their access rules
    'navigation' => [
        'dashboard' => 'auth',                    // Available to all logged-in users
        'chat' => 'auth',                        // Available to all logged-in users
        'reminders' => 'auth',                   // Available to all logged-in users
        
        // Team & Schedule dropdown items
        'team_roster' => 'role:manager,admin',   // Only managers and admins
        'departments_roles' => 'role:manager,admin', // Only managers and admins
        'schedule' => 'role:manager,admin',      // Only managers and admins
        'my_shifts' => 'auth',                   // Available to all logged-in users
        'time_clock' => 'auth',                  // Time Clock available to all logged-in users
        
        // Reports dropdown - only visible to Food Pantry managers
        'reports' => 'role:manager,admin & dept:Food Pantry',
    ],
    
    // Controller-level access rules (server-side enforcement)
    'controllers' => [
        'reports' => 'role:manager,admin & dept:Food Pantry',
        'departments' => 'role:manager,admin',
        'team' => 'role:manager,admin',
        'schedule' => [
            'index' => 'role:manager,admin',     // Schedule management
            'my' => 'auth',                      // My shifts - available to all
        ],
        'notes' => 'auth',                       // Reminders available to all
        'chat' => 'auth',                        // Chat available to all
        'home' => 'auth',                        // Dashboard available to all
        'timeclock' => 'auth',                   // Time Clock controller
    ],
    
    // Specific action-level rules (if needed for finer control)
    'actions' => [
        'reports.index' => 'role:manager,admin & dept:Food Pantry',
        'reports.allReminders' => 'role:manager,admin & dept:Food Pantry',
        'reports.userStats' => 'role:manager,admin & dept:Food Pantry',
        'reports.loginReport' => 'role:manager,admin & dept:Food Pantry',
        'reports.hours' => 'role:manager,admin & dept:Food Pantry',
        'reports.hoursEmployee' => 'role:manager,admin & dept:Food Pantry',
        'team.roster' => 'role:manager,admin',
        'departments.index' => 'role:manager,admin',
        'schedule.index' => 'role:manager,admin',
        'timeclock.index' => 'auth',
        'timeclock.api' => 'auth',
    ],
];

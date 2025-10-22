<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Access Level Map - TimeWise</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <style>
    .access-map-container {
      max-width: 1400px;
      margin: 2rem auto;
      padding: 0 1rem;
    }
    
    .access-map-header {
      margin-bottom: 2rem;
    }
    
    .access-map-header h1 {
      font-size: 2rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }
    
    .access-map-header p {
      color: #6b7280;
      font-size: 1rem;
    }
    
    .legend {
      background: white;
      border-radius: 8px;
      padding: 1.5rem;
      margin-bottom: 2rem;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .legend h3 {
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }
    
    .legend-items {
      display: flex;
      flex-wrap: wrap;
      gap: 1.5rem;
    }
    
    .legend-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .legend-icon {
      width: 24px;
      height: 24px;
      border-radius: 4px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.85rem;
    }
    
    .legend-icon.full { background: #10b981; color: white; }
    .legend-icon.scoped { background: #f59e0b; color: white; }
    .legend-icon.view { background: #3b82f6; color: white; }
    .legend-icon.none { background: #e5e7eb; color: #9ca3af; }
    
    .access-table-wrapper {
      background: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .access-table {
      width: 100%;
      border-collapse: collapse;
    }
    
    .access-table thead {
      background: #f9fafb;
      border-bottom: 2px solid #e5e7eb;
    }
    
    .access-table th {
      padding: 1rem;
      text-align: left;
      font-weight: 600;
      font-size: 0.875rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: #374151;
    }
    
    .access-table th:first-child {
      min-width: 200px;
    }
    
    .level-header {
      text-align: center !important;
      padding: 0.75rem 0.5rem !important;
    }
    
    .level-name {
      font-size: 0.75rem;
      display: block;
      margin-top: 0.25rem;
      color: #6b7280;
      font-weight: 400;
      text-transform: none;
    }
    
    .access-table tbody tr {
      border-bottom: 1px solid #e5e7eb;
    }
    
    .access-table tbody tr:hover {
      background: #f9fafb;
    }
    
    .access-table td {
      padding: 1rem;
      font-size: 0.9rem;
    }
    
    .feature-name {
      font-weight: 500;
      color: #111827;
    }
    
    .feature-description {
      font-size: 0.8rem;
      color: #6b7280;
      margin-top: 0.25rem;
    }
    
    .access-indicator {
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 0.5rem;
    }
    
    .access-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem 0.75rem;
      border-radius: 6px;
      font-size: 0.85rem;
      font-weight: 500;
    }
    
    .access-badge.full {
      background: #d1fae5;
      color: #065f46;
    }
    
    .access-badge.scoped {
      background: #fef3c7;
      color: #92400e;
    }
    
    .access-badge.view {
      background: #dbeafe;
      color: #1e40af;
    }
    
    .access-badge.none {
      background: #f3f4f6;
      color: #9ca3af;
    }
    
    @media (max-width: 768px) {
      .access-map-container {
        padding: 0;
      }
      
      .access-table-wrapper {
        border-radius: 0;
        overflow-x: auto;
      }
      
      .access-table {
        min-width: 800px;
      }
      
      .legend-items {
        flex-direction: column;
        gap: 0.75rem;
      }
    }
  </style>
</head>
<body>
  <?php require_once 'app/views/templates/header.php'; ?>
  
  <div class="access-map-container">
    <div class="access-map-header">
      <h1>Access Level Map</h1>
      <p>Visual overview of what each access level can do in the TimeWise system</p>
    </div>
    
    <div class="legend">
      <h3>Legend</h3>
      <div class="legend-items">
        <div class="legend-item">
          <div class="legend-icon full">✓</div>
          <span>Full Access</span>
        </div>
        <div class="legend-item">
          <div class="legend-icon scoped">🏢</div>
          <span>Department Scoped</span>
        </div>
        <div class="legend-item">
          <div class="legend-icon view">👁️</div>
          <span>View Only</span>
        </div>
        <div class="legend-item">
          <div class="legend-icon none">✗</div>
          <span>No Access</span>
        </div>
      </div>
    </div>
    
    <div class="access-table-wrapper">
      <table class="access-table">
        <thead>
          <tr>
            <th>Feature</th>
            <th class="level-header">
              Level 0
              <span class="level-name">Inactive</span>
            </th>
            <th class="level-header">
              Level 1
              <span class="level-name">Full Admin</span>
            </th>
            <th class="level-header">
              Level 2
              <span class="level-name">Power User</span>
            </th>
            <th class="level-header">
              Level 3
              <span class="level-name">Team Lead</span>
            </th>
            <th class="level-header">
              Level 4
              <span class="level-name">Dept Admin</span>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <div class="feature-name">Dashboard</div>
              <div class="feature-description">Main overview page</div>
            </td>
            <td class="access-indicator">
              <span class="access-badge none">✗</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
          </tr>
          
          <tr>
            <td>
              <div class="feature-name">Chat</div>
              <div class="feature-description">Real-time team communication</div>
            </td>
            <td class="access-indicator">
              <span class="access-badge none">✗</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
          </tr>
          
          <tr>
            <td>
              <div class="feature-name">Time Clock</div>
              <div class="feature-description">Clock in/out tracking</div>
            </td>
            <td class="access-indicator">
              <span class="access-badge none">✗</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
          </tr>
          
          <tr>
            <td>
              <div class="feature-name">My Shifts</div>
              <div class="feature-description">Personal schedule view</div>
            </td>
            <td class="access-indicator">
              <span class="access-badge none">✗</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
          </tr>
          
          <tr>
            <td>
              <div class="feature-name">Reminders</div>
              <div class="feature-description">Personal reminders & notes</div>
            </td>
            <td class="access-indicator">
              <span class="access-badge none">✗</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
          </tr>
          
          <tr>
            <td>
              <div class="feature-name">Team Roster</div>
              <div class="feature-description">View and manage team members</div>
            </td>
            <td class="access-indicator">
              <span class="access-badge none">✗</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓ Full</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge view">👁️ View</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓ Full</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓ Full</span>
            </td>
          </tr>
          
          <tr>
            <td>
              <div class="feature-name">Schedule Management</div>
              <div class="feature-description">Create and edit team schedules</div>
            </td>
            <td class="access-indicator">
              <span class="access-badge none">✗</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓ All Depts</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge none">✗</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge scoped">🏢 Scoped</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge scoped">🏢 Scoped</span>
            </td>
          </tr>
          
          <tr>
            <td>
              <div class="feature-name">Departments & Roles</div>
              <div class="feature-description">Manage departments and roles</div>
            </td>
            <td class="access-indicator">
              <span class="access-badge none">✗</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓ All Depts</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge none">✗</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge scoped">🏢 Scoped</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge scoped">🏢 Scoped</span>
            </td>
          </tr>
          
          <tr>
            <td>
              <div class="feature-name">Change User Departments</div>
              <div class="feature-description">Reassign users to different departments</div>
            </td>
            <td class="access-indicator">
              <span class="access-badge none">✗</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge none">✗</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge none">✗</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
          </tr>
          
          <tr>
            <td>
              <div class="feature-name">Admin Reports</div>
              <div class="feature-description">System-wide analytics and reports</div>
            </td>
            <td class="access-indicator">
              <span class="access-badge none">✗</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge none">✗</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
          </tr>
          
          <tr>
            <td>
              <div class="feature-name">Access Level Map</div>
              <div class="feature-description">This page - view permission matrix</div>
            </td>
            <td class="access-indicator">
              <span class="access-badge none">✗</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge none">✗</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
            <td class="access-indicator">
              <span class="access-badge full">✓</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  
  <?php require_once 'app/views/templates/footer.php'; ?>
</body>
</html>

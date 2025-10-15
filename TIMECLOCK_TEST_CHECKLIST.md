# TimeWise Time Clock View - Testing & Validation Checklist

## Requirements Verification

### ✅ Core Functionality
- [x] PHP backend integration with `/timeclock/api` endpoint
- [x] Proper action names: `clock.in`, `clock.out`, `break.start`, `break.end`, `status`
- [x] Timezone and client time ISO sent with all API requests
- [x] State management: `in`, `out`, `break` statuses
- [x] Satisfaction rating on clock out

### ✅ UI Components
- [x] Live time and date display (updates every second)
- [x] Current status pill (Clocked In / On Break / Clocked Out)
- [x] Real-time timer showing work duration
- [x] Break timer when on break
- [x] Today's Shift card with schedule info
- [x] Next Shift card
- [x] Action buttons (Clock In, Break Start/End, Clock Out)
- [x] Busy overlay during API requests
- [x] Toast notifications for feedback
- [x] Satisfaction modal on clock out

### ✅ Grace Period Logic (±30 minutes)
- [x] Clock in enabled 30 minutes before shift start
- [x] On-time status if within grace period
- [x] Late status if after grace period
- [x] Grace period indicator shown when applicable
- [x] Button disabled if too early or shift ended

### ✅ Shift Card Logic
- [x] Today's Shift card shows scheduled shift
- [x] Card hidden after shift ends (when clocked out and shift complete)
- [x] Displays in history table when hidden
- [x] Next Shift card shows upcoming schedule
- [x] Unscheduled badge when no schedule

### ✅ History Table
- [x] Shows all time entries for today
- [x] Displays clock in/out times
- [x] Shows break duration
- [x] Shows entry type (Scheduled/Unscheduled)
- [x] Calculates hours worked
- [x] Shows on-time/late status
- [x] Total hours calculation at bottom

### ✅ Dynamic Updates
- [x] UI updates after each action without page reload
- [x] State refreshes every 30 seconds
- [x] Refreshes when tab becomes visible again
- [x] Button states update based on current status
- [x] Timers update in real-time

### ✅ Design & Styling
- [x] Bootstrap 5.1.3 components
- [x] Custom CSS with project color scheme
- [x] Responsive design for mobile
- [x] All styles in single file (no external imports)
- [x] Consistent with existing project design

## Backend API Integration

### Status Endpoint (`?a=status`)
**Request:**
- Method: POST
- Parameters: `tz`, `client_time_iso`

**Response:**
```json
{
  "clocked_in": boolean,
  "on_break": boolean,
  "entry": {
    "id": number,
    "clock_in": "YYYY-MM-DD HH:MM:SS",
    "clock_out": "YYYY-MM-DD HH:MM:SS" | null,
    "total_break_minutes": number,
    "satisfaction": number | null
  },
  "entries_today": [...],
  "today_schedule": {...} | null,
  "next_schedule": {...} | null
}
```

### Clock In (`?a=clock.in`)
- Validates user not already clocked in
- Checks start_date (can't clock in before join date)
- Records UTC timestamp with user's timezone context

### Break Start/End (`?a=break.start`, `?a=break.end`)
- Requires active clock in
- Prevents multiple active breaks
- Calculates total break time

### Clock Out (`?a=clock.out`)
- Requires active clock in
- Prevents clock out during break
- Optional satisfaction rating (1-5)

## Database Schema

### time_entries
- `id`: INT AUTO_INCREMENT PRIMARY KEY
- `user_id`: INT NOT NULL
- `employee_id`: INT NULL
- `entry_date`: DATE NOT NULL
- `clock_in`: DATETIME NOT NULL
- `clock_out`: DATETIME NULL
- `total_break_minutes`: INT DEFAULT 0
- `satisfaction`: TINYINT NULL

### time_entry_breaks
- `id`: INT AUTO_INCREMENT PRIMARY KEY
- `time_entry_id`: INT NOT NULL
- `break_start`: DATETIME NOT NULL
- `break_end`: DATETIME NULL

### employees
- Requires `user_id` column for schedule integration
- Requires `start_date` column for join date validation
- Migration script: `add_employee_user_link.php`

### shifts
- Links to `employee_id`
- Contains `start_dt` and `end_dt` for schedule

## Known Limitations

1. **Schedule Integration**: Requires `user_id` column in `employees` table
   - Run migration: `php add_employee_user_link.php`
   - Link users to employees via admin interface

2. **Timezone Handling**: 
   - All times stored in UTC
   - Converted to user's timezone for display
   - Grace period calculations use user's local time

3. **Break Timer**: 
   - Backend doesn't provide current break start time
   - Frontend tracks break start when status changes to 'break'
   - May lose accuracy if page is refreshed during break

## Testing Steps

### Manual Testing
1. **Initial Load**
   - [ ] Navigate to `/timeclock`
   - [ ] Verify status loads correctly
   - [ ] Check live clock is updating

2. **Clock In**
   - [ ] Click "Clock In" button
   - [ ] Verify status changes to "Clocked In"
   - [ ] Check timer starts counting
   - [ ] Verify entry appears in history table

3. **Break Management**
   - [ ] Click "Start Break"
   - [ ] Verify status changes to "On Break"
   - [ ] Check break timer appears
   - [ ] Click "End Break"
   - [ ] Verify status returns to "Clocked In"
   - [ ] Check break time is recorded

4. **Clock Out**
   - [ ] Click "Clock Out"
   - [ ] Verify satisfaction modal appears
   - [ ] Select rating and submit
   - [ ] Check status changes to "Clocked Out"
   - [ ] Verify final entry in history table

5. **Grace Period**
   - [ ] Create scheduled shift
   - [ ] Test clock in before grace period (should be blocked)
   - [ ] Test clock in within grace period (should be allowed)
   - [ ] Test clock in after grace period (should show as late)

6. **Error Handling**
   - [ ] Test with network disconnected
   - [ ] Verify error messages appear
   - [ ] Check retry functionality

## Browser Compatibility
- Chrome/Edge (Chromium)
- Firefox
- Safari
- Mobile browsers (iOS Safari, Chrome Android)

## Accessibility
- Keyboard navigation support
- Screen reader friendly labels
- High contrast mode compatible
- Focus indicators visible

## Performance
- Initial load time: < 2 seconds
- API response time: < 500ms
- Timer updates: Every 1 second
- State refresh: Every 30 seconds

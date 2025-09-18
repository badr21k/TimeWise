<?php require 'app/views/templates/header.php'; ?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0"><i class="fas fa-hourglass-half me-2"></i>Weekly Hours</h1>
    <form class="d-flex" method="get" action="/reports/hours">
      <input type="date" class="form-control me-2" name="week" value="<?php echo htmlspecialchars($week_start); ?>">
      <button class="btn btn-primary" type="submit"><i class="fas fa-sync-alt me-1"></i>Go</button>
    </form>
  </div>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:60%">Employee</th>
            <th class="text-end">Total Hours</th>
            <th class="text-end" style="width:160px">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($rows)): ?>
            <tr><td colspan="3" class="text-center text-muted py-4">No shifts found for this week.</td></tr>
          <?php else: ?>
            <?php foreach ($rows as $r): ?>
              <tr>
                <td><?php echo htmlspecialchars($r['employee_name']); ?></td>
                <td class="text-end fw-semibold"><?php echo number_format((float)$r['total_hours'], 2); ?></td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-primary" href="/reports/hoursEmployee?employee_id=<?php echo (int)$r['employee_id']; ?>&week=<?php echo urlencode($week_start); ?>">
                    <i class="fas fa-calendar-day me-1"></i>View Days
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require 'app/views/templates/footer.php'; ?>

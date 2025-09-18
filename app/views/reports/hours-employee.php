<?php require 'app/views/templates/header.php'; ?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0"><i class="fas fa-user-clock me-2"></i><?php echo htmlspecialchars($employee_name); ?> — Week of <?php echo htmlspecialchars($week_start); ?></h1>
    <form class="d-flex" method="get" action="/reports/hoursEmployee">
      <input type="hidden" name="employee_id" value="<?php echo (int)$employee_id; ?>">
      <input type="date" class="form-control me-2" name="week" value="<?php echo htmlspecialchars($week_start); ?>">
      <button class="btn btn-primary" type="submit"><i class="fas fa-sync-alt me-1"></i>Go</button>
    </form>
  </div>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:50%">Date</th>
            <th class="text-end">Hours</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($per_day)): ?>
            <tr><td colspan="2" class="text-center text-muted py-4">No shifts found for this week.</td></tr>
          <?php else: ?>
            <?php 
              $total = 0.0; 
              foreach ($per_day as $d): 
                $total += (float)$d['hours'];
            ?>
              <tr>
                <td><?php echo date('D, M j, Y', strtotime($d['date'])); ?></td>
                <td class="text-end fw-semibold"><?php echo number_format((float)$d['hours'], 2); ?></td>
              </tr>
            <?php endforeach; ?>
            <tr class="table-light">
              <td class="text-end fw-bold">Total</td>
              <td class="text-end fw-bold"><?php echo number_format($total, 2); ?></td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    <a class="btn btn-outline-secondary" href="/reports/hours?week=<?php echo urlencode($week_start); ?>">
      <i class="fas fa-arrow-left me-1"></i>Back to Weekly Hours
    </a>
  </div>
</div>

<?php require 'app/views/templates/footer.php'; ?>

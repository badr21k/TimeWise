<?php require 'app/views/templates/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="display-6">
                    <i class="fas fa-users text-success me-2"></i>
                    User Statistics
                    <small class="text-muted fs-6">Reminder usage by user</small>
                </h1>
                <a href="/reports" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Reports
                </a>
            </div>

            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card border-0 shadow">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-bar me-2"></i>
                                User Reminder Statistics
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <?php if (empty($userStats)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                                    <h4 class="text-muted">No Users Found</h4>
                                    <p class="text-muted">No users have been registered yet.</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Rank</th>
                                                <th>Username</th>
                                                <th>Total Reminders</th>
                                                <th>Completed</th>
                                                <th>Pending</th>
                                                <th>Completion Rate</th>
                                                <th>Progress</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $rank = 1;
                                            foreach ($userStats as $stat): 
                                                $completionRate = $stat['total_reminders'] > 0 ? ($stat['completed'] / $stat['total_reminders']) * 100 : 0;
                                            ?>
                                                <tr>
                                                    <td>
                                                        <?php if ($rank == 1 && $stat['total_reminders'] > 0): ?>
                                                            <i class="fas fa-trophy text-warning fa-lg"></i>
                                                        <?php elseif ($rank <= 3 && $stat['total_reminders'] > 0): ?>
                                                            <i class="fas fa-medal text-secondary fa-lg"></i>
                                                        <?php else: ?>
                                                            <span class="badge bg-light text-dark"><?php echo $rank; ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($stat['username']); ?></strong>
                                                        <?php if ($stat['username'] === 'admin'): ?>
                                                            <span class="badge bg-danger ms-2">Admin</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary rounded-pill"><?php echo $stat['total_reminders']; ?></span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success rounded-pill"><?php echo $stat['completed']; ?></span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-warning rounded-pill"><?php echo $stat['pending']; ?></span>
                                                    </td>
                                                    <td>
                                                        <span class="fw-bold <?php echo $completionRate >= 70 ? 'text-success' : ($completionRate >= 40 ? 'text-warning' : 'text-danger'); ?>">
                                                            <?php echo number_format($completionRate, 1); ?>%
                                                        </span>
                                                    </td>
                                                    <td style="width: 150px;">
                                                        <?php if ($stat['total_reminders'] > 0): ?>
                                                            <div class="progress" style="height: 20px;">
                                                                <div class="progress-bar bg-success" role="progressbar" 
                                                                     style="width: <?php echo $completionRate; ?>%" 
                                                                     aria-valuenow="<?php echo $completionRate; ?>" 
                                                                     aria-valuemin="0" aria-valuemax="100">
                                                                    <?php if ($completionRate > 15): ?>
                                                                        <?php echo number_format($completionRate, 0); ?>%
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        <?php else: ?>
                                                            <small class="text-muted">No reminders</small>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php 
                                            $rank++;
                                            endforeach; 
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-pie me-2"></i>
                                User Distribution
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="userDistributionChart"></canvas>
                        </div>
                    </div>

                    <?php 
                    $topUsers = array_slice(array_filter($userStats, function($stat) { return $stat['total_reminders'] > 0; }), 0, 3);
                    if (!empty($topUsers)): 
                    ?>
                    <div class="card border-0 shadow mt-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-star me-2"></i>
                                Top Performers
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php foreach ($topUsers as $index => $user): ?>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="me-3">
                                        <?php if ($index == 0): ?>
                                            <i class="fas fa-trophy text-warning fa-2x"></i>
                                        <?php elseif ($index == 1): ?>
                                            <i class="fas fa-medal text-secondary fa-2x"></i>
                                        <?php else: ?>
                                            <i class="fas fa-award text-danger fa-2x"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <h6 class="mb-1"><?php echo htmlspecialchars($user['username']); ?></h6>
                                        <small class="text-muted">
                                            <?php echo $user['total_reminders']; ?> reminders, 
                                            <?php echo number_format(($user['completed'] / $user['total_reminders']) * 100, 1); ?>% complete
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// User Distribution Chart
const ctx = document.getElementById('userDistributionChart').getContext('2d');

// Prepare data for chart
const userLabels = [<?php echo implode(',', array_map(function($stat) { 
    return '"' . addslashes($stat['username']) . ' (' . $stat['total_reminders'] . ')"'; 
}, array_slice(array_filter($userStats, function($s) { return $s['total_reminders'] > 0; }), 0, 8))); ?>];

const userData = [<?php echo implode(',', array_map(function($stat) { 
    return $stat['total_reminders']; 
}, array_slice(array_filter($userStats, function($s) { return $s['total_reminders'] > 0; }), 0, 8))); ?>];

const colors = [
    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
    '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
];

new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: userLabels,
        datasets: [{
            data: userData,
            backgroundColor: colors.slice(0, userData.length),
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    boxWidth: 12,
                    padding: 8,
                    font: {
                        size: 10
                    }
                }
            }
        }
    }
});
</script>

<?php require 'app/views/templates/footer.php'; ?> 
<?php require 'app/views/templates/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="display-6">
                    <i class="fas fa-chart-bar text-primary me-2"></i>
                    Admin Reports
                    <small class="text-muted fs-6">System analytics and insights</small>
                </h1>
                <div class="btn-group" role="group">
                    <a href="/reports/allReminders" class="btn btn-outline-primary">
                        <i class="fas fa-list me-1"></i>All Reminders
                    </a>
                    <a href="/reports/userStats" class="btn btn-outline-success">
                        <i class="fas fa-users me-1"></i>User Stats
                    </a>
                    <a href="/reports/loginReport" class="btn btn-outline-info">
                        <i class="fas fa-sign-in-alt me-1"></i>Login Report
                    </a>
                </div>
            </div>

            <!-- Overview Statistics -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <h3 class="card-title"><?php echo $stats['total_users']; ?></h3>
                            <p class="card-text">Total Users</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-tasks fa-3x mb-3"></i>
                            <h3 class="card-title"><?php echo $stats['total_reminders']; ?></h3>
                            <p class="card-text">Total Reminders</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-dark h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-3x mb-3"></i>
                            <h3 class="card-title"><?php echo $stats['pending_reminders']; ?></h3>
                            <p class="card-text">Pending</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-info text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-sign-in-alt fa-3x mb-3"></i>
                            <h3 class="card-title"><?php echo $stats['total_logins']; ?></h3>
                            <p class="card-text">Total Logins</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-pie me-2"></i>Reminder Status
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="reminderStatusChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-bar me-2"></i>Top Users by Logins
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="loginChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top User Section -->
            <?php if ($topUser && $topUser['reminder_count'] > 0): ?>
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="alert alert-success border-0 shadow-sm">
                        <h5 class="alert-heading">
                            <i class="fas fa-trophy text-warning me-2"></i>
                            Most Active User
                        </h5>
                        <p class="mb-0">
                            <strong><?php echo htmlspecialchars($topUser['username']); ?></strong> 
                            has the most reminders with <strong><?php echo $topUser['reminder_count']; ?></strong> 
                            <?php echo $topUser['reminder_count'] == 1 ? 'reminder' : 'reminders'; ?>.
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Recent Activity and Login Stats -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card border-0 shadow">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-history me-2"></i>Recent Activity
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <?php foreach (array_slice($recentActivity, 0, 10) as $activity): ?>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">
                                                <i class="fas fa-<?php echo $activity['activity_type'] == 'login' ? 'sign-in-alt' : 'plus-circle'; ?> me-2 text-<?php echo $activity['activity_type'] == 'login' ? 'primary' : 'success'; ?>"></i>
                                                <?php echo htmlspecialchars($activity['username']); ?>
                                            </h6>
                                            <small class="text-muted"><?php echo date('M j, Y g:i A', strtotime($activity['activity_time'])); ?></small>
                                        </div>
                                        <p class="mb-1"><?php echo htmlspecialchars($activity['details']); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-line me-2"></i>Login Statistics
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php foreach (array_slice($loginStats, 0, 5) as $stat): ?>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <strong><?php echo htmlspecialchars($stat['username']); ?></strong><br>
                                        <small class="text-muted">Last: <?php echo date('M j', strtotime($stat['last_login'])); ?></small>
                                    </div>
                                    <span class="badge bg-primary rounded-pill"><?php echo $stat['login_count']; ?></span>
                                </div>
                            <?php endforeach; ?>
                            <div class="mt-3">
                                <a href="/reports/loginReport" class="btn btn-sm btn-outline-info w-100">
                                    <i class="fas fa-external-link-alt me-1"></i>View Full Report
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Reminder Status Pie Chart
const reminderCtx = document.getElementById('reminderStatusChart').getContext('2d');
new Chart(reminderCtx, {
    type: 'doughnut',
    data: {
        labels: ['Completed', 'Pending'],
        datasets: [{
            data: [<?php echo $stats['completed_reminders']; ?>, <?php echo $stats['pending_reminders']; ?>],
            backgroundColor: ['#28a745', '#ffc107'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});

// Login Stats Bar Chart
const loginCtx = document.getElementById('loginChart').getContext('2d');
new Chart(loginCtx, {
    type: 'bar',
    data: {
        labels: [<?php echo implode(',', array_map(function($stat) { return '"' . addslashes($stat['username']) . '"'; }, array_slice($loginStats, 0, 5))); ?>],
        datasets: [{
            label: 'Logins',
            data: [<?php echo implode(',', array_map(function($stat) { return $stat['login_count']; }, array_slice($loginStats, 0, 5))); ?>],
            backgroundColor: '#007bff',
            borderColor: '#0056b3',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>

<?php require 'app/views/templates/footer.php'; ?> 
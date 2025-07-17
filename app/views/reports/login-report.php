<?php require 'app/views/templates/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="display-6">
                    <i class="fas fa-sign-in-alt text-info me-2"></i>
                    Login Report
                    <small class="text-muted fs-6">User authentication statistics</small>
                </h1>
                <a href="/reports" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Reports
                </a>
            </div>

            <div class="card border-0 shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        Login Attempts by User
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Username</th>
                                    <th>Successful Logins</th>
                                    <th>Failed Attempts</th>
                                    <th>Total Attempts</th>
                                    <th>Success Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $userSummary = [];
                                foreach ($loginStats as $stat) {
                                    if (!isset($userSummary[$stat['username']])) {
                                        $userSummary[$stat['username']] = [
                                            'good' => 0,
                                            'bad' => 0
                                        ];
                                    }
                                    $userSummary[$stat['username']][$stat['status']] = $stat['attempt_count'];
                                }

                                foreach ($userSummary as $username => $stats):
                                    $total = $stats['good'] + $stats['bad'];
                                    $successRate = $total > 0 ? ($stats['good'] / $total) * 100 : 0;
                                ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($username); ?></strong>
                                            <?php if ($username === 'admin'): ?>
                                                <span class="badge bg-danger ms-2">Admin</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-success rounded-pill"><?php echo $stats['good']; ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger rounded-pill"><?php echo $stats['bad']; ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary rounded-pill"><?php echo $total; ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress me-2" style="width: 100px; height: 20px;">
                                                    <div class="progress-bar bg-<?php echo $successRate >= 80 ? 'success' : ($successRate >= 60 ? 'warning' : 'danger'); ?>" 
                                                         role="progressbar" 
                                                         style="width: <?php echo $successRate; ?>%"
                                                         aria-valuenow="<?php echo $successRate; ?>" 
                                                         aria-valuemin="0" aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <span class="fw-bold"><?php echo number_format($successRate, 1); ?>%</span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'app/views/templates/footer.php'; ?> 
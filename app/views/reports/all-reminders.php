<?php require 'app/views/templates/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="display-6">
                    <i class="fas fa-list text-primary me-2"></i>
                    All Reminders
                    <small class="text-muted fs-6">System-wide reminder overview</small>
                </h1>
                <a href="/reports" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Reports
                </a>
            </div>

            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-database me-2"></i>
                        All User Reminders (<?php echo count($reminders); ?> total)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($reminders)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No Reminders Found</h4>
                            <p class="text-muted">No reminders have been created yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Subject</th>
                                        <th>Content</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reminders as $reminder): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($reminder['username']); ?></strong>
                                            </td>
                                            <td>
                                                <div class="<?php echo $reminder['completed'] ? 'text-decoration-line-through text-muted' : ''; ?>">
                                                    <?php echo htmlspecialchars($reminder['subject']); ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-muted small <?php echo $reminder['completed'] ? 'text-decoration-line-through' : ''; ?>">
                                                    <?php 
                                                    if (!empty($reminder['content'])) {
                                                        echo htmlspecialchars(substr($reminder['content'], 0, 50)) . (strlen($reminder['content']) > 50 ? '...' : '');
                                                    } else {
                                                        echo '<em>No content</em>';
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $reminder['completed'] ? 'success' : 'warning'; ?>">
                                                    <i class="fas fa-<?php echo $reminder['completed'] ? 'check' : 'clock'; ?> me-1"></i>
                                                    <?php echo $reminder['completed'] ? 'Completed' : 'Pending'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?php echo date('M j, Y', strtotime($reminder['created_at'])); ?><br>
                                                    <?php echo date('g:i A', strtotime($reminder['created_at'])); ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-info" 
                                                            onclick="showReminderDetails(<?php echo htmlspecialchars(json_encode($reminder)); ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reminder Details Modal -->
<div class="modal fade" id="reminderModal" tabindex="-1" aria-labelledby="reminderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reminderModalLabel">
                    <i class="fas fa-info-circle me-2"></i>Reminder Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="reminderModalBody">
                <!-- Content will be populated by JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function showReminderDetails(reminder) {
    const modalBody = document.getElementById('reminderModalBody');
    const status = reminder.completed ? 'Completed' : 'Pending';
    const statusClass = reminder.completed ? 'success' : 'warning';
    const statusIcon = reminder.completed ? 'check' : 'clock';

    modalBody.innerHTML = `
        <div class="row">
            <div class="col-md-8">
                <h6>Subject:</h6>
                <p class="border-start border-primary border-3 ps-3">${reminder.subject}</p>

                <h6>Content:</h6>
                <p class="border-start border-secondary border-3 ps-3 text-muted">
                    ${reminder.content || '<em>No additional content</em>'}
                </p>
            </div>
            <div class="col-md-4">
                <h6>Details:</h6>
                <ul class="list-unstyled">
                    <li><strong>User:</strong> ${reminder.username}</li>
                    <li><strong>Status:</strong> 
                        <span class="badge bg-${statusClass}">
                            <i class="fas fa-${statusIcon} me-1"></i>${status}
                        </span>
                    </li>
                    <li><strong>Created:</strong><br>
                        <small class="text-muted">${new Date(reminder.created_at).toLocaleDateString()} at ${new Date(reminder.created_at).toLocaleTimeString()}</small>
                    </li>
                </ul>
            </div>
        </div>
    `;

    const modal = new bootstrap.Modal(document.getElementById('reminderModal'));
    modal.show();
}
</script>

<?php require 'app/views/templates/footer.php'; ?> 
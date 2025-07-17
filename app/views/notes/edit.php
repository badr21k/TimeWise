<?php require 'app/views/templates/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-warning text-dark">
                    <h3 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Edit Reminder
                    </h3>
                    <small class="opacity-75">Update your task details</small>
                </div>
                <div class="card-body p-4">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Error!</strong> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Current Status -->
                    <div class="alert alert-<?php echo $note['completed'] ? 'success' : 'warning'; ?> mb-4">
                        <i class="fas fa-<?php echo $note['completed'] ? 'check-circle' : 'clock'; ?> me-2"></i>
                        <strong>Status:</strong> This reminder is currently 
                        <span class="fw-bold"><?php echo $note['completed'] ? 'Completed' : 'Pending'; ?></span>
                        <?php if (!$note['completed']): ?>
                            <div class="mt-2">
                                <a href="/notes/toggle/<?php echo $note['id']; ?>" class="btn btn-sm btn-success">
                                    <i class="fas fa-check me-1"></i>Mark as Complete
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <form method="POST" action="/notes/edit/<?php echo $note['id']; ?>" id="editReminderForm">
                        <div class="mb-4">
                            <label for="subject" class="form-label fw-bold">
                                <i class="fas fa-heading text-warning me-1"></i>
                                Subject <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg" id="subject" name="subject" required 
                                   placeholder="Enter a clear, descriptive title for your reminder"
                                   value="<?php echo htmlspecialchars($note['subject']); ?>">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Make it specific and actionable (e.g., "Call dentist for appointment")
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="content" class="form-label fw-bold">
                                <i class="fas fa-align-left text-warning me-1"></i>
                                Content <span class="text-muted">(Optional)</span>
                            </label>
                            <textarea class="form-control" id="content" name="content" rows="4" 
                                      placeholder="Add additional details, notes, or context..."><?php echo htmlspecialchars($note['content']); ?></textarea>
                            <div class="form-text">
                                <i class="fas fa-lightbulb me-1"></i>
                                Include important details, deadlines, or any context that will help you complete this task
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="/notes" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>Back to Reminders
                            </a>
                            <div class="btn-group">
                                <button type="submit" class="btn btn-warning btn-lg shadow">
                                    <i class="fas fa-save me-2"></i>Update Reminder
                                </button>
                                <a href="/notes/delete/<?php echo $note['id']; ?>" class="btn btn-outline-danger btn-lg" 
                                   onclick="return confirm('Are you sure you want to delete this reminder?')">
                                    <i class="fas fa-trash me-2"></i>Delete
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Reminder Info -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body bg-light">
                    <h6 class="card-title">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Reminder Information
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Created:</strong> 
                                <span class="text-muted"><?php echo date('F j, Y \a\t g:i A', strtotime($note['created_at'])); ?></span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Status:</strong> 
                                <span class="badge bg-<?php echo $note['completed'] ? 'success' : 'warning'; ?>">
                                    <?php echo $note['completed'] ? 'Completed' : 'Pending'; ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('editReminderForm').addEventListener('submit', function(e) {
    const subject = document.getElementById('subject').value.trim();
    if (subject.length < 3) {
        e.preventDefault();
        showAlert('warning', 'Please enter a subject with at least 3 characters.');
        return false;
    }
});
</script>

<?php require 'app/views/templates/footer.php'; ?>
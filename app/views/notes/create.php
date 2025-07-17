<?php require 'app/views/templates/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        Create New Reminder
                    </h3>
                    <small class="opacity-75">Add a new task to stay organized</small>
                </div>
                <div class="card-body p-4">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Error!</strong> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/notes/create" id="createReminderForm">
                        <div class="mb-4">
                            <label for="subject" class="form-label fw-bold">
                                <i class="fas fa-heading text-primary me-1"></i>
                                Subject <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg" id="subject" name="subject" required 
                                   placeholder="Enter a clear, descriptive title for your reminder"
                                   value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Make it specific and actionable (e.g., "Call dentist for appointment")
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="content" class="form-label fw-bold">
                                <i class="fas fa-align-left text-primary me-1"></i>
                                Content <span class="text-muted">(Optional)</span>
                            </label>
                            <textarea class="form-control" id="content" name="content" rows="4" 
                                      placeholder="Add additional details, notes, or context..."><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
                            <div class="form-text">
                                <i class="fas fa-lightbulb me-1"></i>
                                Include important details, deadlines, or any context that will help you complete this task
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="/notes" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>Back to Reminders
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg shadow">
                                <i class="fas fa-save me-2"></i>Create Reminder
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body bg-light">
                    <h6 class="card-title">
                        <i class="fas fa-lightbulb text-warning me-2"></i>
                        Tips for Effective Reminders
                    </h6>
                    <ul class="list-unstyled mb-0 small">
                        <li><i class="fas fa-check text-success me-2"></i>Use action verbs (Call, Buy, Send, Review)</li>
                        <li><i class="fas fa-check text-success me-2"></i>Include specific details when helpful</li>
                        <li><i class="fas fa-check text-success me-2"></i>Keep the subject clear and concise</li>
                        <li><i class="fas fa-check text-success me-2"></i>Add deadlines or dates in the content if needed</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('createReminderForm').addEventListener('submit', function(e) {
    const subject = document.getElementById('subject').value.trim();
    if (subject.length < 3) {
        e.preventDefault();
        showAlert('warning', 'Please enter a subject with at least 3 characters.');
        return false;
    }
});
</script>

<?php require 'app/views/templates/footer.php'; ?> 
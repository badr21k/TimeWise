<?php require 'app/views/templates/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="display-6">
                    <i class="fas fa-tasks text-primary me-2"></i>
                    My Reminders
                    <small class="text-muted fs-6">Stay organized and productive</small>
                </h1>
                <a href="/notes/create" class="btn btn-primary btn-lg shadow">
                    <i class="fas fa-plus me-2"></i>Create New Reminder
                </a>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Success!</strong> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Error!</strong> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['info'])): ?>
                <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Info:</strong> <?php echo $_SESSION['info']; unset($_SESSION['info']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (empty($notes)): ?>
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">No reminders yet</h4>
                                <p class="text-muted mb-4">Get started by creating your first reminder to stay organized and never miss important tasks.</p>
                                <a href="/notes/create" class="btn btn-primary btn-lg">
                                    <i class="fas fa-plus me-2"></i>Create Your First Reminder
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($notes as $note): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm border-0 <?php echo $note['completed'] ? 'border-success' : ''; ?> reminder-card">
                                <?php if ($note['completed']): ?>
                                    <div class="card-header bg-success text-white">
                                        <i class="fas fa-check-circle me-2"></i>Completed
                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title <?php echo $note['completed'] ? 'text-decoration-line-through text-muted' : ''; ?>">
                                        <?php echo htmlspecialchars($note['subject']); ?>
                                    </h5>
                                    <?php if (!empty($note['content'])): ?>
                                        <p class="card-text text-muted <?php echo $note['completed'] ? 'text-decoration-line-through' : ''; ?>">
                                            <?php echo htmlspecialchars($note['content']); ?>
                                        </p>
                                    <?php endif; ?>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            <?php echo date('M j, Y', strtotime($note['created_at'])); ?>
                                        </small>
                                        <?php if ($note['completed']): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Done
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-top-0">
                                    <div class="btn-group w-100" role="group">
                                        <a href="/notes/edit/<?php echo $note['id']; ?>" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-edit me-1"></i>Edit
                                        </a>
                                        <a href="/notes/toggle/<?php echo $note['id']; ?>" class="btn btn-outline-<?php echo $note['completed'] ? 'warning' : 'success'; ?> btn-sm">
                                            <i class="fas fa-<?php echo $note['completed'] ? 'undo' : 'check'; ?> me-1"></i>
                                            <?php echo $note['completed'] ? 'Undo' : 'Complete'; ?>
                                        </a>
                                        <a href="/notes/delete/<?php echo $note['id']; ?>" class="btn btn-outline-danger btn-sm" 
                                           onclick="return confirm('Are you sure you want to delete this reminder?')">
                                            <i class="fas fa-trash me-1"></i>Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Summary Card -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-4">
                                        <h4 class="text-primary"><?php echo count($notes); ?></h4>
                                        <p class="text-muted mb-0">Total Reminders</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h4 class="text-warning"><?php echo count(array_filter($notes, function($n) { return !$n['completed']; })); ?></h4>
                                        <p class="text-muted mb-0">Pending</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h4 class="text-success"><?php echo count(array_filter($notes, function($n) { return $n['completed']; })); ?></h4>
                                        <p class="text-muted mb-0">Completed</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.reminder-card {
    transition: transform 0.2s ease-in-out;
}
.reminder-card:hover {
    transform: translateY(-5px);
}
</style>

<?php require 'app/views/templates/footer.php'; ?> 
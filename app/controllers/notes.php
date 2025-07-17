<?php

class Notes extends Controller {

    public function index() {
        // Check if user is logged in
        if (!isset($_SESSION['auth'])) {
            header('Location: /login');
            exit;
        }

        // Check if notes table exists
        try {
            $db = db_connect();
            $result = $db->query("SHOW TABLES LIKE 'notes'");
            if ($result->rowCount() == 0) {
                $_SESSION['toast'] = [
                    'type' => 'error',
                    'title' => 'System Error',
                    'message' => 'Database not properly configured. Please contact administrator.'
                ];
                header('Location: /home');
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'title' => 'Database Error',
                'message' => 'Unable to connect to database. Please try again later.'
            ];
            header('Location: /home');
            exit;
        }

        try {
            $noteModel = $this->model('Note');
            $user_id = $_SESSION['user_id'] ?? 1;
            $notes = $noteModel->getNotesByUser($user_id);

            $this->view('notes/index', ['notes' => $notes]);
        } catch (Exception $e) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'title' => 'Load Error',
                'message' => 'Unable to load your reminders. Please try again later.'
            ];
            header('Location: /home');
            exit;
        }
    }

    public function create() {
        if (!isset($_SESSION['auth'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $subject = trim($_POST['subject']);
            $content = trim($_POST['content'] ?? '');

            if (empty($subject)) {
                $_SESSION['toast'] = [
                    'type' => 'error',
                    'title' => 'Validation Error',
                    'message' => 'Subject is required for your reminder.'
                ];
                $_SESSION['error'] = 'Subject is required';
                header('Location: /notes/create');
                exit;
            }

            $noteModel = $this->model('Note');
            $user_id = $this->getUserId();

            if ($noteModel->createNote($user_id, $subject, $content)) {
                $_SESSION['toast'] = [
                    'type' => 'success',
                    'title' => 'Reminder Created!',
                    'message' => 'Your reminder has been created successfully.'
                ];
                $_SESSION['success'] = 'Note created successfully';
                header('Location: /notes');
                exit;
            } else {
                $_SESSION['toast'] = [
                    'type' => 'error',
                    'title' => 'Creation Failed',
                    'message' => 'Unable to create reminder. Please try again.'
                ];
                $_SESSION['error'] = 'Error creating note';
                header('Location: /notes/create');
                exit;
            }
        }

        $this->view('notes/create');
    }

    public function edit($id) {
        if (!isset($_SESSION['auth'])) {
            header('Location: /login');
            exit;
        }

        $noteModel = $this->model('Note');
        $user_id = $this->getUserId();
        $note = $noteModel->getNoteById($id, $user_id);

        if (!$note) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'title' => 'Not Found',
                'message' => 'Reminder not found or you do not have permission to edit it.'
            ];
            $_SESSION['error'] = 'Note not found';
            header('Location: /notes');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $subject = trim($_POST['subject']);
            $content = trim($_POST['content'] ?? '');

            if (empty($subject)) {
                $_SESSION['toast'] = [
                    'type' => 'error',
                    'title' => 'Validation Error',
                    'message' => 'Subject is required for your reminder.'
                ];
                $_SESSION['error'] = 'Subject is required';
                header('Location: /notes/edit/' . $id);
                exit;
            }

            if ($noteModel->updateNote($id, $user_id, $subject, $content)) {
                $_SESSION['toast'] = [
                    'type' => 'success',
                    'title' => 'Reminder Updated!',
                    'message' => 'Your reminder has been updated successfully.'
                ];
                $_SESSION['success'] = 'Note updated successfully';
                header('Location: /notes');
                exit;
            } else {
                $_SESSION['toast'] = [
                    'type' => 'error',
                    'title' => 'Update Failed',
                    'message' => 'Unable to update reminder. Please try again.'
                ];
                $_SESSION['error'] = 'Error updating note';
                header('Location: /notes/edit/' . $id);
                exit;
            }
        }

        $this->view('notes/edit', ['note' => $note]);
    }

    public function delete($id) {
        if (!isset($_SESSION['auth'])) {
            header('Location: /login');
            exit;
        }

        $noteModel = $this->model('Note');
        $user_id = $this->getUserId();

        if ($noteModel->deleteNote($id, $user_id)) {
            $_SESSION['toast'] = [
                'type' => 'success',
                'title' => 'Reminder Deleted',
                'message' => 'Your reminder has been deleted successfully.'
            ];
            $_SESSION['success'] = 'Note deleted successfully';
        } else {
            $_SESSION['toast'] = [
                'type' => 'error',
                'title' => 'Delete Failed',
                'message' => 'Unable to delete reminder. Please try again.'
            ];
            $_SESSION['error'] = 'Error deleting note';
        }

        header('Location: /notes');
        exit;
    }

    public function toggle($id) {
        if (!isset($_SESSION['auth'])) {
            header('Location: /login');
            exit;
        }

        $noteModel = $this->model('Note');
        $user_id = $this->getUserId();

        if ($noteModel->toggleCompleted($id, $user_id)) {
            $_SESSION['toast'] = [
                'type' => 'success',
                'title' => 'Status Updated',
                'message' => 'Reminder status has been updated successfully.'
            ];
            $_SESSION['info'] = 'Reminder status updated';
        } else {
            $_SESSION['toast'] = [
                'type' => 'error',
                'title' => 'Update Failed',
                'message' => 'Unable to update reminder status. Please try again.'
            ];
            $_SESSION['error'] = 'Error updating reminder status';
        }

        header('Location: /notes');
        exit;
    }

    private function getUserId() {
        return $_SESSION['user_id'] ?? 1;
    }
} 
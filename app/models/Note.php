<?php

class Note {
    public function __construct() {}

    // fetch all non-deleted notes for this user
    public function getNotesByUser($user_id) {
        $db = db_connect();
        $stmt = $db->prepare("
            SELECT *
              FROM notes
             WHERE user_id = :user_id
               AND deleted = 0
          ORDER BY created_at DESC
        ");
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // fetch one note by id (and user)
    public function getNoteById($id, $user_id) {
        $db = db_connect();
        $stmt = $db->prepare("
            SELECT *
              FROM notes
             WHERE id = :id
               AND user_id = :user_id
               AND deleted = 0
        ");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // create a new note
    public function createNote($user_id, $subject, $content = '') {
        $db = db_connect();
        $stmt = $db->prepare("
            INSERT INTO notes (user_id, subject, content, created_at)
            VALUES (:user_id, :subject, :content, NOW())
        ");
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':subject', $subject, PDO::PARAM_STR);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // update an existing note
    public function updateNote($id, $user_id, $subject, $content = '') {
        $db = db_connect();
        $stmt = $db->prepare("
            UPDATE notes
               SET subject = :subject,
                   content = :content
             WHERE id = :id
               AND user_id = :user_id
        ");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':subject', $subject, PDO::PARAM_STR);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // soft-delete
    public function deleteNote($id, $user_id) {
        $db = db_connect();
        $stmt = $db->prepare("
            UPDATE notes
               SET deleted = 1
             WHERE id = :id
               AND user_id = :user_id
        ");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // toggle completed flag
    public function toggleCompleted($id, $user_id) {
        $db = db_connect();
        $stmt = $db->prepare("
            UPDATE notes
               SET completed = NOT completed
             WHERE id = :id
               AND user_id = :user_id
        ");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

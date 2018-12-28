<?php
// file: model/NoteMapper.php

require_once(__DIR__."/../core/PDOConnection.php");

/**
 * Class NoteMapper
 *
 * Database interface for Note entities
 *
 * @author lipido <lipido@gmail.com>
 */
class NoteMapper {

    /**
     * Reference to the PDO connection
     * @var PDO
     */
    private $db;

    public function __construct() {
        $this->db = PDOConnection::getInstance();
    }

    /**
     * Retrieves all notes
     *
     *
     * @throws PDOException if a database error occurs
     * @return mixed Array of Note instances
     */
    public function findAll(User $user) {
        $stmt = $this->db->prepare("SELECT * FROM note, user WHERE user.id_user = note.author AND user.username like ? ORDER BY note.creation_date DESC");
        $stmt->execute(array($user->getUsername()));
        $notes_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $notes = array();

        foreach ($notes_db as $note) {
            $author = new User($note["username"]);
            array_push($notes, new Food($note["id_note"], $note["title"], $note["content"],$note["creation_date"], $author));
        }
        return $notes;
    }

    public function findAllShared(User $user) {
        $stmt = $this->db->prepare("SELECT * from note, user,shared WHERE note.id_note = shared.note AND shared.user = (SELECT id_user FROM user WHERE username=?) AND note.author = user.id_user");
        $stmt->execute(array($user->getUsername()));
        $notes_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $notes = array();

        foreach ($notes_db as $note) {
            $author = new User($note["username"]);
            array_push($notes, new Food($note["id_note"], $note["title"], $note["content"],$note["creation_date"], $author));

        }

        return $notes;
    }

    public function sharedWith(User $user, Food $note) {
        $stmt = $this->db->prepare("SELECT * FROM shared,user WHERE shared.user = user.id_user AND user.username = ? AND note = ?");
        $stmt->execute(array($user->getUsername(),$note->getId()));
        $shared = $stmt->fetch(PDO::FETCH_ASSOC);

        if($shared != null) {
            return 1;
        } else {
            return NULL;
        }
    }

    /**
     * Loads a Note from the database given its id
     *
     *
     * @throws PDOException if a database error occurs
     * @return Food The Note instances. NULL
     * if the Note is not found
     */
    public function findById($noteid){
        $stmt = $this->db->prepare("SELECT note.id_note,note.title,note.content,note.creation_date,user.username as author FROM note,user WHERE id_note=? AND note.author=user.id_user");
        $stmt->execute(array($noteid));
        $note = $stmt->fetch(PDO::FETCH_ASSOC);

        if($note != null) {
            return new Food(
                $note["id_note"],
                $note["title"],
                $note["content"],
                $note["creation_date"],
                new User($note["author"]));
        } else {
            return NULL;
        }
    }

    /**
     * Saves a Note into the database
     *
     * @param Food $note The note to be saved
     * @throws PDOException if a database error occurs
     * @return void
     */
    public function save(Food $note) {
        $stmt = $this->db->prepare("INSERT INTO note (id_note,title,content,creation_date,author) values (0,?,?,?,(SELECT id_user FROM user WHERE username = ?))");
        $stmt->execute(array($note->getTitle(),$note->getContent(),$note->getCreationDate(), $note->getAuthor()->getUsername()));
    }

    /**
     * Updates a Note in the database
     *
     * @param Food $note The note to be updated
     * @throws PDOException if a database error occurs
     * @return void
     */
    public function update(Food $note) {
        $stmt = $this->db->prepare("UPDATE note set title=?, content=? where id_note=?");
        $stmt->execute(array($note->getTitle(), $note->getContent(), $note->getId()));
    }

    /**
     * Deletes a Note into the database
     *
     * @param Food $note The note to be deleted
     * @throws PDOException if a database error occurs
     * @return void
     */
    public function delete(Food $note) {
        $stmt = $this->db->prepare("DELETE from note WHERE id_note=?");
        $stmt->execute(array($note->getId()));
    }

    /**
     * Share a Note with user.
     *
     * @param Food $note The note to be shared
     * @param string $user The id of the User with whom the Note is shared
     * @throws PDOException if a database error occurs
     * @return void
     */
    public function share(Food $note, $user) {
        $stmt = $this->db->prepare("INSERT INTO shared (id_shared,note,user,date) values (0,?,?,?)");
        $stmt->execute(array($note->getId(),$user,date("Y-m-d")));
    }

    /**
     * Deletes a Shared Note into the database
     *
     * @param Food $note The note to be deleted
     * @throws PDOException if a database error occurs
     * @return void
     */
    public function deleteShared(Food $note, User $user) {
        $stmt = $this->db->prepare("DELETE from shared WHERE note=? AND user = (SELECT id_user FROM user WHERE username=?)");
        $stmt->execute(array($note->getId(),$user->getUsername()));
    }

}
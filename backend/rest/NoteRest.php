<?php

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/UserMapper.php");

require_once(__DIR__."/../model/Note.php");
require_once(__DIR__."/../model/NoteMapper.php");

require_once(__DIR__."/BaseRest.php");


class NoteRest extends BaseRest {
    private $noteMapper;

    public function __construct() {
        parent::__construct();

        $this->noteMapper = new NoteMapper();
    }

    public function getNotes() {
        $currentUser = parent::authenticateUser();

        $notes = $this->noteMapper->findAll($currentUser);

        // json_encode Note objects.
        // since Note objects have private fields, the PHP json_encode will not
        // encode them, so we will create an intermediate array using getters and
        // encode it finally
        $notes_array = array();
        foreach($notes as $note) {
            array_push($notes_array, array(
                "id" => $note->getId(),
                "title" => $note->getTitle(),
                "content" => $note->getContent(),
                "creation_date" => $note->getCreationDate(),
                "author_id" => $note->getAuthor()->getUsername()
            ));
        }

        header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
        header('Content-Type: application/json');
        echo(json_encode($notes_array));
    }

    public function getNotesShared() {
        $currentUser = parent::authenticateUser();

        $notes = $this->noteMapper->findAllShared($currentUser);

        // json_encode Note objects.
        // since Note objects have private fields, the PHP json_encode will not
        // encode them, so we will create an intermediate array using getters and
        // encode it finally
        $notes_array = array();
        foreach($notes as $note) {
            array_push($notes_array, array(
                "id" => $note->getId(),
                "title" => $note->getTitle(),
                "content" => $note->getContent(),
                "creation_date" => $note->getCreationDate(),
                "author_id" => $note->getAuthor()->getUsername()
            ));
        }

        header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
        header('Content-Type: application/json');
        echo(json_encode($notes_array));
    }

    public function createNote($data) {
        $currentUser = parent::authenticateUser();
        $note = new Food();

        if (isset($data->title) && isset($data->content)) {
            $note->setTitle($data->title);
            $note->setContent($data->content);
            $note->setCreationDate(date("Y-m-d"));
            $note->setAuthor($currentUser);
        }

        try {
            // validate Post object
            $note->checkIsValidForCreate(); // if it fails, ValidationException

            // save the Post object into the database
            $noteId = $this->noteMapper->save($note);

            // response OK. Also send post in content
            header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
            header('Location: '.$_SERVER['REQUEST_URI']."/".$noteId);
            header('Content-Type: application/json');
            echo(json_encode(array(
                "id"=>$noteId,
                "title"=>$note->getTitle(),
                "content" => $note->getContent()
            )));

        } catch (ValidationException $e) {
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            header('Content-Type: application/json');
            echo(json_encode($e->getErrors()));
        }
    }

    public function readNote($noteId) {
        $currentUser = parent::authenticateUser();
        // find the Note object in the database
        $note = $this->noteMapper->findById($noteId);
        if ($note == NULL) {
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            echo("Note with id ".$noteId." not found");
        }
        if ($note->getAuthor()->getUsername() != $currentUser->getUsername() && $this->noteMapper->sharedWith($currentUser,$note) == NULL) {
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("You are not the authorized user for view this note");
            return;
        }

        $note_array = array(
            "id" => $note->getId(),
            "title" => $note->getTitle(),
            "content" => $note->getContent(),
            "creation_date"=>$note->getCreationDate(),
            "author_id" => $note->getAuthor()->getusername()

        );


        header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
        header('Content-Type: application/json');
        echo(json_encode($note_array));
    }

    public function updateNote($noteId, $data) {
        $currentUser = parent::authenticateUser();

        $note = $this->noteMapper->findById($noteId);
        if ($note == NULL) {
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            echo("Note with id ".$noteId." not found");
        }

        // Check if the Note author is the currentUser (in Session)
        if ($note->getAuthor()->getUsername() != $currentUser->getUsername() && $this->noteMapper->sharedWith($currentUser,$note) == NULL) {
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("You are not the authorized user for edit this note");
            return;
        }
        $note->setTitle($data->title);
        $note->setContent($data->content);

        try {
            // validate Note object
            $note->checkIsValidForUpdate(); // if it fails, ValidationException
            $this->noteMapper->update($note);
            header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
        }catch (ValidationException $e) {
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            header('Content-Type: application/json');
            echo(json_encode($e->getErrors()));
        }
    }

    public function deleteNote($noteId) {
        $currentUser = parent::authenticateUser();
        $note = $this->noteMapper->findById($noteId);

        if ($note == NULL) {
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            echo("Note with id ".$noteId." not found");
            return;
        }
        // Check if the Note author is the currentUser (in Session)
        if ($note->getAuthor()->getUsername() != $currentUser->getUsername()) {
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("you are not the author of this note");
            return;
        }

        $this->noteMapper->delete($note);

        header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
    }

    public function unsharedNote($noteId) {
        $currentUser = parent::authenticateUser();
        $note = $this->noteMapper->findById($noteId);

        if ($note == NULL) {
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            echo("Note with id ".$noteId." not found");
            return;
        }
        // Check if the Note author is the currentUser (in Session)
        if ($note->getAuthor()->getUsername() == $currentUser->getUsername()) {
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("you can't unshared this. you are the author of this note");
            return;
        }

        $this->noteMapper->deleteShared($note,$currentUser);

        header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
    }

    /*public function createComment($postId, $data) {
        $currentUser = parent::authenticateUser();

        $post = $this->postMapper->findById($postId);
        if ($post == NULL) {
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            echo("Post with id ".$postId." not found");
        }

        $comment = new Comment();
        $comment->setContent($data->content);
        $comment->setAuthor($currentUser);
        $comment->setPost($post);

        try {
            $comment->checkIsValidForCreate(); // if it fails, ValidationException

            $this->commentMapper->save($comment);

            header($_SERVER['SERVER_PROTOCOL'].' 201 Created');

        }catch(ValidationException $e) {
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            header('Content-Type: application/json');
            echo(json_encode($e->getErrors()));
        }
    }*/

    public function shareNote($noteId, $data) {
        $currentUser = parent::authenticateUser();

        $note = $this->noteMapper->findById($noteId);

        if($note->getAuthor()->getUsername() !=$currentUser->getUsername()){
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("you are not the author of this note");
            return;
        }

        try {
            foreach ($data as $user){
                $this->noteMapper->share($note,$user);
            }

            header($_SERVER['SERVER_PROTOCOL'].' 201 Shared');

        }catch(ValidationException $e) {
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            header('Content-Type: application/json');
            echo(json_encode($e->getErrors()));
        }
    }
}

// URI-MAPPING for this Rest endpoint
$noteRest = new NoteRest();
URIDispatcher::getInstance()
    ->map("GET",	"/note", array($noteRest,"getNotes"))
    ->map("GET","/note/shared", array($noteRest,"getNotesShared"))
    ->map("GET","/note/$1", array($noteRest,"readNote"))
    ->map("POST", "/note", array($noteRest,"createNote"))
    //->map("POST", "/post/$1/comment", array($postRest,"createComment"))
    ->map("POST", "/note/shared/$1", array($noteRest,"shareNote"))
    ->map("PUT",	"/note/$1", array($noteRest,"updateNote"))
    ->map("DELETE","/note/shared/$1", array($noteRest,"unsharedNote"))
    ->map("DELETE", "/note/$1", array($noteRest,"deleteNote"));

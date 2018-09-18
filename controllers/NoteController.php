<?php
class NoteController {
  public function actionAdd() {

    $username = '';
    $email ='';
    $message = '';
    $picture = '';

    if (isset($_POST['createNote'])) {
      $username = $_POST['username'];
      $email = $_POST['email'];
      $message = $_POST['message'];
      $picture = $_FILES['picture'];

      $errors = false;
      $pictureNewDest = '';

      if (isset($_FILES['picture']) && (file_exists($_FILES['picture']['tmp_name']) || is_uploaded_file($_FILES['picture']['tmp_name']))) {
        if (Note::checkPictureType($picture['type'])) {
          $pictureNewDest = Note::loadPicture($picture);
        } else {
          $errors[] = 'Формат изображений: JPG/GIF/PNG';
        }
      }

      if ($errors == false) {
        if ($pictureNewDest != '') {
          Note::addNotes($username, $email, $message, $pictureNewDest);
        } else {
          Note::addNotes($username, $email, $message);
        }
        header("Location: /index/");

      }

      require_once(ROOT . '/views/note/index.php');
      return true;

    }
  }

  public function actionIndex() {
    require_once(ROOT . '/views/note/index.php');
    return true;
  }
}
 ?>

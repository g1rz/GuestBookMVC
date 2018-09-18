<?php

class Note {

  public static function getNotes() {

  }

  public static function addNotes($username, $email, $message, $picture = '') {
    $db = Db::getConnection();

    $sql = 'INSERT INTO notes (author, email, date, text, image)' .
            'VALUES (:username, :email, NOW(), :message, :image)';
    $result = $db->prepare($sql);
    $result->bindParam(':username', $username, PDO::PARAM_STR);
    $result->bindParam(':email', $email, PDO::PARAM_STR);
    $result->bindParam(':message', $message, PDO::PARAM_STR);
    $result->bindParam(':image', $picture, PDO::PARAM_STR);

    return $result->execute();

  }

  public static function checkPictureType($pictureType) {
    if ($pictureType == 'image/gif' || $pictureType == 'image/png' || $pictureType == 'image/jpeg' || $pictureType == 'image/pjpeg') {
      return true;
    }
    return false;
  }

  // проверка не превышают ли размеры изображения 320*240
  public static function checkPictureSize($pictureWidth, $pictureHeight) {
    if ($pictureWidth <= 320 && $pictureHeight <= 240) {
      return true;
    }
    return false;
  }

  // обработка размеров изоражений
  public static function getSizePicture($width, $height) {
    $sizeArray = array();
    $picWidth;
    $picHeight;

    if (Note::checkPictureSize($width, $height)) {
      // старые размеры
      $picWidth = $width;
      $picHeight = $height;
    } else {
      // новые размеры
      if ($width > $height) {
        $picWidth = 320;
        $picHeight = floor((320 * $height) / $width);
      } else {
        $picWidth = floor(($width * 320) / $height);
        $picHeight = 320;
      }
    }

    array_push($sizeArray, $picWidth, $picHeight);

    return $sizeArray;
  }

  // Загрузка изображения с изменением размера и возврат имени нового изоражения
  public static function loadPicture($picture) {
    $pathToDirectory = 'pics/';
    $imageInfo = getimagesize($picture['tmp_name']);

    // получаем размеры изображения (новые если изображение больше 320*240)
    $sizes = Note::getSizePicture($imageInfo[0], $imageInfo[1]);

    $filename    = $picture['name'];
    $source    = $picture['tmp_name'];
    $pictureType = $picture['type'];
    $target    = $pathToDirectory . $filename;

    move_uploaded_file($source, $target); //загрузка оригинала в папку $pathToDirectory

    if ($pictureType == 'image/jpeg') {
      $im = imagecreatefromjpeg($pathToDirectory . $filename);
    } elseif ($pictureType == 'image/gif') {
      $im = imagecreatefromgif($pathToDirectory . $filename);
    } elseif ($pictureType == 'image/png') {
      $im = imagecreatefrompng($pathToDirectory . $filename);
    }

    $dest = imagecreatetruecolor($sizes[0], $sizes[1]);

    imagecopyresampled($dest, $im, 0, 0, 0, 0, $sizes[0], $sizes[1], $imageInfo[0], $imageInfo[1]);

    $date = time();

    $newName = $pathToDirectory.substr($filename, 0, 20 ).$date.'.jpg';
    imagejpeg($dest, $newName);

    unlink($target);

    return $newName;
  }
}

 ?>

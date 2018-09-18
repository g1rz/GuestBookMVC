<?php require_once(ROOT . '/views/layouts/header.php'); ?>

<main class="container">
  <?php if (isset($errors) && is_array($errors)): ?>
    <ul>
      <?php foreach ($errors as $error): ?>
        <li class="error"><?php echo $error; ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
  <form enctype="multipart/form-data" action="/add" method="post">
    <label for="username">Имя:</label><br>
    <input type="text" id="username" name="username" required><br>
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required><br>
    <label for="message">Сообщение:</label><br>
    <textarea name="message" rows="8" cols="80" id="message" required></textarea><br>
    <label for="picture">Изображение (320*240):</label><br>
    <input type="file" id="picture" name="picture"><br>
    <input type="submit" name="createNote" value="Создать">
    <a href="#" id="preview" class="prev">Предпросмотр</a>
  </form>
  <br><br><br>

  <div class="note preview-note" style="display:none">
    <div class="note-about">
      <div class="name"></div>
      <div class="email"></div>
      <div class="date"></div>
    </div>
    <div class="note-text"></div>
    <img class="picture-prev" id="picture_preview" src="" alt="">
  </div>

</main>

<script>
jQuery(document).ready(function () {
  $('#preview').click(function () {
      var now = new Date();
      $('.preview-note .name').html($('#username').val());
      $('.preview-note .email').html($('#email').val());
      $('.preview-note .date').html(now.getFullYear() + '/' + now.getMonth() + '/' + now.getDate());
      $('.preview-note .note-text').html($('#message').val());

      var input = $('#picture')[0];
      if ( input.files && input.files[0] ) {
        if ( input.files[0].type.match('image.*') ) {
          var reader = new FileReader();
          reader.onload = function(e) { $('#picture_preview').attr('src', e.target.result); }
          reader.readAsDataURL(input.files[0]);
        } else console.log('is not image mime type');
      } else console.log('not isset files data or files API not supordet');

      // $('.preview .created_at').html('('+now.toString()+')');
      // $('.preview .body').html($('.add-comment-form .body').val());

      $('.preview-note').show();
      return false;
  });
  });
</script>

<?php require_once(ROOT . '/views/layouts/footer.php'); ?>

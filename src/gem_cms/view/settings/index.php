<section class="cms-settings">

  <article>
    <?php foreach ($settings as $key => $value):?>
      <form action="/cms?content=settings" method="POST" class="cms-setting">
        <p class="cms-setting-key"><?php echo $value['name'] ?></p>
        <input type="hidden" name="id" value="<?php echo $value['id'] ?>">
        <input type="text" name="value" value="<?php echo $value['value'] ?>">

        <div class="cms-setting-controls">
          <label for="change<?php echo $value['id'] ?>" class="cms-setting-change">&check;</label>
          <input type="submit" name="action" value="change" class="hidden" id='change<?php echo $value['id'] ?>'>
          <a class="cms-setting-delete" href="/cms?content=settings&action=delete&id=<?php echo $value['id']?>">&cross;</a>

        </div>
      </form>
    <?php endforeach; ?>
  </article>

  <form action="/cms?content=settings" method="POST" class="cms-new-setting">
    <input type="text" name="name" placeholder="key">
    <input type="text" name="value" placeholder="value">

    <label for="addkey" class="cms-setting-new-key">Add Key</label>
    <input id="addkey" type="submit" name="action" value="Add Key" class="hidden">
  </form>

</section>

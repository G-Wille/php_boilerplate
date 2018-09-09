<section class="cms-content-container">

  <?php foreach ($content as $key => $value):?>
    <article class="cms-content-block">

      <div class="cms-content-side">
        <label for="content"><?php echo $value['name'] ?></label>
        <a href="/cms?content=content-detail&id=<?php echo $value['id'] ?>">edit</a>
      </div>

      <p><?php echo substr($value['text'], 0, 420); ?>...</p>

    </article>
  <?php endforeach; ?>

</section>

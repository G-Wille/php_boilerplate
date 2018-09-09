<section class="cms-content-detail">
  <h2><?php echo $detail['name'] ?></h2>
  <form method="POST">
    <input type="hidden" name="id" value="<?php echo $detail['id'] ?>">
    <textarea name="text" onkeyup="textAreaAdjust(this)"><?php echo $detail['text'] ?></textarea>
    <input type="submit" name="action" value="update">

  </form>

</section>

<script type="text/javascript">
  function textAreaAdjust(o) {
    setTimeout(function() {o.style.height = (o.scrollHeight)+"px";}, 1);
  }
  textAreaAdjust(document.querySelector('.cms-content-detail textarea'));
</script>

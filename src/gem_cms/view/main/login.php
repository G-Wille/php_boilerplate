
<form action="/cms" method="POST">
  <input type="text" name="username" placeholder="username">

  <?php if(!isset($_GET['forgotpassword'])):?>
    <input type="password" name="password" placeholder="password">
  <?php else: ?>
    <input type="hidden" name="forgotpassword" value="true">
  <?php endif; ?>

  <input type="submit" name="action" value="<?php echo (!isset($_GET['forgotpassword'])) ? 'Login' : 'Request new password'  ?>">
</form>

<?php if(!isset($_GET['forgotpassword'])):?>
  <a href="/cms?content=login&forgotpassword=1">Forgot password</a>
<?php endif; ?>

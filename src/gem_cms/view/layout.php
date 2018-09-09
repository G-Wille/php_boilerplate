<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>CMS - <?php echo $title;?></title>
    <?php echo $css;?>

    <script>

      WebFontConfig = {
        google: {
          families: ['Poppins:300,600,800']
        }
      };

      (function(d) {
        var wf = d.createElement('script'), s = d.scripts[0];
        wf.async = true;
        wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js';
        s.parentNode.insertBefore(wf, s);
      })(document);

    </script>
  </head>
  <body>
    <main class="cms">

      <?php if (!empty($_SESSION['user'])) { ?>
        <nav class="cms-navigation">
          <a href="/cms" class="cms-logo"><span class="hidden">GEM</span></a>

            <ul class="cms-links">
              <?php foreach ($navItems as $item):?>
                <li><a href="/cms?content=<?php echo strtolower($item) ?>" class="<?php echo ($_GET['content'] === strtolower($item)) ? 'cms-active' : null; ?>"><?php echo $item ?></a></li>
              <?php endforeach; ?>
            </ul>

          <a href="/cms?content=user" class="cms-user-container">
            <span class="cms-user-icon"><?php echo strtoupper((JWT::content($_SESSION['user'], $this->secret)['username'][0])); ?></span>
          </a>

        </nav>
      <?php } ?>


      <section class="cms-content">

        <header class="cms-header">
          <h1><?php echo $title;?></h1>
          <p><?php echo date('l jS \of F Y') ?></p>
        </header>

        <article class="cms-content-box">
          <?php echo $content;?>
        </article>

      </section>

      <?php if (isset($_SESSION['errors'])):?>
        <section class="cms-error-info-box cms-error-box">
          <ul>
            <?php foreach ($_SESSION['errors'] as $key => $value):?>
              <li><?php echo $value . ' (' . $key . ').'  ?></li>
            <?php endforeach; ?>
          </ul>
        </section>
      <?php endif; ?>

      <?php if (isset($_SESSION['info'])):?>
        <section class="cms-error-info-box cms-info-box">
          <ul>
            <?php foreach ($_SESSION['info'] as $key => $value):?>
              <li><?php echo $value . ' (' . $key . ').'  ?></li>
            <?php endforeach; ?>
          </ul>
        </section>
      <?php endif; ?>

    </main>

    <?php echo $js;?>

  </body>
</html>

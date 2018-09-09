<section class="cms-dashboard">

  <article class="cms-top-container">
    <div class='cms-item'>
      <p class="cms-number"><?php echo $stats['total_unique_visitors'] ?></p>
      <p class="cms-number-underline">Total unique visitors</p>
    </div>

    <div class='cms-item'>
      <p class="cms-number"><?php echo $stats['total_visits'] ?></p>
      <p class="cms-number-underline">Total visits</p>
    </div>

    <div class='cms-item'>
      <p class="cms-number"><?php echo $stats['average_clicks'] ?></p>
      <p class="cms-number-underline">Average page clicks</p>
    </div>
  </article>

  <article class="cms-bottom-container">
    <div class='cms-item'>
      <p class="cms-number"><?php echo $stats['visitors_this_week']['total'] ?></p>
      <p class="cms-number-underline">This Week <span class="cms-percent <?php echo (filter_var($stats['visitors_this_week']['percentage'], FILTER_VALIDATE_FLOAT) && $stats['visitors_this_week']['percentage'] > 0) ? "pos" : "neg" ?>"><?php echo $stats['visitors_this_week']['percentage'] ?>%</span></p>

      <div class="chart">
        <?php foreach ($stats['visitors_this_week']['chart'] as $key => $value):?>
          <div class="bar-<?php echo $key ?>" style="grid-row-start: <?php echo $value ?>;"></div>
        <?php endforeach; ?>
      </div>

    </div>

    <div class='cms-item'>
      <p class="cms-number"><?php echo $stats['visitors_last_week']['total'] ?></p>
      <p class="cms-number-underline">Last Week <span class="cms-percent <?php echo (filter_var($stats['visitors_last_week']['percentage'], FILTER_VALIDATE_FLOAT) && $stats['visitors_last_week']['percentage'] > 0) ? "pos" : "neg" ?>"><?php echo $stats['visitors_last_week']['percentage'] ?>%</span></p>

      <div class="chart">
        <?php foreach ($stats['visitors_last_week']['chart'] as $key => $value):?>
          <div class="bar-<?php echo $key ?>" style="grid-row-start: <?php echo $value ?>;"></div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class='cms-item cms-big-item'>
      <p class="cms-number"><?php echo $stats['visitors_this_year']['total'] ?></p>
      <p class="cms-number-underline">This Year</p>
      <div class="chart">
        <?php foreach ($stats['visitors_this_year']['chart'] as $key => $value):?>
          <div class="bar-<?php echo $key ?>" style="grid-row-start: <?php echo $value ?>;"></div>
        <?php endforeach; ?>
      </div>
    </div>

</section>

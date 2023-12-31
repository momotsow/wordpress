<?php
  
  get_header();

  while(have_posts()) {
    the_post(); ?>
    <div class="page-banner">

      <div class="inner_banner" style="background-color: red"></div>
      <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php the_title(); ?></h1>
      </div>  
    </div>

    <div class="container container--narrow page-section">
          <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('lesson'); ?>">
        <i class="fa fa-home" aria-hidden="true"></i> All Lessons</a> <span class="metabox__main"><?php the_title(); ?></span></p>
      </div>

      <div class="one-third"> <!-- update -->
        <?php the_post_thumbnail('lessonLandscape'); ?>
      </div>

      
      <div class="generic-content"><?php the_content(); ?></div>

      <?php 
        $relatedGrades = new WP_Query(array(
          'posts_per_page' => 5,
          'post_type' => 'grade',
          'orderby' => 'title',
          'order' => 'ASC',
          'meta_query' => array(
            array(
              'key' => 'related_program',
              'compare' => 'LIKE',
              'value' => '"' . get_the_ID() . '"'
            )
          )
        ));
       
        if ($relatedGrades->have_posts()) {
          echo '<hr class="section-break">';
        echo '<h2 class="headline headline--medium">' . get_the_title() . ' Grades!!</h2>';

        echo '<ul class="professor-cards">';
        while($relatedGrades->have_posts()) {
          $relatedGrades->the_post(); ?>
          <li class="professor-card__list-item">
            <a class="professor-card" href="<?php the_permalink(); ?>">
              <img class="professor-card__image" src="<?php the_post_thumbnail_url('lessonLandscape') ?>">
              <span class="professor-card__name"><?php the_title(); ?></span>
            </a>
          </li>
        <?php }
        echo '</ul>';
        }

        wp_reset_postdata();

        $today = date('Ymd');
        $homepageEvents = new WP_Query(array(
          'posts_per_page' => 2,
          'post_type' => 'event',
          'meta_key' => 'event_date',
          'orderby' => 'meta_value_num',
          'order' => 'ASC',
          'meta_query' => array(
            array(
              'key' => 'event_date',
              'compare' => '>=',
              'value' => $today,
              'type' => 'numeric'
            ),
            array(
              'key' => 'related_program',
              'compare' => 'LIKE',
              'value' => '"' . get_the_ID() . '"'
            )
          )
        ));

        if ($homepageEvents->have_posts()) {
          echo '<hr class="section-break">';
        echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>';

        while($homepageEvents->have_posts()) {
          $homepageEvents->the_post(); ?>
          <div class="event-summary card">
            <a class="event-summary__date t-center" href="#">
              <span class="event-summary__month"><?php
                $eventDate = new DateTime(get_field('event_date'));
                echo $eventDate->format('M')
              ?></span>
              <span class="event-summary__day"><?php echo $eventDate->format('d') ?></span>  
            </a>
            <div class="event-summary__content">
              <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
              <p><?php if (has_excerpt()) {
                  echo get_the_excerpt();
                } else {
                  echo wp_trim_words(get_the_content(), 18);
                  } ?> <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a></p>
            </div>
          </div>
        <?php }
        }

      ?>

    </div>
    

    
  <?php }

  get_footer();

?>
<?php
get_header(); 
   $post_id = get_the_ID();
   $type = get_post_meta($post_id, "course_type", true);
   $target = get_post_meta($post_id, "course_target", true);
  $duration = get_post_meta($post_id, "course_duration", true);
  $description = get_post_meta($post_id, "course_description", true);
  $legislation = get_post_meta($post_id, "course_legislation", true);?>
  
<div id="primary">
    <div id="content" role="main">
     <?php $mypost = array( 'post_type' => 'course', );
      $loop = new WP_Query( $mypost ); ?>
      <!-- Cycle through all posts -->
      <?php while ( $loop->have_posts() ) : $loop->the_post();?>
          <article id="post-<?php echo $post_id; ?>" <?php post_class(); ?>>
              <header class="entry-header">
                <!-- Display featured image in top-aligned floating div -->
                 <div style="float: top; margin: 10px">
                    <?php the_post_thumbnail( array( 100, 100 ) ); ?>
                 </div>
                 <!-- Display Title and Author Name -->
                 <strong>Title: </strong><?php the_title(); ?>
                 <br />
                 <strong>Course Type: </strong>
                 <p><?php echo $type; ?></p>
                 <br>
                  <strong>Course Target: </strong>
                 <?php echo $target; ?>
                 <br>
                  <strong>Course Duration: </strong>
                 <?php echo $duration; ?>
                 <br>
                  <strong>Course Short Description: </strong>
                 <?php echo $description; ?>
                 <br>
                   <strong>Course Legislation: </strong>
                 <?php echo $legislation; ?>
                 <br />
                <strong>Course Category: </strong>
                <?php 
                the_terms( $post->ID, 'course-categories' ,  ' ' );
                    ?>
<br />
                 
              </header>
              <!-- Display movie review contents -->
              <div class="entry-content">
                   <?php the_content(); ?>
              </div>
              <hr/>
         </article>
   <?php endwhile;  ?>
   </div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>
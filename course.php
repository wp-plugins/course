<?php
/*
Plugin Name: Course
Plugin URI: http://www.imhysoft.com/products
Description: Declares a plugin that will create a custom post type displaying courses offered by an institution.
Version: 1.0
Author: Imran Ahmed Khan
Author URI: http://www.imhysoft.com/
License: GPLv2
*/

add_action( 'init', 'create_course' );

function create_course() {
    register_post_type( 'course',
        array(
            'labels' => array(
                'name' => 'Courses',
                'singular_name' => 'Course',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Course',
                'edit' => 'Edit',
                'edit_item' => 'Edit Course',
                'new_item' => 'New Course',
                'view' => 'View',
                'view_item' => 'View Course',
                'search_items' => 'Search Courses',
                'not_found' => 'No Courses found',
                'not_found_in_trash' => 'No Courses found in Trash',
                'parent' => 'Parent Course'
            ),
 
            'public' => true,
			'show_ui' => true,
            'show_in_menu' => true,
            'capability_type' => 'post',
            'map_meta_cap' => true,
            'menu_position' => 15,
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
            'taxonomies' => array( 'course-categories' ),
            'has_archive' => true
        )
    );
}

add_action( 'init', 'create_my_taxonomies', 0 );

function create_my_taxonomies() {
    register_taxonomy(
        'course-categories',
        'course',
        array(
            'labels' => array(
                'name' => 'Course Category',
                'add_new_item' => 'Add New Course Category',
                'new_item_name' => "New Course Category"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => true
        )
    );
}

add_action( 'admin_init', 'my_admin' );

function my_admin() {
    add_meta_box( 'course_meta_box',
        'Course Details',
        'display_course_meta_box',
        'course', 'normal', 'high'
    );
}


function display_course_meta_box( $course ) {
    // Retrieve the course related information
	$course_type = esc_html( get_post_meta( $course->ID, 'course_type', true ) );
    $course_target = esc_html( get_post_meta( $course->ID, 'course_target', true ) );
	$course_duration = esc_html( get_post_meta( $course->ID, 'course_duration', true ) );
	$course_description = esc_html( get_post_meta( $course->ID, 'course_description', true ) );
	$course_legislation = esc_html( get_post_meta( $course->ID, 'course_legisltion', true ) );
   
    ?>
    <table>
        <tr>
            <td style="width: 100%">Course Type</td>
            <td><input type="text" size="80" name="course_type" value="<?php echo $course_type; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">Course Target</td>
            <td><input type="text" size="80" name="course_target" value="<?php echo $course_target; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">Course Duration</td>
            <td><input type="text" size="80" name="course_duration" value="<?php echo $course_duration; ?>" /></td>
            
        </tr>
        <tr>
            <td style="width: 100%">Course Description</td>
            <td><textarea name="course_description" id="course_description" cols="50" rows="1"><?php echo $course_description; ?></textarea></td>
        </tr>
        <tr>
            <td style="width: 100%">Course Legislation</td>
            <td><input type="text" size="80" name="course_legislation" value="<?php echo $course_legislation; ?>" /></td>
            
        </tr>
    </table>
    <?php
}

add_action( 'save_post', 'add_course_fields', 10, 2 );

function add_course_fields( $course_id, $course ) {
    // Check post type for our courses
    if ( $course->post_type == 'course' ) {
        // Store data in post meta table if present in post data
		 if ( isset( $_POST['course_type'] ) && $_POST['course_type'] != '' ) {
            update_post_meta( $course_id, 'course_type', $_POST['course_type'] );
        }
        if ( isset( $_POST['course_target'] ) && $_POST['course_target'] != '' ) {
            update_post_meta( $course_id, 'course_target', $_POST['course_target'] );
        }
        if ( isset( $_POST['course_duration'] ) && $_POST['course_duration'] != '' ) {
            update_post_meta( $course_id, 'course_duration', $_POST['course_duration'] );
        }
		  if ( isset( $_POST['course_description'] ) && $_POST['course_description'] != '' ) {
            update_post_meta( $course_id, 'course_description', $_POST['course_description'] );
        }
		  if ( isset( $_POST['course_legislation'] ) && $_POST['course_legislation'] != '' ) {
            update_post_meta( $course_id, 'course_legislation', $_POST['course_legislation'] );
        }
    }
}

add_filter( 'manage_edit-course_columns', 'my_columns' );

function my_columns( $columns ) {
    $columns['course-type'] = 'Type';
    $columns['course-target'] = 'Target';
	$columns['course-duration'] = 'Duration';
    unset( $columns['comments'] );
    return $columns;
}

	
 add_action( 'manage_posts_custom_column', 'populate_columns' );

function populate_columns( $column ) {
    if ( 'course-type' == $column ) {
		$course_type = esc_html( get_post_meta( get_the_ID(), 'course_type', true ) );
        
        echo $course_type;
    }
    elseif ( 'course-target' == $column ) {
        $course_target = esc_html( get_post_meta( get_the_ID(), 'course_target', true ) );
        echo $course_target;
    }
	 elseif ( 'course-duration' == $column ) {
        $course_duration = esc_html( get_post_meta( get_the_ID(), 'course_duration', true ) );
        echo $course_duration;
    }
} 

add_action( 'restrict_manage_posts', 'my_filter_list' );

function my_filter_list() {
    $screen = get_current_screen();
    global $wp_query;
    if ( $screen->post_type == 'course' ) {
        wp_dropdown_categories( array(
            'show_option_all' => 'Show All Course Categories',
            'taxonomy' => 'course-categories',
            'name' => 'course-categories',
            'orderby' => 'name',
            'selected' => ( isset( $wp_query->query['course-categories'] ) ? $wp_query->query['course-categories'] : '' ),
            'hierarchical' => false,
            'depth' => 3,
            'show_count' => false,
            'hide_empty' => true,
        ) );
    }
}


add_filter( 'parse_query','perform_filtering' );

function perform_filtering( $query ) {
    $qv = &$query->query_vars;
    if ( ( $qv['course-categories'] ) && is_numeric( $qv['course-categories'] ) ) {
        $term = get_term_by( 'id', $qv['course-categories'], 'course-categories' );
        $qv['course-categories'] = $term->slug;
    }
}

add_filter( 'template_include', 'include_template_function', 1 );

function include_template_function( $template_path ) {
    if ( get_post_type() == 'course' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-course.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-course.php';
            }
			
        }
		 elseif ( is_archive() ) {
            if ( $theme_file = locate_template( array ( 'archive-course.php' ) ) ) {
                $template_path = $theme_file;
            } else { $template_path = plugin_dir_path( __FILE__ ) . '/archive-course.php';
 
            }
        }
	
    }
    return $template_path;
}

?>
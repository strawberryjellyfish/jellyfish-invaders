<?php
/*
  Plugin Name: Jellyfish Invaders
  Plugin URI: http://strawberryjellyfish.com/wordpress-plugin-jellyfish-invaders/
  Description: Randomly animates retro space invaders on your WordPress blog
  Author: Robert Miller <rob@strawberryjellyfish.com>
  Version: 0.6
  Author URI: http://strawberryjellyfish.com/
 */
?>
<?php 
/*
 *  Hooks and Actions
 */


// set default options
register_activation_hook( __FILE__,'jellyfish_invaders_default_options' );

// add admin page
add_action( 'admin_menu', 'jellyfish_invaders_settings_menu' );

// Register and define the settings
add_action('admin_init', 'jellyfish_invaders_admin_init');

// enqueue javascript and css

$jellyfish_invaders_options = get_option('jellyfish_invaders_options');

// only need scripts if viewing blog and invaders are enabled
if (!is_admin() && ($jellyfish_invaders_options['enable'] == true)) {
    add_action('wp_enqueue_scripts', 'jellyfish_invaders_queue_scripts');
    add_action('wp_footer', 'jellyfish_invaders_print_script',100);
}


/*
 * Plugin Functions
 */


function jellyfish_invaders_queue_scripts() {
    //enqueue Spritely jquery library for animation and necessary css to page footer
    wp_register_script( 'spritely', plugins_url( 'js/jquery.spritely-0.6.js', __FILE__ ), array('jquery'), '', true );
    wp_enqueue_script( 'spritely' );
    wp_enqueue_style( 'jellyfish_invaders_style',plugins_url( 'jellyfish_invaders.css', __FILE__ ) );
}


function jellyfish_invaders_settings_menu() {
    // this plugin is purely for visual effect it is therefore more logical to
    // add the options page to the Appearance section
    add_theme_page( 'Jellyfish Invaders Settings', 'Jellyfish Invaders', 'manage_options', 'jellyfish-invaders', 'jellyfish_invaders_config_page' );
}

function jellyfish_invaders_default_options() {
    // set up default options on activation and add any new options on upgrade
    if ( get_option( 'jellyfish_invaders_options' ) === false ) {
        $new_options['number_of_invaders'] = 5;
        $new_options['duration'] = 3000;
        $new_options['pause'] = 1000; 
        $new_options['attack_mode'] = 1;
        $new_options['size'] = 2;
        $new_options['enable'] = true;
        $new_options['use_custom_field'] = false;
        $new_options['wiggle'] = true;
        $new_options['random'] = true;
        $new_options['no_boundry'] = true;
        $new_options['top'] = 0;
        $new_options['bottom'] = 400;
        $new_options['left'] = 0;
        $new_options['right'] = 800;
        
        $new_options['version'] = "1.0";
    
        add_option( 'jellyfish_invaders_options', $new_options );
    } else {
        $existing_options = get_option( 'jellyfish_invaders_options' );
        if ( $existing_options['version'] < 1.1 ) {
            //$existing_options['version'] = "1.1";
            // add any new options here with version control
            update_option( 'jellyfish_invaders_options', $existing_options );
        }
    }
}
    

function jellyfish_invaders_config_page() {
?>
<div class="wrap">
    <?php screen_icon(); ?>
    <h2>Jellyfish Invaders</h2>
    <form action="options.php" method="post">
    <?php settings_fields('jellyfish_invaders_options'); ?>
    <?php do_settings_sections('jellyfish_invaders'); ?>
    <input name="Submit" type="submit" value="Save Changes"/>
    </form>
</div>
<?php
}

function jellyfish_invaders_admin_init(){
    register_setting( 'jellyfish_invaders_options', 'jellyfish_invaders_options', 'jellyfish_invaders_validate_options' );
    add_settings_section( 'jellyfish_invaders_main', 'Invader Behaviour', 'jellyfish_invaders_behaviour_text', 'jellyfish_invaders');
    add_settings_field( 'jellyfish_invaders_enable', 'Enable Invaders', 'jellyfish_invaders_enable_input', 'jellyfish_invaders', 'jellyfish_invaders_main' );
    add_settings_field( 'jellyfish_invaders_use_custom_field', 'Where to show', 'jellyfish_invaders_use_custom_field_input', 'jellyfish_invaders', 'jellyfish_invaders_main' );
    add_settings_field( 'jellyfish_invaders_number_of_invaders', 'Number of Invaders', 'jellyfish_invaders_number_of_invaders_input', 'jellyfish_invaders', 'jellyfish_invaders_main' );
    add_settings_field( 'jellyfish_invaders_size', 'Invader Size', 'jellyfish_invaders_size_input', 'jellyfish_invaders', 'jellyfish_invaders_main');
    add_settings_field( 'jellyfish_invaders_duration', 'Fly Time', 'jellyfish_invaders_duration_input', 'jellyfish_invaders', 'jellyfish_invaders_main');
    add_settings_field( 'jellyfish_invaders_pause', 'Pause Time', 'jellyfish_invaders_pause_input', 'jellyfish_invaders', 'jellyfish_invaders_main' );
    add_settings_field( 'jellyfish_invaders_random', 'Random', 'jellyfish_invaders_random_input', 'jellyfish_invaders', 'jellyfish_invaders_main' );
    add_settings_field( 'jellyfish_invaders_wiggle', 'Wiggle', 'jellyfish_invaders_wiggle_input', 'jellyfish_invaders', 'jellyfish_invaders_main' );
    add_settings_field( 'jellyfish_invaders_attack_mode', 'Attack Mode', 'jellyfish_invaders_attack_mode_input', 'jellyfish_invaders', 'jellyfish_invaders_main' );
    add_settings_section( 'jellyfish_invaders_boundry', 'Invader Boundries', 'jellyfish_invaders_boundry_text', 'jellyfish_invaders');
    add_settings_field( 'jellyfish_invaders_no_boundry', 'Ignore Boundries', 'jellyfish_invaders_no_boundry_input', 'jellyfish_invaders', 'jellyfish_invaders_boundry' );
    add_settings_field( 'jellyfish_invaders_top', 'Top', 'jellyfish_invaders_top_input', 'jellyfish_invaders', 'jellyfish_invaders_boundry' );
    add_settings_field( 'jellyfish_invaders_left', 'Left', 'jellyfish_invaders_left_input', 'jellyfish_invaders', 'jellyfish_invaders_boundry' );
    add_settings_field( 'jellyfish_invaders_right', 'Right', 'jellyfish_invaders_right_input', 'jellyfish_invaders', 'jellyfish_invaders_boundry' );
    add_settings_field( 'jellyfish_invaders_bottom', 'Bottom', 'jellyfish_invaders_bottom_input', 'jellyfish_invaders', 'jellyfish_invaders_boundry' );
}
// Draw the section header
function jellyfish_invaders_behaviour_text() {
    echo '<p>Here you can set the number and general behaviour of your invaders, too many invaders may effect system performance!</p>';
}
// Draw the section header
function jellyfish_invaders_boundry_text() {
    echo '<p>The following settings can be used to define an area on the page that the invaders will appear in. You can use this to confine them to a specific area on your page, to avoid getting in the way of content for example.</p>';
    echo '<p>If you just want them to fly free everywhere check the <b>Ignore Boundries</b> checkbox</p> ';
}

// Settings form callback functions
function jellyfish_invaders_number_of_invaders_input() {
    $options = get_option( 'jellyfish_invaders_options' );
    $number_of_invaders = $options['number_of_invaders'];
    echo "<input id='number_of_invaders' name='jellyfish_invaders_options[number_of_invaders]' type='text' value='$number_of_invaders' /> ";
}

function jellyfish_invaders_duration_input() {
    $options = get_option( 'jellyfish_invaders_options' );
    $duration = $options['duration'];
    echo "<input id='duration' name='jellyfish_invaders_options[duration]' type='text' value='$duration' /> ";
}

function jellyfish_invaders_pause_input() {
    $options = get_option( 'jellyfish_invaders_options' );
    $pause = $options['pause'];
    echo "<input id='pause' name='jellyfish_invaders_options[pause]' type='text' value='$pause' /> ";
}

function jellyfish_invaders_wiggle_input() {
    $options = get_option( 'jellyfish_invaders_options' );
    $html = "<input id='wiggle' name='jellyfish_invaders_options[wiggle]' type='checkbox' ". checked( true, $options['wiggle'], false ). " / > ";
    $html .= '<label for="wiggle">Invaders wiggle not rest</label>';  
    echo $html;
}

function jellyfish_invaders_enable_input() {
    $options = get_option( 'jellyfish_invaders_options' );
    $html = "<input id='enable' name='jellyfish_invaders_options[enable]' type='checkbox' ". checked( true, $options['enable'], false ). " / > ";
    $html .= '<label for="wiggle">Uncheck this to turn the Invaders off</label>';  
    echo $html;
}

function jellyfish_invaders_random_input() {
    $options = get_option( 'jellyfish_invaders_options' );
    $html = "<input id='random' name='jellyfish_invaders_options[random]' type='checkbox' ". checked( true, $options['random'], false ). " / > ";
    $html .= '<label for="random">Add some variation to duration and delay</label>';  
    echo $html;
}

function jellyfish_invaders_use_custom_field_input() {  
    $options = get_option( 'jellyfish_invaders_options' );
    $use_custom_field = $options['use_custom_field'];
    $html = '<input type="radio" id="invader_use_custom_field_one" name="jellyfish_invaders_options[use_custom_field]" value="false"' . checked( false, $use_custom_field, false ) . '/>';  
    $html .= '<label for="invader_size_one">Show Everywhere</label>        ' ;  
    $html .= '<input type="radio" id="invader_use_custom_field_two" name="jellyfish_invaders_options[use_custom_field]" value="true"' . checked( true, $use_custom_field, false ) . '/>';  
    $html .= '<label for="invader_size_two">Only on posts or pages with custom field</label>        ' ;
    echo $html;  
} 
function jellyfish_invaders_size_input() {  
    $options = get_option( 'jellyfish_invaders_options' );
    $size = $options['size'];
    $html = '<input type="radio" id="invader_size_one" name="jellyfish_invaders_options[size]" value="1"' . checked( 1, $size, false ) . '/>';  
    $html .= '<label for="invader_size_one">Small</label>        ' ;  
    $html .= '<input type="radio" id="invader_size_two" name="jellyfish_invaders_options[size]" value="2"' . checked( 2, $size, false ) . '/>';  
    $html .= '<label for="invader_size_two">Medium</label>        ' ;  
    $html .= '<input type="radio" id="invader_size_three" name="jellyfish_invaders_options[size]" value="3"' . checked( 3, $size, false ) . '/>';  
    $html .= '<label for="invader_size_three">Large</label>  ';
    echo $html;  
} 

function jellyfish_invaders_attack_mode_input() {  
    $options = get_option( 'jellyfish_invaders_options' );
    $attack = $options['attack_mode'];
    $html = '<input type="radio" id="invader_attack_mode_one" name="jellyfish_invaders_options[attack_mode]" value="1"' . checked( 1, $attack, false ) . '/>';  
    $html .= '<label for="invader_attack_mode_one">Off</label>        ';  
    $html .= '<input type="radio" id="invader_attack_mode_two" name="jellyfish_invaders_options[attack_mode]" value="2"' . checked( 2, $attack, false ) . '/>';  
    $html .= '<label for="invader_attack_mode_two">Solo</label>        ';  
    $html .= '<input type="radio" id="invader_attack_mode_three" name="jellyfish_invaders_options[attack_mode]" value="3"' . checked( 3, $attack, false ) . '/>';  
    $html .= '<label for="invader_attack_mode_two">Squadron</label>        ';
    echo $html;  
} 

function jellyfish_invaders_no_boundry_input() {
    $options = get_option( 'jellyfish_invaders_options' );
    $html = "<input id='enable' name='jellyfish_invaders_options[no_boundry]' type='checkbox' ". checked( true, $options['no_boundry'], false ). " / > ";
    $html .= '<label for="wiggle">Let them roam entire page (overrides the settings below)</label>';  
    echo $html;
}
function jellyfish_invaders_top_input() {
    $options = get_option( 'jellyfish_invaders_options' );
    $top = $options['top'];
    echo "<input id='top' name='jellyfish_invaders_options[top]' type='text' value='$top' /> ";
}
function jellyfish_invaders_left_input() {
    $options = get_option( 'jellyfish_invaders_options' );
    $left = $options['left'];
    echo "<input id='left' name='jellyfish_invaders_options[left]' type='text' value='$left' /> ";
}
function jellyfish_invaders_right_input() {
    $options = get_option( 'jellyfish_invaders_options' );
    $right = $options['right'];
    echo "<input id='right' name='jellyfish_invaders_options[right]' type='text' value='$right' /> ";
}
function jellyfish_invaders_bottom_input() {
    $options = get_option( 'jellyfish_invaders_options' );
    $bottom = $options['bottom'];
    echo "<input id='bottom' name='jellyfish_invaders_options[bottom]' type='text' value='$bottom' /> ";
}


// Validate user input 
function jellyfish_invaders_validate_options( $input ) {
    $valid = array();
    
    if ($input['enable'] == true) {
        $valid['enable'] = true;
    } else {
        $valid['enable'] = false;
    }
    
    if ($input['random'] == true) {
        $valid['random'] = true;
    } else {
        $valid['random'] = false;
    }

    if ($input['wiggle'] == true) {
        $valid['wiggle'] = true;
    } else {
        $valid['wiggle'] = false;
    }
    // number of invaders min 1, max 30
    $valid['number_of_invaders'] = min(30, max(1, absint($input['number_of_invaders'])));
    // duration min 250, max 10000
    $valid['duration'] = min(10000, max(250, absint($input['duration'])));
    // pause min 0, max 10000
    $valid['pause'] = min(10000, max(0, absint($input['pause'])));

    $valid['size'] = intval($input['size']);

    $valid['attack_mode'] = intval($input['attack_mode']);    

    if ($input['use_custom_field'] == 'true') {
        $valid['use_custom_field'] = true;
    } else {
        $valid['use_custom_field'] = false;
    }
    if ($input['no_boundry'] == true) {
        $valid['no_boundry'] = true;
    } else {
        $valid['no_boundry'] = false;
    }
 
    $valid['top'] = absint($input['top']);
    $valid['bottom'] = absint($input['bottom']);
    $valid['left'] = absint($input['left']);
    $valid['right'] = absint($input['right']);

    return $valid;
}



/*
 * Generate the invaders javascript based on user settings
 */

function jellyfish_invaders_print_script() {
    global $wpdb;
    $options = get_option('jellyfish_invaders_options');
    $count = 0;
    $script = '';
    $small_speed = 2;
    $medium_speed = 1.5;
    $large_speed = 1;
 
    // check if we are displaying globablly or ONLY on post/page that
    // has jellyfish_invaders custom field set to TRUE
    if ($options['use_custom_field'] == true) {
        if (is_single() OR is_page()) {
            $cv = get_post_meta(get_the_ID(), 'jellyfish_invaders',true); 
            if ( ($cv != 'true') && ($cv != 'on')){  
               // abort: no custom field
                return;
            }
        } else {
            // abort: not a single page or post
            return;
        }
    }
    
    // first set up variables for things that are applicable to all invaders
    if ($options['no_boundry'] == true) {
        // free to roam entire page
        $top = 0;
        $left = 0;
        $right ="jQuery(document).width()-jQuery('#invader0').width()";
        $bottom = "jQuery(document).height()-jQuery('#invader0').height()";       
    } else {
        // use boundry settings
        $top = $options['top'];
        $left = $options['left'];
        $right = $options['right'];
        $bottom = $options['bottom'];
    }
    
    // choose class (size of invader depending on settings)
    // as we are acting on sizes here also set up the
    // appropiate speed and pause bounce
    if ($options['size'] === 1) {
          $class='small-invader';
          $spd = $small_speed;
          $bounce = ', bounce:[1, 55, '. 2000 / $spd . ']';
    } elseif ($options['size'] === 2) {
          $class='medium-invader';
          $spd = $medium_speed;
          $bounce = ', bounce:[1, 110, '. 2000 / $spd . ']';
    } else {
          $class='large-invader';
          $spd = $large_speed;
          $bounce = ', bounce:[1, 220, '. 2000 / $spd . ']';
    }
    
    // blank the pause bounce if it is not wanted
    if ($options['wiggle'] != true) {
        $bounce ='';
    }    
  
    while ($count < $options['number_of_invaders'] ) {
        // create js for each invader required, include a bit of random variation 
        // to the duration and pause to make it less uniform if set in settings
        if ($options['random'] == true) {
            $duration = max(0, ($options['duration']+(($options['duration']/100) * rand(-50, 50))));
            $pause = max(0, ($options['pause']+(($options['pause']/100) * rand(-50, 50))));           
        } else {
            $duration = $options['duration'];
            $pause = $options['pause'];                      
        }
        $script.="var invaderDiv = jQuery(document.createElement('div')).attr('id','invader$count').addClass('$class').appendTo('body');\n";
    	$script .= "jQuery('#invader$count').sprite({fps: $spd, no_of_frames: 2 $bounce}).spRandom({top: $top, left: $left, right: $right, bottom: $bottom, speed: $duration, pause: $pause}).active().activeOnClick().bounce;\n";       
        $count ++;
    }
    echo "<script>jQuery(document).ready(function() {";
    echo $script;
    // add a ftytotap even if attack mode is set, either just first invader or all elements with
    // invader classes (hopefully these are just invaders!)
    if ($options['attack_mode'] === 3) {
        echo "jQuery('html').flyToTap({el_to_move: '.".$class."', ms: 500, do_once: false});";
    } elseif ($options['attack_mode'] === 2) {
                echo "jQuery('html').flyToTap({el_to_move: '#invader0', ms: 500, do_once: false});";
    }
    echo "});</script>";
}
?>
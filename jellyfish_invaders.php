<?php
/*
  Plugin Name: Jellyfish Invaders
  Plugin URI: http://strawberryjellyfish.com/wordpress-plugins/jellyfish-invaders/
  Description: Randomly animates retro space invaders on your WordPress blog
  Author: Robert Miller <rob@strawberryjellyfish.com>
  Version: 0.9
  Author URI: http://strawberryjellyfish.com/
 */

/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
Online: http://www.gnu.org/licenses/gpl.txt
*/

/*
 *  Hooks and Actions
 */

// set default options
register_activation_hook( __FILE__, 'jellyfish_invaders_default_options' );

// add admin page
add_action( 'admin_menu', 'jellyfish_invaders_settings_menu' );

// Register and define the settings
add_action( 'admin_init', 'jellyfish_invaders_admin_init' );

// enqueue JavaScript and css

$jellyfish_invaders_options = get_option( 'jellyfish_invaders_options' );

// only need scripts if viewing blog and invaders are enabled
if ( !is_admin() && ( $jellyfish_invaders_options['enable'] == true ) ) {
    add_action( 'wp_enqueue_scripts', 'jellyfish_invaders_queue_scripts' );
}


/*
 * Plugin Functions
 */

function jellyfish_invaders_queue_scripts() {
    // to save unnecessary requests and bandwidth, only include js scripts
    // and css when invaders are needed. This is slightly complicated by the fact
    // we can have them displaying on a per post basis.

    $jellyfish_invaders_options = get_option( 'jellyfish_invaders_options' );
    $need_invaders = false;

    if ( !is_admin() && ( $jellyfish_invaders_options['enable'] == true ) ) {
        // most likely we need to print scripts
        $need_invaders = true;
        if ( $jellyfish_invaders_options['use_custom_field'] == true ) {
            // but now only show invaders on specific pages
            // check we need them before queueing up the js and css
            if ( is_single() or is_page() ) {
                $cv = get_post_meta( get_the_ID(), 'jellyfish_invaders', true );
                if ( ( $cv != 'true' ) && ( $cv != 'on' ) ) {
                    // abort: no custom field, no invaders needed
                    $need_invaders = false;
                }
            } else {
                // abort: not a single page or post can't show them anyway
                $need_invaders = false;
            }
        }
    }

    if ( $need_invaders ) {
        //enqueue Spritely jQuery library and js for animation and necessary css to page footer
        wp_register_script( 'spritely', plugins_url( 'js/jquery.spritely.js', __FILE__ ), array( 'jquery' ), '', true );
        wp_enqueue_script( 'spritely' );
        wp_enqueue_style( 'jellyfish_invaders_style', plugins_url( 'jellyfish_invaders.css', __FILE__ ) );
        add_action( 'wp_footer', 'jellyfish_invaders_print_script', 100 );
    }
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
        $new_options['boundary'] = false;
        $new_options['top'] = 0;
        $new_options['bottom'] = 400;
        $new_options['left'] = 0;
        $new_options['right'] = 800;
        $new_options['z_index'] = 999;
        $new_options['container_element'] = "body";

        $new_options['version'] = 1.1;

        add_option( 'jellyfish_invaders_options', $new_options );
    } else {
        $existing_options = get_option( 'jellyfish_invaders_options' );
        if ( $existing_options['version'] < 1.1 ) {
            // new options with version control
            $existing_options['version'] = 1.1;
            $existing_options['z_index'] = 999;
            $existing_options['container_element'] = "body";
            $existing_options['boundary'] = false;
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
    <?php settings_fields( 'jellyfish_invaders_options' ); ?>
    <?php do_settings_sections( 'jellyfish_invaders' ); ?>
    <input name="Submit" type="submit" class="button action" value="Save Changes"/>
    </form>
</div>
<?php
}

function jellyfish_invaders_admin_init() {
    register_setting( 'jellyfish_invaders_options', 'jellyfish_invaders_options', 'jellyfish_invaders_validate_options' );
    add_settings_section( 'jellyfish_invaders_main', 'General Settings', 'jellyfish_invaders_behaviour_text', 'jellyfish_invaders' );
    add_settings_field( 'jellyfish_invaders_enable', 'Enable Invaders', 'jellyfish_invaders_enable_input', 'jellyfish_invaders', 'jellyfish_invaders_main' );
    add_settings_field( 'jellyfish_invaders_use_custom_field', 'Where to show', 'jellyfish_invaders_use_custom_field_input', 'jellyfish_invaders', 'jellyfish_invaders_main' );
    add_settings_field( 'jellyfish_invaders_number_of_invaders', 'Number of Invaders', 'jellyfish_invaders_number_of_invaders_input', 'jellyfish_invaders', 'jellyfish_invaders_main' );
    add_settings_field( 'jellyfish_invaders_size', 'Invader Size', 'jellyfish_invaders_size_input', 'jellyfish_invaders', 'jellyfish_invaders_main' );
    add_settings_field( 'jellyfish_invaders_duration', 'Fly Time', 'jellyfish_invaders_duration_input', 'jellyfish_invaders', 'jellyfish_invaders_main' );
    add_settings_field( 'jellyfish_invaders_pause', 'Pause Time', 'jellyfish_invaders_pause_input', 'jellyfish_invaders', 'jellyfish_invaders_main' );
    add_settings_field( 'jellyfish_invaders_random', 'Random', 'jellyfish_invaders_random_input', 'jellyfish_invaders', 'jellyfish_invaders_main' );
    add_settings_field( 'jellyfish_invaders_wiggle', 'Wiggle', 'jellyfish_invaders_wiggle_input', 'jellyfish_invaders', 'jellyfish_invaders_main' );
    add_settings_field( 'jellyfish_invaders_attack_mode', 'Attack Mode', 'jellyfish_invaders_attack_mode_input', 'jellyfish_invaders', 'jellyfish_invaders_main' );
    add_settings_section( 'jellyfish_invaders_advanced', 'Advanced Settings', 'jellyfish_invaders_advanced_text', 'jellyfish_invaders' );
    add_settings_field( 'jellyfish_invaders_container_element', 'Containing Element', 'jellyfish_invaders_container_element_input', 'jellyfish_invaders', 'jellyfish_invaders_advanced' );
    add_settings_field( 'jellyfish_invaders_z_index', 'Z-index', 'jellyfish_invaders_z_index_input', 'jellyfish_invaders', 'jellyfish_invaders_advanced' );
    add_settings_field( 'jellyfish_invaders_boundary', 'Use Electric Fence', 'jellyfish_invaders_boundary_input', 'jellyfish_invaders', 'jellyfish_invaders_advanced' );
    add_settings_field( 'jellyfish_invaders_top', 'Fence Top', 'jellyfish_invaders_top_input', 'jellyfish_invaders', 'jellyfish_invaders_advanced' );
    add_settings_field( 'jellyfish_invaders_left', 'Fence Left', 'jellyfish_invaders_left_input', 'jellyfish_invaders', 'jellyfish_invaders_advanced' );
    add_settings_field( 'jellyfish_invaders_right', 'Fence Right', 'jellyfish_invaders_right_input', 'jellyfish_invaders', 'jellyfish_invaders_advanced' );
    add_settings_field( 'jellyfish_invaders_bottom', 'Fence Bottom', 'jellyfish_invaders_bottom_input', 'jellyfish_invaders', 'jellyfish_invaders_advanced' );
}
// Draw the section header
function jellyfish_invaders_behaviour_text() {
    echo '';
}
// Draw the section header
function jellyfish_invaders_advanced_text() {
    echo '<p>Invaders live in the document body and normally have free
    roam of the entire page but you can confine them to within a specific page
    element or set up a virtual electric fence to keep them within a box area
    you define in pixels.</p> ';
}

// Settings form callback functions
function jellyfish_invaders_number_of_invaders_input() {
    $options = get_option( 'jellyfish_invaders_options' );
    $number_of_invaders = $options['number_of_invaders'];
    $html = "<input id='number_of_invaders' name='jellyfish_invaders_options[number_of_invaders]' type='text' value='$number_of_invaders' size=5/> ";
    $html .= '<label for="number_of_invaders"> 1 - 10 is a good number </label>';
    echo $html;
}

function jellyfish_invaders_duration_input() {
    $options = get_option( 'jellyfish_invaders_options' );
    $duration = $options['duration'];
    $html = "<input id='duration' name='jellyfish_invaders_options[duration]' type='text' value='$duration' size=5 /> ";
    $html .= '<label for="duration">ms (1000 = 1 second)</label>';
    echo $html;
}

function jellyfish_invaders_pause_input() {
    $options = get_option( 'jellyfish_invaders_options' );
    $pause = $options['pause'];
    $html = "<input id='pause' name='jellyfish_invaders_options[pause]' type='text' value='$pause' size=5 /> ";
    $html .= '<label for="pause">ms</label>';
    echo $html;
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
    $html .= '<br/><input type="radio" id="invader_use_custom_field_two" name="jellyfish_invaders_options[use_custom_field]" value="true"' . checked( true, $use_custom_field, false ) . '/>';
    $html .= '<label for="invader_siz
    e_two">Only on posts or pages with custom field</label>        ' ;
    echo $html;
}
function jellyfish_invaders_size_input() {
    $options = get_option( 'jellyfish_invaders_options' );
    $size = $options['size'];
    $html = '<input type="radio" id="invader_size_one" name="jellyfish_invaders_options[size]" value="1"' . checked( 1, $size, false ) . '/>';
    $html .= '<label for="invader_size_one">Small</label>        ' ;
    $html .= '<br/><input type="radio" id="invader_size_two" name="jellyfish_invaders_options[size]" value="2"' . checked( 2, $size, false ) . '/>';
    $html .= '<label for="invader_size_two">Medium</label>        ' ;
    $html .= '<br/><input type="radio" id="invader_size_three" name="jellyfish_invaders_options[size]" value="3"' . checked( 3, $size, false ) . '/>';
    $html .= '<label for="invader_size_three">Large</label>  ';
    echo $html;
}

function jellyfish_invaders_attack_mode_input() {
    $options = get_option( 'jellyfish_invaders_options' );
    $attack = $options['attack_mode'];
    $html = '<input type="radio" id="invader_attack_mode_one" name="jellyfish_invaders_options[attack_mode]" value="1"' . checked( 1, $attack, false ) . '/>';
    $html .= '<label for="invader_attack_mode_one">Off</label>        ';
    $html .= '<br/><input type="radio" id="invader_attack_mode_two" name="jellyfish_invaders_options[attack_mode]" value="2"' . checked( 2, $attack, false ) . '/>';
    $html .= '<label for="invader_attack_mode_two">Solo</label>        ';
    $html .= '<br/><input type="radio" id="invader_attack_mode_three" name="jellyfish_invaders_options[attack_mode]" value="3"' . checked( 3, $attack, false ) . '/>';
    $html .= '<label for="invader_attack_mode_three">Squadron</label>        ';
    echo $html;
}

function jellyfish_invaders_z_index_input() {
    $options = get_option( 'jellyfish_invaders_options' );
    $z_index = $options['z_index'];
    $html = "<input id='z_index' name='jellyfish_invaders_options[z_index]' type='text' value='$z_index' size=5 /> ";
    $html .= '<label for="z_index">Layer invaders behind / in front of other page elements</label>';
    echo $html;
}

function jellyfish_invaders_container_element_input() {
    $options = get_option( 'jellyfish_invaders_options' );
    $container_element = $options['container_element'];
    $html = "<input id='container_element' name='jellyfish_invaders_options[container_element]' type='text' value='$container_element' /> ";
    $html .= '<label for="container_element">Element id (eg. #content)</label>';
    echo $html;
}

function jellyfish_invaders_boundary_input() {
    $options = get_option( 'jellyfish_invaders_options' );
    $html = "<input id='enable' name='jellyfish_invaders_options[boundary]' type='checkbox' ". checked( true, $options['boundary'], false ). " / > ";
    $html .= '<label for="enable"> Confine to fenced area (overrides <b>Containing Element</b> option)</label>';
    echo $html;
}
function jellyfish_invaders_top_input() {
    $options = get_option( 'jellyfish_invaders_options' );
    $top = $options['top'];
    $html = "<input id='top' name='jellyfish_invaders_options[top]' type='text' value='$top' size=5 /> ";
    $html .= '<label for="top">px</label>';
    echo $html;
}
function jellyfish_invaders_left_input() {
    $options = get_option( 'jellyfish_invaders_options' );
    $left = $options['left'];
    $html = "<input id='left' name='jellyfish_invaders_options[left]' type='text' value='$left' size=5 /> ";
    $html .= '<label for="left">px</label>';
    echo $html;
}
function jellyfish_invaders_right_input() {
    $options = get_option( 'jellyfish_invaders_options' );
    $right = $options['right'];
    $html = "<input id='right' name='jellyfish_invaders_options[right]' type='text' value='$right' size=5 /> ";
    $html .= '<label for="right">px</label>';
    echo $html;
}
function jellyfish_invaders_bottom_input() {
    $options = get_option( 'jellyfish_invaders_options' );
    $bottom = $options['bottom'];
    $html = "<input id='bottom' name='jellyfish_invaders_options[bottom]' type='text' value='$bottom' size=5 /> ";
    $html .= '<label for="bottom">px</label>';
    echo $html;
}


// Validate user input
function jellyfish_invaders_validate_options( $input ) {
    $valid = array();

    if ( $input['enable'] == true ) {
        $valid['enable'] = true;
    } else {
        $valid['enable'] = false;
    }

    if ( $input['random'] == true ) {
        $valid['random'] = true;
    } else {
        $valid['random'] = false;
    }

    if ( $input['wiggle'] == true ) {
        $valid['wiggle'] = true;
    } else {
        $valid['wiggle'] = false;
    }
    // number of invaders min 1, max 30
    $valid['number_of_invaders'] = min( 30, max( 1, absint( $input['number_of_invaders'] ) ) );
    // duration min 250, max 10000
    $valid['duration'] = min( 10000, max( 250, absint( $input['duration'] ) ) );
    // pause min 0, max 10000
    $valid['pause'] = min( 10000, max( 0, absint( $input['pause'] ) ) );

    $valid['size'] = intval( $input['size'] );

    $valid['attack_mode'] = intval( $input['attack_mode'] );

    $valid['z_index'] = intval( $input['z_index'] );
    $valid['container_element'] = wp_filter_nohtml_kses( $input['container_element'] );

    if ( $input['use_custom_field'] == 'true' ) {
        $valid['use_custom_field'] = true;
    } else {
        $valid['use_custom_field'] = false;
    }
    if ( $input['boundary'] == true ) {
        $valid['boundary'] = true;
    } else {
        $valid['boundary'] = false;
    }

    $valid['top'] = absint( $input['top'] );
    $valid['bottom'] = absint( $input['bottom'] );
    $valid['left'] = absint( $input['left'] );
    $valid['right'] = absint( $input['right'] );


    return $valid;
}

/*
 * Generate the invaders JavaScript based on user settings
 */

function jellyfish_invaders_print_script() {
    global $wpdb;
    $options = get_option( 'jellyfish_invaders_options' );
    $count = 0;
    $small_speed = 2;
    $medium_speed = 1.5;
    $large_speed = 1;

    // check if we are displaying globally or ONLY on post/page that
    // has jellyfish_invaders custom field set to TRUE
    if ( $options['use_custom_field'] == true ) {
        if ( is_single() or is_page() ) {
            $cv = get_post_meta( get_the_ID(), 'jellyfish_invaders', true );
            if ( ( $cv != 'true' ) && ( $cv != 'on' ) ) {
                // abort: no custom field
                return;
            }
        } else {
            // abort: not a single page or post
            return;
        }
    }

    // choose class (size of invader depending on settings)
    // as we are acting on sizes here also set up the
    // appropriate speed and pause bounce
    if ( $options['size'] === 1 ) {
        $class='small-invader';
        $spd = $small_speed;
        $bounce = ', bounce:[1, 55, '. 2000 / $spd . ']';
        $width = 55;
        $height = 45;
    } elseif ( $options['size'] === 2 ) {
        $class='medium-invader';
        $spd = $medium_speed;
        $bounce = ', bounce:[1, 110, '. 2000 / $spd . ']';
        $width = 110;
        $height = 90;
    } else {
        $class='large-invader';
        $spd = $large_speed;
        $bounce = ', bounce:[1, 220, '. 2000 / $spd . ']';
        $width = 220;
        $height = 180;
    }

    // blank the pause bounce if it is not wanted
    if ( $options['wiggle'] != true ) {
        $bounce ='';
    } else {
        $width * 2;
    }

    $top = $options['top'];
    $left = $options['left'];
    $right = $options['right'];
    $bottom = $options['bottom'];
    $z_index = $options['z_index'] ? $options['z_index'] : 0 ;
    $container = $options['container_element'] ? $options['container_element'] : 'body';

    $script = "var invaderContainer = jQuery('$container');";

    if ( $options['boundary'] == true ) {
        // use boundary settings
        $container = 'body';
        $script .= "
            var invaderContainer = jQuery('$container');
            var invaderBoundaryTop = $top;
            var invaderBoundaryLeft = $left;
            var invaderBoundaryRight = $right - $width;
            var invaderBoundaryBottom = $bottom - $height;
        ";

    } else {
        // free to roam entire containing element
        $script .= "
            var invaderContainer = jQuery('$container');
            var invaderBoundaryTop = invaderContainer.offset().top;
            var invaderBoundaryLeft = invaderContainer.offset().left;
            var invaderBoundaryRight = ( invaderBoundaryLeft + invaderContainer.width() ) - $width;
            var invaderBoundaryBottom = ( invaderBoundaryTop + invaderContainer.height() ) - $height;
        ";
    }

    while ( $count < $options['number_of_invaders'] ) {
        // create js for each invader required, include a bit of random variation
        // to the duration and pause to make it less uniform if set in settings
        if ( $options['random'] == true ) {
            $duration = max( 0, ( $options['duration']+( ( $options['duration']/100 ) * rand( -50, 50 ) ) ) );
            $pause = max( 0, ( $options['pause']+( ( $options['pause']/100 ) * rand( -50, 50 ) ) ) );
        } else {
            $duration = $options['duration'];
            $pause = $options['pause'];
        }
        $script.="
            var jellyfishInvaderDiv$count = jQuery('<div>', { id: 'jellyfishInvader$count', class: '$class'})
                .css('z-index', $z_index)
                .appendTo('$container')
                .sprite({
                    fps: $spd,
                    no_of_frames: 2
                    $bounce
                })
                .spRandom({
                    top: invaderBoundaryTop,
                    left: invaderBoundaryLeft,
                    right: invaderBoundaryRight,
                    bottom: invaderBoundaryBottom,
                    speed: $duration,
                    pause: $pause
                })
                .active()
                .activeOnClick()
                .bounce;
            \n";
        $count ++;
    }
    echo "<script>jQuery(document).ready(function() {";
    echo $script;

    // add a ftytotap even if attack mode is set, either just first invader or all elements with
    // invader classes (hopefully these are just invaders!)
    if ( $options['attack_mode'] === 3 ) {
        echo "jQuery('$container').flyToTap({el_to_move: '.".$class."', ms: 500, do_once: false});";
    } elseif ( $options['attack_mode'] === 2 ) {
        echo "jQuery('$container').flyToTap({el_to_move: '#jellyfishInvader0', ms: 500, do_once: false});";
    }

    echo "});</script>";
}
?>

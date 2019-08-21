<?php
/**
*
*Plugin Name: PHP Tag Checker
*Author: Presslabs
*Version: 1.0
*
* Description: This plugin scans every plugin and theme main PHP file whether the file has the PHP end tag "?>" at the end.
*
***/


function php_end_tag_chk()
{

    $description = 'This plugin searche every main php file from plugins and themes and check whether the file has the php end tag "?>" at the end. Acording to <a target="_blank" href="http://www.php-fig.org/psr/psr-2/">PSR-2 standard</a>: The closing "?>" tag MUST be omitted from files containing only PHP. Click <a target="_blank" href=http://hardcorewp.com/2013/always-omit-closing-php-tags-in-wordpress-plugins/>here</a> for more information about this issue.';
    $content = '<strong><h4>Plugin PHP tag cleaner init.</h4></strong>'.$description.'<hr />';

    $dirty_plugins = php_end_tag_chk_checker_plugin();
    $dirty_themes  = php_end_tag_chk_checker_theme();

    if ( $dirty_plugins != '' ) {
        $content .='<p><strong>Dirty plugins:</strong><br />';
        $content .= $dirty_plugins;
        $content .= '</p>';
    }
    if ( $dirty_themes != '' ) {
        $content .='<p><strong>Dirty themes:</strong><br />';
        $content .= $dirty_themes;
        $content .= '</p>';
    }

    if ( $dirty_plugins != '' || $dirty_themes!= '' )
    {
    ?>
        <div id='notif22' class='error'>
            <?php echo $content; ?>
        <hr>
            <p>
                <button id='dismiss-btn' type='button' title='Deactivate.'>Don't show this again</button>
                <span class='description'>(This button deactivate the plugin.)</span>
            </p>
        </div>
    <?php
    }
}
add_action( 'admin_notices', 'php_end_tag_chk' );

function php_end_tag_chk_checker_plugin() {
    $all_plugins = get_plugins();
    $content_plugin = '';
    foreach ( $all_plugins as $key => $value ) {
        $plugin_path = WP_PLUGIN_DIR . '/' . $key;
        $link = admin_url( 'plugin-editor.php' ) . '?plugin=' . $key;
        if ( check_for_php_tag( $plugin_path ) ) {
            $line = filesize( $plugin_path );
            $content_plugin .= ' - ' . $value['Name'] . "   <a href=$link&scrollto=$line title='Click here to edit the file.'>edit</a>" . '<br />';
        }
    }
    return $content_plugin;
}

function php_end_tag_chk_checker_theme() {
    $all_themes = get_themes();
    $content_theme = '';
    foreach ( $all_themes as $key => $value ) {
        $theme_path = $value->get_stylesheet_directory() . '/functions.php';
        $link = admin_url( 'theme-editor.php' ) . '?theme=' . $value->get_stylesheet() . '&file=functions.php';
        if ( check_for_php_tag( $theme_path ) ) {
            $line = filesize( $theme_path );
            $content_theme .= ' - ' . $value['Name'] . "   <a href=$link&scrollto=$line title='Click here to edit the file.'>edit</a>" . '<br />';
        }
    }
    return $content_theme;
}

function check_for_php_tag( $file_name ) {
    $file_content = file( $file_name );
    $pos = count( $file_content ) - 1;
    $max_back_line = count( $file_content ) - 4;

    while ( $max_back_line <= $pos ) {
        $r = array();
        if ( preg_match( '/^(\?>([^?]*$))/m', $file_content[ $pos ], $r ) ) {
            return true;
        }
        $pos -= 1;
    }
    return false;
}

function php_end_tag_chk_load_script() {
    $url = plugins_url( 'button.js', __FILE__ );
    wp_enqueue_script( 'php-cleaner-js', $url , array( 'jquery' ), filemtime( './button.js' ), true );
    $params = array(
          'ajaxurl'    => admin_url( 'admin-ajax.php' ),
          'ajax_nonce' => wp_create_nonce( 'php_end_tag_chk_deactivate_plugin' )
    );
    wp_localize_script( 'php-cleaner-js', 'php_end_tag_chk_ajax', $params );
}
add_action( 'admin_enqueue_scripts', 'php_end_tag_chk_load_script' );

function php_end_tag_chk_ajax_dismiss() {
    if ( check_ajax_referer( 'php_end_tag_chk_deactivate_plugin', 'nonce', false ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );
    }
}
add_action( 'wp_ajax_dismiss_php_end_tag_chk', 'php_end_tag_chk_ajax_dismiss' );

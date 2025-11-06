<?php
/**
 * Plugin Name: VennQuizzes
 * Plugin URI: https://github.com/markfenske84/vennquizzes
 * Description: Create interactive Venn diagram quizzes to visualize data and help users determine where their focus should be.
 * Version: 1.0.0
 * Author: Webfor
 * Author URI: https://webfor.com/
 * Text Domain: vennquizzes
 * 
 * @package VennQuizzes
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('VENNQUIZ_VERSION', '1.0.0');
define('VENNQUIZ_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('VENNQUIZ_PLUGIN_URL', plugin_url(__FILE__));
define('VENNQUIZ_STYLES_PATH', 'includes/css');
define('VENNQUIZ_SCRIPTS_PATH', 'includes/js');
define('VENNQUIZ_PARTIALS_PATH', VENNQUIZ_PLUGIN_DIR . 'includes/partials');

/**
 * Enqueue frontend scripts and styles
 */
function venn_quiz_enqueue_scripts() {
    // Enqueue D3.js
    wp_enqueue_script('d3js', 'https://d3js.org/d3.v4.min.js', array(), '4.0', true);
    
    // Enqueue Venn.js
    wp_enqueue_script(
        'venn-js',
        plugins_url('includes/js/venn.js/venn.js', __FILE__),
        array('d3js'),
        VENNQUIZ_VERSION,
        true
    );
    
    // Enqueue quiz calculator
    wp_enqueue_script(
        'venn-quiz-calc',
        plugins_url('includes/js/quizcalc.js', __FILE__),
        array('venn-js'),
        VENNQUIZ_VERSION,
        true
    );
    
    // Enqueue styles
    wp_enqueue_style(
        'venn-quiz-style',
        plugins_url('includes/css/style.css', __FILE__),
        array(),
        VENNQUIZ_VERSION
    );
}

/**
 * Shortcode: [venn_quiz id="123"]
 */
function venn_quiz_shortcode($atts) {
    // Enqueue scripts when shortcode is used
    venn_quiz_enqueue_scripts();
    
    $atts = shortcode_atts(array(
        'id' => 0,
    ), $atts, 'venn_quiz');
    
    // Check if the file exists
    $form_file = VENNQUIZ_PARTIALS_PATH . '/quiz-form.php';
    if (!file_exists($form_file)) {
        return '<p>Error: Quiz form template not found.</p>';
    }
    
    ob_start();
    include $form_file;
    return ob_get_clean();
}
add_shortcode('venn_quiz', 'venn_quiz_shortcode');

/**
 * Main plugin class
 */
class VennQuizzes {
    /**
     * Constructor
     */
    public function __construct() {
        // Register custom post type for Quizzes
        require_once(VENNQUIZ_PLUGIN_DIR . 'includes/quiz-post-type.php');
        new VennQuiz_PostType();
        
        // Register Gutenberg block
        require_once(VENNQUIZ_PLUGIN_DIR . 'includes/block-editor.php');
        new VennQuiz_BlockEditor();
    }

    /**
     * Activation hook
     */
    public static function activate() {
        flush_rewrite_rules();
    }

    /**
     * Deactivation hook
     */
    public static function deactivate() {
        flush_rewrite_rules();
    }
}

// Initialize plugin
if (class_exists('VennQuizzes')) {
    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('VennQuizzes', 'activate'));
    register_deactivation_hook(__FILE__, array('VennQuizzes', 'deactivate'));

    // Instantiate the plugin class
    new VennQuizzes();
}

// Initialize plugin updater
require_once(VENNQUIZ_PLUGIN_DIR . 'plugin-update-checker/plugin-update-checker.php');
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$updateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/markfenske84/vennquizzes',
    __FILE__,
    'vennquizzes'
);

// Set the branch that contains the stable release
$updateChecker->setBranch('main');

// Optional: If your GitHub repo is private, specify the access token
// $updateChecker->setAuthentication('your-token-here');


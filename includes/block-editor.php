<?php
if(!class_exists('VennQuiz_BlockEditor'))
{
    class VennQuiz_BlockEditor
    {
        /**
         * Constructor
         */
        public function __construct()
        {
            add_action('init', array(&$this, 'register_block'));
        }

        /**
         * Register the Gutenberg block
         */
        public function register_block()
        {
            // Register block editor styles
            wp_register_style(
                'venn-quiz-block-editor-style',
                plugins_url('css/block-editor.css', __FILE__),
                array('wp-edit-blocks'),
                filemtime(plugin_dir_path(__FILE__) . 'css/block-editor.css')
            );

            // Register block script
            wp_register_script(
                'venn-quiz-block',
                plugins_url('js/block-editor.js', __FILE__),
                array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-data'),
                filemtime(plugin_dir_path(__FILE__) . 'js/block-editor.js'),
                true
            );

            // Register the block
            register_block_type('vennquizzes/venn-quiz', array(
                'api_version' => 2,
                'editor_script' => 'venn-quiz-block',
                'editor_style' => 'venn-quiz-block-editor-style',
                'render_callback' => array(&$this, 'render_block'),
                'attributes' => array(
                    'quizId' => array(
                        'type' => 'number',
                        'default' => 0,
                    ),
                    'align' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                ),
                'supports' => array(
                    'align' => array('wide', 'full'),
                    'html' => false,
                ),
            ));
        }

        /**
         * Render the block on the frontend
         */
        public function render_block($attributes)
        {
            // Enqueue scripts when block is used
            if (function_exists('venn_quiz_enqueue_scripts')) {
                venn_quiz_enqueue_scripts();
            }
            
            $quiz_id = isset($attributes['quizId']) ? intval($attributes['quizId']) : 0;
            
            if (!$quiz_id) {
                return '<div class="venn-quiz-block-notice" style="padding: 2rem; text-align: center; background: #f0f0f1; border-radius: 8px; color: #646970;"><p style="margin: 0;">⚠️ Please select a quiz to display.</p></div>';
            }

            // Build wrapper classes
            $wrapper_classes = array('venn-quiz-block-wrapper');
            if (!empty($attributes['align'])) {
                $wrapper_classes[] = 'align' . $attributes['align'];
            }

            // Use the same rendering as the shortcode
            $atts = array('id' => $quiz_id);
            ob_start();
            echo '<div class="' . esc_attr(implode(' ', $wrapper_classes)) . '">';
            include plugin_dir_path(dirname(__FILE__)) . 'includes/partials/quiz-form.php';
            echo '</div>';
            return ob_get_clean();
        }
    }
}


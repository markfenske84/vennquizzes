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
            // Register block script
            wp_register_script(
                'venn-quiz-block',
                plugins_url('js/block-editor.js', __FILE__),
                array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-data'),
                filemtime(plugin_dir_path(__FILE__) . 'js/block-editor.js')
            );

            // Register the block
            register_block_type('vennquizzes/venn-quiz', array(
                'editor_script' => 'venn-quiz-block',
                'render_callback' => array(&$this, 'render_block'),
                'attributes' => array(
                    'quizId' => array(
                        'type' => 'number',
                        'default' => 0,
                    ),
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
                return '<p>Please select a quiz to display.</p>';
            }

            // Use the same rendering as the shortcode
            $atts = array('id' => $quiz_id);
            ob_start();
            include plugin_dir_path(dirname(__FILE__)) . 'includes/partials/quiz-form.php';
            return ob_get_clean();
        }
    }
}


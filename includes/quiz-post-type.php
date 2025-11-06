<?php
if(!class_exists('VennQuiz_PostType'))
{
    class VennQuiz_PostType
    {
        /**
         * Construct the custom post type
         */
        public function __construct()
        {
            add_action('init', array(&$this, 'register_quiz_post_type'));
            add_action('add_meta_boxes', array(&$this, 'add_quiz_meta_boxes'));
            add_action('save_post_venn_quiz', array(&$this, 'save_quiz_meta'), 10, 2);
            add_action('admin_enqueue_scripts', array(&$this, 'enqueue_admin_scripts'));
        }

        /**
         * Register the Quiz custom post type
         */
        public function register_quiz_post_type()
        {
            $labels = array(
                'name'                  => _x('Quizs', 'Post Type General Name', 'vennquizzes'),
                'singular_name'         => _x('Quiz', 'Post Type Singular Name', 'vennquizzes'),
                'menu_name'             => __('Quizs', 'vennquizzes'),
                'name_admin_bar'        => __('Quiz', 'vennquizzes'),
                'archives'              => __('Quiz Archives', 'vennquizzes'),
                'attributes'            => __('Quiz Attributes', 'vennquizzes'),
                'parent_item_colon'     => __('Parent Quiz:', 'vennquizzes'),
                'all_items'             => __('All Quizs', 'vennquizzes'),
                'add_new_item'          => __('Add New Quiz', 'vennquizzes'),
                'add_new'               => __('Add New', 'vennquizzes'),
                'new_item'              => __('New Quiz', 'vennquizzes'),
                'edit_item'             => __('Edit Quiz', 'vennquizzes'),
                'update_item'           => __('Update Quiz', 'vennquizzes'),
                'view_item'             => __('View Quiz', 'vennquizzes'),
                'view_items'            => __('View Quizs', 'vennquizzes'),
                'search_items'          => __('Search Quiz', 'vennquizzes'),
                'not_found'             => __('Not found', 'vennquizzes'),
                'not_found_in_trash'    => __('Not found in Trash', 'vennquizzes'),
            );

            $args = array(
                'label'                 => __('Quiz', 'vennquizzes'),
                'description'           => __('Venn Diagram Quizs', 'vennquizzes'),
                'labels'                => $labels,
                'supports'              => array('title'),
                'hierarchical'          => false,
                'public'                => false,
                'show_ui'               => true,
                'show_in_menu'          => true,
                'menu_position'         => 20.5,
                'menu_icon'             => 'dashicons-chart-pie',
                'show_in_admin_bar'     => true,
                'show_in_nav_menus'     => false,
                'can_export'            => true,
                'has_archive'           => false,
                'exclude_from_search'   => true,
                'publicly_queryable'    => false,
                'capability_type'       => 'post',
                'show_in_rest'          => true, // Enable for Gutenberg block
            );

            register_post_type('venn_quiz', $args);
        }

        /**
         * Add meta boxes for quiz configuration
         */
        public function add_quiz_meta_boxes()
        {
            add_meta_box(
                'venn_quiz_categories',
                __('Category Labels', 'vennquizzes'),
                array(&$this, 'render_categories_meta_box'),
                'venn_quiz',
                'normal',
                'high'
            );

            add_meta_box(
                'venn_quiz_questions',
                __('Quiz Questions', 'vennquizzes'),
                array(&$this, 'render_questions_meta_box'),
                'venn_quiz',
                'normal',
                'high'
            );

            add_meta_box(
                'venn_quiz_messages',
                __('Success Messages', 'vennquizzes'),
                array(&$this, 'render_messages_meta_box'),
                'venn_quiz',
                'normal',
                'default'
            );
            
            add_meta_box(
                'venn_quiz_shortcode',
                __('Shortcode', 'vennquizzes'),
                array(&$this, 'render_shortcode_meta_box'),
                'venn_quiz',
                'side',
                'high'
            );
        }

        /**
         * Render shortcode meta box
         */
        public function render_shortcode_meta_box($post)
        {
            ?>
            <p>Use this shortcode to display the quiz:</p>
            <input type="text" readonly value='[venn_quiz id="<?php echo $post->ID; ?>"]' onclick="this.select();" style="width: 100%;">
            <?php
        }

        /**
         * Render categories meta box
         */
        public function render_categories_meta_box($post)
        {
            wp_nonce_field('venn_quiz_meta_box', 'venn_quiz_meta_box_nonce');
            
            $category_1 = get_post_meta($post->ID, '_venn_category_1_label', true) ?: 'Search';
            $category_2 = get_post_meta($post->ID, '_venn_category_2_label', true) ?: 'Social';
            $category_3 = get_post_meta($post->ID, '_venn_category_3_label', true) ?: 'Direct';
            ?>
            <table class="form-table">
                <tr>
                    <th><label for="venn_category_1_label">Category 1 Label</label></th>
                    <td>
                        <input type="text" id="venn_category_1_label" name="venn_category_1_label" value="<?php echo esc_attr($category_1); ?>" class="regular-text" required>
                        <p class="description">Label for the first bubble in the Venn diagram</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="venn_category_2_label">Category 2 Label</label></th>
                    <td>
                        <input type="text" id="venn_category_2_label" name="venn_category_2_label" value="<?php echo esc_attr($category_2); ?>" class="regular-text" required>
                        <p class="description">Label for the second bubble in the Venn diagram</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="venn_category_3_label">Category 3 Label</label></th>
                    <td>
                        <input type="text" id="venn_category_3_label" name="venn_category_3_label" value="<?php echo esc_attr($category_3); ?>" class="regular-text" required>
                        <p class="description">Label for the third bubble in the Venn diagram</p>
                    </td>
                </tr>
            </table>
            <?php
        }

        /**
         * Render questions meta box
         */
        public function render_questions_meta_box($post)
        {
            $questions = get_post_meta($post->ID, '_venn_quiz_questions', true);
            if (!is_array($questions)) {
                $questions = array();
            }
            ?>
            <div id="venn-questions-container">
                <?php 
                if (empty($questions)) {
                    // Add one empty question by default
                    $this->render_question_row(0, array());
                } else {
                    foreach ($questions as $index => $question) {
                        $this->render_question_row($index, $question);
                    }
                }
                ?>
            </div>
            <p>
                <button type="button" class="button button-secondary" id="add-question">Add Question</button>
            </p>
            <?php
        }

        /**
         * Render a single question row
         */
        private function render_question_row($index, $question)
        {
            $question_text = isset($question['question_text']) ? $question['question_text'] : '';
            $answer_type = isset($question['answer_type']) ? $question['answer_type'] : 'radio';
            $answers = isset($question['answers']) ? $question['answers'] : array();
            $range_category = isset($question['range_category']) ? $question['range_category'] : 'category_1';
            ?>
            <div class="venn-question-item" data-index="<?php echo $index; ?>">
                <h4>Question #<span class="question-number"><?php echo $index + 1; ?></span></h4>
                <table class="form-table">
                    <tr>
                        <th><label>Question Text</label></th>
                        <td>
                            <input type="text" name="venn_questions[<?php echo $index; ?>][question_text]" value="<?php echo esc_attr($question_text); ?>" class="large-text" required>
                        </td>
                    </tr>
                    <tr>
                        <th><label>Answer Type</label></th>
                        <td>
                            <select name="venn_questions[<?php echo $index; ?>][answer_type]" class="answer-type-select">
                                <option value="radio" <?php selected($answer_type, 'radio'); ?>>Radio (Single Choice)</option>
                                <option value="checkbox" <?php selected($answer_type, 'checkbox'); ?>>Checkbox (Multiple Choice)</option>
                                <option value="range" <?php selected($answer_type, 'range'); ?>>Range Slider</option>
                            </select>
                        </td>
                    </tr>
                </table>
                
                <div class="answers-container" style="<?php echo $answer_type === 'range' ? 'display:none;' : ''; ?>">
                    <h5>Answers (max 4)</h5>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>Answer Text</th>
                                <th>Category 1 Score</th>
                                <th>Category 2 Score</th>
                                <th>Category 3 Score</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="answers-list">
                            <?php 
                            if (!empty($answers)) {
                                foreach ($answers as $ans_index => $answer) {
                                    $this->render_answer_row($index, $ans_index, $answer);
                                }
                            } else {
                                // Add one empty answer by default
                                $this->render_answer_row($index, 0, array());
                            }
                            ?>
                        </tbody>
                    </table>
                    <button type="button" class="button button-small add-answer">Add Answer</button>
                </div>

                <div class="range-container" style="<?php echo $answer_type === 'range' ? '' : 'display:none;'; ?>">
                    <table class="form-table">
                        <tr>
                            <th><label>Range Slider Category</label></th>
                            <td>
                                <select name="venn_questions[<?php echo $index; ?>][range_category]">
                                    <option value="category_1" <?php selected($range_category, 'category_1'); ?>>Category 1</option>
                                    <option value="category_2" <?php selected($range_category, 'category_2'); ?>>Category 2</option>
                                    <option value="category_3" <?php selected($range_category, 'category_3'); ?>>Category 3</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>

                <p>
                    <button type="button" class="button duplicate-question">Duplicate Question</button>
                    <button type="button" class="button button-link-delete remove-question">Remove Question</button>
                </p>
                <hr>
            </div>
            <?php
        }

        /**
         * Render a single answer row
         */
        private function render_answer_row($question_index, $answer_index, $answer)
        {
            $answer_text = isset($answer['answer_text']) ? $answer['answer_text'] : '';
            $score_1 = isset($answer['score_1']) ? $answer['score_1'] : 0;
            $score_2 = isset($answer['score_2']) ? $answer['score_2'] : 0;
            $score_3 = isset($answer['score_3']) ? $answer['score_3'] : 0;
            ?>
            <tr class="answer-row">
                <td>
                    <input type="text" name="venn_questions[<?php echo $question_index; ?>][answers][<?php echo $answer_index; ?>][answer_text]" value="<?php echo esc_attr($answer_text); ?>" class="regular-text" required>
                </td>
                <td>
                    <input type="number" name="venn_questions[<?php echo $question_index; ?>][answers][<?php echo $answer_index; ?>][score_1]" value="<?php echo esc_attr($score_1); ?>" min="0" class="small-text" required>
                </td>
                <td>
                    <input type="number" name="venn_questions[<?php echo $question_index; ?>][answers][<?php echo $answer_index; ?>][score_2]" value="<?php echo esc_attr($score_2); ?>" min="0" class="small-text" required>
                </td>
                <td>
                    <input type="number" name="venn_questions[<?php echo $question_index; ?>][answers][<?php echo $answer_index; ?>][score_3]" value="<?php echo esc_attr($score_3); ?>" min="0" class="small-text" required>
                </td>
                <td>
                    <button type="button" class="button button-small duplicate-answer" title="Duplicate this answer">Duplicate</button>
                    <button type="button" class="button button-small remove-answer">Remove</button>
                </td>
            </tr>
            <?php
        }

        /**
         * Render success messages meta box
         */
        public function render_messages_meta_box($post)
        {
            $message_1 = get_post_meta($post->ID, '_venn_success_message_1', true) ?: 'Congrats, you need to focus on {category_1}.';
            $message_2 = get_post_meta($post->ID, '_venn_success_message_2', true) ?: 'Congrats, you need to focus on {category_2}.';
            $message_3 = get_post_meta($post->ID, '_venn_success_message_3', true) ?: 'Congrats, you need to focus on {category_3}.';
            ?>
            <table class="form-table">
                <tr>
                    <th><label for="venn_success_message_1">Success Message for Category 1</label></th>
                    <td>
                        <textarea id="venn_success_message_1" name="venn_success_message_1" rows="3" class="large-text"><?php echo esc_textarea($message_1); ?></textarea>
                        <p class="description">Message shown when Category 1 has the highest score. Use {category_1} as placeholder for the category name.</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="venn_success_message_2">Success Message for Category 2</label></th>
                    <td>
                        <textarea id="venn_success_message_2" name="venn_success_message_2" rows="3" class="large-text"><?php echo esc_textarea($message_2); ?></textarea>
                        <p class="description">Message shown when Category 2 has the highest score. Use {category_2} as placeholder for the category name.</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="venn_success_message_3">Success Message for Category 3</label></th>
                    <td>
                        <textarea id="venn_success_message_3" name="venn_success_message_3" rows="3" class="large-text"><?php echo esc_textarea($message_3); ?></textarea>
                        <p class="description">Message shown when Category 3 has the highest score. Use {category_3} as placeholder for the category name.</p>
                    </td>
                </tr>
            </table>
            <?php
        }

        /**
         * Save quiz meta data
         */
        public function save_quiz_meta($post_id, $post)
        {
            // Security checks
            if (!isset($_POST['venn_quiz_meta_box_nonce']) || !wp_verify_nonce($_POST['venn_quiz_meta_box_nonce'], 'venn_quiz_meta_box')) {
                return;
            }

            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }

            if (!current_user_can('edit_post', $post_id)) {
                return;
            }

            // Save category labels
            if (isset($_POST['venn_category_1_label'])) {
                update_post_meta($post_id, '_venn_category_1_label', sanitize_text_field($_POST['venn_category_1_label']));
            }
            if (isset($_POST['venn_category_2_label'])) {
                update_post_meta($post_id, '_venn_category_2_label', sanitize_text_field($_POST['venn_category_2_label']));
            }
            if (isset($_POST['venn_category_3_label'])) {
                update_post_meta($post_id, '_venn_category_3_label', sanitize_text_field($_POST['venn_category_3_label']));
            }

            // Save success messages
            if (isset($_POST['venn_success_message_1'])) {
                update_post_meta($post_id, '_venn_success_message_1', wp_kses_post($_POST['venn_success_message_1']));
            }
            if (isset($_POST['venn_success_message_2'])) {
                update_post_meta($post_id, '_venn_success_message_2', wp_kses_post($_POST['venn_success_message_2']));
            }
            if (isset($_POST['venn_success_message_3'])) {
                update_post_meta($post_id, '_venn_success_message_3', wp_kses_post($_POST['venn_success_message_3']));
            }

            // Save questions
            if (isset($_POST['venn_questions']) && is_array($_POST['venn_questions'])) {
                $questions = array();
                foreach ($_POST['venn_questions'] as $question) {
                    $sanitized_question = array(
                        'question_text' => sanitize_text_field($question['question_text']),
                        'answer_type' => sanitize_text_field($question['answer_type']),
                    );

                    if ($sanitized_question['answer_type'] === 'range') {
                        $sanitized_question['range_category'] = sanitize_text_field($question['range_category']);
                    } else {
                        $sanitized_question['answers'] = array();
                        if (isset($question['answers']) && is_array($question['answers'])) {
                            foreach ($question['answers'] as $answer) {
                                $sanitized_question['answers'][] = array(
                                    'answer_text' => sanitize_text_field($answer['answer_text']),
                                    'score_1' => intval($answer['score_1']),
                                    'score_2' => intval($answer['score_2']),
                                    'score_3' => intval($answer['score_3']),
                                );
                            }
                        }
                    }

                    $questions[] = $sanitized_question;
                }
                update_post_meta($post_id, '_venn_quiz_questions', $questions);
            }
        }

        /**
         * Enqueue admin scripts
         */
        public function enqueue_admin_scripts($hook)
        {
            global $post_type, $post;
            
            if (($hook == 'post-new.php' || $hook == 'post.php') && $post_type == 'venn_quiz') {
                // Enqueue CSS
                wp_enqueue_style(
                    'venn-quiz-admin-css',
                    plugins_url('css/admin-quiz.css', __FILE__),
                    array(),
                    filemtime(plugin_dir_path(__FILE__) . 'css/admin-quiz.css')
                );
                
                // Enqueue vanilla JavaScript (no jQuery dependency)
                wp_enqueue_script(
                    'venn-quiz-admin',
                    plugins_url('js/admin-quiz.js', __FILE__),
                    array(), // No dependencies
                    filemtime(plugin_dir_path(__FILE__) . 'js/admin-quiz.js'),
                    true
                );
            }
        }
    }
}

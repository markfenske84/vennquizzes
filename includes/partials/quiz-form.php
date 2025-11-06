<?php
// Get quiz ID from shortcode attribute
$quiz_id = isset($atts['id']) ? intval($atts['id']) : 0;

// If no quiz ID provided, try to get the first quiz
if (!$quiz_id) {
    $quizzes = get_posts(array(
        'post_type' => 'venn_quiz',
        'posts_per_page' => 1,
        'post_status' => 'publish'
    ));
    
    if (!empty($quizzes)) {
        $quiz_id = $quizzes[0]->ID;
    } else {
        echo '<p>No quiz found. Please create a quiz first.</p>';
        return;
    }
}

// Get quiz data
$category_1 = get_post_meta($quiz_id, '_venn_category_1_label', true) ?: 'Search';
$category_2 = get_post_meta($quiz_id, '_venn_category_2_label', true) ?: 'Social';
$category_3 = get_post_meta($quiz_id, '_venn_category_3_label', true) ?: 'Direct';
$questions = get_post_meta($quiz_id, '_venn_quiz_questions', true);
$success_message_1 = get_post_meta($quiz_id, '_venn_success_message_1', true) ?: 'Congrats, you need to focus on {category_1}.';
$success_message_2 = get_post_meta($quiz_id, '_venn_success_message_2', true) ?: 'Congrats, you need to focus on {category_2}.';
$success_message_3 = get_post_meta($quiz_id, '_venn_success_message_3', true) ?: 'Congrats, you need to focus on {category_3}.';

// Replace placeholders in success messages
$success_message_1 = str_replace('{category_1}', $category_1, $success_message_1);
$success_message_2 = str_replace('{category_2}', $category_2, $success_message_2);
$success_message_3 = str_replace('{category_3}', $category_3, $success_message_3);

if (!is_array($questions) || empty($questions)) {
    echo '<p>This quiz has no questions yet.</p>';
    return;
}

// Sanitize category names for data attributes
$cat_1_slug = sanitize_title($category_1);
$cat_2_slug = sanitize_title($category_2);
$cat_3_slug = sanitize_title($category_3);
?>

<div class="venn-quiz-form" data-quiz-id="<?php echo esc_attr($quiz_id); ?>" data-category-1="<?php echo esc_attr($cat_1_slug); ?>" data-category-2="<?php echo esc_attr($cat_2_slug); ?>" data-category-3="<?php echo esc_attr($cat_3_slug); ?>">
    
    <?php foreach ($questions as $q_index => $question): ?>
        <?php 
        $question_text = isset($question['question_text']) ? $question['question_text'] : '';
        $answer_type = isset($question['answer_type']) ? $question['answer_type'] : 'radio';
        $answers = isset($question['answers']) ? $question['answers'] : array();
        $range_category = isset($question['range_category']) ? $question['range_category'] : 'category_1';
        ?>

        <?php if ($answer_type === 'select'): ?>
            <div class="quiz-question-wrapper" data-question-index="<?php echo $q_index; ?>" data-question-type="select">
                <label for="question-<?php echo $q_index; ?>" style="font-weight: 700;"><?php echo esc_html($question_text); ?></label><br>
                <select class="quiz-question" name="question-<?php echo $q_index; ?>" id="question-<?php echo $q_index; ?>">
                    <option value="" data-score-<?php echo esc_attr($cat_1_slug); ?>="0" data-score-<?php echo esc_attr($cat_2_slug); ?>="0" data-score-<?php echo esc_attr($cat_3_slug); ?>="0">Select an answer...</option>
                    <?php foreach ($answers as $ans_index => $answer): ?>
                        <option value="answer-<?php echo $ans_index; ?>" 
                            data-score-<?php echo esc_attr($cat_1_slug); ?>="<?php echo esc_attr($answer['score_1']); ?>" 
                            data-score-<?php echo esc_attr($cat_2_slug); ?>="<?php echo esc_attr($answer['score_2']); ?>" 
                            data-score-<?php echo esc_attr($cat_3_slug); ?>="<?php echo esc_attr($answer['score_3']); ?>">
                            <?php echo esc_html($answer['answer_text']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

        <?php elseif ($answer_type === 'radio'): ?>
            <div class="quiz-question-wrapper" data-question-index="<?php echo $q_index; ?>" data-question-type="radio" data-question-name="question-<?php echo $q_index; ?>">
                <p style="margin-bottom: 0; font-weight: 700;"><?php echo esc_html($question_text); ?></p>
                <?php foreach ($answers as $ans_index => $answer): ?>
                    <div>
                        <input class="quiz-question" type="radio" 
                            id="question-<?php echo $q_index; ?>-answer-<?php echo $ans_index; ?>" 
                            name="question-<?php echo $q_index; ?>" 
                            value="answer-<?php echo $ans_index; ?>"
                            data-score-<?php echo esc_attr($cat_1_slug); ?>="<?php echo esc_attr($answer['score_1']); ?>" 
                            data-score-<?php echo esc_attr($cat_2_slug); ?>="<?php echo esc_attr($answer['score_2']); ?>" 
                            data-score-<?php echo esc_attr($cat_3_slug); ?>="<?php echo esc_attr($answer['score_3']); ?>">
                        <label for="question-<?php echo $q_index; ?>-answer-<?php echo $ans_index; ?>"><?php echo esc_html($answer['answer_text']); ?></label>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php elseif ($answer_type === 'checkbox'): ?>
            <div class="quiz-question-wrapper" data-question-index="<?php echo $q_index; ?>" data-question-type="checkbox">
                <p style="margin-bottom: 0; font-weight: 700;"><?php echo esc_html($question_text); ?></p>
                <?php foreach ($answers as $ans_index => $answer): ?>
                    <div>
                        <input class="quiz-question" type="checkbox" 
                            id="question-<?php echo $q_index; ?>-answer-<?php echo $ans_index; ?>" 
                            name="question-<?php echo $q_index; ?>[]" 
                            value="answer-<?php echo $ans_index; ?>"
                            data-score-<?php echo esc_attr($cat_1_slug); ?>="<?php echo esc_attr($answer['score_1']); ?>" 
                            data-score-<?php echo esc_attr($cat_2_slug); ?>="<?php echo esc_attr($answer['score_2']); ?>" 
                            data-score-<?php echo esc_attr($cat_3_slug); ?>="<?php echo esc_attr($answer['score_3']); ?>">
                        <label for="question-<?php echo $q_index; ?>-answer-<?php echo $ans_index; ?>"><?php echo esc_html($answer['answer_text']); ?></label>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php elseif ($answer_type === 'range'): ?>
            <div class="quiz-question-wrapper" data-question-index="<?php echo $q_index; ?>" data-question-type="range">
                <label for="question-<?php echo $q_index; ?>" style="font-weight: 700;"><?php echo esc_html($question_text); ?></label><br>
                <input class="quiz-question range-<?php echo esc_attr($range_category); ?>" 
                    type="range" 
                    id="question-<?php echo $q_index; ?>"
                    name="question-<?php echo $q_index; ?>" 
                    min="0" 
                    max="100" 
                    value="0" 
                    data-range-category="<?php echo esc_attr($range_category); ?>" />
            </div>

        <?php endif; ?>

    <?php endforeach; ?>

    <button class="get-results">Get Results</button>

    <div class="results-overlay">
        <button class="close-results-overlay">&lt; return to quiz</button>
        <h3>Scores (testing purposes only)</h3>
        <b><?php echo esc_html($category_1); ?>:</b> <span id="total-<?php echo esc_attr($cat_1_slug); ?>"></span><br>
        <b><?php echo esc_html($category_2); ?>:</b> <span id="total-<?php echo esc_attr($cat_2_slug); ?>"></span><br>
        <b><?php echo esc_html($category_3); ?>:</b> <span id="total-<?php echo esc_attr($cat_3_slug); ?>"></span>
        <br><br>
        <div class="success-messages-wrap text-center">
            <div class="success-messages success-messages__category-1">
                <?php echo wp_kses_post($success_message_1); ?>
            </div>
            <div class="success-messages success-messages__category-2">
                <?php echo wp_kses_post($success_message_2); ?>
            </div>
            <div class="success-messages success-messages__category-3">
                <?php echo wp_kses_post($success_message_3); ?>
            </div>
        </div>
        <div class="venn-diag">
            <div id="venn"></div>
        </div>
        <button class="print-results" onclick="window.print();return false;">Print Result</button>
    </div>
</div>

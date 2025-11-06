document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin Quiz JS: Document Ready');
    
    var questionIndex = document.querySelectorAll('.venn-question-item').length;
    console.log('Initial question count:', questionIndex);
    
    var addQuestionBtn = document.getElementById('add-question');
    console.log('Add Question button found:', addQuestionBtn !== null);
    console.log('Button element:', addQuestionBtn);

    // Add new question
    if (addQuestionBtn) {
        addQuestionBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Add question clicked, current index:', questionIndex);
            
            var template = getQuestionTemplate(questionIndex);
            document.getElementById('venn-questions-container').insertAdjacentHTML('beforeend', template);
            questionIndex++;
            updateQuestionNumbers();
        });
    }

    // Remove question - delegated event
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-question')) {
            e.preventDefault();
            if (confirm('Are you sure you want to remove this question?')) {
                var questionItem = e.target.closest('.venn-question-item');
                if (questionItem) {
                    questionItem.remove();
                    updateQuestionNumbers();
                }
            }
        }
    });

    // Duplicate question - delegated event
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('duplicate-question')) {
            e.preventDefault();
            console.log('Duplicate question clicked');
            
            var questionItem = e.target.closest('.venn-question-item');
            if (questionItem) {
                // Clone the question item
                var clonedItem = questionItem.cloneNode(true);
                
                // Insert after the current question
                questionItem.parentNode.insertBefore(clonedItem, questionItem.nextSibling);
                
                questionIndex++;
                updateQuestionNumbers();
            }
        }
    });

    // Add answer - delegated event
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-answer')) {
            e.preventDefault();
            console.log('Add answer clicked');
            
            var questionItem = e.target.closest('.venn-question-item');
            var qIndex = questionItem.getAttribute('data-index');
            var answersList = questionItem.querySelector('.answers-list');
            var answerCount = answersList.querySelectorAll('.answer-row').length;
            
            if (answerCount >= 4) {
                alert('Maximum 4 answers allowed per question.');
                return;
            }
            
            var answerIndex = answerCount;
            var answerTemplate = getAnswerTemplate(qIndex, answerIndex);
            answersList.insertAdjacentHTML('beforeend', answerTemplate);
        }
    });

    // Remove answer - delegated event
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-answer')) {
            e.preventDefault();
            var answerRow = e.target.closest('.answer-row');
            var answersList = answerRow.closest('.answers-list');
            
            if (answersList.querySelectorAll('.answer-row').length > 1) {
                answerRow.remove();
            } else {
                alert('At least one answer is required.');
            }
        }
    });

    // Duplicate answer - delegated event
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('duplicate-answer')) {
            e.preventDefault();
            console.log('Duplicate answer clicked');
            
            var answerRow = e.target.closest('.answer-row');
            var answersList = answerRow.closest('.answers-list');
            var answerCount = answersList.querySelectorAll('.answer-row').length;
            
            if (answerCount >= 4) {
                alert('Maximum 4 answers allowed per question.');
                return;
            }
            
            // Clone the answer row
            var clonedRow = answerRow.cloneNode(true);
            
            // Insert after the current answer
            answerRow.parentNode.insertBefore(clonedRow, answerRow.nextSibling);
            
            // Update answer indices for the question
            var questionItem = answersList.closest('.venn-question-item');
            var qIndex = questionItem.getAttribute('data-index');
            var rows = answersList.querySelectorAll('.answer-row');
            
            rows.forEach(function(row, index) {
                // Update all input names in this row
                row.querySelectorAll('input').forEach(function(input) {
                    var name = input.getAttribute('name');
                    if (name) {
                        // Replace the answer index in the name
                        var newName = name.replace(/\[answers\]\[\d+\]/, '[answers][' + index + ']');
                        input.setAttribute('name', newName);
                    }
                });
            });
        }
    });

    // Toggle answer type containers - delegated event
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('answer-type-select')) {
            var questionItem = e.target.closest('.venn-question-item');
            var answerType = e.target.value;
            
            var answersContainer = questionItem.querySelector('.answers-container');
            var rangeContainer = questionItem.querySelector('.range-container');
            
            if (answerType === 'range') {
                answersContainer.style.display = 'none';
                rangeContainer.style.display = 'block';
            } else {
                answersContainer.style.display = 'block';
                rangeContainer.style.display = 'none';
            }
        }
    });

    // Update question numbers
    function updateQuestionNumbers() {
        var questionItems = document.querySelectorAll('.venn-question-item');
        questionItems.forEach(function(item, index) {
            item.setAttribute('data-index', index);
            var questionNumber = item.querySelector('.question-number');
            if (questionNumber) {
                questionNumber.textContent = index + 1;
            }
            
            // Update all name attributes
            var inputs = item.querySelectorAll('input, select, textarea');
            inputs.forEach(function(input) {
                var name = input.getAttribute('name');
                if (name) {
                    // Replace the question index in the name
                    var newName = name.replace(/venn_questions\[\d+\]/, 'venn_questions[' + index + ']');
                    input.setAttribute('name', newName);
                }
            });
        });
    }

    // Get question template
    function getQuestionTemplate(index) {
        var answerRow = getAnswerTemplate(index, 0);
        
        var template = '<div class="venn-question-item" data-index="' + index + '">' +
            '<h4>Question #<span class="question-number">' + (index + 1) + '</span></h4>' +
            '<table class="form-table">' +
                '<tr>' +
                    '<th><label>Question Text</label></th>' +
                    '<td>' +
                        '<input type="text" name="venn_questions[' + index + '][question_text]" value="" class="large-text" required>' +
                    '</td>' +
                '</tr>' +
                '<tr>' +
                    '<th><label>Answer Type</label></th>' +
                    '<td>' +
                        '<select name="venn_questions[' + index + '][answer_type]" class="answer-type-select">' +
                            '<option value="radio">Radio (Single Choice)</option>' +
                            '<option value="checkbox">Checkbox (Multiple Choice)</option>' +
                            '<option value="range">Range Slider</option>' +
                        '</select>' +
                    '</td>' +
                '</tr>' +
            '</table>' +
            
            '<div class="answers-container">' +
                '<h5>Answers (max 4)</h5>' +
                '<table class="widefat">' +
                    '<thead>' +
                        '<tr>' +
                            '<th>Answer Text</th>' +
                            '<th>Category 1 Score</th>' +
                            '<th>Category 2 Score</th>' +
                            '<th>Category 3 Score</th>' +
                            '<th></th>' +
                        '</tr>' +
                    '</thead>' +
                    '<tbody class="answers-list">' +
                        answerRow +
                    '</tbody>' +
                '</table>' +
                '<button type="button" class="button button-small add-answer">Add Answer</button>' +
            '</div>' +

            '<div class="range-container" style="display:none;">' +
                '<table class="form-table">' +
                    '<tr>' +
                        '<th><label>Range Slider Category</label></th>' +
                        '<td>' +
                            '<select name="venn_questions[' + index + '][range_category]">' +
                                '<option value="category_1">Category 1</option>' +
                                '<option value="category_2">Category 2</option>' +
                                '<option value="category_3">Category 3</option>' +
                            '</select>' +
                        '</td>' +
                    '</tr>' +
                '</table>' +
            '</div>' +

            '<p>' +
                '<button type="button" class="button duplicate-question">Duplicate Question</button>' +
                '<button type="button" class="button button-link-delete remove-question">Remove Question</button>' +
            '</p>' +
            '<hr>' +
        '</div>';
        
        return template;
    }

    // Get answer template
    function getAnswerTemplate(questionIndex, answerIndex) {
        return '<tr class="answer-row">' +
            '<td>' +
                '<input type="text" name="venn_questions[' + questionIndex + '][answers][' + answerIndex + '][answer_text]" value="" class="regular-text" required>' +
            '</td>' +
            '<td>' +
                '<input type="number" name="venn_questions[' + questionIndex + '][answers][' + answerIndex + '][score_1]" value="0" min="0" class="small-text" required>' +
            '</td>' +
            '<td>' +
                '<input type="number" name="venn_questions[' + questionIndex + '][answers][' + answerIndex + '][score_2]" value="0" min="0" class="small-text" required>' +
            '</td>' +
            '<td>' +
                '<input type="number" name="venn_questions[' + questionIndex + '][answers][' + answerIndex + '][score_3]" value="0" min="0" class="small-text" required>' +
            '</td>' +
            '<td>' +
                '<button type="button" class="button button-small duplicate-answer" title="Duplicate this answer">Duplicate</button>' +
                '<button type="button" class="button button-small remove-answer">Remove</button>' +
            '</td>' +
        '</tr>';
    }
});

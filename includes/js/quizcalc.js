document.addEventListener('DOMContentLoaded', function() {

    var score = 0;
    var currentScores = { category1: 0, category2: 0, category3: 0 };
    
    // Get category slugs from the quiz form
    var quizForm = document.querySelector('.venn-quiz-form');
    if (!quizForm) return;
    
    var cat1Slug = quizForm.dataset.category1 || 'search';
    var cat2Slug = quizForm.dataset.category2 || 'social';
    var cat3Slug = quizForm.dataset.category3 || 'direct';
    
    // Convert slugs to camelCase for dataset access
    var cat1Key = toCamelCase('score-' + cat1Slug);
    var cat2Key = toCamelCase('score-' + cat2Slug);
    var cat3Key = toCamelCase('score-' + cat3Slug);

    var getResultsBtn = document.querySelector('.get-results');
    var closeResultsBtn = document.querySelector('.close-results-overlay');
    var resultsOverlay = document.querySelector('.results-overlay');
    var vennDiag = document.querySelector('.venn-diag');
    var successMessagesWrap = document.querySelector('.success-messages-wrap');

    // Get Results button handler
    if (getResultsBtn) {
        getResultsBtn.addEventListener('click', function(){
            // Validate all questions are answered
            var unansweredQuestions = validateAllQuestionsAnswered();
            if (unansweredQuestions.length > 0) {
                // Mark unanswered questions
                markUnansweredQuestions(unansweredQuestions);
                
                // Scroll to first unanswered question
                var firstUnanswered = document.querySelector('.quiz-question-wrapper.unanswered');
                if (firstUnanswered) {
                    firstUnanswered.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                
                alert('Please answer all questions before viewing results.');
                return;
            }
            
            // Show results overlay and content
            resultsOverlay.style.display = 'block';
            if (vennDiag) vennDiag.style.display = 'block';
            if (successMessagesWrap) successMessagesWrap.style.display = 'block';
            
            // Highlight winning category
            highlightWinningCategory(currentScores);
        });
    }
    
    // Close button handler
    if (closeResultsBtn) {
        closeResultsBtn.addEventListener('click', function(){
            resultsOverlay.style.display = 'none';
        });
    }

    // Watch for changes on all quiz questions
    var quizQuestions = document.querySelectorAll('.quiz-question');
    quizQuestions.forEach(function(element) {
        element.addEventListener('change', handleQuestionChange);
        element.addEventListener('input', handleQuestionChange); // For range sliders
    });

    // Handle question changes
    function handleQuestionChange(event) {
        // Remove unanswered class from this question's wrapper when answered
        if (event && event.target) {
            var wrapper = event.target.closest('.quiz-question-wrapper');
            if (wrapper) {
                wrapper.classList.remove('unanswered');
            }
        }
        
        // Calculate scores
        currentScores = calculateScores();
        
        // Update score displays
        updateScoreDisplays(currentScores);
        
        // Generate Venn diagram
        generateVennDiagram(currentScores);
    }

    // Mark unanswered questions with visual indicator
    function markUnansweredQuestions(unansweredWrappers) {
        // First, remove all unanswered marks
        var allWrappers = document.querySelectorAll('.quiz-question-wrapper');
        allWrappers.forEach(function(wrapper) {
            wrapper.classList.remove('unanswered');
        });
        
        // Then, add unanswered class to specified wrappers
        unansweredWrappers.forEach(function(wrapper) {
            wrapper.classList.add('unanswered');
        });
    }

    // Update score display elements
    function updateScoreDisplays(scores) {
        var totalCat1 = document.getElementById('total-' + cat1Slug);
        var totalCat2 = document.getElementById('total-' + cat2Slug);
        var totalCat3 = document.getElementById('total-' + cat3Slug);
        
        if (totalCat1) totalCat1.textContent = scores.category1;
        if (totalCat2) totalCat2.textContent = scores.category2;
        if (totalCat3) totalCat3.textContent = scores.category3;
    }

    // Highlight the winning category
    function highlightWinningCategory(scores) {
        var successMessage1 = document.querySelector('.success-messages__category-1');
        var successMessage2 = document.querySelector('.success-messages__category-2');
        var successMessage3 = document.querySelector('.success-messages__category-3');
        
        // Remove all winning classes first
        if (successMessage1) successMessage1.classList.remove('winnerwinner-chickendinner');
        if (successMessage2) successMessage2.classList.remove('winnerwinner-chickendinner');
        if (successMessage3) successMessage3.classList.remove('winnerwinner-chickendinner');
        
        // Add winning class to highest score
        if (scores.category1 > scores.category2 && scores.category1 > scores.category3) {
            if (successMessage1) successMessage1.classList.add('winnerwinner-chickendinner');
        } else if (scores.category2 > scores.category1 && scores.category2 > scores.category3) {
            if (successMessage2) successMessage2.classList.add('winnerwinner-chickendinner');
        } else if (scores.category3 > scores.category2 && scores.category3 > scores.category1) {
            if (successMessage3) successMessage3.classList.add('winnerwinner-chickendinner');
        }
    }

    // Validate that all questions have been answered
    // Returns an array of unanswered question wrappers
    function validateAllQuestionsAnswered() {
        var unansweredWrappers = [];
        
        // Check radio button groups
        var radioWrappers = quizForm.querySelectorAll('.quiz-question-wrapper[data-question-type="radio"]');
        radioWrappers.forEach(function(wrapper) {
            var radioName = wrapper.dataset.questionName;
            var radios = wrapper.querySelectorAll('input[type="radio"]');
            var hasChecked = false;
            
            radios.forEach(function(radio) {
                if (radio.checked) {
                    hasChecked = true;
                }
            });
            
            if (!hasChecked) {
                unansweredWrappers.push(wrapper);
            }
        });

        // Check select dropdowns (must have a non-empty value)
        var selectWrappers = quizForm.querySelectorAll('.quiz-question-wrapper[data-question-type="select"]');
        selectWrappers.forEach(function(wrapper) {
            var select = wrapper.querySelector('select.quiz-question');
            if (select && (!select.value || select.value === '')) {
                unansweredWrappers.push(wrapper);
            }
        });

        return unansweredWrappers;
    }

    // Helper function to convert hyphenated name to camelCase for dataset API
    function toCamelCase(str) {
        return str.replace(/-([a-z])/g, function(match, letter) {
            return letter.toUpperCase();
        });
    }

    // Calculate scores based on selected answers
    function calculateScores() {
        var totalCategory1 = score;
        var totalCategory2 = score;
        var totalCategory3 = score;

        // Select dropdowns
        var selects = document.querySelectorAll('select option:checked');
        selects.forEach(function(option) {
            totalCategory1 += parseFloat(option.dataset[cat1Key] || 0);
            totalCategory2 += parseFloat(option.dataset[cat2Key] || 0);
            totalCategory3 += parseFloat(option.dataset[cat3Key] || 0);
        });

        // Radio buttons
        var radios = document.querySelectorAll('input[type=radio]:checked');
        radios.forEach(function(radio) {
            totalCategory1 += parseFloat(radio.dataset[cat1Key] || 0);
            totalCategory2 += parseFloat(radio.dataset[cat2Key] || 0);
            totalCategory3 += parseFloat(radio.dataset[cat3Key] || 0);
        });

        // Checkboxes
        var checkboxes = document.querySelectorAll('input[type=checkbox]:checked');
        checkboxes.forEach(function(checkbox) {
            totalCategory1 += parseFloat(checkbox.dataset[cat1Key] || 0);
            totalCategory2 += parseFloat(checkbox.dataset[cat2Key] || 0);
            totalCategory3 += parseFloat(checkbox.dataset[cat3Key] || 0);
        });

        // Range sliders - each range slider is assigned to a specific category
        var ranges = document.querySelectorAll('input[type=range]');
        ranges.forEach(function(range) {
            var rangeCategory = range.dataset.rangeCategory;
            var value = parseFloat(range.value);
            
            if (rangeCategory === 'category_1') {
                totalCategory1 += value;
            } else if (rangeCategory === 'category_2') {
                totalCategory2 += value;
            } else if (rangeCategory === 'category_3') {
                totalCategory3 += value;
            }
        });

        return {
            category1: totalCategory1,
            category2: totalCategory2,
            category3: totalCategory3
        };
    }

    // Generate the Venn diagram
    function generateVennDiagram(scores) {
        var vennDiv = document.getElementById("venn");
        if (!vennDiv) return;
        
        // Clear existing diagram
        vennDiv.innerHTML = '';

        var totalScore = scores.category1 + scores.category2 + scores.category3;
        var intersect = totalScore / 20;

        // Get the actual category labels from results overlay
        var labels = resultsOverlay.querySelectorAll('b');
        var cat1Label = labels[0] ? labels[0].textContent.replace(':', '').trim() : 'Category 1';
        var cat2Label = labels[1] ? labels[1].textContent.replace(':', '').trim() : 'Category 2';
        var cat3Label = labels[2] ? labels[2].textContent.replace(':', '').trim() : 'Category 3';

        var sets = [ 
            {sets: [cat1Label], size: scores.category1},
            {sets: [cat2Label], size: scores.category2},
            {sets: [cat3Label], size: scores.category3},
            {sets: [cat1Label, cat2Label], size: intersect},
            {sets: [cat1Label, cat3Label], size: intersect},
            {sets: [cat2Label, cat3Label], size: intersect},
            {sets: [cat1Label, cat2Label, cat3Label], size: intersect}
        ];
    
        var chart = venn.VennDiagram();
        var width = 230;
        var height = 250;
        
        d3.select("#venn")
            .datum(sets)
            .call(chart.height(height).width(width));
        
        var vennSvg = vennDiv.children[0];
        if (vennSvg) {
            vennDiv.setAttribute("class", "svg-container oneten-height");
            vennSvg.removeAttribute("height");
            vennSvg.removeAttribute("width");
            vennSvg.setAttribute("viewBox", '0 0 ' + width + ' ' + height);
            vennSvg.setAttribute("preserveAspectRatio", "xMaxYMin meet");
            vennSvg.setAttribute("class", "svg-content-responsive");
        }
    }

});

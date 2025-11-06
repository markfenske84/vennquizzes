(function(blocks, element, blockEditor, components, data) {
    var el = element.createElement;
    var SelectControl = components.SelectControl;
    var useSelect = data.useSelect;
    var Fragment = element.Fragment;
    var InspectorControls = blockEditor.InspectorControls;
    var PanelBody = components.PanelBody;

    blocks.registerBlockType('vennquizzes/venn-quiz', {
        title: 'Venn Quiz',
        description: 'Display an interactive Venn diagram quiz',
        icon: 'chart-pie',
        category: 'widgets',
        keywords: ['quiz', 'venn', 'diagram', 'survey'],
        supports: {
            align: ['wide', 'full'],
            html: false
        },
        attributes: {
            quizId: {
                type: 'number',
                default: 0
            }
        },

        edit: function(props) {
            var quizId = props.attributes.quizId;

            function setQuizId(value) {
                props.setAttributes({ quizId: parseInt(value) });
            }

            // Get all quizzes
            var quizzes = useSelect(function(select) {
                return select('core').getEntityRecords('postType', 'venn_quiz', {
                    per_page: -1,
                    status: 'publish'
                });
            }, []);

            // Get selected quiz details
            var selectedQuiz = useSelect(function(select) {
                if (!quizId) return null;
                return select('core').getEntityRecord('postType', 'venn_quiz', quizId);
            }, [quizId]);

            // Build options array
            var options = [{ label: 'Select a quiz...', value: 0 }];
            if (quizzes) {
                quizzes.forEach(function(quiz) {
                    options.push({
                        label: quiz.title.rendered,
                        value: quiz.id
                    });
                });
            }

            // Show loading state
            if (!quizzes) {
                return el(
                    'div',
                    { className: 'venn-quiz-loading' },
                    el('div', { className: 'venn-quiz-loading__spinner' }),
                    el('div', { className: 'venn-quiz-loading__text' }, 'Loading quizzes...')
                );
            }

            // Main block content
            var blockContent;
            
            if (quizId > 0 && selectedQuiz) {
                // Show preview when quiz is selected
                blockContent = el(
                    'div',
                    { className: 'venn-quiz-preview' },
                    el('div', { className: 'venn-quiz-preview__icon' }, '📊'),
                    el('div', { className: 'venn-quiz-preview__label' }, 'Venn Quiz'),
                    el('div', { className: 'venn-quiz-preview__title' }, selectedQuiz.title.rendered),
                    el('div', { className: 'venn-quiz-preview__description' }, 
                        'This quiz will be displayed on the frontend. Preview it by viewing the page.'
                    ),
                    el('div', { className: 'venn-quiz-preview__id' }, 'ID: ' + quizId)
                );
            } else {
                // Show empty state
                blockContent = el(
                    'div',
                    { className: 'venn-quiz-no-selection' },
                    el('div', { className: 'venn-quiz-no-selection__icon' }, '📊'),
                    el('div', { className: 'venn-quiz-no-selection__text' }, 
                        'No quiz selected'
                    ),
                    el('div', { className: 'venn-quiz-no-selection__help' }, 
                        'Choose a quiz from the settings panel on the right →'
                    )
                );
            }

            return el(
                Fragment,
                {},
                // Sidebar controls
                el(
                    InspectorControls,
                    {},
                    el(
                        PanelBody,
                        {
                            title: 'Quiz Settings',
                            initialOpen: true
                        },
                        el(SelectControl, {
                            label: 'Select Quiz',
                            value: quizId,
                            options: options,
                            onChange: setQuizId,
                            help: quizzes.length === 0 
                                ? 'No quizzes found. Create one first!' 
                                : 'Choose which quiz to display'
                        }),
                        quizId > 0 && el(
                            'div',
                            { 
                                style: { 
                                    marginTop: '16px',
                                    padding: '12px',
                                    background: '#f0f6fc',
                                    borderRadius: '4px',
                                    borderLeft: '4px solid #2271b1'
                                }
                            },
                            el('div', { 
                                style: { 
                                    fontSize: '13px', 
                                    marginBottom: '8px',
                                    fontWeight: '600'
                                } 
                            }, '✓ Quiz Selected'),
                            el('div', { 
                                style: { 
                                    fontSize: '12px', 
                                    color: '#646970' 
                                } 
                            }, 'The quiz will display on the frontend. You can preview it by viewing the page.')
                        )
                    )
                ),
                // Main block display
                el(
                    'div',
                    { className: 'venn-quiz-block-editor' },
                    blockContent
                )
            );
        },

        save: function() {
            // Return null since we use render_callback for dynamic rendering
            return null;
        }
    });
})(
    window.wp.blocks,
    window.wp.element,
    window.wp.blockEditor || window.wp.editor,
    window.wp.components,
    window.wp.data
);

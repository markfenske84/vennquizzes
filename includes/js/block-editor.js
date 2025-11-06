(function(blocks, element, editor, components, data) {
    var el = element.createElement;
    var SelectControl = components.SelectControl;
    var useSelect = data.useSelect;

    blocks.registerBlockType('vennquizzes/venn-quiz', {
        title: 'Venn Quiz',
        icon: 'chart-pie',
        category: 'widgets',
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

            return el(
                'div',
                { className: 'venn-quiz-block-editor' },
                el(SelectControl, {
                    label: 'Select Quiz',
                    value: quizId,
                    options: options,
                    onChange: setQuizId,
                    help: 'Choose which quiz to display'
                }),
                quizId > 0 && el(
                    'div',
                    { className: 'components-placeholder' },
                    el('div', { className: 'components-placeholder__label' }, 'Venn Quiz'),
                    el('div', { className: 'components-placeholder__instructions' }, 
                        'Quiz ID: ' + quizId + ' - Preview available on frontend'
                    )
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
    window.wp.editor,
    window.wp.components,
    window.wp.data
);


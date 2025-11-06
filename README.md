# VennQuizzes

An interactive WordPress plugin that creates Venn diagram quizzes to help users visualize data and determine where their focus should be.

Version: 1.1.0

Author: Webfor

Repository: https://github.com/markfenske84/vennquizzes

## Description

VennQuizzes allows you to create engaging, interactive quizzes that use Venn diagrams to visualize results. Users answer a series of questions, and based on their responses, a Venn diagram is generated showing the overlap between three customizable categories.

## Features

- Create unlimited quizzes with custom questions
- Three question types: Radio (single choice), Checkbox (multiple choice), and Range sliders
- Customize category labels for each quiz
- Dynamic Venn diagram visualization using D3.js
- Customizable success messages for each category
- Gutenberg block support
- Shortcode support `[venn_quiz id="123"]`
- Automatic plugin updates from GitHub
- No external dependencies (uses native WordPress functionality)

## Installation

1. Download the plugin ZIP file or clone this repository
2. Upload to your `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Start creating quizzes under the 'Quizs' menu item in the admin dashboard

## Usage

### Creating a Quiz

1. Navigate to **Quizs > Add New** in your WordPress admin
2. Enter a title for your quiz
3. Configure the **Category Labels** (3 categories that will appear in the Venn diagram)
4. Add **Quiz Questions** with answers and scores for each category
5. Customize the **Success Messages** that appear based on results
6. Publish your quiz

### Displaying a Quiz

**Using Shortcode:**
```
[venn_quiz id="123"]
```

**Using Gutenberg Block:**
Add the "Venn Quiz" block in the block editor and select your quiz from the dropdown.

## Question Types

1. **Radio (Single Choice)** - User selects one answer
2. **Checkbox (Multiple Choice)** - User can select multiple answers
3. **Range Slider** - User adjusts a slider from 0-100 for a specific category

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher

## Automatic Updates

This plugin uses the [Plugin Update Checker](https://github.com/YahnisElsts/plugin-update-checker) library to automatically check for updates from the GitHub repository. When a new version is released, you'll see an update notification in your WordPress admin.

## Developer Information

### File Structure

```
vennquizzes/
├── vennquizzes.php              # Main plugin file
├── includes/
│   ├── quiz-post-type.php       # Custom post type and meta boxes
│   ├── block-editor.php         # Gutenberg block registration
│   ├── css/
│   │   ├── admin-quiz.css       # Admin styling
│   │   └── style.css            # Frontend styling
│   ├── js/
│   │   ├── admin-quiz.js        # Admin JavaScript
│   │   ├── block-editor.js      # Gutenberg block JavaScript
│   │   ├── quizcalc.js          # Quiz calculation logic
│   │   └── venn.js/             # Venn.js library
│   └── partials/
│       └── quiz-form.php        # Frontend quiz form template
└── plugin-update-checker/       # Auto-update library
```

### Hooks and Filters

The plugin provides several WordPress hooks you can use:

- `venn_quiz_enqueue_scripts` - Enqueues frontend scripts and styles
- `register_activation_hook` - Runs on plugin activation
- `register_deactivation_hook` - Runs on plugin deactivation

### Custom Post Type

The plugin registers a custom post type called `venn_quiz` with the following meta fields:

- `_venn_category_1_label` - Category 1 label
- `_venn_category_2_label` - Category 2 label
- `_venn_category_3_label` - Category 3 label
- `_venn_quiz_questions` - Array of questions and answers
- `_venn_success_message_1` - Success message for category 1
- `_venn_success_message_2` - Success message for category 2
- `_venn_success_message_3` - Success message for category 3

## Contributing

Contributions are welcome! Please submit pull requests to the GitHub repository.

## License

This plugin is free software and may be redistributed under the terms of the GPL v2 or later.

## Support

For support, please open an issue on the [GitHub repository](https://github.com/markfenske84/vennquizzes/issues).

## Changelog

### 1.1.0
- Major UI overhaul with modern, style-agnostic design
- Enhanced Gutenberg block editor with beautiful previews
- Improved admin interface with better visual hierarchy
- Block theme support (alignwide, alignfull)
- Custom-styled form elements (radio, checkbox, range sliders)
- Smooth animations and transitions
- Better mobile responsiveness
- Improved accessibility with proper focus states
- Print-friendly styles for quiz results
- Enhanced results overlay with backdrop blur

### 1.0.0
- Initial release
- Custom post type for quizzes
- Three question types (radio, checkbox, range)
- Venn diagram visualization
- Gutenberg block support
- Shortcode support
- Automatic updates from GitHub

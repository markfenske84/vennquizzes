# VennQuizzes Plugin - Complete Documentation

Version 1.0.0

---

## Table of Contents

**For Non-Technical Users:**
- [What is VennQuizzes?](#what-is-vennquizzes)
- [Installation Guide](#installation-guide)
- [Creating Your First Quiz](#creating-your-first-quiz)
- [Adding the Quiz to Your Website](#adding-the-quiz-to-your-website)
- [Understanding Question Types](#understanding-question-types)
- [Customizing Success Messages](#customizing-success-messages)
- [Frequently Asked Questions](#frequently-asked-questions)

**For Developers:**
- [Technical Overview](#technical-overview)
- [Development Setup](#development-setup)
- [File Structure](#file-structure)
- [Hooks and Filters](#hooks-and-filters)
- [Custom Post Type Details](#custom-post-type-details)
- [JavaScript Architecture](#javascript-architecture)
- [Styling and Customization](#styling-and-customization)
- [Contributing Guidelines](#contributing-guidelines)

---

## For Non-Technical Users

### What is VennQuizzes?

VennQuizzes is a WordPress plugin that helps you create interactive quizzes with beautiful Venn diagram visualizations. Your users answer questions, and the plugin generates a Venn diagram showing which of three categories they should focus on.

**Perfect for:**
- Business consultants helping clients identify focus areas
- Educational quizzes to show subject strengths
- Marketing tools to guide customers to products
- Personality assessments with visual results

**Example Use Case:**  
A marketing consultant creates a quiz asking clients about their business goals, current strategies, and challenges. Based on their answers, the quiz shows whether they should focus on Search Marketing, Social Media, or Direct Sales, visualized in an interactive Venn diagram.

---

### Installation Guide

**Step 1: Download the Plugin**
- Go to https://github.com/markfenske84/vennquizzes
- Click the green "Code" button
- Select "Download ZIP"

**Step 2: Install in WordPress**
1. Log into your WordPress admin panel
2. Navigate to **Plugins > Add New**
3. Click **Upload Plugin** at the top
4. Click **Choose File** and select the downloaded ZIP file
5. Click **Install Now**
6. After installation completes, click **Activate Plugin**

**Step 3: Verify Installation**
- Look for "Quizs" in your WordPress admin menu (left sidebar)
- If you see it, installation was successful!

---

### Creating Your First Quiz

**Step 1: Start a New Quiz**
1. In your WordPress admin, click **Quizs > Add New**
2. Enter a title for your quiz (e.g., "Marketing Focus Quiz")

**Step 2: Set Up Your Three Categories**

Scroll down to the "Category Labels" section. These are the three categories that will appear in your Venn diagram.

Example:
- Category 1: Search Marketing
- Category 2: Social Media
- Category 3: Direct Sales

**Step 3: Add Your Questions**

In the "Quiz Questions" section, you'll see one blank question by default.

For each question:
1. **Question Text**: Enter your question (e.g., "What's your primary business goal?")
2. **Answer Type**: Choose from three options (explained below)
3. **Answers**: Add 2-4 possible answers
4. **Scores**: For each answer, set scores (0-100) for each category

**Example Question:**
- Question: "What's your current biggest challenge?"
- Answer Type: Radio (Single Choice)
- Answers:
  - "Getting found online" → Search: 30, Social: 5, Direct: 5
  - "Building relationships" → Search: 5, Social: 30, Direct: 10
  - "Converting leads" → Search: 10, Social: 10, Direct: 30

**Adding More Questions:**
- Click "Add Question" button to add additional questions
- Click "Duplicate Question" to copy an existing question
- Click "Remove Question" to delete a question

**Step 4: Customize Success Messages**

Scroll to the "Success Messages" section. These messages appear when one category has the highest score.

You can use `{category_1}`, `{category_2}`, or `{category_3}` as placeholders:

Example:
- Message for Category 1: "Great! Based on your answers, you should focus on {category_1}. This will help you reach your goals faster."

**Step 5: Publish Your Quiz**

Click the blue **Publish** button in the top right corner.

**Step 6: Get Your Shortcode**

After publishing, look at the right sidebar for the "Shortcode" box. It will show something like:

```
[venn_quiz id="123"]
```

Copy this shortcode - you'll need it to display your quiz!

---

### Adding the Quiz to Your Website

You have two options for displaying your quiz:

**Option 1: Using Shortcode (Any Page/Post)**

1. Edit the page or post where you want the quiz
2. If using the Classic Editor:
   - Simply paste the shortcode: `[venn_quiz id="123"]`
3. If using Gutenberg (Block Editor):
   - Add a "Shortcode" block
   - Paste your shortcode into the block

**Option 2: Using the Gutenberg Block**

1. Edit your page in the Block Editor
2. Click the + icon to add a block
3. Search for "Venn Quiz"
4. Select the block
5. Use the dropdown menu in the block to select your quiz

---

### Understanding Question Types

**1. Radio (Single Choice)**

Best for: Questions where only one answer applies

How it works:
- User can select only one answer
- That answer's scores are added to the totals

Example: "What's your primary goal?"

**2. Checkbox (Multiple Choice)**

Best for: Questions where multiple answers might apply

How it works:
- User can select multiple answers
- All selected answers' scores are added to the totals

Example: "Which platforms do you currently use? (Check all that apply)"

**3. Range Slider**

Best for: Rating scales or intensity questions

How it works:
- User drags a slider from 0 to 100
- The slider value is added to ONE category only
- Choose which category in the "Range Slider Category" dropdown

Example: "How important is SEO to your business? (0 = not important, 100 = very important)"

---

### Customizing Success Messages

Success messages appear after users complete the quiz, showing them which category they should focus on.

**Using Placeholders:**

You can use these placeholders in your messages:
- `{category_1}` - Replaced with your Category 1 label
- `{category_2}` - Replaced with your Category 2 label  
- `{category_3}` - Replaced with your Category 3 label

**Example:**

If Category 1 is "Search Marketing", this message:

"Congratulations! Based on your answers, {category_1} is your top priority."

Becomes:

"Congratulations! Based on your answers, Search Marketing is your top priority."

**Tips for Great Success Messages:**
- Keep them positive and actionable
- Explain WHY this category matters for them
- Provide next steps or resources
- Keep it under 100 words for readability

---

### Frequently Asked Questions

**Q: How many quizzes can I create?**  
A: Unlimited! Create as many quizzes as you need.

**Q: Can I edit a quiz after publishing?**  
A: Yes, just go to **Quizs > All Quizs**, click the quiz title, make your changes, and click Update.

**Q: How do I delete a quiz?**  
A: Go to **Quizs > All Quizs**, hover over the quiz title, and click "Trash".

**Q: Can I have more or fewer than 3 categories?**  
A: Currently, the plugin is designed for exactly 3 categories (it's a Venn diagram with 3 circles). Future versions may support different numbers.

**Q: What happens if two categories have the same score?**  
A: The Venn diagram will show overlap, and the success message for the first tied category will display.

**Q: Can I see how users answered my quiz?**  
A: Not in the current version. This is a feature planned for future releases.

**Q: Is the quiz mobile-friendly?**  
A: Yes! The quiz and Venn diagram work on all devices.

**Q: How do I update the plugin?**  
A: The plugin will automatically check for updates from GitHub. When an update is available, you'll see a notification in your WordPress admin under **Plugins**. Just click "Update Now".

---

## For Developers

### Technical Overview

VennQuizzes is a modern WordPress plugin built with:

**Backend:**
- Native WordPress Custom Post Types (no external dependencies)
- Custom meta boxes for quiz configuration
- REST API enabled for Gutenberg integration

**Frontend:**
- D3.js v4 for Venn diagram rendering
- Venn.js library for diagram calculations
- Vanilla JavaScript (no jQuery dependency)
- Responsive CSS

**Updates:**
- Plugin Update Checker library for GitHub-based automatic updates

**Key Features:**
- No database schema modifications (uses post meta)
- Gutenberg block support
- Shortcode support
- Vanilla JS admin interface (jQuery-free)
- Modular file structure

---

### Development Setup

**Prerequisites:**
- WordPress 5.0+
- PHP 7.0+
- Git
- Text editor or IDE

**Local Development Setup:**

1. Clone the repository:
```bash
git clone git@github.com:markfenske84/vennquizzes.git
cd vennquizzes
```

2. Symlink or copy to your WordPress plugins directory:
```bash
ln -s $(pwd) /path/to/wordpress/wp-content/plugins/vennquizzes
```

3. Activate the plugin in WordPress admin

4. Create a test quiz to verify functionality

**Development Tools:**

The plugin doesn't require a build process. All JavaScript and CSS are written in vanilla formats.

For debugging:
- Enable WordPress debug mode: `define('WP_DEBUG', true);` in wp-config.php
- Check browser console for JavaScript errors
- Use WordPress Debug Bar plugin for PHP debugging

---

### File Structure

```
vennquizzes/
├── vennquizzes.php                 # Main plugin file (entry point)
│
├── includes/
│   ├── quiz-post-type.php          # Custom post type registration and meta boxes
│   ├── block-editor.php            # Gutenberg block registration
│   │
│   ├── css/
│   │   ├── admin-quiz.css          # Admin area styling
│   │   └── style.css               # Frontend quiz styling
│   │
│   ├── js/
│   │   ├── admin-quiz.js           # Admin interface logic
│   │   ├── block-editor.js         # Gutenberg block editor
│   │   ├── quizcalc.js             # Quiz calculation and result rendering
│   │   └── venn.js/                # Venn.js library (external)
│   │
│   └── partials/
│       └── quiz-form.php           # Frontend quiz HTML template
│
├── plugin-update-checker/          # Auto-update library
│
├── README.md                        # GitHub repository readme
├── DOCUMENTATION.md                 # This file
└── .gitignore                       # Git ignore rules
```

**Key File Responsibilities:**

**vennquizzes.php**
- Plugin headers and constants
- Script/style enqueueing
- Shortcode registration
- Main plugin class initialization
- Plugin update checker initialization

**includes/quiz-post-type.php**
- Registers 'venn_quiz' custom post type
- Creates admin meta boxes (categories, questions, messages)
- Handles saving quiz data to post meta
- Renders admin interface HTML

**includes/block-editor.php**
- Registers Gutenberg block
- Provides block rendering callback
- Handles block attributes

**includes/partials/quiz-form.php**
- Frontend quiz form HTML generation
- Outputs quiz questions based on stored data
- Provides data attributes for JavaScript

**includes/js/quizcalc.js**
- Calculates scores based on user answers
- Generates Venn diagram data
- Displays results overlay
- Handles print functionality

**includes/js/admin-quiz.js**
- Dynamic question/answer addition and removal
- Question duplication functionality
- Answer type switching (radio/checkbox/range)
- Admin UI interactivity

---

### Hooks and Filters

**Actions:**

`init` (priority 10)
- Registers custom post type
- Registers Gutenberg block

`add_meta_boxes` (priority 10)
- Adds quiz configuration meta boxes

`save_post_venn_quiz` (priority 10, 2 args)
- Saves quiz meta data

`admin_enqueue_scripts` (priority 10)
- Enqueues admin scripts and styles

**Filters:**

Currently no custom filters are implemented. Future versions may add:
- `vennquiz_question_types` - Modify available question types
- `vennquiz_success_message` - Filter success message before display
- `vennquiz_score_calculation` - Customize score calculation

**Custom Hooks for Extending:**

To add custom functionality, you can hook into WordPress actions:

```php
// Modify quiz data before saving
add_action('save_post_venn_quiz', 'my_custom_quiz_save', 20, 2);
function my_custom_quiz_save($post_id, $post) {
    // Your custom logic here
}

// Modify frontend scripts
add_action('wp_enqueue_scripts', 'my_custom_quiz_scripts', 20);
function my_custom_quiz_scripts() {
    // Your custom scripts here
}
```

---

### Custom Post Type Details

**Post Type:** `venn_quiz`

**Supports:**
- title

**Capabilities:**
- Uses default 'post' capabilities

**REST API:**
- Enabled (`show_in_rest: true`)

**Meta Fields:**

All meta fields are prefixed with `_venn_` and stored serialized:

```php
// Category labels (text)
_venn_category_1_label
_venn_category_2_label
_venn_category_3_label

// Success messages (text with HTML allowed)
_venn_success_message_1
_venn_success_message_2
_venn_success_message_3

// Questions (serialized array)
_venn_quiz_questions
```

**Questions Array Structure:**

```php
array(
    array(
        'question_text' => 'Your question here',
        'answer_type' => 'radio', // 'radio', 'checkbox', or 'range'
        
        // For radio/checkbox types:
        'answers' => array(
            array(
                'answer_text' => 'Answer option',
                'score_1' => 10,  // Category 1 score
                'score_2' => 5,   // Category 2 score
                'score_3' => 0    // Category 3 score
            ),
            // ... more answers
        ),
        
        // For range type:
        'range_category' => 'category_1' // 'category_1', 'category_2', or 'category_3'
    ),
    // ... more questions
)
```

**Retrieving Quiz Data:**

```php
$quiz_id = 123;

// Get category labels
$cat1 = get_post_meta($quiz_id, '_venn_category_1_label', true);
$cat2 = get_post_meta($quiz_id, '_venn_category_2_label', true);
$cat3 = get_post_meta($quiz_id, '_venn_category_3_label', true);

// Get questions
$questions = get_post_meta($quiz_id, '_venn_quiz_questions', true);

// Get success messages
$msg1 = get_post_meta($quiz_id, '_venn_success_message_1', true);
$msg2 = get_post_meta($quiz_id, '_venn_success_message_2', true);
$msg3 = get_post_meta($quiz_id, '_venn_success_message_3', true);
```

---

### JavaScript Architecture

**Frontend (quizcalc.js):**

The quiz calculator is initialized on DOM load and handles:

1. **Score Calculation:**
   - Listens for "Get Results" button click
   - Iterates through all questions
   - Sums scores based on answer type
   - Handles radio, checkbox, and range inputs differently

2. **Venn Diagram Rendering:**
   - Uses D3.js and Venn.js libraries
   - Calculates set sizes based on scores
   - Renders interactive SVG diagram
   - Applies hover effects

3. **Results Display:**
   - Shows overlay with scores
   - Displays appropriate success message
   - Provides print functionality

**Key Functions:**

```javascript
// Calculate total scores for all categories
function calculateScores()

// Render the Venn diagram
function renderVennDiagram(scores)

// Show the results overlay
function showResults()

// Handle print button
function printResults()
```

**Admin (admin-quiz.js):**

Handles dynamic admin interface:

1. **Question Management:**
   - Add new questions
   - Remove questions
   - Duplicate questions
   - Re-number questions

2. **Answer Management:**
   - Add answers to questions
   - Remove answers
   - Duplicate answers
   - Enforce 4-answer limit

3. **Answer Type Switching:**
   - Toggle between radio/checkbox/range
   - Show/hide relevant fields

**Event Handling:**

Uses event delegation for dynamically added elements:

```javascript
document.addEventListener('click', function(e) {
    if (e.target.matches('.add-question')) {
        // Handle add question
    }
    // ... more handlers
});
```

---

### Styling and Customization

**Customizing Quiz Appearance:**

The plugin provides CSS classes you can override in your theme:

**Frontend Classes:**

```css
.venn-quiz-form { }                    /* Quiz container */
.quiz-question-wrapper { }             /* Question wrapper */
.quiz-question { }                     /* Question input */
.get-results { }                       /* Submit button */
.results-overlay { }                   /* Results overlay */
.success-messages { }                  /* Success message */
.venn-diag { }                         /* Venn diagram container */
```

**Example Custom Styling:**

```css
/* Make the submit button larger and blue */
.get-results {
    background-color: #0073aa;
    color: white;
    padding: 15px 30px;
    font-size: 18px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
}

/* Customize the Venn diagram size */
.venn-diag {
    max-width: 600px;
    margin: 0 auto;
}

/* Style the success message */
.success-messages {
    background-color: #f0f8ff;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #0073aa;
}
```

**Customizing Admin Interface:**

Admin styles can be overridden but will be reset on plugin update. Consider using a separate plugin for permanent admin customizations:

```php
add_action('admin_enqueue_scripts', 'my_custom_admin_styles');
function my_custom_admin_styles($hook) {
    if ($hook == 'post.php' || $hook == 'post-new.php') {
        global $post_type;
        if ($post_type == 'venn_quiz') {
            wp_enqueue_style('my-custom-admin', 
                get_stylesheet_directory_uri() . '/custom-admin.css');
        }
    }
}
```

---

### Contributing Guidelines

We welcome contributions! Here's how to contribute:

**Reporting Issues:**

1. Check existing issues on GitHub
2. Create a new issue with:
   - Clear description of the problem
   - Steps to reproduce
   - Expected vs actual behavior
   - WordPress version, PHP version
   - Browser (for frontend issues)

**Submitting Pull Requests:**

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/my-new-feature`
3. Make your changes
4. Test thoroughly
5. Commit with clear messages: `git commit -am 'Add new feature'`
6. Push to your fork: `git push origin feature/my-new-feature`
7. Submit a Pull Request

**Code Standards:**

- Follow WordPress Coding Standards
- Use meaningful variable and function names
- Comment complex logic
- Maintain backward compatibility
- Test on multiple WordPress versions

**Testing Checklist:**

Before submitting:
- [ ] Create a new quiz and verify it saves correctly
- [ ] Test all three question types
- [ ] Verify Venn diagram renders correctly
- [ ] Test shortcode on a page
- [ ] Test Gutenberg block
- [ ] Test on mobile devices
- [ ] Check browser console for errors
- [ ] Verify no PHP warnings/errors

**Version Control:**

When releasing new versions:

1. Update version number in `vennquizzes.php`
2. Update `VENNQUIZ_VERSION` constant
3. Add changelog entry to README.md
4. Commit: `git commit -am "Release v1.1.0"`
5. Tag: `git tag -a v1.1.0 -m "Version 1.1.0"`
6. Push: `git push origin main --tags`

The Plugin Update Checker will automatically notify users of the new version.

---

### Development Roadmap

**Future Features Under Consideration:**

- Analytics/tracking of quiz responses
- Export quiz results to CSV
- Email results to users
- Support for 2 or 4+ categories
- Conditional logic (skip questions based on previous answers)
- Quiz templates/presets
- Import/export quizzes
- Quiz result permalinks
- Social sharing of results
- A/B testing different success messages

**Performance Improvements:**

- Lazy load Venn.js library
- Minify JavaScript and CSS
- Cache quiz data for better performance
- Optimize admin interface for quizzes with many questions

---

### API Reference

**Shortcode:**

```php
[venn_quiz id="123"]
```

Parameters:
- `id` (required): The post ID of the quiz to display

**Gutenberg Block:**

Block name: `vennquizzes/venn-quiz`

Attributes:
- `quizId` (number): The post ID of the quiz to display

**PHP Functions:**

```php
// Enqueue frontend scripts (called automatically)
venn_quiz_enqueue_scripts()

// Render quiz shortcode
venn_quiz_shortcode($atts)
```

---

### Troubleshooting

**Common Issues:**

**Issue: Quiz doesn't appear on page**
- Solution: Verify the quiz ID in your shortcode matches an actual published quiz
- Check browser console for JavaScript errors
- Ensure the plugin is activated

**Issue: Venn diagram doesn't render**
- Solution: Check that D3.js is loading (view page source)
- Verify no JavaScript conflicts with other plugins
- Test with a default WordPress theme

**Issue: Scores are incorrect**
- Solution: Check that each answer has scores set for all three categories
- Verify that scores are numbers, not text
- Check browser console for calculation errors

**Issue: Plugin updates don't appear**
- Solution: The plugin checks for updates every 12 hours
- Try deactivating and reactivating the plugin
- Verify the GitHub repository is accessible

**Issue: Admin interface not loading**
- Solution: Check for JavaScript errors in browser console
- Try disabling other plugins to check for conflicts
- Clear browser cache

---

### Support and Contact

**Getting Help:**

- GitHub Issues: https://github.com/markfenske84/vennquizzes/issues
- Email: support@webfor.com

**Commercial Support:**

For priority support, custom development, or consulting:
- Website: https://webfor.com
- Email: hello@webfor.com

---

### License

VennQuizzes is free software, licensed under the GNU General Public License v2 or later.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see https://www.gnu.org/licenses/gpl-2.0.html

---

### Credits

**Developed by:** Webfor (https://webfor.com)

**Libraries Used:**
- D3.js v4 (https://d3js.org) - BSD License
- Venn.js (https://github.com/benfred/venn.js) - MIT License
- Plugin Update Checker (https://github.com/YahnisElsts/plugin-update-checker) - MIT License

**Special Thanks:**
- Ben Frederickson for the Venn.js library
- Mike Bostock for D3.js
- Yahnis Elsts for Plugin Update Checker

---

**Document Version:** 1.0.0  
**Last Updated:** November 6, 2025  
**Compatible with:** WordPress 5.0+ and VennQuizzes 1.0.0


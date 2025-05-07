# WordPress LearnDash Quiz Auto Solver

A tool designed to automate the process of solving LearnDash quizzes, primarily intended for QA testing purposes. This solution helps QA teams save significant time by automatically filling in quiz answers instead of manually entering them.

## Features

- **Universal Compatibility**: Works with various LearnDash quiz configurations including:
  - Random questions
  - Multiple page quizzes
  - Various question types

- **Supported Question Types**:
  - Single Choice Questions
  - Multiple Choice Questions
  - Fill in the Blank (Cloze)
  - Matrix Sort Questions
  - Sort Answer Questions

## How It Works

The plugin integrates with LearnDash's quiz interface and automatically:
1. Detects all questions in the current quiz
2. Retrieves correct answers from the backend
3. Automatically fills in the appropriate answers based on question type
4. Handles dynamic content and UI updates

## Usage

1. Install and activate the plugin
3. Edit author profile and add `LD TEST QA` role.
2. Navigate to the LearnDash course list page on admin side and click `Show Quiz Answers` to activate. 
3. The answers will be automatically filled in


## Note

This tool is specifically designed for QA testing purposes and should not be used in production environments. The tool does have QA role and activate/deactivate to safeguard from accidental enabling. 


## Support

For any issues or questions, please create an issue in the repository. 
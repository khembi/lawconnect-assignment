# LawConnect Take-Home Test

This application was developed as a take-home test for LawConnect, implementing a knowledge base about the Monopoly board game using the TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire).

## Features

The system allows users to:
- Create and manage topics about Monopoly
- Tag topics for better organization
- Search for information across the knowledge base
- Receive AI-generated suggestions when no matching topics are found (using PHP Prism)

## Technical Stack

### Core Stack (TALL)
- **T**ailwind CSS: Utility-first CSS framework
- **A**lpine.js: Lightweight JavaScript framework
- **L**aravel: PHP web application framework
- **L**ivewire: Full-stack framework for dynamic interfaces

### Additional Technologies
- Database: MySQL/PostgreSQL
- Testing: PHPUnit
- AI Integration: PHP Prism (supports multiple LLM providers)

## Setup Instructions

### Prerequisites
- PHP 8.1+
- Composer
- Node.js and npm
- SQLite or PostgreSQL

### Installation Steps
1. Clone the repository:
   ```bash
   git clone [repository-url]
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install JavaScript dependencies:
   ```bash
   npm install
   ```

4. Create a copy of the environment file:
   ```bash
   cp .env.example .env
   ```

5. Generate an application key:
   ```bash
   php artisan key:generate
   ```

6. Configure your database connection in the .env file:
   ```env
   DB_CONNECTION=sqlite
   ```

7. Configure your OpenAI API key in the .env file:
   ```env
   OPENAI_API_KEY=your_api_key
   ```

8. Run database migrations and seed the database:
   ```bash
   php artisan migrate:fresh --seed
   ```

9. Build frontend assets:
   ```bash
   npm run build
   ```

10. Start the development server:
    ```bash
    php artisan serve
    ```

11. Visit http://localhost:8000 in your browser

## Testing

The application includes comprehensive tests for models, controllers, services, and Livewire components.


# Run all tests
```bash
php artisan test
```

# Run specific test
```bash
php artisan test --filter=tests/Feature/Http/Controllers/TopicControllerTest.php
```


## Technical Implementation

### Architecture
- **Service Layer Pattern**: Implemented a service layer (TopicService.php) to separate business logic from controllers
- **Livewire Components**: Used for interactive features like search (SearchTopic.php) and AI suggestions (SuggestTopic.php)
- **Topic Status System**: Added draft/published status for content preparation
- **AI Integration**: Utilized PHP Prism for flexible LLM provider integration

### SOLID Principles Implementation

#### Single Responsibility Principle (S)
- **TopicService**: Handles only topic-related business logic
- Each Livewire component has a single purpose (SearchTopic for search, SuggestTopic for AI suggestions)

#### Open/Closed Principle (O)
- **PHP Prism Integration**: System is open for extension to support new LLM providers without modifying existing code
- **Tag System**: New tag types can be added without changing the core tagging logic

#### Liskov Substitution Principle (L)
- **Topic Model**: Extends base Model class while maintaining expected behavior
- **Tag Model**: Can be used anywhere the base Model is expected

#### Interface Segregation Principle (I)
- **Taggable Interface**: Topics implement only the tag-related methods they need
- **Searchable Interface**: Search functionality is separated into its own interface

#### Dependency Inversion Principle (D)
- **Service Injection**: Components depend on abstractions (interfaces) rather than concrete implementations
- **TopicService**: Injected into controllers and Livewire components through dependency injection

## Using the App

### Creating Topics
1. Click "Create a new topic" on the homepage
2. Fill in the topic name and content
3. Select relevant tags
4. Submit the form

### Searching for Information
1. Use the search bar at the top
2. Type keywords related to Monopoly
3. Click on a result to view the full topic

### Filtering by Tags
1. Click on any tag to see related topics
2. Results will show only relevant topics

### AI Suggestions
- Search for non-existing topics
- View AI-generated suggestions
- Get relevant Monopoly information even without exact matches

## Key Files and Their Roles

### Controllers and Services
- `app/Http/Controllers/TopicController.php`: CRUD operations
- `app/Services/TopicService.php`: Functional logic
- `app/Livewire/SearchTopic.php`: Interactive search
- `app/Livewire/SuggestTopic.php`: AI suggestions

### Views and Routes
- `routes/web.php`: Application routes
- `resources/views/pages/topic/edit.blade.php`: Topic editing
- `resources/views/components/tag.blade.php`: Tag component

### Tests
- `tests/Feature/Http/Controllers/TopicControllerTest.php`
- `tests/Unit/Services/TopicServiceTest.php`
- `tests/Feature/Livewire/SearchTopicTest.php`

## Conclusion

This project demonstrates a practical implementation of a knowledge base system with AI-enhanced capabilities, focusing on the Monopoly board game. The architecture follows Laravel best practices with clear separation of concerns and comprehensive test coverage.
<p align="center">
  <!-- Your App Logo or Banner (if available) -->
</p>

# Basic Social Media App API

This API provides functionalities for a basic social media application, including friend requests, acceptance and rejection, mutual friends, sorting by countries and states, and a structured foundation for future enhancements like shared post likes and comments.

## Overview

The API is designed to handle the following features:
- **Friend Management:** Allows users to send, accept, or reject friend requests.
- **Mutual Friends:** Provides functionality to find mutual friends between users.
- **Sorting by Geography:** Sorts users based on countries and states.
- **Extended Functionality Structure:** Includes a foundational structure for future enhancements such as shared post likes and comments, similar to the functionality seen in platforms like Facebook.

## Getting Started

### Prerequisites

- PHP installed on your system
- Composer for managing PHP dependencies
- Postman for testing the API endpoints

### Installation

1. Clone this repository to your local machine.
2. Run `composer install` in the project root directory to install PHP dependencies.

### Database Setup

1. Configure your database settings in the `.env` file.
2. Run `php artisan migrate --seed` to migrate the database tables and seed sample data.

## Usage
 
### Future Enhancements

The API structure lays the groundwork for extended features such as:
- **Shared Post Likes**: Option to implement likes on shared posts distinct from original posts.
- **Shared Post Comments**: Ability to comment on shared posts separately from original post comments.

## Postman Collection

Import the Postman collection from [this link](https://api.postman.com/collections/21322026-a2ab3bde-624c-4ace-8537-242f042b826a?access_key=PMAT-01HJJTD79S5FT9W73DNDJEA2Q6) to easily test the API endpoints.

## Contributing

Contributions are welcome! Fork this repository, make changes, and create a pull request. Let's improve this together.

## License

This project is licensed under the [MIT License](LICENSE).


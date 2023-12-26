Basic Social Media App API
This API serves as the foundation for a basic social media application, equipped with features like friend request management, mutual friend identification, and sorting capabilities based on location.

Setup
Installation
Clone the repository.
Run composer install to install dependencies.
Execute php artisan migrate --seed to initialize the database with sample data.
Endpoints
Friend Requests
Accept a Friend Request

Endpoint: POST /friend-request/accept/{request_id}
Description: Accepts a pending friend request.
Reject a Friend Request

Endpoint: POST /friend-request/reject/{request_id}
Description: Rejects a pending friend request.
Mutual Friends
Retrieve Mutual Friends
Endpoint: GET /friends/mutual/{user_id}
Description: Retrieves a list of mutual friends between two users.
Sorting
Sort Friends by Country

Endpoint: GET /friends/sort/country
Description: Sorts friends by their country of residence.
Sort Friends by State

Endpoint: GET /friends/sort/state
Description: Sorts friends by their state/province.
Postman Collection
Explore and interact with the API using the provided Postman Collection:
Postman Collection URL

Contributing
Contributions to this project are welcomed! Fork the repository, make your changes, and create a pull request for review.

License
This project is licensed under the MIT License.

Feel free to customize this further or add any specific details about your application. This README provides an overview of the API's functionalities and how to interact with 

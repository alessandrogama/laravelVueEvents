# LaravelVue Project

This project is a web application built using Laravel for the backend and Vue.js for the frontend.

## Requirements

- Docker
- Docker Compose

## Installation

1. Clone the repository:
    ```sh
    git clone https://github.com/yourusername/laravelvue.git
    ```
2. Navigate to the project directory:
    ```sh
    cd laravelvue
    ```
3. Copy the `.env.example` file to `.env` and configure your environment variables:
    ```sh
    cp .env.example .env
    ```
4. Build and start the Docker containers:
    ```sh
    docker-compose up --build
    ```
5. Install PHP dependencies inside the PHP container:
    ```sh
    docker-compose exec app composer install
    ```
6. Install JavaScript dependencies inside the Node container:
    ```sh
    docker-compose exec node npm install
    ```
    or
    ```sh
    docker-compose exec node yarn install
    ```
7. Generate an application key:
    ```sh
    docker-compose exec app php artisan key:generate
    ```
8. Run database migrations:
    ```sh
    docker-compose exec app php artisan migrate
    ```

## Usage

1. Start the Laravel development server:
    ```sh
    docker-compose exec app php artisan serve --host=0.0.0.0 --port=8000
    ```
2. Start the Vue.js development server:
    ```sh
    docker-compose exec node npm run dev
    ```
    or
    ```sh
    docker-compose exec node yarn dev
    ```

## Contributing

1. Fork the repository.
2. Create a new branch:
    ```sh
    git checkout -b feature/your-feature-name
    ```
3. Make your changes.
4. Commit your changes:
    ```sh
    git commit -m 'Add some feature'
    ```
5. Push to the branch:
    ```sh
    git push origin feature/your-feature-name
    ```
6. Open a pull request.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
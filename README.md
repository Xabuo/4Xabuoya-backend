# 4Xabuoya – Food Delivery E-Commerce

## Project Description

4Xabuoya is a food delivery e-commerce application developed using ReactJS. It allows users to browse products, view product details, and manage product records through API integration.

This project was developed for Activity 7: ReactJS Events, State & API Integration – Product Management with Axios and a REST API.

## Technologies Used

- ReactJS
- Vite
- JavaScript
- Material UI (MUI)
- Axios
- React Router
- Laravel REST API
- MySQL / MariaDB

## Requirements

- Node.js and npm
- PHP
- Composer
- MySQL or MariaDB
- Laravel backend

## Installation and Setup

1. Open the frontend project folder:

   `e-commerce-fe`

2. Install the dependencies:

   ```bash
   npm install
   ```

3. Create a `.env` file in the frontend root folder and configure the API URL:

   ```env
   VITE_API_BASE_URL=http://127.0.0.1:8000/api
   ```

4. Start the Laravel backend in the backend project folder:

   ```bash
   php artisan serve
   ```

5. Start the React frontend:

   ```bash
   npm run dev
   ```

6. Open the application in your browser:

   `http://localhost:5173`

## API Configuration

The frontend uses Axios to communicate with the Laravel REST API.

**API Base URL:**

`http://127.0.0.1:8000/api`

The API base URL is configured through the `VITE_API_BASE_URL` environment variable.

## Product API Endpoints

| Method | Endpoint | Description |
|---|---|---|
| GET | `/products` | Display all products |
| GET | `/products/{id}` | View a product |
| POST | `/products` | Create a product |
| PUT | `/products/{id}` | Update a product |
| DELETE | `/products/{id}` | Delete a product |

## API Integration Notes

- Axios is used for HTTP requests.
- React hooks (`useState` and `useEffect`) manage application state and API data.
- Product information is retrieved from the Laravel backend.
- Laravel API responses may contain product information inside a `data` property, which the frontend handles.
- Product fields returned by the API include `product_name`, `product_description`, `price`, `stock_level`, and `image_url`.
- Product creation, updating, and deletion use the corresponding REST API endpoints.
- Admin authentication is required for protected product management operations.

## Screenshots

The screenshots for Activity 7 are saved in the `screenshots` folder.

1. Product List
2. Product Details
3. Create Product Form
4. Delete Confirmation

## Author

4Xabuoya – ReactJS E-Commerce Project

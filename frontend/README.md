# Order Management System Frontend

## Description

This is the frontend for the Order Management System built using Vue 3 and Quasar v2. It provides a user-friendly interface to manage orders, allowing users to log in, view, search, and synchronize orders.

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Features](#features)
- [API Integration](#api-integration)
- [Contributing](#contributing)
- [License](#license)

## Requirements

- Node.js >= 18
- npm >= 6
- Vue.js >= 3
- Quasar Framework >= 2
- Pinia

## Installation

1. Clone the repository:

   ```bash
   git clone git@github.com:Harish120/matat-task.git
   cd matat-task/frontend
   ```

2. Install the dependencies:

   ```bash
   npm install
   ```

3. Set up your environment file:

   Create a `.env.dev` file in the root directory and add the API base URL:

   ```
   VUE_APP_API_URL=http://127.0.0.1:8000/api
   ```

4. Run the development server:

   ```bash
   npm run dev
      or
   quasar dev
   ```

5. Open your browser and navigate to `http://localhost:3000`.

## Usage

You can use the application to log in, view orders, and synchronize them with the backend API. Make sure the backend API is running and accessible.

## Features

- User authentication (login and logout)
- View and search orders
- Pagination for order listing
- Synchronization of orders with the backend API

## API Integration

The frontend communicates with the backend API for authentication and order management. Ensure the backend is set up as described in its `README.md` file.

### API Endpoints

- **Login**: `POST /login`
- **Register**: `POST /register`
- **Get Orders**: `GET /orders`
- **Sync Orders**: `POST /orders/sync`

## Contributing

Contributions are welcome! Please open an issue or submit a pull request for any changes you'd like to make.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

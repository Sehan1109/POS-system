# POS System

A comprehensive Point of Sale (POS) system built with Laravel, Livewire, and Tailwind CSS. This application provides a modern, responsive interface for managing sales, inventory, customers, suppliers, and more in a retail or business environment.

## Features

- **Product Management**: Add, edit, and manage products with categories, pricing, and stock levels
- **Sales Processing**: Real-time sales tracking with itemized receipts and customer management
- **Inventory Control**: Monitor stock levels, receive goods, and manage purchase orders
- **Customer Management**: Maintain customer records and transaction history
- **Supplier Management**: Track suppliers and manage procurement processes
- **Expense Tracking**: Record and categorize business expenses
- **User Management**: Role-based access control for different user types
- **Activity Logging**: Comprehensive audit trail of system activities
- **Responsive Design**: Mobile-friendly interface built with Tailwind CSS
- **Real-time Updates**: Livewire-powered dynamic components for seamless user experience

## Tech Stack

- **Backend**: Laravel 11.x
- **Frontend**: Livewire, Tailwind CSS
- **Database**: MySQL/PostgreSQL (configurable)
- **Build Tool**: Vite
- **Testing**: Pest PHP
- **Authentication**: Laravel Sanctum/Breeze

## Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18.x or higher
- NPM or Yarn
- MySQL/PostgreSQL database

### Setup Steps

1. **Clone the repository**

    ```bash
    git clone https://github.com/yourusername/pos-system.git
    cd pos-system
    ```

2. **Install PHP dependencies**

    ```bash
    composer install
    ```

3. **Install Node.js dependencies**

    ```bash
    npm install
    ```

4. **Environment Configuration**

    ```bash
    cp .env.example .env
    ```

    Update the `.env` file with your database credentials and other settings.

5. **Generate Application Key**

    ```bash
    php artisan key:generate
    ```

6. **Run Database Migrations**

    ```bash
    php artisan migrate
    ```

7. **Seed the Database (Optional)**

    ```bash
    php artisan db:seed
    ```

8. **Build Assets**

    ```bash
    npm run build
    # or for development
    npm run dev
    ```

9. **Start the Development Server**
    ```bash
    php artisan serve
    ```

The application will be available at `http://localhost:8000`

## Usage

### User Roles

- **Admin**: Full system access, user management, system configuration
- **Manager**: Sales oversight, inventory management, reporting
- **Cashier**: Sales processing, basic inventory viewing

### Key Workflows

1. **Product Setup**: Create categories and add products with pricing and stock information
2. **Sales Processing**: Add items to cart, apply discounts, process payments
3. **Inventory Management**: Receive goods via Goods Received Notes (GRN), manage purchase orders
4. **Reporting**: View sales reports, inventory levels, and financial summaries

## Testing

Run the test suite using Pest PHP:

```bash
./vendor/bin/pest
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Database Schema

The application includes the following main entities:

- Users (with roles)
- Categories
- Products
- Customers
- Suppliers
- Sales & Sale Items
- Purchase Orders & Items
- Goods Received Notes & Items
- Expenses
- Activity Logs
- Settings

## API Documentation

The application provides RESTful APIs for integration with external systems. API documentation is available at `/api/documentation` when the application is running.

## Security

This application follows Laravel security best practices:

- CSRF protection
- SQL injection prevention via Eloquent ORM
- Input validation and sanitization
- Role-based access control

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For support, please contact the development team or create an issue in the GitHub repository.

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for version history and updates.

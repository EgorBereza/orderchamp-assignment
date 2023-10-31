# Assignment

Our users should be able to add products that are in stock to their shopping cart. During checkout, our visitors should be able to become users and our users should be able to review their previously stored information (name, address, contact details).

Fifteen minutes after checkout, a user should receive a discount code of € 5,- for future purchases. If a user chooses to use a discount code, you should keep track of what discount code was applied and what amount was subtracted from the checkout.

# Installation

# MySql Database Creation
- `CREATE USER 'egor'@'localhost' IDENTIFIED BY '12345';`
- `CREATE DATABASE ecommerce;`
- `GRANT ALL PRIVILEGES ON ecommerce.* TO 'egor'@'localhost';`
- `FLUSH PRIVILEGES;`


# Deployment
- `git clone https://github.com/EgorBereza/orderchamp-assignment.git`
- `composer install`
- `php artisan migrate --seed`
- `php artisan queue:work`
- `php artisan serve`

# Notes

1)The requirements of this assignment were addressed by implementing a set of internal rest endpoints intended to be invoked through Ajax requests from the     application's frontend.
  All the routes can be found in the routes/web.php.

2)To facilitate testing, I've temporarily excluded specific routes from CSRF token verification in VerifyCsrfToken.php to enable access through Postman. In a typical production scenario, these routes would not need to be excluded from CSRF verification because they would be accessed via Ajax requests from the frontend.






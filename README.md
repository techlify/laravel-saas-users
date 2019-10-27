# LaravelRbac

Just another Role Based Access Control package for Laravel. This one focuses on keeping things simple & sweet. 

## Installation

Install this package with composer using the following command:

```
composer require techlify-inc/laravel-rbac
```

Run migrations

```
$ php artisan migrate
```

Add the Rbac trait to your `User` model

```php

class User extends Authenticatable
{
    use TechlifyInc\LaravelRbac\Traits\LaravelRbac;
}
```

## Usage

### Roles

#### Creating roles

```php
use \TechlifyInc\LaravelRbac\Models\Role;

$adminRole = Role::create([
    'name' => 'Administrator',
    'slug' => 'admin'
]);

$managerRole = Role::create([
    'name' => 'Manager',
    'slug' => 'manager'
]);
```

#### Assigning And Removing Roles
	
You can simple attach role to user:
```php
use App\User;

$user = User::find(1);
$user->attachRole($adminRole);
//or you can attach using the role slug
$user->attachRole("admin");
```
And the same if you want to detach role:
```php
$user->detachRole($adminRole);
//or you can remove using the role slug
$user->detachRole("admin");
```
### Checking for roles

You can simple check if user has role:
```php
use App\User;

$user = User::find(1);
if ($user->hasRole('admin')) {
    
}
```

### Permissions

#### Creating permissions

```php
use \TechlifyInc\LaravelRbac\Models\Permission;

$createPermission = Permission::create([
    'name' => 'Create product',
    'slug' => 'product.create'
]);

$removePermission = Permission::create([
    'name' => 'Delete product',
    'slug' => 'product.remove'
]);
```

#### Attaching And Detaching permissions

You can attach permission to role very simple:
```php
use \TechlifyInc\LaravelRbac\Models\Role;

$adminRole = Role::find(1);
$adminRole->attachPermission($createPermission);
//or you can insert only slug
$adminRole->attachPermission("product.create");
```
And the same to detach permission:
```php
$adminRole->detachPermission($createPermission);
$adminRole->detachPermission("product.create");
```

### Checking for permissions

You can simple check if user has permission:
```php
use App\User;

$user = User::find(1);
if ($user->hasPermission('product.create')) {
    
}

// OR for currently logged in user
if (auth()->user()->hasPermission('product.create'))
```

You can also enforce permissions at route level using the middleware (v0.2 onwards): 

```php
Route::get("customers", "CustomerController@index")->middleware("LaravelRbacEnforcePermission:customer_view");
```

# LaravelUserManagement is now merged into this package

A simple package for Laravel that provides user management services

## Installation

Install this package with composer using the following command:

```
composer require techlify-inc/laravel-user-management
```

Run migrations

```
$ php artisan migrate
```

## Usage

This package provides the following API services that your frontend can use: 

### User Management

```php

// Get the set of users
GET api/users

// Get a single user
GET api/users/{id}

// Add a new user
POST api/users

// Update a user record
PATCH api/users/{id}

// Delete a user record
DELETE api/users/{id}

```


### User Password Management

```php

// Change the current user password
POST api/user/current/update-password {current_password, new_password}

```

### User Session Management

```php

// Log out the currently logged in user
POST api/user/logout

// Get the User record of the currently logged in user
GET api/user/current

```
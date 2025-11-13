# 🍊 Pantry-Cloud

Easy to use library for interacting with the [pantrycloud](https://getpantry.cloud) API.

## 🛠 Instalation

You can install the package via composer:

```bash
composer require seba/pantry
```

## ❔ Usage

```php
$pantry = new new \Pantry\Client("YOUR_PANTRY_ID");
```

### Create a Basket

```php
$basket = $pantry->createBasket("ILoveThisBasket", [
    "key" => "value"
]);
```

### Get a Basket

There are several ways to get a basket

```php
$basket = $pantry->getBasket("ILoveThisBasket"); // Get the basket instance
echo $basket; // The basket data as a json string
var_dump($basket); // The basket data as an object
$basketData = $basket(); // The basket data as an object
$basketData = $basket->get(); // The basket data as an object
```

### Update a Basket

```php
$basket->update([
    "newKey" => "newValue"
]);
```

### Delete a Basket

```php
$basket->delete();
```

### Get Pantry information

There are several ways to get information about the pantry

```php
echo $pantry; // The pantry data as a json string
var_dump($pantry); // The pantry data as an object
$pantryData = $pantry(); // The pantry data as an object
$pantryData = $pantry->getData(); // The pantry data as an object
```

## ⚖️ License

This project is under the [MIT License](https://github.com/SebaOfficial/pantry-cloud/blob/main/LICENSE).

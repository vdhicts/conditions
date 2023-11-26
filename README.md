# Conditions

This package offers a way to handle conditions for actions in your application. For example to check if a record has the
required state (and which conditions are considered valid and/or invalid) or if a certain transition would be possible.
See the Usage section for more examples.

## Requirements

This package requires PHP 8.1+.

## Installation

You can install the package via composer:

`composer require vdhicts/conditions`

## Usage

### Terms

- **Condition**: A condition is a single check which can be fulfilled or not. A condition can have a level, message and additional data.
- **ConditionCollection**: A collection of conditions. 
- **ConditionLevel**: A condition level is a level which can be used to indicate the severity of a condition. Useful for displaying or filtering the conditions. Does not affect the fulfillment of a condition, so an info level condition which is not fulfilled is still a not fulfilled condition.
- **ConditionTransformer**: A condition transformer is used to transform a condition to another presentation or create the condition from another presentation.

### Condition

First create a condition. A condition has at least a name. _When the fulfilled parameter is not provided, the condition 
is considered fulfilled._

```php
use Vdhicts\Conditions\Condition;
use Vdhicts\Conditions\Enums\ConditionLevel;

// Basic variant
$condition = new Condition(
    name: 'Contact has email address',
    fulfilled: $contact->email_address !== null,
);

// Extended variant
$condition = new Condition(
    name: 'Contact has email address',
    fulfilled: $contact->email_address !== null,
    level: ConditionLevel::Error,
    message: 'The contact needs to have an e-mail address to receive the newsletter.',
    data: [
        'contact_id' => $contact->id,
        'newsletter_id' => $newsletter->id,
    ]   
);

// Fluent variant
$condition = (new Condition())
    ->setName('Contact has email address')
    ->setFulfilled($contact->email_address !== null)
    ->setLevel(ConditionLevel::Error)
    ->setMessage('The contact needs to have an e-mail address to receive the newsletter.')
    ->setData([
        'contact_id' => $contact->id,
        'newsletter_id' => $newsletter->id,
    ]);
```

### Condition collection

When you have multiple conditions, you can add them to a condition collection. A condition collection is considered 
fulfilled when all conditions in it are fulfilled or there aren't any conditions provided.

```php
use Vdhicts\Conditions\ConditionCollection;

// Basic variant
$conditionCollection = new ConditionCollection(collect([
    new Condition('Contact has e-mail address', $contact->email_address !== null),
    new Condition('Contact has name', $contact->name !== null),
]));

// Fluent variant
$conditionCollection = (new ConditionCollection())
    ->add(new Condition('Contact has e-mail address', $contact->email_address !== null))
    ->add(new Condition('Contact has name', $contact->name !== null));
```

The collection can be checked if it is fulfilled or not.

```php
$conditionCollection->isFulfilled(); // true or false
$conditionCollection->isNotFulfilled(); // true or false
```

To easily process the conditions, there are also some methods available to retrieve the conditions based on certain 
requirements.

```php
$conditionCollection->get(); // Returns a collection of all conditions

$conditionCollection->getFulfilledConditions(); // Returns a collection of fulfilled conditions
$conditionCollection->getNotFulfilledConditions(); // Returns a collection of not fulfilled conditions

$conditionCollection->only([ConditionLevel::Error, ConditionLevel::Warning]); // Returns a condition collection of conditions with the provided levels
$conditionCollection->except([ConditionLevel::Info]); // Returns a condition collection of conditions without the provided levels
```

### Transformer

A transformer can be used to transform a condition to another presentation or create the condition from another presentation.

```php
use Vdhicts\Conditions\Condition;
use Vdhicts\Conditions\ConditionTransformer;

// Transform a condition to an array
$array = ConditionTransformer::toArray(new Condition(
    name: 'Contact has email address',
    fulfilled: $contact->email_address !== null,
));

// Transform an array to a condition
$condition = ConditionTransformer::fromArray([
    'name' => 'Contact has email address',
    'fulfilled' => $contact->email_address !== null,
]);
```

## Contribution

Found a bug or want to add a new feature? Great! There are also many other ways to make meaningful contributions such 
as reviewing outstanding pull requests and writing documentation. Even opening an issue for a bug you found is 
appreciated.

## Security

If you discover any security related issues in this or other packages of Vdhicts, please email security@vdhicts.nl 
instead of using the issue tracker.

## License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).

## About Vdhicts

[Vdhicts](https://www.vdhicts.nl) develops and implements IT solutions for businesses based on the Laravel framework.

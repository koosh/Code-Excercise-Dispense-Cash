# Cash Machine

Calculates breakdown of an amount into exact bills of given denominations.
 
## Example:

How to use see ```sample.php```

For denominations: 100, 50, 20, 10 outputs

Amount: 30
Result: [20, 10]

Amount: 80
Result: [50, 20, 10]

Amount: 125
Result: throw NoteUnavailableException

Amount: -130
Result: throw InvalidArgumentException

Amount: NULL
Result: [Empty Set]

## Testing

```composer install```

```php vendor/phpunit/phpunit/phpunit --no-configuration tests --teamcity```

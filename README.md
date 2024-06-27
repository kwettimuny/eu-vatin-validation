# EU VATIN Validation

1. Set country code & VATIN
```php
const REQUESTER_COUNTRY_CODE = 'YOUR_COUNTRY_CODE';
const REQUESTER_VAT_NUMBER = 'YOUR_VAT_NUMBER';
```
2. Set the VATINs that require validation at `/tmp/eu-vatin-validation/input.csv` Eg.
```csv
AB,AB123456789
CD,CD101112131
```
3. Create `/tmp/eu-vatin-validation/validated.csv`
4. Create `/tmp/eu-vatin-validation/failed.csv`
5. Run `php index.php`

# Thanks

- [@DragonBe](https://github.com/DragonBe) for [vies](https://github.com/DragonBe/vies)
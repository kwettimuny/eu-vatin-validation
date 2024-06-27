<?php

echo date('Y-m-d g:i A') . " Began Validating EU VATINs\n";

require_once('vendor/autoload.php');

use DragonBe\Vies\Vies;
use DragonBe\Vies\ViesException;
use DragonBe\Vies\ViesServiceException;

$vies = new Vies;

const REQUESTER_COUNTRY_CODE = 'YOUR_COUNTRY_CODE';
const REQUESTER_VAT_NUMBER = 'YOUR_VAT_NUMBER';

$inputCSV = fopen('/tmp/eu-vatin-validation/input.csv', 'r');

if (!$inputCSV) {
    echo "Failed to open input.csv file\n";
}

$validated = [];
$failed = [];

while (($line = fgetcsv($inputCSV)) !== false) {
    $countryCode = $line[0];
    $vatNumber = $line[1];

    $vatNumberWithoutCountryCode = $vatNumber;

    if (substr($vatNumber, 0, strlen($countryCode)) === $countryCode) {
        $vatNumberWithoutCountryCode = substr($vatNumber, strlen($countryCode));
    }

    try {
        $vatResult = $vies->validateVat(
            $countryCode,
            $vatNumberWithoutCountryCode,
            REQUESTER_COUNTRY_CODE,
            REQUESTER_VAT_NUMBER
        );
    
        $isValid = $vatResult->isValid() ? 'Valid' : 'Invalid';

        echo "$countryCode\t\t\t$vatNumber\t\t\t$isValid\n";

        array_push($validated, [
            $countryCode,
            $vatNumber,
            $isValid
        ]);
    } catch (ViesException $exception) {
        echo "$countryCode\t\t\t$vatNumber\t\t\t" . $exception->getMessage() . "\n";

        array_push($failed, [
            $countryCode,
            $vatNumber,
            $exception->getMessage()
        ]);
    } catch (ViesServiceException $exception) {
        echo "$countryCode\t\t\t$vatNumber\t\t\t" . $exception->getMessage() . "\n";

        array_push($failed, [
            $countryCode,
            $vatNumber,
            $exception->getMessage()
        ]);
    }
}

fclose($inputCSV);

$validatedCSV = fopen('/tmp/eu-vatin-validation/validated.csv', 'w');

if (!$validatedCSV) {
    echo "Failed to open validated.csv file\n";
}

foreach ($validated as $row) {
    fputcsv($validatedCSV, $row);
}

fclose($validatedCSV);

$failedCSV = fopen('/tmp/eu-vatin-validation/failed.csv', 'w');

if (!$failedCSV) {
    echo "Failed to open failed.csv file\n";
}

foreach ($failed as $row) {
    fputcsv($failedCSV, $row);
}

fclose($failedCSV);

echo date('Y-m-d g:i A') . " Ended Validating EU VATINs\n";
<?php
include_once '../vendor/autoload.php';
use XchangeZilla\CurrencyConverter;
use \XchangeZilla\Currency;

$cc = new CurrencyConverter('13a4050b8ce94b88b540e63510da5d3e');
echo $cc->convert(Currency::USD_CURRENCY, Currency::TRY_CURRENCY, 100);

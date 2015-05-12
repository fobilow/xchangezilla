<?php
namespace XchangeZilla;

use XchangeZilla\Exceptions\InvalidCurrencyException;
use XchangeZilla\Exceptions\InvalidResponseException;

class CurrencyConverter
{
  private $_apiKey;
  private $_rates;

  /**
   * @param string $apiKey
   */
  public function __construct($apiKey)
  {
    $this->_apiKey = $apiKey;
  }

  /**
   * @param bool $refresh
   *
   * @return mixed
   * @throws InvalidResponseException
   */
  public function getRates($refresh = false)
  {
    if($this->_rates === null || $refresh)
    {
      $data         = file_get_contents(
        'https://openexchangerates.org/api/latest.json?'
        . 'app_id=' . $this->_apiKey
      );
      $this->_rates = json_decode($data);
    }
    if(!$this->_rates)
    {
      throw new InvalidResponseException(
        'Failed to get rates from openexchangerates.org'
      );
    }
    return $this->_rates;
  }

  /**
   * Rates should be a JSON string
   *
   * @param string $rates
   */
  public function setRates($rates)
  {
    $this->_rates = $rates;
  }

  /**
   * @param string $currency
   *
   * @return double
   * @throws InvalidCurrencyException
   * @throws InvalidResponseException
   */
  public function getRate($currency)
  {
    $rates = $this->getRates();
    if(isset($rates->rates->$currency))
    {
      return $rates->rates->$currency;
    }
    throw new InvalidCurrencyException("Invalid From or To currency");
  }

  /**
   * @param string $from
   * @param string $to
   * @param double $amount
   *
   * @return float
   * @throws InvalidCurrencyException
   */
  public function convert($from, $to, $amount)
  {
    return ($this->getRate($to) * $amount) / $this->getRate($from);
  }
}



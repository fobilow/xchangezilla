<?php

/**
 * @author  oke.ugwu
 */
namespace XchangeZilla;

use XchangeZilla\Exceptions\InvalidCurrencyException;
use XchangeZilla\Exceptions\InvalidResponseException;

class CurrencyConverter
{
  private $_apiKey;
  private $_rates;

  public function __construct($apiKey)
  {
    $this->_apiKey = $apiKey;
  }

  public function convert($from, $to, $amount, $refresh = false)
  {
    if($this->_rates === null || $refresh)
    {
      $data         = file_get_contents(
        'https://openexchangerates.org/api/latest.json?'
        . 'app_id=' . $this->_apiKey
      );
      $this->_rates = json_decode($data);
    }
    if($this->_rates)
    {
      if(isset($this->_rates->rates->$from)
        && isset($this->_rates->rates->$to)
      )
      {
        return ($this->_rates->rates->$to * $amount) / $this->_rates->rates->$from;
      }
      else
      {
        throw new InvalidCurrencyException("Invalid From or To currency");
      }
    }
    else
    {
      throw new InvalidResponseException(
        'Failed to get rates from openexchanerates.org'
      );
    }
  }
}



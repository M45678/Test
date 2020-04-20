<?php

function getYandexData($str = '') {
    // EXAMPLE
    //$str = 'Пароль: 5361
    //    Спишется 101,51р.
    //    Перевод на счет  41001***********';

    if (empty($str) || !preg_match('/[\D]/', $str))
        return false;

    // get each row data
    $messageArray = explode("\n", $str);

    // make result
    $response = [];

    // yandex purse length (КОШЕЛЕК), it has constant length
    $yandex_length = 16;

    array_map(function($row) use(&$response, $yandex_length) {
        // find amount
        if (stripos($row, 'р.') !== false && stripos($row, ',') !== false) {
            $response['amount'] = preg_replace('/[\D]/', '', $row);
        } else {
            $row = preg_replace('/[\D]/', '', $row);
            if (strlen($row) === $yandex_length) {
                $response['purse'] = $row;
            } elseif (strlen($row)) {
                $response['code'] = $row;
            }
        }
    }, $messageArray);

    if (!count($response)) {
        $response['msg'] = 'error parsing';
    }

    return $response;
}


// second version
function getYandexData($str = '') {
 if (empty($str) || !preg_match('/[^0-9]/', $str))
        return false;

    // get each row data
    $messageArray = explode("\n", $str);

    // make result
    $response = [];

    // yandex purse length (КОШЕЛЕК), it has constant length
    $yandex_length = 16;

    array_map(function($row) use(&$response, $yandex_length) {
        $number = filter_var($row, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
        // get amount
        if (stripos($number, ',') !== false) {
            $response['amount'] = preg_replace('/[^0-9,]/', '', $number);
        } else {
	        // get purse
            if (strlen($number) === $yandex_length) {
                $response['purse'] = $number;
            } elseif (strlen($number)) {
                $response['code'] = $number;
            }
        }
    }, $messageArray);

      if (!count($response)) {
        $response['msg'] = 'error parsing';
      }

    return $response;
 }
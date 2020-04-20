<?php 
/**
 * @author Marine Gasparyan <marine.gasparyan.96@gmail.com>
 * @date   20.04.2020 
 */
 
/**
 * @param string $str
 *
 * @return array
 */
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

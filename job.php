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

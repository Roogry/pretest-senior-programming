<?php

/**
 * Melakukan format nilai rupiah
 *
 * @param  int|double $nominal
 * @return string - string dengan format rupiah.
 *
 * @example currency(1500);
 */
function currency($nominal){
    return 'Rp ' . number_format($nominal,0,',','.');
}

/**
 * Mengurutkan array object dengan
 * menggunakan algoritma insertion sort
 *
 * @param  array $arr - The array object.
 * @param  object $fieldName - The field name of the object.
 *
 * @return object|int|null index of the search key if found,
 *
 * @example insertionSort($products, 'price');
 */
function insertionSort($arr, $fieldName)
{
    $n = sizeof($arr);

    for ($i = 1; $i < $n; $i++) {
        $key = $arr[$i];
        $j = $i - 1;

        // Move elements of arr[0..i-1],
        // that are greater than key, to
        // one position ahead of their
        // current position
        while ($j >= 0 && $arr[$j][$fieldName] > $key[$fieldName]) {
            $arr[$j + 1] = $arr[$j];
            $j = $j - 1;
        }

        $arr[$j + 1] = $key;
    }

    return $arr;
}

/**
 * Mencari suatu nilai number dalam array
 * dengan menggunakan algoritma binary search
 *
 * @param  array $arr - The sorted array.
 * @param  object $x - The object to be searched for.
 *
 * @return int|null index of the search key if found,
 *
 * @example binarySearch($arr, $value);
 */
function binarySearch($arr, $x)
{
    // check for empty array
    if (count($arr) === 0) return false;
    $low = 0;
    $high = count($arr) - 1;

    while ($low <= $high) {

        // compute middle index
        $mid = floor(($low + $high) / 2);

        // element found at mid
        if ($arr[$mid] == $x) {
            return $mid;
        }

        if ($x < $arr[$mid]) {
            // search the left side of the array
            $high = $mid - 1;
        } else {
            // search the right side of the array
            $low = $mid + 1;
        }
    }

    // If we reach here element x doesnt exist
    return null;
}

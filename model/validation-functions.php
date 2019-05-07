<?php
/**
 * Pair Programming IV with Bruce Turner & Brian Kiehn
 * GitHub Repo:https://github.com/BTurner15/pets4
 * File: validation-functions.php
 * Last Modification:
 *     - Monday May 5 2019
 *     - Time: 6:15 pm
 *     - Version 1.0
 */
/* validate a color
 *
    *@param String color
 *   return boolean
 * */
function validColor($color)
{
    global $f3;
    return in_array($color, $f3->get('colors'));
}
/* Validate a string
 * @param String animalStr
 * @return boolean if string is not empty, and all alphabetic
 */
function validString($animalStr)
{
    global $f3;
    return ctype_alpha ($animalStr) AND ($animalStr !="");
}
/* for Part# 1 of Pair Programming Pets IV */
function validQuantity($qty)
{
    global $f3;
    return (!empty($qty)) && (ctype_digit($qty)) && ((int)$qty >= 1);
}
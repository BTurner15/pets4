<?php
/**
 * Bruce Turner & Brian Kiehn Pair Programming IV
 * Creation Date: 4/26/2019
 * Time: 2:28 PM
 * Modified by: Bruce 05-06-2019 for Part# 1 of PP IV
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
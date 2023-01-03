<?php

use App\Models\Option;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

function getValue($array, $sting)
{
    return Arr::get($array, $sting);
}

function getValueRemoveExtraSpace($value, $string)
{
    return removeSpacesTNR(trim(getValue($value, $string)));
}

function removeSpacesTNR($text)
{
    return $text = preg_replace("/\r|\n|\t/", "", $text);
}

function stringToDotArray($string)
{
    $replaced = Str::replaceLast(']', '', $string);
    $replaced = Str::replaceFirst('[', '.', $replaced);
    $replaced = Str::replace('][', '.', $replaced);
    return  $replaced;
}

function dotToString($string)
{
    $replaced = Str::replace('.', '][', $string . ']');
    $replaced = Str::replaceFirst(']', '', $replaced);
    return  $replaced;
}

function getOptionLanguage($key)
{
    if (request()->segment(1) == 'ar') {
        $key = $key . '_ar';
    }

    $value = Option::where('key', $key)->first();
    if ($value)
        return  $value->value;
    return;
}

function getOption($key)
{
    $value = Option::where('key', $key)->first();
    if ($value)
        return  $value->value;
    return;
}


function getOptionTwo($key)
{
    $value = Option::where('key', $key)->first();
    if ($value)
        return  $value->value;
    return true;
}

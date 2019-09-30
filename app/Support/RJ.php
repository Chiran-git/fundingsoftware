<?php

namespace App\Support;

use Auth;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class RJ {
    /**
     * Property to hold global script variables that can be set
     * by server to be available on the client side
     *
     * @var array
     */
    private static $scriptVariables = [];

    /**
     * Method to set a server side variable to be available on client side
     *
     * @param string $name  Name of the variable
     * @param mixed  $value Value of the variable
     *
     * @return void
     */
    public static function setScriptVariable($name, $value)
    {
        self::$scriptVariables[$name] = $value;
    }

    /**
     * Method to return the script variables.
     *
     * @return array
     */
    public static function scriptVariables() {
        return self::$scriptVariables;
    }

    /**
     * Method to get the CDN url for the given file on the given disk
     *
     * @param string $file
     * @param string $disk
     *
     * @return string
     */
    public static function assetCdn($file, $disk = 'public_uploads') {
        return Storage::disk($disk)->url($file);
    }

    /**
     * Method to format the donation money
     *
     * @param decimal $amount
     * @param string $symbol
     *
     * @return string
     */
    public static function donationMoney($amount, $symbol = '')
    {
        // If amount is empty or null, then return 0
        if (empty($amount)) {
            return $symbol . '0';
        }

        $decimals = 0;
        // If the amount has decimals, then we will format with 2 decimals
        // Else with no demials.
        if (is_numeric($amount) && floor($amount) != $amount) {
            $decimals = 2;
        }

        return $symbol . number_format($amount, $decimals);
    }

    /**
     * Method to convert the amount to fractional units
     *
     * @param decimal $amount Amount in normal unit i.e. dollars
     *
     * @return integer Amount in fractional unit i.e. cents
     */
    public static function converToFractionUnit($amount)
    {
        return (int)round($amount * 100);
    }

    /**
     * Method to convert the amount from fractional unit to whole unit
     *
     * @param integer $amount Amount in fractional unit i.e. cents
     *
     * @return decimal Amount in normal unit i.e. dollars
     */
    public static function convertToWholeUnit($amount)
    {
        return round($amount / 100, 2);
    }

    /**
     * Method to generate the FB share url
     *
     * @param string $url Url to share
     *
     * @return string Url to redirect to facebook
     */
    public static function fbShareUrl($url)
    {
        return 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
    }

    /**
     * Method to generate the Tweet url
     *
     * @param string $text Text to tweet
     *
     * @return string Url to redirect to tweeter
     */
    public static function tweetUrl($text)
    {
        return 'https://twitter.com/intent/tweet?text=' . urlencode($text);
    }


    /**
     * Common method to impersonate
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return boolean
     */
    public static function impersonateOrganization(User $owner, Request $request)
    {
        $impersonatedBy = $request->user()->first_name." ".$request->user()->last_name;
        $impersonatorRole = $request->user()->user_type;
        $impersonatorImage = $request->user()->image ? RJ::assetCdn($request->user()->image) : null;
        if ($owner) {
            Auth::user()->impersonate($owner);
            // Set session of the original user
            Session::put('impersonator_name', $impersonatedBy);
            Session::put('impersonator_role', $impersonatorRole);
            Session::put('impersonator_image', $impersonatorImage);
            return true;
        } else {
            return false;
        }
    }
}

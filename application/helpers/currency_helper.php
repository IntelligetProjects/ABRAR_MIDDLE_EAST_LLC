<?php

/**
 * convert a number to currency forma
 * 
 * @param number $number
 * @param string $currency
 * @return number with currency symbol
 */
if (!function_exists('to_currency')) {

    function to_currency($number = 0, $currency = "", $no_of_decimals = 3)
    {
        $ci = get_instance();
        $decimal_separator = get_setting("decimal_sepsarator");
        $thousand_separator = get_setting("thousand_separator");
        $currency_symbol = $ci->session->userdata('view_currency_symbol');

        set_default_view_curreny();
        $number = check_view_currency($number);

        if (get_setting("no_of_decimals") == "0") {
            $no_of_decimals = 0;
        }

        $negative_sign = "";
        if ($number < 0) {
            $number = $number * -1;
            $negative_sign = "-";
        }
        // if (!$currency) {
        //     $currency = $currency_symbol;
        // } else if ($currency == "no") {
        //     $currency = "";
        // }
        $currency = '/' . $currency_symbol;

        $currency_position = get_setting("currency_position");
        if (!$currency_position) {
            $currency_position = "left";
        }

        if ($decimal_separator === ",") {
            if ($thousand_separator !== " ") {
                $thousand_separator = ".";
            }

            if ($currency_position === "right") {
                return $negative_sign . number_format($number, $no_of_decimals, ",", $thousand_separator) . $currency;
            } else {
                return $negative_sign . $currency . number_format($number, $no_of_decimals, ",", $thousand_separator);
            }
        } else {
            if ($thousand_separator !== " ") {
                $thousand_separator = ",";
            }

            if ($currency_position === "right") {
                return $negative_sign . number_format($number, $no_of_decimals, ".", $thousand_separator) . $currency;
            } else {
                return $negative_sign . $currency . number_format($number, $no_of_decimals, ".", $thousand_separator);
            }
        }
    }
}


if (!function_exists('check_view_currency')) {
    function check_view_currency($number = 0.0)
    {
        $ci = get_instance();

        if ($ci->session->userdata('row_data_currency_rate')  && $ci->session->userdata('row_data_currency_rate') != 1 && $ci->login_user->is_admin) {
            //handle different currency for multiple currency view 
            $row_currency_rate = $ci->session->userdata('row_data_currency_rate');
            $number = bcmul($number, $row_currency_rate, 6);
            return $number;
        } else {
            $view_rate = $ci->session->userdata('view_currency_rate');
            if ($view_rate != 1) {
                return bcmul($number, $view_rate, 6);
            } else {
                return $number;
            }
        }
    }
}

if (!function_exists('set_row_data_currency_rate')) {
    function set_row_data_currency_rate($currency_rate_at_creation)
    {
        $ci = get_instance();
        //check if user can view data from multiple cost centers
        if ($ci->login_user->is_admin && $currency_rate_at_creation) {
            //set row currency rate
            $ci->session->set_userdata('row_data_currency_rate', $currency_rate_at_creation);
        }
    }
}

if (!function_exists('unset_row_data_currency_rate')) {
    function unset_row_data_currency_rate()
    {
        $ci = get_instance();
        //check if user can view data from multiple cost centers
        if ($ci->session->userdata('row_data_currency_rate')) {
            //set row currency rate
            $ci->session->unset_userdata("row_data_currency_rate");
        }
    }
}

if (!function_exists('can_view_all_cost_centers_data')) {
    function can_view_all_cost_centers_data()
    {
        $ci = get_instance();
        return $ci->login_user->is_admin;
    }
}

if (!function_exists('get_current_view_currency_id')) {
    function get_current_view_currency_id()
    {
        $ci = get_instance();
        $id = $ci->session->userdata('view_currency_id');
        return $id ? $id : 1;
    }
}


if (!function_exists('set_current_view_currency')) {
    function set_current_view_currency($currency_id)
    {
        $ci = get_instance();
        $currency = $ci->Currencies_model->get_one($currency_id);
        if ($currency) {
            $ci->session->set_userdata('view_currency_id', $currency->id);
            $ci->session->set_userdata('view_currency_name', $currency->name);
            $ci->session->set_userdata('view_currency_symbol', $currency->symbol);
            $ci->session->set_userdata('view_currency_rate', $currency->rate);
        } else {
            // $currency = $ci->Currencies_model->get_one(1); // get default curreny 
            $ci->session->set_userdata('view_currency_id', 1);
            $ci->session->set_userdata('view_currency_name', "OMR");
            $ci->session->set_userdata('view_currency_symbol', "OMR");
            $ci->session->set_userdata('view_currency_rate', 1);
        }

        // var_dump($ci->session->userdata('view_currency_rate'));
        // die();
    }
}

if (!function_exists('set_default_view_curreny')) {
    function set_default_view_curreny()
    {
        $ci = get_instance();
        if (!$ci->session->userdata('view_currency_rate')) {
            $data =  $ci->Cost_centers_model->get_details(array("id" => $ci->login_user->cost_center_id))->row();
            $ci->session->set_userdata('view_currency_name', $data->currency_name);
            $ci->session->set_userdata('view_currency_symbol', $data->currency_symbol);
            $ci->session->set_userdata('view_currency_rate', 1);
        }
    }
}

/**
 * convert a number to quantity format
 * 
 * @param number $number
 * @return number
 */
if (!function_exists('to_decimal_format')) {

    function to_decimal_format($number = 0)
    {
        $decimal_separator = get_setting("decimal_separator");

        $decimal = 0;
        if (is_numeric($number) && floor($number) != $number) {
            $decimal = get_setting("no_of_decimals") == "0" ? 0 : 2;
        }
        if ($decimal_separator === ",") {
            return number_format($number, $decimal, ",", ".");
        } else {
            return number_format($number, $decimal, ".", ",");
        }
    }
}

/**
 * convert a currency value to data format
 *  
 * @param number $currency
 * @return number
 */
if (!function_exists('unformat_currency')) {

    function unformat_currency($currency = "")
    {
        // remove everything except a digit "0-9", a comma ",", and a dot "."
        $new_money = preg_replace('/[^\d,-\.]/', '', $currency);
        $decimal_separator = get_setting("decimal_separator");
        if ($decimal_separator === ",") {
            $new_money = str_replace(".", "", $new_money);
            $new_money = str_replace(",", ".", $new_money);
        } else {
            $new_money = str_replace(",", "", $new_money);
        }
        return $new_money;
    }
}

/**
 * get array of international currency codes
 * 
 * @return array
 */
if (!function_exists('get_international_currency_code_list')) {

    function get_international_currency_code_list()
    {
        return array(
            "AED",
            "AFN",
            "ALL",
            "AMD",
            "ANG",
            "AOA",
            "ARS",
            "AUD",
            "AWG",
            "AZN",
            "BAM",
            "BBD",
            "BDT",
            "BGN",
            "BHD",
            "BIF",
            "BMD",
            "BND",
            "BOB",
            "BOV",
            "BRL",
            "BSD",
            "BTN",
            "BWP",
            "BYR",
            "BZD",
            "CAD",
            "CDF",
            "CHE",
            "CHF",
            "CHW",
            "CLF",
            "CLP",
            "CNY",
            "COP",
            "COU",
            "CRC",
            "CUC",
            "CUP",
            "CVE",
            "CZK",
            "DJF",
            "DKK",
            "DOP",
            "DZD",
            "EGP",
            "ERN",
            "ETB",
            "EUR",
            "FJD",
            "FKP",
            "GBP",
            "GEL",
            "GHS",
            "GIP",
            "GMD",
            "GNF",
            "GTQ",
            "GYD",
            "HKD",
            "HNL",
            "HRK",
            "HTG",
            "HUF",
            "IDR",
            "ILS",
            "INR",
            "IQD",
            "IRR",
            "ISK",
            "JMD",
            "JOD",
            "JPY",
            "KES",
            "KGS",
            "KHR",
            "KMF",
            "KPW",
            "KRW",
            "KWD",
            "KYD",
            "KZT",
            "LAK",
            "LBP",
            "LKR",
            "LRD",
            "LSL",
            "LYD",
            "MAD",
            "MDL",
            "MGA",
            "MKD",
            "MMK",
            "MNT",
            "MOP",
            "MRO",
            "MUR",
            "MVR",
            "MWK",
            "MXN",
            "MXV",
            "MYR",
            "MZN",
            "NAD",
            "NGN",
            "NIO",
            "NOK",
            "NPR",
            "NZD",
            "OMR",
            "PAB",
            "PEN",
            "PGK",
            "PHP",
            "PKR",
            "PLN",
            "PYG",
            "QAR",
            "RON",
            "RSD",
            "RUB",
            "RWF",
            "SAR",
            "SBD",
            "SCR",
            "SDG",
            "SEK",
            "SGD",
            "SHP",
            "SLL",
            "SOS",
            "SRD",
            "SSP",
            "STD",
            "SYP",
            "SZL",
            "THB",
            "TJS",
            "TMT",
            "TND",
            "TOP",
            "TRY",
            "TTD",
            "TWD",
            "TZS",
            "UAH",
            "UGX",
            "USD",
            "USN",
            "USS",
            "UYI",
            "UYU",
            "UZS",
            "VEF",
            "VND",
            "VUV",
            "WST",
            "XAF",
            "XAG",
            "XAU",
            "XBA",
            "XBB",
            "XBC",
            "XBD",
            "XCD",
            "XDR",
            "XFU",
            "XOF",
            "XPD",
            "XPF",
            "XPT",
            "XSU",
            "XTS",
            "XUA",
            "YER",
            "ZAR",
            "ZMW"
        );
    }
};


/**
 * get dropdown list fro international currency code
 * 
 * @return array
 */
if (!function_exists('get_international_currency_code_dropdown')) {

    function get_international_currency_code_dropdown()
    {
        $result = array();
        foreach (get_international_currency_code_list() as $value) {
            $result[$value] = $value;
        }
        return $result;
    }
};


/**
 * get ignor minor amount 
 * 
 * @return int
 */
if (!function_exists('ignor_minor_value')) {

    function ignor_minor_value($value)
    {
        if (abs($value) < 0.05) {
            $value = 0;
        }
        return $value;
    }
};

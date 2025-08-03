<?php
/**
 * Plugin Name:       JetBooking Shamsi Dates
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Converts JetBooking dates to Persian Shamsi calendar.
 * Version:           1.0.0
 * Author:            Nima Fadaei
 * Author URI:        https://nimaafadaei.ir/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       jetbooking-shamsi-dates
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class JBS_ParsiDate {
        protected static $instance;

        public $sessions = array( 'بهار', 'تابستان', 'پاییز', 'زمستان' );

        public $persian_day_names = array( 'یکشنبه', 'دوشنبه', 'سه شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه', 'شنبه' );
        public $persian_day_small = array( 'ی', 'د', 'س', 'چ', 'پ', 'ج', 'ش' );

        public $j_days_in_month = array( 31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29 );
        private $j_days_sum_month = array( 0, 0, 31, 62, 93, 124, 155, 186, 216, 246, 276, 306, 336 );
        private $g_days_sum_month = array( 0, 0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334 );
        public $wpp_months_name = array(
                                        '',
                                        'فروردین',
                                        'اردیبهشت',
                                        'خرداد',
                                        'تیر',
                                        'مرداد',
                                        'شهریور',
                                        'مهر',
                                        'آبان',
                                        'آذر',
                                        'دی',
                                        'بهمن',
                                        'اسفند'
                                );

        public function IsPerLeapYear( $year ) {
                $mod = $year % 33;

                if ( $mod == 1 or $mod == 5 or $mod == 9 or $mod == 13 or $mod == 17 or $mod == 22 or $mod == 26 or $mod == 30 ) {
                        return true;
                }

                return false;
        }

        private function IsLeapYear( $year ) {
                if ( ( ( $year % 4 ) == 0 && ( $year % 100 ) != 0 ) || ( ( $year % 400 ) == 0 ) && ( $year % 100 ) == 0 ) {
                        return true;
                }

                return false;
        }

        public function persian_date( $format, $date = 'now', $lang = 'per' ) {
                $timestamp       = is_numeric( $date ) && (int) $date == $date ? $date : strtotime( $date );
                $date            = getdate( $timestamp );

                list( $date['year'], $date['mon'], $date['mday'] ) = self::gregorian_to_persian( $date['year'], $date['mon'], $date['mday'] );

                $date['mon']  = (int) $date['mon'];
                $date['mday'] = (int) $date['mday'];
                $out          = '';
                $len          = strlen( $format );

                for ( $i = 0; $i < $len; $i ++ ) {
                        switch ( $format[ $i ] ) {
                                //day
                                case'd':
                                        $out .= ( $date['mday'] < 10 ) ? '0' . $date['mday'] : $date['mday'];
                                        break;
                                case'D':
                                        $out .= $this->persian_day_small[ $date['wday'] ];
                                        break;
                                case'l':
                                        $out .= $this->persian_day_names[ $date['wday'] ];
                                        break;
                                case'j':
                                        $out .= $date['mday'];
                                        break;
                                case'N':
                                        $out .= $this->week_day( $date['wday'] ) + 1;
                                        break;
                                case'w':
                                        $out .= $this->week_day( $date['wday'] );
                                        break;
                                case'z':
                                        if ( $date['mon'] == 12 && self::IsPerLeapYear( $date['year'] ) ) {
                                                $out .= 30 + $date['mday'];
                                        } else {
                                                $out .= $this->j_days_in_month[ $date['mon'] ] + $date['mday'];
                                        }
                                        break;
                                //week
                                case'W':
                                        $yday = $this->j_days_sum_month[ $date['mon'] - 1 ] + $date['mday'];
                                        $out  .= intval( $yday / 7 );
                                        break;
                                //month
                                case'f':
                                        $mon = $date['mon'];
                                        switch ( $mon ) {
                                                case( $mon < 4 ):
                                                        $out .= $this->sessions[0];
                                                        break;
                                                case( $mon < 7 ):
                                                        $out .= $this->sessions[1];
                                                        break;
                                                case( $mon < 10 ):
                                                        $out .= $this->sessions[2];
                                                        break;
                                                case( $mon > 9 ):
                                                        $out .= $this->sessions[3];
                                                        break;
                                        }
                                        break;
                                case 'M':
                                case'F':
                                        $out .= $this->wpp_months_name[ $date['mon'] ];
                                        break;
                                case'm':
                                        $out .= ( $date['mon'] < 10 ) ? '0' . $date['mon'] : $date['mon'];
                                        break;
                                case'n':
                                        $out .= $date['mon'];
                                        break;
                                case'S':
                                        $out .= 'ام';
                                        break;
                                case't':
                                        if ( $date['mon'] == 12 && self::IsPerLeapYear( $date['year'] ) ) {
                                                $out .= 30;
                                        } else {
                                                $out .= $this->j_days_in_month[ $date['mon'] - 1 ];
                                        }
                                        break;
                                //year
                                case'L':
                                        $out .= ( ( $date['year'] % 4 ) == 0 ) ? 1 : 0;
                                        break;
                                case'o':
                                case'Y':
                                        $out .= $date['year'];
                                        break;
                                case'y':
                                        $out .= substr( $date['year'], 2, 2 );
                                        break;
                                //time
                                case'a':
                                        $out .= ( $date['hours'] < 12 ) ? 'ق.ظ' : 'ب.ظ';
                                        break;
                                case'A':
                                        $out .= ( $date['hours'] < 12 ) ? 'قبل از ظهر' : 'بعد از ظهر';
                                        break;
                                case'B':
                                        $out .= (int) ( 1 + ( $date['mon'] / 3 ) );
                                        break;
                                case'g':
                                        $out .= ( $date['hours'] > 12 ) ? $date['hours'] - 12 : $date['hours'];
                                        break;
                                case'G':
                                        $out .= $date['hours'];
                                        break;
                                case'h':
                                        $hour = ( $date['hours'] > 12 ) ? $date['hours'] - 12 : $date['hours'];
                                        $out  .= ( $hour < 10 ) ? '0' . $hour : $hour;
                                        break;
                                case'H':
                                        $out .= ( $date['hours'] < 10 ) ? '0' . $date['hours'] : $date['hours'];
                                        break;
                                case'i':
                                        $out .= ( $date['minutes'] < 10 ) ? '0' . $date['minutes'] : $date['minutes'];
                                        break;
                                case's':
                                        $out .= ( $date['seconds'] < 10 ) ? '0' . $date['seconds'] : $date['seconds'];
                                        break;
                                //full date time
                                case'c':
                                        $out = $date['year'] . '/' . $date['mon'] . '/' . $date['mday'] . ' ' . $date['hours'] . ':' . ( ( $date['minutes'] < 10 ) ? '0' . $date['minutes'] : $date['minutes'] ) . ':' . ( ( $date['seconds'] < 10 ) ? '0' . $date['seconds'] : $date['seconds'] );
                                        break;
                                case'r':
                                        $out = $this->persian_day_names[ $date['wday'] ] . ',' . $date['mday'] . ' ' . $this->wpp_months_name[ $date['mon'] ] . ' ' . $date['year'] . ' ' . $date['hours'] . ':' . ( ( $date['minutes'] < 10 ) ? '0' . $date['minutes'] : $date['minutes'] ) . ':' . ( ( $date['seconds'] < 10 ) ? '0' . $date['seconds'] : $date['seconds'] );
                                        break;
                                case'U':
                                        $out = $timestamp;
                                        break;
                                default:
                                        $out .= $format[ $i ];
                        }
                }

                if ( ! in_array( strtolower( $format ), [ 'u', 'timestamp' ] ) && $lang == 'per' ) {
                        return self::trim_number( $out );
                } else {
                        return $out;
                }
        }

        function gregorian_to_persian( $gy, $gm, $gd ) {
                $dayOfYear = $this->g_days_sum_month[ (int) $gm ] + $gd;

                if ( self::IsLeapYear( $gy ) and $gm > 2 ) {
                        $dayOfYear ++;
                }

                $d_33 = (int) ( ( ( $gy - 16 ) % 132 ) * 0.0305 );
                $leap = $gy % 4;
                $a    = ( ( $d_33 == 1 or $d_33 == 2 ) and ( $d_33 == $leap or $leap == 1 ) ) ? 78 : ( ( $d_33 == 3 and $leap == 0 ) ? 80 : 79 );
                $b    = ( $d_33 == 3 or $d_33 < ( $leap - 1 ) or $leap == 0 ) ? 286 : 287;

                if ( (int) ( ( $gy - 10 ) / 63 ) == 30 ) {
                        $b --;
                        $a ++;
                }

                if ( $dayOfYear > $a ) {
                        $jy = $gy - 621;
                        $jd = $dayOfYear - $a;
                } else {
                        $jy = $gy - 622;
                        $jd = $dayOfYear + $b;
                }

                for ( $i = 0; $i < 11 and $jd > $this->j_days_in_month[ $i ]; $i ++ ) {
                        $jd -= $this->j_days_in_month[ $i ];
                }

                $jm = ++ $i;

                return array( $jy, strlen( $jm ) == 1 ? '0' . $jm : $jm, strlen( $jd ) == 1 ? '0' . $jd : $jd );
        }

        private function week_day( $wday ) {
                return $wday == 6 ? 0 : ++ $wday;
        }

        public function trim_number( $num, $sp = '٫' ) {
                $eng    = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.' );
                $per    = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', $sp );
                $number = filter_var( $num, FILTER_SANITIZE_NUMBER_INT );

                return empty( $number ) ? str_replace( $per, $eng, $num ) : str_replace( $eng, $per, $num );
        }

        public static function getInstance() {
                if ( ! isset( self::$instance ) ) {
                        self::$instance = new self();
                }

                return self::$instance;
        }
}

function jbs_parsidate( $input, $datetime = 'now', $lang = 'per' ) {
        $bndate = JBS_ParsiDate::getInstance();

        return $bndate->persian_date( $input, $datetime, $lang );
}

function jbs_convert_date_to_shamsi( $date_string ) {
    // Assuming the date is in Y-m-d format.
    // We can add more logic here to handle different formats if needed.
    return jbs_parsidate( 'Y/m/d', $date_string );
}

function jbs_convert_date_range_to_shamsi( $date_range_string ) {
    $dates = explode( ' - ', $date_range_string );
    if ( count( $dates ) === 2 ) {
        $from = jbs_parsidate( 'Y/m/d', $dates[0] );
        $to = jbs_parsidate( 'Y/m/d', $dates[1] );
        return $from . ' - ' . $to;
    }
    return $date_range_string;
}

add_filter( 'jet-booking/render/check-in-date', 'jbs_convert_date_to_shamsi' );
add_filter( 'jet-booking/render/check-out-date', 'jbs_convert_date_to_shamsi' );
add_filter( 'jet-booking/render/dates-range', 'jbs_convert_date_range_to_shamsi' );

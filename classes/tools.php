<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace local_whoisip;

use local_whoisip\requests\ipapi;
use moodle_exception;
use stdClass;

global $CFG;
require_once($CFG->dirroot.'/user/lib.php');

/**
 * Tools
 *
 * @package    local_whoisip
 * @copyright  Tresipunt {@link http://www.tresipunt.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tools {

    /**
     * Get Data by IP.
     */
    public static function get_data_by_ip(){
        try {
            $api = new ipapi();
            $api->get_data();
        } catch (moodle_exception $e) {
            var_dump($e->getMessage());
        }
    }


    /**
     * Update User with IP Data.
     * 
     * @param stdClass $user
     * @param stdClass $data
     */
    public static function update_user(stdClass $user, stdClass $data) {
        $user->city = $data->city;
        $user->country = $data->countrycode;
        user_update_user($user);
        profile_load_data($user);
        if (isset($user->profile_field_isp)) {
            $user->profile_field_isp = $data->isp;
        } else {
            $code = '3001';
            $msg = 'El campo personalizado de usuario ISP no existe.';
            error_log($code . ': ' . $msg);
        }
        if (isset($user->profile_field_lat)) {
            $user->profile_field_lat = $data->lat;
        }
        if (isset($user->profile_field_lon)) {
            $user->profile_field_lon = $data->lon;
        }
        profile_save_data($user);
    }

}
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

namespace local_whoisip\models;

use moodle_exception;
use stdClass;
use local_whoisip\tools;

/**
 * Logs
 *
 * @package    local_whoisip
 * @copyright  Tresipunt {@link http://www.tresipunt.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class logs {

    const TABLE = 'local_whoisip_logs';

    /**
     * Get by User ID.
     * 
     * @param int $userid
     * @return stdClass|bool
     */
    public static function get_by_userid(int $userid) {
        global $DB;
        try {
            $obj = $DB->get_record(self::TABLE, ['userid' => $userid]);
            return $obj;
        } catch (moodle_exception $e) {
            $code = '2002';
            $msg = $e->getMessage();
            error_log($code . ': ' . $msg);
            return false;
        }
    }

    /**
     * Insert or update.
     *
     * @param stdClass $data
     * @param stdClass|null $user
     */
    public static function insert_or_update(stdClass $data, stdClass $user = null) {
        global $DB, $USER;

        $user = !is_null($user) ? $user : $USER;
        
        $userid = optional_param('userid', $user->id, PARAM_INT);

        $user = \core_user::get_user($userid);

        $object = new stdClass();
        $object->userid = $user->id;
        $object->country = isset($data->country) ? $data->country : '';
        $object->countrycode = isset($data->countryCode) ? $data->countryCode : '';
        $object->region = isset($data->region) ? $data->region : '';
        $object->city = isset($data->city) ? $data->city : '';
        $object->zip = isset($data->zip) ? $data->zip : '';
        $object->lat = isset($data->lat) ? $data->lat : 0;
        $object->lon = isset($data->lon) ? $data->lon : 0;
        $object->timezone = isset($data->timezone) ? $data->timezone : '';
        $object->isp = isset($data->isp) ? $data->isp : '';
        $object->org = isset($data->org) ? $data->org : '';
        $object->asi = isset($data->as) ? $data->as : '';
        $object->timecreated = time();

        try {
            $obj = self::get_by_userid($user->id);
            if ($obj) {
                $object->id = $obj->id;
                $DB->update_record(self::TABLE, $object);
            } else {
                $DB->insert_record(self::TABLE, $object);
            }

            tools::update_user($user, $object);

        } catch (moodle_exception $e) {
            $code = '2001';
            $msg = $e->getMessage();
            error_log($code . ': ' . $msg);
        }

    }

}

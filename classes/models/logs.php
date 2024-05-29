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

/**
 * Logs
 *
 * @package    local_whoisip
 * @copyright  Tresipunt {@link http://www.tresipunt.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class logs {

    /**
     * Insert or update.
     *
     * @param stdClass $data
     * @param stdClass|null $user
     */
    public static function insert_or_update(stdClass $data, stdClass $user = null) {
        global $DB, $USER;

        $user = !is_null($user) ? $user : $USER;

        $object = new stdClass();
        $object->userid = $user->id;
        $object->timecreated = time();
        $object->country = isset($data->country) ? $data->country : '';
        $object->countrycode = isset($data->countryCode) ? $data->countryCode : '';
        $object->region = isset($data->region) ? $data->region : '';
        $object->city = isset($data->city) ? $data->city : '';

        try {
            $obj = $DB->get_record('local_whoisip_logs', ['userid' => $user->id]);
            if ($obj) {
                $DB->update_record('local_whoisip_logs', $obj);
            } else {
                $DB->insert_record('local_whoisip_logs', $object);
            }
        } catch (moodle_exception $e) {
            $code = '2001';
            $msg = $e->getMessage();
            error_log($code . ': ' . $msg);
        }

    }

}

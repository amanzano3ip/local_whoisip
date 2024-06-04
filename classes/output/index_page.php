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

namespace local_whoisip\output;
use renderable;
use templatable;
use renderer_base;
use stdClass;

/**
 * Class index_page
 *
 * @package    local_whoisip
 * @copyright  Tresipunt {@link http://www.tresipunt.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class index_page implements renderable, templatable {


    public function export_for_template(renderer_base $output) {
        global $DB;

        $data = new stdClass();

        $logs = $DB->get_records('local_whoisip_logs');

        $items = [];
        foreach ($logs as $log) {
            $item = new stdClass();
            $item->userid = $log->userid;
            $item->country = $log->country;
            $item->city = $log->city;
            $items[] = $item;
        }
        
        $data->logs = $items;

        return $data;

    }



}

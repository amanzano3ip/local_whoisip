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

/**
 * Local WhoIsIP settings
 *
 * @package    local_whoisip
 * @copyright  Tresipunt {@link http://www.tresipunt.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {

    $settings = new admin_settingpage(
            'local_whoisip',
            get_string('settingpage', 'local_whoisip')
    );


    $ADMIN->add('localplugins', $settings);
    //$ADMIN->add('modules', new admin_category('local_whoisip_category',
    //        'Local WhoIsIP Categoría'));
    //$ADMIN->add('local_whoisip_category', $settings);
    //$ADMIN->add('local_whoisip_category', new admin_externalpage('local_whoisip_index',
    //        'Página Principal',
    //        $CFG->wwwroot . '/local/whoisip/index.php'));

    $setting = new admin_setting_configtext(
            'local_whoisip/url',
            get_string('urlapi', 'local_whoisip'),
            get_string('urlapi_desc', 'local_whoisip'),
            'http://ip-api.com',
            PARAM_URL,
            60);

    $settings->add($setting);    

    $setting = new admin_setting_configtext(
        'local_whoisip/timeout',
        get_string('timeout', 'local_whoisip'),
        get_string('timeout_desc', 'local_whoisip'),
        5,
        PARAM_INT);

    $settings->add($setting);

}

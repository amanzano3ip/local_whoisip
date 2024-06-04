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
 * Index Page.
 *
 * @package    local_whoisip
 * @copyright  Tresipunt {@link http://www.tresipunt.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_whoisip\output\index_page;

include('../../config.php');

global $PAGE, $OUTPUT;

require_login();

if (is_siteadmin()) {

    $PAGE->set_url(new moodle_url('/local/whoisip/index.php'));
    $PAGE->set_context(context_system::instance());
    $PAGE->set_title('Listado de Logs de Geolocalización');
    $PAGE->set_heading('Listado de Logs de Geolocalización');
    $PAGE->set_pagelayout('incourse');

    $renderer = $PAGE->get_renderer('local_whoisip');
    $page = new index_page();

    echo $OUTPUT->header();

    echo $renderer->render($page);

    echo $OUTPUT->footer();

} else {

    throw new moodle_exception('Usted no es administrador');
}


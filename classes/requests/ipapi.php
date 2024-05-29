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

namespace local_whoisip\requests;

use curl;
use dml_exception;
use local_whoisip\models\logs;

global $CFG;
require_once($CFG->libdir . '/filelib.php');

/**
 * Requests
 *
 * @package    local_whoisip
 * @copyright  Tresipunt {@link http://www.tresipunt.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class ipapi {

    /** @var string URL API */
    protected $url;

    /**
     * Constructor.
     *
     * @throws dml_exception
     */
    public function __construct() {
        $this->url = get_config('local_whoisip', 'url');
    }

    /**
     * Get URL.
     *
     * @return string
     */
    protected function get_url(): string {
        return $this->url . '/json/' . $this->get_mock_ip();
    }

    /**
     * Get IP.
     *
     * @return string
     */
    protected function get_ip(): string {
        // Comprobar si está configurada la cabecera HTTP_CLIENT_IP
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        }
        // Comprobar si está configurada la cabecera HTTP_X_FORWARDED_FOR (posiblemente detrás de un proxy)
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Podría contener una lista de IPs si hay múltiples proxies, tomar la primera
            $ipAddress = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        }
        // De lo contrario, usar la IP remota
        else {
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        }

        return $ipAddress;
    }

    /**
     * Get Mock IP.
     *
     * @return string
     */
    public function get_mock_ip() {
        return '72.59.9.12';
    }

    /**
     * Get Data.
     */
    public function get_data() {
        try {
            $curl = new curl();
            $response = $curl->get($this->get_url());
            $response = json_decode($response);
            if (isset($response->status)) {
                if ($response->status === 'success') {
                    logs::insert_or_update($response);
                    error_log('PETICIÓN OK:' . json_encode($response));
                } else {
                    $code = '1002';
                    $msg = $response->message;
                    error_log($code . ': ' . $msg);
                }
            } else {
                $code = '1001';
                $msg = 'La respuesta no es la esperada. Compruebe la URL del API.';
                error_log($code . ': ' . $msg);
            }
        } catch (\Exception $e) {
            $code = '1000';
            $msg = $e->getMessage();
            error_log($code . ': ' . $msg);
        }

    }

}
<?php
/* 
 * Main API interface
 * 
 * All attributes are handled as GET request. Each module is included from
 * different file under "api" directory.
 * 
 * Copyleft (ↄ) 2015 Marek Pikuła
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'var.php';
require_once BASE_DIR . '/api/var.php';

echo XML_HEADER . XML_API_OPEN; //introduction to client

//check for client attribute and if it's valid
if ((check_attrib('client')) && (strcasecmp(filter_input(INPUT_GET, 'client'), 'android') != 0)) {
    error(APIError::client, true);
}

//check for version attribute and for beta version – it's unstable, so any API inconsistance is user fault
if ((check_attrib('version')) && (strcasecmp(filter_input(INPUT_GET, 'version'), 'beta') != 0)) {
    //check if client version is up to date
    if ((strcasecmp(filter_input(INPUT_GET, 'client'), 'android') == 0)         //check for Android version
            && (filter_input(INPUT_GET, 'version') != VERSION_APP_ANDROID)) {
        error(APIError::version, true, 'current="' . VERSION_APP_ANDROID . '"');
    }
}

//check for module attribute
if (check_attrib('module')) {
    //check if attribute is valid and if so, include it
    switch (filter_input(INPUT_GET, 'module')) {
        case "lucky": include 'api/lucky.php'; break;

        default: error(APIError::module, true); break;  //error if module name was not found
    }
}

echo XML_API_CLOSE;

<?php

/**
 * SMART Monitor drives controller.
 *
 * @category   Apps
 * @package    SMART_Monitor
 * @subpackage Controllers
 * @author     Tim Burgess <trburgess@gmail.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/smart_monitor/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Smart monitor drives controller.
 *
 * @category   Apps
 * @package    SMART_Monitor
 * @subpackage Controllers
 * @author     Tim Burgess <trburgess@gmail.com>
 * @copyright  2012 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/smart_monitor/
 */

class Drives extends ClearOS_Controller
{
    /**
     * Smart monitor logs controller
     *
     * @return view
     */

    function index()
    {
        // Load libraries
        //---------------

        $this->lang->load('base');

        // Load data
        //----------
        $drivelist = array();
        $drive = array();

        $drivelist = $this->smart_monitor->get_drives();
        foreach ($drivelist as $value) {
            $drive['name'] = $value;
            $drive['info'] = $this->smart_monitor->get_drive_info($value);
            if ($drive['info']['available']) {
                $drive['teststatus'] = $this->smart_monitor->get_test_status($value);
                $drive['assessment'] = $this->smart_monitor->get_health($value);
            }
            $drives[] = $drive;
        }
        $data['drives'] = $drives;

        // Load views
        //-----------

        $this->page->view_form('smart_monitor/drives', $data, lang('base_settings'));
    }
}

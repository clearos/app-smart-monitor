<?php

/**
 * SMART Log controller.
 *
 * @category   apps
 * @package    smart-monitor
 * @subpackage controllers
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
 * Smart log controller.
 *
 * @category   apps
 * @package    smart-monitor
 * @subpackage controllers
 * @author     Tim Burgess <trburgess@gmail.com>
 * @copyright  2012 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/smart_monitor/
 */

class Logs extends ClearOS_Controller
{
    /**
     * Smart monitor attributes controller
     *
     * @return view
     */

    function index($dev,$drive)
    {
        // Load libraries
        //---------------
        $this->lang->load('base');
        $this->load->library('smart_monitor/Smart_Monitor');

        // Load data
        //----------
        $path = "/$dev/$drive";
        $log = $this->smart_monitor->get_drive_log($path);
        $data['drive'] = $path;
        $data['details'] = $log;

        // Load views
        //-----------
        $options['type'] = MY_Page::TYPE_WIDE_CONFIGURATION;
        $this->page->view_form('smart_monitor/logs', $data, lang('base_settings'), $options);
    }
}

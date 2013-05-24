<?php

/**
 * SMART Monitor.
 *
 * @category   apps
 * @package    smart-monitor
 * @subpackage controllers
 * @author     Tim Burgess <trburgess@gmail.com>
 * @copyright  2011-2012 ClearFoundation
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
 * SMART Monitor Controller.
 *
 * @category   apps
 * @package    smart-monitor
 * @subpackage controllers
 * @author     Tim Burgess <trburgess@gmail.com>
 * @copyright  2011-2012 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/smart_monitor/
 */

class Smart_Monitor extends ClearOS_Controller
{
    function index() 
    {

        // Load libraries
        //---------------
        $this->lang->load('smart_monitor');
        $this->lang->load('base');
        $this->load->library('smart_monitor/Smart_Monitor');

        // Load views 
        //--------------

        $views = array('smart_monitor/settings', 'smart_monitor/server', 'smart_monitor/drives', 'smart_monitor/status');

        $this->page->view_forms($views, lang('smart_monitor_app_name'));
    }

    /**
     * Enable SMART entry view.
     *
     * @param string $drive Drive name 
     *
     * @return view
     */

    function enable($dev,$drive)
    {
        $this->load->library('smart_monitor/Smart_Monitor');
        
        $path = "/$dev/$drive";

        try{
            $this->smart_monitor->enable_smart($path);

            // Return to summary page with status message
            $this->page->set_status_added();
            $this->page->set_message(lang('smart_enabled').' '.$path, 'info');
            redirect('/smart_monitor');
        } catch (Exception $e) {
            $this->page->view_exception($e);
        }

    }

    /**
     * Disable SMART entry view.
     *
     * @param string $drive Drive name
     *
     * @return view
     */

    function disable($dev,$drive)
    {
        $this->load->library('smart_monitor/Smart_Monitor');

        $path = "/$dev/$drive";

        try{
            $this->smart_monitor->disable_smart($path);

            // Return to summary page with status message
            $this->page->set_status_added();
            $this->page->set_message(lang('smart_disabled').' '.$path, 'info');
            redirect('/smart_monitor');
        } catch (Exception $e) {
            $this->page->view_exception($e);
        }


    }

    /**
     * Run Short Self Test.
     *
     * @param string $drive Drive
     *
     * @return view
     */

    function start_test($dev,$drive)
    {
        $this->load->library('smart_monitor/Smart_Monitor');

        $path = "/$dev/$drive";

        try{
            $this->smart_monitor->start_short_test($path);

            // Return to summary page with status message
            $this->page->set_status_added();
            $this->page->set_message(lang('smart_short_test').' '.$path, 'info');
            redirect('/smart_monitor');
        } catch (Exception $e) {
            $this->page->view_exception($e);
        }


    }


}

?>


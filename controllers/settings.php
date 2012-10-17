<?php

/**
 * SMART email notification controller.
 *
 * @category   Apps
 * @package    Smart_Monitor
 * @subpackage Controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
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
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

use \Exception as Exception;

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * SMART email notification controller.
 *
 * @category   Apps
 * @package    Smart_Monitor
 * @subpackage Controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/smart_monitor/
 */

class Settings extends ClearOS_Controller
{
    /**
     * SMART email notification default controller
     *
     * @return view
     */

    function index()
    {
        $this->_view_edit('view');
    }

    /**
     * SMART email notification edit controller
     *
     * @return view
     */

    function edit()
    {
        $this->_view_edit('edit');
    }

    /**
     * View/edit common view
     *
     * @param string $mode form mode
     *
     * @return view
     */

    function _view_edit($mode = NULL)
    {
        // Load dependencies
        //------------------

        $this->load->library('smart_monitor/Smart_Monitor');
        $this->lang->load('smart_monitor');

        $data['mode'] = $mode;

        // Set validation rules
        //---------------------
         
        $this->form_validation->set_policy('sender', 'smart_monitor/Smart_Monitor', 'validate_email', TRUE);
        $form_ok = $this->form_validation->run();

        // Handle form submit
        //-------------------

        if (($this->input->post('submit') || $this->input->post('update_and_test')) && $form_ok) {
            try {
                $this->smart_monitor->set_sender($this->input->post('sender'));

                $this->page->set_status_updated();

                if ($this->input->post('update_and_test'))
                    redirect('/smart_monitor/test');
                else
                    redirect('/smart_monitor');
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }

        // Load view data
        //---------------

        try {
            $data['sender'] = $this->smart_monitor->get_sender();
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }

        // Load views
        //-----------

        $this->page->view_form('smart_monitor/settings', $data, lang('smart_monitor_app_name'));
    }

    /**
     * SMART email notification test controller
     *
     * @return view
     */

    function test()
    {

        $daemon = "smartd";
        $this->load->library('base/Daemon', $daemon);
        $this->load->library('smart_monitor/Smart_Monitor');

        try {
            //TODO: Fudge to force a notification email to be sent, restart the daemon with -M test flag
            $this->smart_monitor->set_test(TRUE);
            $this->daemon->set_running_state(FALSE);
            $this->daemon->set_running_state(TRUE);
            $this->smart_monitor->set_test(FALSE);
            //introduce small delay to allow service to pick up
            sleep(3);
            //restart again without test
            $this->daemon->set_running_state(FALSE);
            $this->daemon->set_running_state(TRUE);
            // Return to summary page with status message
            $this->page->set_status_added();
            $this->page->set_message(lang('smart_test_notification_sent'), 'info');
            redirect('/smart_monitor');
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }

    }
}

<?php

/**
 * SMART Monitor class.
 *
 * @category   Apps
 * @package    Smart_Monitor
 * @subpackage Libraries
 * @author     Tim Burgess <trburgess@gmail.com>
 * @copyright  2012 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/smart_monitor/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Lesser General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// N A M E S P A C E
///////////////////////////////////////////////////////////////////////////////

namespace clearos\apps\smart_monitor;

///////////////////////////////////////////////////////////////////////////////
// B O O T S T R A P
///////////////////////////////////////////////////////////////////////////////

$bootstrap = getenv('CLEAROS_BOOTSTRAP') ? getenv('CLEAROS_BOOTSTRAP') : '/usr/clearos/framework/shared';
require_once $bootstrap . '/bootstrap.php';

///////////////////////////////////////////////////////////////////////////////
// T R A N S L A T I O N S
///////////////////////////////////////////////////////////////////////////////

clearos_load_language('smart_monitor');

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

// Classes
//--------

use \clearos\apps\base\Shell as Shell;
use \clearos\apps\base\Engine as Engine;

clearos_load_library('base/Shell');
clearos_load_library('base/Engine');

// Exceptions
//-----------

use \clearos\apps\base\Engine_Exception as Engine_Exception;
use \clearos\apps\base\Validation_Exception as Validation_Exception;

clearos_load_library('base/Engine_Exception');
clearos_load_library('base/Validation_Exception');

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Smart Monitor class.
 *
 * @category   Apps
 * @package    Smart_Monitor
 * @subpackage Libraries
 * @author     Tim Burgess <trburgess@gmail.com>
 * @copyright  2012 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/smart_monitor/
 */

class Smart_Monitor extends Engine
{
    ///////////////////////////////////////////////////////////////////////////////
    // V A R I A B L E S
    ///////////////////////////////////////////////////////////////////////////////

    const CMD_SMARTCTL = '/usr/sbin/smartctl';
    const FILE_PARTITIONS = '/proc/partitions';
    const CMD_CAT = '/bin/cat';

    ///////////////////////////////////////////////////////////////////////////////
    // M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * SMART constructor.
     *
     */

    public function __construct()
    {
        clearos_profile(__METHOD__, __LINE__);

    } 


    /**
     * Function to determine drives 
     */
    public function GetDrives()
    {
        clearos_profile(__METHOD__, __LINE__);

        $shell = new Shell();
        $args = self::FILE_PARTITIONS;
        $shell->execute(self::CMD_CAT, $args, FALSE, $options);
        $retval = $shell->get_output();
        
        $count = 0;
        foreach ($retval as $line){
            if(preg_match('/\b[sh]d[a-z]\b/',$line)){
                $line2 = " ".$line;
                $line2 = preg_replace('/\s+/m','|',$line2);
                $pieces = explode("|",$line2);
                $drives[$count] = "/dev/" . $pieces[4];
                $count++;
            }
        }
        return $drives;
    }

    /**
      * Function to determine drive attributes
     */
    public function GetSmartData($drive)
    {
        clearos_profile(__METHOD__, __LINE__);

        $shell = new Shell();
        $args = '-A ' . $drive;
        $shell->execute(self::CMD_SMARTCTL, $args, true, $options);
        $retval = $shell->get_output();

        foreach ($retval as $line){
            $line2 = " ".$line;
            $line2 = preg_replace('/\s+/m','|',$line2);
            $pieces = explode("|",$line2);

            if($pieces[1]=='194'){
                $field = 'Temp';
                # fudge for odd raw values which contain hours and minutes
                $output[$field]['Raw'] = $pieces[10].$pieces[11].$pieces[12].$pieces[13].$pieces[14];
                $output[$field]['T'] = $pieces[6];
                $output[$field]['W'] = $pieces[5];
                $output[$field]['V'] = $pieces[4];
            }
            if($pieces[1]=='1'){
                $field = 'RawReadErrorRate';
                $output[$field]['Raw'] = $pieces[10];
                $output[$field]['T'] = $pieces[6];
                $output[$field]['W'] = $pieces[5];
                $output[$field]['V'] = $pieces[4];
            }
            if($pieces[1]=='12'){
                $field ='PowerCycle';
                $output[$field]['Raw'] = $pieces[10];
                $output[$field]['T'] = $pieces[6];
                $output[$field]['W'] = $pieces[5];
                $output[$field]['V'] = $pieces[4];
            }
            if($pieces[1]=='9'){
                $field = 'PowerOnHours';
                $output[$field]['Raw'] = $pieces[10];
                $output[$field]['T'] = $pieces[6];
                $output[$field]['W'] = $pieces[5];
                $output[$field]['V'] = $pieces[4];
            }
            if($pieces[1]=='7'){
                $field = 'SeekErrorRate';
                $output[$field]['Raw'] = $pieces[10];
                $output[$field]['T'] = $pieces[6];
                $output[$field]['W'] = $pieces[5];
                $output[$field]['V'] = $pieces[4];
            }
            if($pieces[1]=='5'){
                $field = 'ReAllocSector';
                $output[$field]['Raw'] = $pieces[10];
                $output[$field]['T'] = $pieces[6];
                $output[$field]['W'] = $pieces[5];
                $output[$field]['V'] = $pieces[4];
            }
            if($pieces[1]=='10'){
                $field = 'SpinRetryCnt';
                $output[$field]['Raw'] = $pieces[10];
                $output[$field]['T'] = $pieces[6];
                $output[$field]['W'] = $pieces[5];
                $output[$field]['V'] = $pieces[4];
            }
            if($pieces[1]=='3'){
                $field = 'SpinUpTime';
                $output[$field]['Raw'] = $pieces[10];
                $output[$field]['T'] = $pieces[6];
                $output[$field]['W'] = $pieces[5];
                $output[$field]['V'] = $pieces[4];
            }            
        }
        return $output;
    }

    /**
     * Function to determine drive attributes
     */
    public function GetSmartAttributes($drive)
    {
        clearos_profile(__METHOD__, __LINE__);

        $shell = new Shell();
        $args = '--attributes ' . $drive;
        $shell->execute(self::CMD_SMARTCTL, $args, true, $options);
        $retval = $shell->get_output();

        return $retval;
    }

    /**
      * Function to determine drive health
     */
    public function GetHealth($drive)
    {
 
        clearos_profile(__METHOD__, __LINE__);
        $shell = new Shell();
        $args = '-H ' . $drive;
        $shell->execute(self::CMD_SMARTCTL, $args, true, $options);
        $retval = $shell->get_output();

        foreach ($retval as $line){
            if(preg_match('/test result/',$line)){
                $line2 = " ".$line;
                $pieces = explode(":",$line2);
                $output = trim($pieces[1]);
            }
        }
        return $output;
    }

    /**
     * Function to determine test status, true if running
     */
    public function GetTestStatus($drive)
    {

        clearos_profile(__METHOD__, __LINE__);

        $shell = new Shell();
        $args = '-c ' . $drive;
        $shell->execute(self::CMD_SMARTCTL, $args, true, $options);
        $retval = $shell->get_output();

        $count = 0;
        foreach ($retval as $line){
            if($count==1){
                $output['status'] .= "<br>" .$line;
                $count = 0;
            }
            if(preg_match('/Self-test execution status/',$line)){
                $line2 = " ".$line;
                $line2 = preg_replace('/\s+/m',"|",$line2);
                $pieces = explode("|",$line2);
                $value = trim($pieces[5]);
                if($value!=0){
                    $output['running'] = true;
                    $output['status'] = substr($line,40);
                    $count = 1;
                } else {
                    $output['running'] = false;
                }
            }
        }
        return $output;
    }



    /**
      * Function to determine drive Information
     */
    public function GetDriveInfo($drive)
    {
        clearos_profile(__METHOD__, __LINE__);
    
        $shell = new Shell();
        $args = '-i ' . $drive;
        $shell->execute(self::CMD_SMARTCTL, $args, true, $options);
        $retval = $shell->get_output();

        foreach ($retval as $line){
            if(preg_match('/SMART support is/',$line)){
                 $line2 = " ".$line;
                 $pieces = explode(":",$line2);
                 $support = trim($pieces[1]);
                 if(preg_match('/Available/',$support))
                     $output['available'] = true;
                 if(preg_match('/Enabled/',$support))
                     $output['enabled'] = true;
             }
             if(preg_match('/Device supports SMART/',$line)){
                 $output['available'] = true;
                 if(preg_match('/and is Enabled/',$line))
                     $output['enabled'] = true;
             }
             if(preg_match('/Model Family/',$line)){
                 $line2 = " ".$line;
                 $pieces = explode(":",$line2);
                 $value = trim($pieces[1]);
                 $output['model'] = $value;
             }
             if(preg_match('/Device Model/',$line)){
                 $line2 = " ".$line;
                 $pieces = explode(":",$line2);
                 $value = trim($pieces[1]);
                 $output['device'] = $value;
             }
             if(preg_match('/Serial Number/',$line)){
                 $line2 = " ".$line;
                 $pieces = explode(":",$line2);
                 $value = trim($pieces[1]);
                 $output['serial'] = $value;
             }
             if(preg_match('/User Capacity/',$line)){
                 $line2 = " ".$line;
                 $line2 = preg_replace('/\s+/m',"|",$line);
                 $pieces = explode("|",$line2);
                 $value = preg_replace('/,/','',$pieces[2]);
                 $output['capacity'] = round($value/(1000*1000*1000));
             }
        }
        return $output;
    }

    /**
      * Function to determine drive SMART self tests
     */
    public function GetDriveLog($drive)
    {

        clearos_profile(__METHOD__, __LINE__);
 
        $shell = new Shell();
        $args = '--log=selftest ' . $drive;
        $shell->execute(self::CMD_SMARTCTL, $args, true, $options);
        $retval = $shell->get_output();

        foreach ($retval as $line){
            if(preg_match('/^#/',$line)){
                $found=true;
                $num = substr($line,0,3);
                $line2 = preg_replace('/\s\s+/m',"|",$line);
                $pieces = explode("|",$line2);
                $data['details'] = array(
                    'Num' => $num,
                    'Description' => $pieces[1],
                    'Status' => $pieces[2],
                    'Remaining' => $pieces[3],
                    'Lifetime' => $pieces[4],
                    'Error' => $pieces[5]
                );
                $table[] = $data;
            }
        }
        return $table;
    }

    /**
      * Function to initiate drive self test (short)
     */
    public function StartShortTest($drive)
    {

        clearos_profile(__METHOD__, __LINE__);
      
        $shell = new Shell();
        $args = '--test=short ' . $drive;
        $shell->execute(self::CMD_SMARTCTL, $args, true, $options);
        $output = $shell->get_output();
    
        return $output;
    }

    /**
      * Function to enable SMART
     */
    public function EnableSmart($drive)
    {
        clearos_profile(__METHOD__, __LINE__); 

        $shell = new Shell();
        $args = '--smart=on ' . $drive;
        $shell->execute(self::CMD_SMARTCTL, $args, true, $options);
        $output = $shell->get_output();
    
        return $output;
    }

    /**
      * Function to disable SMART
     */
    public function DisableSmart($drive)
    {
        clearos_profile(__METHOD__, __LINE__);
 
        $shell = new Shell();
        $args = '--smart=off ' . $drive;
        $shell->execute(self::CMD_SMARTCTL, $args, true, $options);
        $output = $shell->get_output();

        return $output;
    }

    /**
      * Function to draw graphs (associative array)
     */
    
    public function DrawGraph($values,$filename,$drivename)
    {

    # ------- The graph values in the form of associative array --Debug Only
    /*$values=array(
        "T" => 060,
        "W" => 130,
        "V" => 200,
    );*/

 
        $img_width=54;
        $img_height=100; 
        $margins=0;
        $topmargin=15;
        $bottommargin=15;

    # ---- Find the size of graph by substracting the size of borders
        $graph_width=$img_width - $margins * 2;
        $graph_height=$img_height- $margins * 2; 
        $img=imagecreate($img_width,$img_height);
    
        $bar_width=14;
        $total_bars=count($values);
        $gap= ($graph_width- $total_bars * $bar_width ) / ($total_bars +1);

 
    # -------  Define Colors ----------------
        $bar_color=imagecolorallocate($img,107,142,35);
        $background_color=imagecolorallocate($img,240,240,255);
        $border_color=imagecolorallocate($img,240,240,255);
        $line_color=imagecolorallocate($img,220,220,220);
 
    # ------ Create the border around the graph ------
        imagefilledrectangle($img,1,1,$img_width-2,$img_height-2,$border_color);
        imagefilledrectangle($img,$margins,$margins,$img_width-1-$margins,$img_height-1-$margins,$background_color);
 
    # ------- Max value is required to adjust the scale    -------
        $max_value=max($values);
        $ratio= ($graph_height-$bottommargin-$topmargin)/$max_value;

    # -------- Create scale and draw horizontal lines  --------
    # Horiz bars not required
        $horizontal_lines=0;
    /*$horizontal_gap=$graph_height/$horizontal_lines;

    for($i=1;$i<=$horizontal_lines;$i++){
        $y=$img_height - $margins - $horizontal_gap * $i ;
        imageline($img,$margins,$y,$img_width-$margins,$y,$line_color);
        $v=intval($horizontal_gap * $i /$ratio);
        imagestring($img,0,5,$y-5,$v,$bar_color);

    }*/
 
    # ----------- Draw the bars here ------
        for($i=0;$i< $total_bars; $i++){ 
        # ------ Extract key and value pair from the current pointer position
        list($key,$value)=each($values); 
        $x1= $margins + $gap + $i * ($gap+$bar_width) ;
        $x2= $x1 + $bar_width; 
        $y1=$margins -$bottommargin +$graph_height- intval($value * $ratio) ;
        $y2=$img_height-$margins -$bottommargin;
        imagestring($img,0,$x1+1,$y1-10,$value,$bar_color);
        imagestring($img,0,$x1+4,$img_height-10,$key,$bar_color);        
        imagefilledrectangle($img,$x1,$y1,$x2,$y2,$bar_color);
    }
    //header("Content-type:image/png");
    # dump graphs in temporary folder
        imagepng($img,'/var/clearos/smart_monitor/graph_'.$drivename.'_'.$filename.'.png',0);
    
    }

    ///////////////////////////////////////////////////////////////////////////////
    // V A L I D A T I O N   M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

}
// vim: syntax=php ts=4
?>


<?php

function ChangePasswordForm()
{
    $form = '
    <form action="" method="post" class="validate">
        <fieldset class="generalform">
	       <legend></legend>
	           <fieldset class="basic"><legend></legend>
                    <div class="formfield ">
                        <label class="formlabel  requiredfield" for="newpassword">New Password</label>
                        <div class="forminputwrapper">
                            <input type="password" id="newpassword" name="newpassword" class="form-control validatepassword" required>
                        </div>
                        <div class="clear"></div>
                     </div>
        
                     <div class="formfield ">
                        <label class="formlabel  requiredfield" for="conformpassword">Confirm New Password</label>
                        <div class="forminputwrapper">
                            <input type="password" id="conformpassword" name="confirmpassword" class="form-control validatepassword" required>
                        </div>
                        <div class="clear"></div>
                    </div>
                </fieldset>
        
                <div class="formbutton">
		            <input name="btnsubmit" id="btnsubmit" class="btn primary-color primary-bg-color py-2" value="Submit" type="submit">
	            </div>
            </fieldset>
        </form>';
    return $form;
    
}

/**
 * Status.
 *
 * @return string
 */
 function DisplayStats()
{
  /*   global $db, $tables, $ApplicationSettings;
    $listing = '';
    $listing .= '<h2> Welcome </h2>';
    $totalVehicles = $db->Execute("select * from " . $GLOBALS['Tables']['vehicle']);
    
    $listing .= '<table class="gridtable ui-widget ui-widget-content">
                  <tr>
                    <td align="right" width="50%"><b>Total Vehicles</b>: </td><td>' . \TAS\DB::Count($totalVehicles) . '</td>
                  </tr>
                </table> <div class="clear"></div>
                <p>&nbsp;</p>';
    return $listing; */
} 
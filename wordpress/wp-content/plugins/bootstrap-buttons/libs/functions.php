<?php

/*
  Built using My Shortcodes Pro
  (C) 2012 - David Cramer
  All Rights Reserved
*/

$bootstrapbuttonsElement = false;
$bootstrapbuttonsfooterOutput = '';
$bootstrapbuttonsheaderscripts = '';
$bootstrapbuttonsjavascript = array();

function bootstrapbuttons_setup(){

if(file_exists(BOOTSTRAPBUTTONS_PATH.'libs/define.php')){
    $define = unserialize(file_get_contents(BOOTSTRAPBUTTONS_PATH.'libs/define.php'));
}
if(file_exists(BOOTSTRAPBUTTONS_PATH.'libs/configs.php')){
    $configs = unserialize(file_get_contents(BOOTSTRAPBUTTONS_PATH.'libs/configs.php'));
}
if(!empty($define)){
    update_option('bootstrapbuttons', $define);
    unlink(BOOTSTRAPBUTTONS_PATH.'libs/define.php');
}
if(!empty($configs)){
 foreach($configs as $ID=>$element){
    update_option($ID, $element);
 }
 unlink(BOOTSTRAPBUTTONS_PATH.'libs/configs.php');
 
}
return true;
}
function bootstrapbuttons_exit(){
return true;
}
function bootstrapbuttons_widgetcss(){
    wp_enqueue_style('bootstrapbuttonswidgetcoreCSS', BOOTSTRAPBUTTONS_URL.'styles/core.css');
    wp_enqueue_style('bootstrapbuttonswidgetpanelCSS', BOOTSTRAPBUTTONS_URL.'styles/panel.css');
}
function bootstrapbuttons_widgetjs(){

    echo "<script>";
    echo "function bootstrapbuttons_addAnother(id){\n";

     echo "jQuery('#'+id).clone().val('').appendTo(jQuery('#'+id).parent()).attr('name', jQuery('#'+id).parent().find('.multiple').attr('ref')+'_'+jQuery('#'+id).parent().find('.widefat').length+']').wrap('<div class=\"addValue\">').after(' <div style=\"float:none; display:inline;\" class=\"fbutton addValueRemove\"><div style=\"padding:2px 2px 1px; margin-bottom: 5px; font-weight:normal;\" class=\"button\"><i style=\"margin-top:-1px;\" class=\"icon-minus-sign\"></i><span> Remove Value</span></div></div>');\n";

    echo "}";
        echo "jQuery('.addValueRemove').live('click', function(){\n";
                echo "var parent = jQuery(this).parent().parent();\n";
                echo "jQuery(this).parent().remove();\n";
                echo "var index = 1;\n";
                echo "jQuery(parent).find('.multiple').each(function(){\n";
                    echo "jQuery(this).attr('name', jQuery(parent).find('.multiple').attr('ref')+'_'+index+']');\n";
                    echo "index++;\n";
                echo "})\n";
            echo "})\n";
    echo "</script>";

}
function bootstrapbuttons_process() {
    global $bootstrapbuttonsfooterOutput, $bootstrapbuttonsheaderscripts, $bootstrapbuttonsjavascript;

    if(is_admin ()){
     if(!empty ($_GET['bootstrapbuttons'])){
         if($_GET['bootstrapbuttons'] == 'insert'){
             include(BOOTSTRAPBUTTONS_PATH.'/shortcode.php');
             die;
         }
     }


        return;
    }

    $url = url_to_postid($_SERVER['REQUEST_URI']);
    if(empty($url)){
        $url = get_option('page_on_front');
    }
    if(empty($url)){
        return;
    }
    $post = get_post($url);
    if(!empty($post)) {
        preg_match_all('/' . get_shortcode_regex() . '/s', $post->post_content, $used);
        $elements = get_option('bootstrapbuttons');
        if(empty($elements)){
            return;
        }
        foreach ($elements as $element => $options) {
            if(!empty($options['shortcode'])){
                if ($keys = array_keys($used[2], $options['shortcode'])) {
                    foreach($keys as $key){
                         if(!empty($used[3][$key])){
                             $setAtts = shortcode_parse_atts($used[3][$key]);
                         }else{
                             $setAtts = array();
                         }
                        if(!empty($options['variables'])){
                            $atts = array();
                            foreach($options['variables']['names'] as $varkey=>$variable){
                                if($options['variables']['type'][$varkey] == 'Dropdown'){
                                    $options['variables']['defaults'][$varkey] = trim(strtok($options['variables']['defaults'][$varkey], ','));
                                }
                                if(!empty($options['variables']['multiple'][$varkey])){
                                    $endLoop = true;
                                    $loopIndex = 1;
                                    while($endLoop){
                                        if(isset($setAtts[$variable.'_'.$loopIndex])){
                                            $atts[$variable.'_'.$loopIndex] = $setAtts[$variable.'_'.$loopIndex];
                                            $loopIndex++;
                                        }else{
                                            if($loopIndex === 1){
                                                $atts[$variable.'_'.$loopIndex] = $options['variables']['defaults'][$varkey];
                                            }
                                            $endLoop = false;
                                        }
                                    }
                                }else{
                                    $atts[$variable] = $options['variables']['defaults'][$varkey];
                                }
                            }
                        }
                         if(!empty($setAtts)){
                             $shortcodes[$element][] = shortcode_atts($atts, $setAtts);
                         }else{
                             $shortcodes[$element][] = false;
                         }
                    }
                }
            }
        }
    }

    if(empty($elements)){
        $elements = get_option('bootstrapbuttons');
        if(empty($elements)){
            return;
        }
    }

    $sidebars = get_option('sidebars_widgets');
    unset($sidebars['wp_inactive_widgets']);
    $widgets = array();
    foreach($sidebars as $sidebar=>$set){
        if(is_active_sidebar($sidebar)){
            foreach($set as $widget){
                foreach($elements as $element=>$options){
                    if(substr($widget,0,strlen($options['shortcode'])+1) == $options['shortcode'].'-'){
                        $prewidget = explode('-', $widget);
                        $widgets[$prewidget[1]] = $element;
                    }
                }
            }
        }
    }
    foreach($widgets as $key=>$element){
        $options = $elements[$element];
        $config = get_option('widget_'.$options['shortcode']);
        $setAtts = $config[$key];
        if(!empty($options['variables'])){
            $atts = array();
            foreach($options['variables']['names'] as $varkey=>$variable){
                if($options['variables']['type'][$varkey] == 'Dropdown'){
                    $options['variables']['defaults'][$varkey] = trim(strtok($options['variables']['defaults'][$varkey], ','));
                }
                if(!empty($options['variables']['multiple'][$varkey])){
                    $endLoop = true;
                    $loopIndex = 1;
                    while($endLoop){
                        if(isset($setAtts[$variable.'_'.$loopIndex])){
                            $atts[$variable.'_'.$loopIndex] = $setAtts[$variable.'_'.$loopIndex];
                            $loopIndex++;
                        }else{
                            if($loopIndex === 1){
                                $atts[$variable.'_'.$loopIndex] = $options['variables']['defaults'][$varkey];
                            }
                            $endLoop = false;
                        }
                    }
                }else{
                    $atts[$variable] = $options['variables']['defaults'][$varkey];
                }

            }
        }
        if(!empty($setAtts)){
            $shortcodes[$element][] = shortcode_atts($atts, $setAtts);
        }else{
            $shortcodes[$element][] = false;
        }

    }


    /* end widget scan */
    if(empty($shortcodes)){
        return;
    }
    foreach ($shortcodes as $ID=>$Instances) {
        foreach($Instances as $no=>$atts){
            $Element = get_option($ID);

            $instanceID = 'msp'.md5(serialize($atts)).$ID;

            $Element['_cssCode'] = str_replace('{{_id_}}',$instanceID, $Element['_cssCode']);
            $Element['_phpCode'] = str_replace('{{_id_}}',$instanceID, $Element['_phpCode']);


            if (!empty($Element['_jsLib'])) {
                foreach ($Element['_jsLib'] as $handle => $src) {
                    $in_footer = false;
                    if ($Element['_jsLibLoc'][$handle] == 2) {
                        $in_footer = true;
                    }
                    if(!empty($Element['_assetLabel'])){
                        foreach($Element['_assetLabel'] as $assetKey=>$AssetLabel){
                            $src = str_replace('{{'.$AssetLabel.'}}', BOOTSTRAPBUTTONS_URL.$Element['_assetURL'][$assetKey], $src);
                        }
                    }

                    wp_register_script($handle, $src, array('jquery'), false, $in_footer);
                    wp_enqueue_script($handle);
                }
            }
            if (!empty($Element['_cssLib'])) {
                foreach ($Element['_cssLib'] as $handle => $src) {
                    if(!empty($Element['_assetLabel'])){
                        foreach($Element['_assetLabel'] as $assetKey=>$AssetLabel){
                            $src = str_replace('{{'.$AssetLabel.'}}', BOOTSTRAPBUTTONS_URL.$Element['_assetURL'][$assetKey], $src);
                        }
                    }
                    wp_enqueue_style($handle, $src);
                }
            }

            if (!empty($Element['_cssCode'])) {

                if (!empty($Element['_variable'])) {

                    foreach ($Element['_variable'] as $VarKey => $Variable) {
                        $VarVal = $atts[$Variable];
                        if (!empty($atts[$Variable . '_1'])) {
                            $startcounter = true;
                            $index = 1;
                            while ($startcounter == true) {
                                if (!empty($atts[$Variable . '_' . $index])) {
                                    $varArray[trim($Variable)][] = $atts[$Variable . '_' . $index];
                                } else {
                                    $startcounter = false;
                                }
                                $index++;
                            }
                        }


                        $Element['_cssCode'] = str_replace('{{' . $Variable . '}}', $VarVal, $Element['_cssCode']);
                    }
                }
                 if(!empty($Element['_assetLabel'])){
                     foreach($Element['_assetLabel'] as $assetKey=>$AssetLabel){
                         $Element['_cssCode'] = str_replace('{{'.$AssetLabel.'}}', BOOTSTRAPBUTTONS_URL.$Element['_assetURL'][$assetKey], $Element['_cssCode']);
                     }
                 }


                ob_start();
                    eval(' ?>' . $Element['_cssCode'] . ' <?php ');
                $Css = ob_get_clean();
                $bootstrapbuttonsheaderscripts .= "
                " . $Css . "
                ";
            }

            if (!empty($Element['_phpCode'])) {
                eval($Element['_phpCode']);
            }
        }
    }
}

function bootstrapbuttons_shortcode_ajax(){

    if(empty($_POST['process']) && empty($_POST['shortcode'])){
        return false;
    }

    $elements = get_option('bootstrapbuttons');
    foreach($elements as $ID=>$element){
        if($element['shortcode'] == $_POST['shortcode']){
            break;
        }
    }
    $Config = get_option($ID);
    if(!empty($Config['_phpCode'])){
        eval($Config['_phpCode']);
        $_POST['process']();
    }


die;
}
function bootstrapbuttons_button() {

    echo "<a onclick=\"return false;\" id=\"my-shortcodes\" title=\"Bootstrap Buttons Shortcode Builder\" class=\"thickbox button\" style=\"border-radius:5px;\" href=\"?bootstrapbuttons=insert&TB_iframe=1&width=640&height=307\">\n";
    echo "Bootstrap Buttons";
    echo "</a>\n";
}

function bootstrapbuttons_header() {
    global $bootstrapbuttonsheaderscripts;

    if(!empty($bootstrapbuttonsheaderscripts)){
        echo "<style>\n";
            echo $bootstrapbuttonsheaderscripts;
        echo "</style>\n";
        $bootstrapbuttonsheaderscripts = '';
    }
}
function bootstrapbuttons_footer() {
    global $bootstrapbuttonsfooterOutput;
    if(!empty($bootstrapbuttonsfooterOutput)){
        echo "<script>\n";
        echo $bootstrapbuttonsfooterOutput;
        echo "</script>\n";
        $bootstrapbuttonsfooterOutput = '';
    }
}
function bootstrapbuttons_load_elementConfig($die = false){
         if(empty($_POST['element'])){
             echo 'Please select a shortcode to continue';
             die;
         }
        $Element = get_option($_POST['element']);
        if(empty($Element['_defaultContent'])){
         $Element['_defaultContent'] = 'Content Goes Here';
        }
        echo '<input type="hidden" id="shortcodekey" value="'.$Element['_shortcode'].'" />';
        echo '<input type="hidden" id="shortcodetype" value="'.$Element['_shortcodeType'].'" />';
        echo '<input type="hidden" id="defaultContent" value="'.$Element['_defaultContent'].'" />';
        if(empty($Element['_variable'])){
            echo 'This shortcode has no attributes to set.';
        }else{
            foreach($Element['_variable'] as $key=>$attr){

                echo '<div class="attr" ref="'.$attr.'">';
                echo '<span class="label">'.$attr.'</span>';
                $var = $attr;
                if(!empty($Element['_isMultiple'][$key])){
                    $var = $attr.'_1';
                }

                switch($Element['_type'][$key]){
                    default:
                    case 'Text':
                        echo '<input class="attrVal" type="text" id="'.$var.'" value="'.$Element['_variableDefault'][$key].'"/>';
                        break;
                    case 'File':
                        echo '<input class="attrVal" type="text" id="'.$var.'" value="'.$Element['_variableDefault'][$key].'" style="width:300px;"/>';
                        break;
                    case 'Dropdown':
                        $options = explode(',', $Element['_variableDefault'][$key]);
                        echo '<select class="attrVal" id="'.$var.'">';
                            foreach($options as $option){
                                echo '<option value="'.trim($option).'">'.trim($option).'</option>';
                            }
                        echo '</select>';
                }
                if(!empty($Element['_isMultiple'][$key])){
                    echo '<div class="fbutton" style="float:none; display:inline;">';
                    echo '  <div class="button" onclick="bootstrapbuttons_addAnother(\''.$var.'\');" style="padding:3px; font-weight:normal;">';
                    echo '      <i class="icon-plus" style="margin-top:-1px;"></i><span> Add Another '.ucwords(strtok($var, '_')).'</span>';
                    echo '  </div>';
                    echo '</div>';

                }
                echo ' <span class="description">'.$Element['_variableInfo'][$key].'</span>';
                echo '</div>';

            }
        }
        if($die == true){
            return;
        }
        die;
    }
function bootstrapbuttons_configOption($ID, $Name, $Type, $Title, $Config) {

    $Return = '';

    switch ($Type) {
        case 'hidden':
            $Val = '';
            if (!empty($Config['_' . $Name])) {
                $Val = $Config['_' . $Name];
            }
            $Return .= '<input type="hidden" name="data[_' . $Name . ']" id="' . $ID . '" value="' . $Val . '" />';
            break;
        case 'textfield':
            $Val = '';
            if (!empty($Config['_' . $Name])) {
                $Val = $Config['_' . $Name];
            }
            $Return .= '<label>'.$Title . '</label> <input type="text" name="data[_' . $Name . ']" id="' . $ID . '" value="' . $Val . '" />';
            break;
        case 'textarea':
            $Val = '';
            if (!empty($Config['_' . $Name])) {
                $Val = $Config['_' . $Name];
            }
            $Return .= '<label>'.$Title . '</label> <textarea name="data[_' . $Name . ']" id="' . $ID . '" cols="70" rows="25">' . htmlentities($Val) . '</textarea>';
            break;
        case 'radio':
            $parts = explode('|', $Title);
            $options = explode(',', $parts[1]);
            $Return .= '<label>'.$parts[0]. '</label>';
            $index = 1;
            foreach ($options as $option) {
                $sel = '';
                if (!empty($Config['_' . $Name])) {
                    if ($Config['_' . $Name] == $index) {
                        $sel = 'checked="checked"';
                    }
                }
                if (empty($Config)) {
                    if ($index === 1) {
                        $sel = 'checked="checked"';
                    }
                }

                $Return .= ' <input type="radio" name="data[_' . $Name . ']" id="' . $ID . '_' . $index . '" value="' . $index . '" ' . $sel . '/> <label for="' . $ID . '_' . $index . '" style="width:auto;">' . $option . '</label>';
                $index++;
            }
            break;
    }

    return '<div class="bootstrapbuttonsconfigOption">' . $Return . '</div>';
}

function bootstrapbuttons_doShortcode($atts, $content, $shortcode) {
    global $bootstrapbuttonsfooterOutput, $bootstrapbuttonsjavascript;

    $elements = get_option('bootstrapbuttons');
    foreach ($elements as $id => $element) {
        if (!empty($element['shortcode'])) {
            if ($element['shortcode'] === $shortcode) {
                break;
            }
        }
    }
    if (empty($id)) {
        return;
    }
    $Element = get_option($id);

    if(!empty($Element['_variable'])){
        $defaultatts = array();
        foreach($Element['_variable'] as $varkey=>$variable){
            if($Element['_type'][$varkey] == 'Dropdown'){
                $Element['_variableDefault'][$varkey] = trim(strtok($Element['_variableDefault'][$varkey], ','));
            }
            if(!empty($Element['_isMultiple'][$varkey])){
                $endLoop = true;
                $loopIndex = 1;
                while($endLoop){
                    if(isset($atts[$variable.'_'.$loopIndex])){
                        $defaultatts[$variable.'_'.$loopIndex] = $atts[$variable.'_'.$loopIndex];
                        $varArray[trim($variable)][] = $atts[$variable . '_' . $loopIndex];
                        $loopIndex++;
                    }else{
                        if($loopIndex === 1){
                            $defaultatts[$variable.'_'.$loopIndex] = $Element['_variableDefault'][$varkey];
                            $varArray[trim($variable)][] = $Element['_variableDefault'][$varkey];
                        }
                        $endLoop = false;
                    }
                }
            }else{
                $defaultatts[$variable] = $Element['_variableDefault'][$varkey];
            }

        }
        $atts = shortcode_atts($defaultatts, $atts);
    }else{
        $atts = false;
    }

    $instanceID = 'msp'.md5(serialize($atts)).$id;

    $Element['_mainCode'] = str_replace('{{content}}', $content, $Element['_mainCode']);
    $Element['_mainCode'] = str_replace('{{_id_}}',$instanceID, $Element['_mainCode']);

    $Element['_javascriptCode'] = str_replace('{{content}}', $content, $Element['_javascriptCode']);
    $Element['_javascriptCode'] = str_replace('{{_id_}}',$instanceID, $Element['_javascriptCode']);

    $pattern = '\[(\[?)(loop)\b([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
    preg_match_all('/' . $pattern . '/s', $Element['_mainCode'], $loops);
    if (!empty($loops)) {
        foreach ($loops[0] as $loopKey => $loopcode) {
            if (!empty($loops[3][$loopKey])) {
                $LoopCodes[$loopKey] = $loops[5][$loopKey];
                $Element['_mainCode'] = str_replace($loopcode, '{{__loop_' . $loopKey . '_}}', $Element['_mainCode']);
                $Element['_javascriptCode'] = str_replace($loopcode, '{{__loop_' . $loopKey . '_}}', $Element['_javascriptCode']);
            }
        }
    }
    if(!empty($Element['_assetLabel'])){
        foreach($Element['_assetLabel'] as $assetKey=>$AssetLabel){
            $Element['_mainCode'] = str_replace('{{'.$AssetLabel.'}}', BOOTSTRAPBUTTONS_URL.$Element['_assetURL'][$assetKey], $Element['_mainCode']);
        }
    }

    if (!empty($Element['_variable'])) {
        foreach ($Element['_variable'] as $VarKey => $Variable) {
            $VarVal = $Element['_variableDefault'][$VarKey];
            if (isset($atts[$Variable])) {
                $VarVal = $atts[$Variable];
            }

            $Element['_mainCode'] = str_replace('{{' . $Variable . '}}', $VarVal, $Element['_mainCode']);
            $Element['_javascriptCode'] = str_replace('{{' . $Variable . '}}', $VarVal, $Element['_javascriptCode']);
        }

        if (!empty($LoopCodes) && !empty($varArray)) {
            foreach ($LoopCodes as $loopKey => $loopCode) {
                $loopReplace = '';
                if (!empty($varArray[trim($loops[3][$loopKey])])) {
                    foreach ($varArray[trim($loops[3][$loopKey])] as $replaceKey => $replaceVar) {
                        $loopReplace .= $loopCode;
                        foreach ($varArray as $Variable => $VarableArray) {
                            if (!empty($varArray[$Variable][$replaceKey])) {
                                $loopReplace = str_replace('{{' . $Variable . '}}', $varArray[$Variable][$replaceKey], $loopReplace);
                            } else {
                                $loopReplace = str_replace('{{' . $Variable . '}}', '', $loopReplace);
                            }
                            $loopReplace = str_replace('[increment]', $replaceKey, $loopReplace);
                        }
                    }
                    $Element['_mainCode'] = str_replace('{{__loop_' . $loopKey . '_}}', $loopReplace, $Element['_mainCode']);
                    $Element['_javascriptCode'] = str_replace('{{__loop_' . $loopKey . '_}}', $loopReplace, $Element['_javascriptCode']);
                }
            }
        }
    }

    if (!empty($Element['_javascriptCode'])) {

        ob_start();
            eval(' ?>' . $Element['_javascriptCode'] . ' <?php ');
        $js = ob_get_clean();

        $bootstrapbuttonsfooterOutput .= "
        " . $js . "

        ";
    }


    ob_start();
    eval(' ?>' . $Element['_mainCode'] . ' <?php ');
    $Output = ob_get_clean();
    $Output = str_replace("\r\n", "", $Output);
    $Output = str_replace("\r", "", $Output);
    $Output = str_replace("\n", "", $Output);

    return do_shortcode($Output);
}

?>
<?php
function {{_pluginID}}_posttypes(){
     {{_registerPostTypes}}
}
function {{_pluginID}}_metaData(){
    add_meta_box('General Config', 'General Config', '{{_pluginID}}_metaForm', '{{_pluginID}}', 'normal', 'high',array());
}

function {{_pluginID}}_metaForm($post){

    $elements = get_option('{{_pluginID}}');
    foreach($elements as $element=>$settings){

        if($post->post_type == $settings['shortcode']){
            $Element = get_option($element);
            break;
        }
    }

    $instance = get_post_meta($post->ID, $element, true);

    foreach($Element['_variable'] as $key=>$attr){


        echo '<div><label class="label" style="width:100%;">'.ucfirst($attr).'</label>';
        $var = $attr;
        $class = "";
        $ref = "";
        if(!empty($Element['_isMultiple'][$key])){
            $var = $attr.'_1';
               $class = "multiple";
               $ref = 'ref="'.str_replace('_1]', '', 'elementMeta['.$element.']['.$var.']').'"';
        }
         if(!empty($instance[$var])){
              $Element['_variableDefault'][$key] = $instance[$var];
          }
        switch($Element['_type'][$key]){
            default:
            case 'Text':
                echo '<input class="widefat '.$class.'" '.$ref.' name="elementMeta['.$element.']['.$var.']" type="text" id="field_'.$var.'" value="'.$Element['_variableDefault'][$key].'"/>';
                break;
            case 'File':
                echo '<input class="widefat '.$class.'" '.$ref.' name="elementMeta['.$element.']['.$var.']" type="text" id="field_'.$var.'" value="'.$Element['_variableDefault'][$key].'" style="width:300px;"/>';
                break;
            case 'Dropdown':
                $options = explode(',', $Element['_variableDefault'][$key]);
                echo '<select class="widefat '.$class.'" '.$ref.' name="elementMeta['.$element.']['.$var.']" id="field_'.$var.'">';
                    foreach($options as $option){
                        echo '<option value="'.trim($option).'">'.trim($option).'</option>';
                    }
                echo '</select>';
        }
        if(!empty($Element['_isMultiple'][$key])){
            echo '<div class="fbutton" style="float:none; display:inline;">';
            echo '  <div class="button" onclick="footlocker_addAnother(\'field_'.$var.'\');" style="padding:2px 2px 1px; margin-bottom: 5px; font-weight:normal;">';
            echo '      <i class="icon-plus" style="margin-top:-1px;"></i> Add Value';
            echo '  </div>';
            echo '</div>';
            if(!empty($instance[$attr.'_1'])){
               $index = 2;
               $getLoop = true;
               $baseName = str_replace('_1]', '', $this->get_field_name($var));
               $baseID = str_replace('_1', '', $this->get_field_id($var));
               while($getLoop){
                   if(!empty($instance[$attr.'_'.$index])){
                    echo '<div class="addValue">';
                    echo '<input type="text" value="'.$instance[$attr.'_'.$index].'" id="'.$baseID.'" name="'.$baseName.'_'.$index.']" ref="'.$baseName.'_'.$index.'" class="widefat multiple"> ';
                    echo '<div class="fbutton addValueRemove" style="float:none; display:inline;">';
                    echo '<div class="button" style="padding:2px 2px 1px; margin-bottom: 5px; font-weight:normal;">';
                    echo '<i class="icon-minus-sign" style="margin-top:-1px;"></i>';
                    echo '<span> Remove Value</span>';
                    echo '</div></div></div>';
                    $index++;
                   }else{
                       $getLoop = false;
                   }
               }
            }
        }
        echo '</div><div><span class="description">'.$Element['_variableInfo'][$key].'</span></div>';

    }
}
function footlocker_saveMetaData($post_id){

    if(empty($_POST['elementMeta'])){
        return;
    }

    foreach($_POST['elementMeta'] as $element=>$array){
        $data = $array;
    }
    update_post_meta($post_id, $element, $data);
}

?>

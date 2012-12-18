<?php


class bootstrapbuttons_bsbutton extends WP_Widget {
        function bootstrapbuttons_bsbutton() {
            $widget_ops = array( 'description' => 'Bootstrap Button');
            $control_ops = array( 'width' => 290, 'id_base' => 'bsbutton');
            parent::WP_Widget('bsbutton', 'Button', $widget_ops, $control_ops);
        }
    function form($instance) {
            // outputs the options form on admin
            $Elements = get_option('bootstrapbuttons');
            foreach($Elements as $ID=>$Config){
                $Element = get_option($ID);
            }
            $title = '';
            if(!empty($instance['_title'])){
                $title = $instance['_title'];
            }
             $addTitle = '1';
             if($addTitle === '1'){
                 echo '<p><label class="label">Title</label>';
                 echo '<input class="widefat" name="'.$this->get_field_name('_title').'" type="text" id="'.$this->get_field_id('_title').'" value="'.$title.'"/>';
                 echo '</p>';
             }

            foreach($Element['_variable'] as $key=>$attr){


                echo '<div><label class="label" style="width:100%;">'.ucfirst($attr).'</label>';
                $var = $attr;
                $class = "";
                $ref = "";
                if(!empty($Element['_isMultiple'][$key])){
                    $var = $attr.'_1';
                       $class = "multiple";
                       $ref = 'ref="'.str_replace('_1]', '', $this->get_field_name($var)).'"';
                }
                 if(!empty($instance[$var])){
                      $Element['_variableDefault'][$key] = $instance[$var];
                  }
                switch($Element['_type'][$key]){
                    default:
                    case 'Text':
                        echo '<input class="widefat '.$class.'" '.$ref.' name="'.$this->get_field_name($var).'" type="text" id="'.$this->get_field_id($var).'" value="'.$Element['_variableDefault'][$key].'"/>';
                        break;
                    case 'File':
                        echo '<input class="widefat '.$class.'" '.$ref.' name="'.$this->get_field_name($var).'" type="text" id="'.$this->get_field_id($var).'" value="'.$Element['_variableDefault'][$key].'" style="width:300px;"/>';
                        break;
                    case 'Dropdown':
                        $options = explode(',', $Element['_variableDefault'][$key]);
                        echo '<select class="widefat '.$class.'" '.$ref.' name="'.$this->get_field_name($var).'" id="'.$this->get_field_id($var).'">';
                            foreach($options as $option){
                                echo '<option value="'.trim($option).'">'.trim($option).'</option>';
                            }
                        echo '</select>';
                }
                if(!empty($Element['_isMultiple'][$key])){
                    echo '<div class="fbutton" style="float:none; display:inline;">';
                    echo '  <div class="button" onclick="bootstrapbuttons_addAnother(\''.$this->get_field_id($var).'\');" style="padding:2px 2px 1px; margin-bottom: 5px; font-weight:normal;">';
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
            if($Element['_shortcodeType'] == 2){
                $content = $Element['_defaultContent'];
                if(!empty($instance['_content'])){
                    $content = $instance['_content'];
                }
                echo '<p>Content: <textarea id="'.$this->get_field_id('_content').'" class="widefat" name="'.$this->get_field_name('_content').'" rows="12">'.$content .'</textarea></p>';
            }

        }
    function update($new_instance, $old_instance) {
            // processes widget options to be saved

            return $new_instance;
        }
    function widget($args, $instance) {
         extract( $args );
         if(!empty($instance['_content'])){
             $content = $instance['_content'];
             unset($instance['_content']);
         }else{
             $content = false;
         }
         foreach($instance as $key=>$val){
             $instance[$key] = strip_tags($val);
         }
         $showTitle = '1';
         $showWrap = '1';
         if($showWrap === '1'){
             echo $before_widget;
         }
         if(!empty($instance['_title']) && $showTitle === '1'){
             echo $before_title.$instance['_title'].$after_title;
         }
         echo bootstrapbuttons_doShortcode($instance, $content, 'bsbutton');
         if($showWrap === '1'){
             echo $after_widget;
         }
        }
}

function bootstrapbuttons_bsbutton_init(){
    register_widget('bootstrapbuttons_bsbutton');
}


?>
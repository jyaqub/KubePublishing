<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
        <title>Bootstrap Buttons Builder</title>
        <link media="all" type="text/css" href="<?php echo get_admin_url(); ?>load-styles.php?c=1&dir=ltr&load=wp-admin,media&ver=3.4-RC1" rel="stylesheet">
        <link id="colors-css" media="all" type="text/css" href="<?php echo get_admin_url(); ?>css/colors-fresh.css" rel="stylesheet">
        <link media="all" type="text/css" href="<?php echo BOOTSTRAPBUTTONS_URL; ?>styles/core.css" rel="stylesheet">
        <link media="all" type="text/css" href="<?php echo BOOTSTRAPBUTTONS_URL; ?>styles/panel.css" rel="stylesheet">
        <script type='text/javascript' src='<?php echo get_admin_url(); ?>load-scripts.php?c=1&amp;load=jquery,utils'></script>
    </head>
    <body>
        <div class="toolbar">
            <span id="element"><?php

                
                $Elements = get_option('bootstrapbuttons');
                if(count($Elements) === 1){
                    foreach($Elements as $ID=>$Config){
                            echo '<h2 style="float:left; margin-top: 8px;">'.$Config['name'].'</h2>';
                            echo '<input type="hidden" id="selectedelement" value="'.$ID.'" />';
                    }
                    $preload = true;
                }else{
                    $Items = array();
                    foreach($Elements as $ID=>$Config){
                            $Items[$ID] = $Config['name'];
                    }

                    echo "Shortcode: <select class=\"\" id=\"selectedelement\" onchange=\"bootstrapbuttons_loadElement();\">\n";
                    echo "<option value=\"\"></option>";
                    foreach($Items as $ID=>$Element){
                        echo "<option value=\"".$ID."\">".$Element."</option>\n";
                    }
                    echo "</select>";
                }
            ?></span>
            <div class="fbutton" style="float:right; margin-right:5px;">
                <div class="button" onclick="bootstrapbuttons_sendCode();">
                    <i class="icon-plus" style="margin-top:-1px;"></i> Insert Shortcode
                </div>
            </div>
        </div>
        <div class="content" id="content">
        <?php

            if(isset($preload)){
                $_POST['element'] = $ID;
                echo bootstrapbuttons_load_elementConfig(true);
            }

        ?>
        </div>
        <div class="footer">

        </div>

        <script>

            function bootstrapbuttons_addAnother(id){
                jQuery('#'+id).clone().val('').appendTo(jQuery('#'+id).parent()).attr('id', jQuery('#'+id).parent().attr('ref')+'_'+jQuery('#'+id).parent().find('.attrVal').length).wrap('<div class="addValue">').after(' <div style="float:none; display:inline;" class="fbutton addValueRemove"><div style="padding:3px; font-weight:normal;" class="button"><i style="margin-top:-1px;" class="icon-minus-sign"></i><span> Remove Value</span></div></div>');
            }
            function bootstrapbuttons_sendCode(){
                if(jQuery('#selectedelement').length > 0){
                    if(jQuery('#selectedelement').val() == ''){
                        return;
                    }
                    var shortcode = jQuery('#shortcodekey').val();
                    var output = '['+shortcode;
                    var ctype = '';
                    if(jQuery('#shortcodetype').val() == '2'){
                        var ctype = jQuery('#defaultContent').val()+'[/'+shortcode+']';
                    }
                    jQuery('.attrVal').each(function(){
                        output += ' '+this.id+'="'+this.value+'"';
                    });
                    var win = window.dialogArguments || opener || parent || top;
                    win.send_to_editor(output+']'+ctype);
                }
            }
            function bootstrapbuttons_loadElement(){
                    var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>';
                    var element = jQuery('#selectedelement').val();
                    var data = {
                            action: 'bootstrapbuttons_load_elementConfig',
                            element: element
                    };
                    jQuery('#content').html('Loading Config...');
                    jQuery.post(ajaxurl, data, function(response) {
                        jQuery('#content').html(response);
                    });

            }

            jQuery('.addValueRemove').live('click', function(){
                var parent = jQuery(this).parent().parent();
                jQuery(this).parent().remove();
                var index = 1;
                jQuery(parent).find('.attrVal').each(function(){
                    jQuery(this).attr('id', jQuery(parent).attr('ref')+'_'+index);
                    index++;
                })
            })

        </script>
    </body>
</html>
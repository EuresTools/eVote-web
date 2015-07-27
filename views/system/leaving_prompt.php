<?php

?>
<script type="text/javascript">
<?php $this->beginBlock('JS_READY') ?>
var UNLOAD_MSG = "You will lose any unsaved changes!";

function doBeforeUnload() {
   if(window.event)
      window.event.returnValue = UNLOAD_MSG; // IE
   else
      return UNLOAD_MSG; // FF
}


function setBunload(on)
{
    if(!on)
    {
        $(window).unbind("unload");
        if(window.body)
        {
           if(window.body.onbeforeunload)
             window.body.onbeforeunload = null;
        }else
        {
           if(window.onbeforeunload)
              window.onbeforeunload = null;
        }
    }else
    {
       $(window).unload(function () {
          //alert("entry unlocked!");
          //setTimeout(function() {doUnlock();},150);
        }
       );
    }
}

jQuery(document).ready(function() {
    var field_selector ='input, select, textarea';
     $("#content form").find(field_selector).each(function() {
        $(this).change( function(){
            window.onbeforeunload =  doBeforeUnload;
        });
    });
    setBunload(true);
    $("#content form").submit(function(){
      // remove onunload check if the form is submitted.
      setBunload(false);
    });
});
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_READY'], yii\web\View::POS_READY);

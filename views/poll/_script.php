<?php
use yii\helpers\Url;

?>
<script type="text/javascript">
<?php $this->beginBlock('JS_END') ?>
    yii.process = (function ($) {
        var _onSearch = false;
        var pub = {
            action: function () {
            	var container = $('#options-container');
                var action = $(this).data('action');
                var count = $("#options-container > div").length;
                var form = $(container).closest("form");
                var form_id = $(form).attr('id');

                console.log(['action',action]);


                if (action ==='add-option') {
                	// clone the 1st element of the container and clone it
                	//var first_child = $(container).find(':first-child');
                	//
                	var new_count=count + 1;
                	var new_index= new_count-1;

                	console.log(['new_count',new_count]);
                	var first_child = $(container).children(':first-child');
                	console.log(['first_child',first_child]);

                	var clone =  $(first_child).clone().appendTo("#options-container");
                	$(clone).attr("class", $(clone).attr("class").replace(/field-option-\d+-text/, 'field-option-'+ new_index + '-text'));


                	
                	$(clone).find("label").each(function(){
        				$(this).attr("for", $(this).attr("for").replace(/option-\d+-text/, 'option-'+ new_index + '-text'));
        				$(this).html($(this).html().replace(/Option \d+/, 'Option '+ new_count));
        				
        				//$(this).attr("for", $(this).attr("for").replace(/option-\d+-text/, 'option-'+ new_count + '-text'));
    				});
    				var new_input_id;
    				var new_input_name; 
    				$(clone).find("input").each(function(){

        				$(this).attr("id", $(this).attr("id").replace(/option-\d+-text/, 'option-'+ new_index + '-text') );

        				new_input_id = $(this).attr("id");
        				//console.log(['name',$(this).attr("name")]);
        				$(this).attr("name",'Option['+new_index+'][text]');
        				new_input_name = $(this).attr("name");
        				$(this).val('');
        				//$(this).attr("name", $(this).attr("name").replace(/Option[\d+][text]/, 'Option['+ new_index + '][text]') );
    				});
    				$(clone).find("div.help-block").html();
    				$(clone).removeClass('has-error');


    				

    				console.log($(first_child).find("input").attr('id'));
    				console.log(form_id);
//var test = jQuery('#'+form_id).yiiActiveForm('find', {'id':$(first_child).find("input").attr('id')});
var test = jQuery('#'+form_id).yiiActiveForm('find', $(first_child).find("input").attr('id'));
    				console.log(['test',test]);

    					var test_copy = test;
    					test_copy.id = new_input_id;
    					test_copy.name = new_input_name;
    					test_copy.container =  '.field-'+new_input_id;
    					test_copy.input =  '#'+new_input_id;

    					$('#'+form_id).yiiActiveForm('add',test_copy);

    					// jQuery('#w0').yiiActiveForm('add',[{"id":"option-2-text","name":"[2]text","container":".field-option-2-text","input":"#option-2-text","validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Text cannot be blank."});yii.validation.string(value, messages, {"message":"Text must be a string.","max":255,"tooLong":"Text should contain at most 255 characters.","skipOnEmpty":1});}}]);

    					/*

    					$('#'+form_id).yiiActiveForm('add', 
    					{
    'id': new_input_id,
    'name': new_input_name,
    'container': '.field-'+new_input_id,
    'input': '#'+new_input_id,
    'error': '.help-block',
    'validate': function (attribute, value, messages, deferred, $form) {
            yii.validation.required(value, messages, {'message': 'Text cannot be blank.'});
            yii.validation.string(value, messages, {"message":"Text must be a string.","max":255,"tooLong":"Text should contain at most 255 characters.","skipOnEmpty":1});
        }
});
    					*/


    			}	


                if (action ==='remove-option') {
		    		if (count > 2) {
				        $('#options-container > div:last-child').remove();
				       // jQuery('#form-id').yiiActiveForm("remove", {"id":        "input-id"});
		    		}
		    		//updateRemoveButton();
                }
                return false;
            }
        }
        return pub;
    })(window.jQuery);
<?php $this->endBlock(); ?>

<?php $this->beginBlock('JS_READY') ?>
    $('a[data-action]').click(yii.process.action);
<?php $this->endBlock(); ?>
</script>
<?php
yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'], yii\web\View::POS_END);
$this->registerJs($this->blocks['JS_READY'], yii\web\View::POS_READY);

$(function() {

    
    var form = $("#signup-form");
    form.steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        labels: {
            previous : 'Previous',
            next : 'Next',
            finish : 'Submit',
            current : ''
        },
		titleTemplate : '<div class="title"><span class="title-text">#title#</span><span class="title-number">0#index#</span></div>',
		onStepChanging: function (event, currentIndex, newIndex) {
			form.validate().settings.ignore = ":disabled,:hidden";
			return form.valid();
		},
		onFinishing: function (event, currentIndex) {
			form.validate().settings.ignore = ":disabled";
			return form.valid();
		},
        onFinished: function (event, currentIndex)
        {
            var data = jQuery("#signup-form").serialize();
            // alert(data);
            jQuery("#signup-form").submit();
            form.submit();
        }
    });

    $('.forward').click(function(){
    	$("#signup-form").steps('next');
    })
    $('.backward').click(function(){
        $("#signup-form").steps('previous');
    })
    $('#submit').click(function(){
        $("#signup-form").steps('finish');
    })
    
    
})

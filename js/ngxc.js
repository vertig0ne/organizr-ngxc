$(document).on('change asColorPicker::close', '#ngxc-settings-page :input', function(e) {
    var input = $(this);
    switch ($(this).attr('type')) {
        case 'switch':
        case 'checkbox':
            var value = $(this).prop("checked") ? true : false;
            break;
        default:
            var value = $(this).val();
    }
        var post = {
        api:'api/?v1/update/config',
        name:$(this).attr("name"),
        type:$(this).attr("data-type"),
        value:value,
        messageTitle:'',
        messageBody:'Updated Value for '+$(this).parent().parent().find('label').text(),
        error:'Organizr Function: API Connection Failed'
    };
        var callbacks = $.Callbacks();
    //callbacks.add( buildCustomizeAppearance );
    settingsAPI(post,callbacks);
    //disable button then renable
    $('#ngxc-settings-page :input').prop('disabled', 'true');
    setTimeout(
        function(){
            $('#ngxc-settings-page :input').prop('disabled', null);
            input.emulateTab();
        },
        2000
    );

});

$(document).on('click', '#ngxc-settings-button', function() {
    var post = {
        plugin:'ngxc/settings/get', // used for switch case in your API call
    };
    ajaxloader(".content-wrap","in");
    organizrAPI('POST','api/?v1/plugin',post).success(function(data) {
        var response = JSON.parse(data);
        $('#ngxc-settings-items').html(buildFormGroup(response.data));
    }).fail(function(xhr) {
        console.error("Organizr Function: API Connection Failed");
    });
    ajaxloader();
});

$(document).on('click', '.ngxc-WriteConfig', function() {
    var post = {
        plugin:'ngxc/settings/save', // used for switch case in your API call
    };
    ajaxloader(".content-wrap","in");
    organizrAPI('POST','api/?v1/plugin',post).success(function(data) {
        var response = JSON.parse(data);
        if(response.data == true){
            messageSingle('',window.lang.translate('Write Successful'),'bottom-right','#FFF','success','5000');
        }else{
            messageSingle('',response.data,'bottom-right','#FFF','error','5000');
        }
    }).fail(function(xhr) {
        console.error("Organizr Function: API Connection Failed");
    });
    ajaxloader();
});

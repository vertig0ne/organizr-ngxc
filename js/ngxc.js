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

$(document).on('click', '.ngxc-write-config', function() {
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

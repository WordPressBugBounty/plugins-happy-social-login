jQuery(document).ready(function($) {

    function waitForCsfAjaxSave() {
        return new Promise((resolve, reject) => {
            jQuery(document).ajaxSuccess(function (event, xhr, options) {
                if (options.data && options.data.indexOf('action=csf_' + $('.csf-options').data('unique') + '_ajax_save') !== -1) {
                    resolve(xhr.responseText);
                }else{
                    reject('Something went wrong');
                }
                //Make sure you removed progress... from save button and revert its value to save
                $('input.csf-save-ajax').val("Save");
            });
        });
    }

    $(document).on('hslogin::verify', function(e) {
        $('input.csf-save-ajax').trigger('click');
        //First save the data and then open the popup and verify the credentials
        waitForCsfAjaxSave().then(()=>{
            let $el = $(e.detail.el);
            let provider = $el.data('provider');
            window.launchAuthWindow(provider);
        })
    });

    //Use it when you send the response from popup and want to do somehting on Opener page
    // window.addEventListener('storage', function(event) {
    //     if (event.key === 'hsloginResponse') {
    //         let res = JSON.parse(localStorage.getItem('hsloginResponse'));
    //         console.log(res);
    //         localStorage.removeItem('hsloginResponse');
    //     }
    // });
});
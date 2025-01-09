"use strict";

window.addEventListener( 'elementor/init', ()=> {

    elementor.hooks.addAction( 'panel/open_editor/widget/hslogin_social_login', ( panel, widgetModel, widgetView )=> {

        //Listen to changes only for the direct attributes under the settings model
        //If there is a collection i.e. repeater under the settings model, we need to attach event to that collection not this model.
        // widgetModel.attributes.settings can also be accessed via widgetModel.get('settings')
        widgetModel.attributes.settings.on('change', (changedModel) => {
            // console.log('Simple Control changed', changedModel);
        });


        //Because buttons repeater is a collection of models,
        //We need to attach event to those collection instead of the parent model settings.
        //Available events are add, remove, reset, sort, change
        widgetModel.attributes.settings.attributes.buttons.on('change', (changedRepeater)=>{

            // console.log('Repeater Field changed', changedRepeater.changed);

            if(changedRepeater.changed.provider){

                let icons = {
                    'facebook': {
                        'value' : 'hslogin-facebook',
                        'library' : 'hslogin'
                    },
                    'google': {
                        'value' : 'hslogin-google',
                        'library' : 'hslogin'
                    },
                    'x': {
                        'value' : 'fab fa-x-twitter',
                        'library' : 'fa-brands'
                    },
                    'linkedin': {
                        'value' : 'hslogin-linkedin',
                        'library' : 'hslogin'
                    },
                    'github': {
                        'value' : 'fab fa-github',
                        'library' : 'fa-brands'
                    },
                    'apple': {
                        'value' : 'hslogin-apple',
                        'library' : 'hslogin'
                    },
                }
                //Set the icon value
                changedRepeater.set('icon', {
                    library: icons[changedRepeater.changed.provider].library,
                    value: icons[changedRepeater.changed.provider].value
                });

                //Set the Label value
                changedRepeater.set('label',
                    `Continue with ${changedRepeater.changed.provider.replace(/\w/, s => s.toUpperCase())}`
                );

                //Editor View
                let editorView = panel.getCurrentPageView();
                //Repeaters View
                let repeatersModel = editorView.collection.findWhere({name: "buttons"} );
                const repeatersView = editorView.children.findByModelCid( repeatersModel.cid );
                //Individual Repeater View whose settings was changed
                let changedRepeaterView = repeatersView.children.findByModelCid(changedRepeater.cid);

                //Icon Control View
                let iconControlModel = changedRepeaterView.collection.findWhere({name: 'icon'});
                let iconControlView = changedRepeaterView.children.findByModelCid(iconControlModel.cid);
                //Update the icon control view
                iconControlView.render();

                //label Control View
                let labelControlModel = changedRepeaterView.collection.findWhere({name: 'label'});
                let labelControlView = changedRepeaterView.children.findByModelCid(labelControlModel.cid);
                //update the label control view
                labelControlView.render();
            }

        });
    });
})




// {{{hsloginCapitalizeFirstLetter(controlId)}}}
window.hsloginCapitalizeFirstLetter = function(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}
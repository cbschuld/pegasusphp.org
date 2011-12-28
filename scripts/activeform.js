var submitButtonFaded = new Array();
function setSubmitStatus(name,button,validationArray) {
        iRetVal = 0;
        for( var key in validationArray ) {
                if( validationArray[key] == 0 ) {
                        iRetVal++;
                }
        }
        if( iRetVal == 0 ) {
                button.attr('disabled', false );
                if( submitButtonFaded[name] ) {
                        button.fadeTo("fast", 1 );
                        submitButtonFaded[name] = false;
                }
        }
        else {
                button.attr('disabled', true );
                if( submitButtonFaded[name] == undefined || ! submitButtonFaded[name] ) {
                        button.fadeTo("fast", 0.5 );
                        submitButtonFaded[name] = true;
                }
        }
}
function isActiveFormValid(validationArray) {
        for( var key in validationArray ) {
                if( validationArray[key] == 0 ) {
                        return false;
                }
        }
        return true;
}
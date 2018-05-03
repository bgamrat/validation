function validate(el) {
    var test;
    var lengthConstraints, minLength, maxLength;
    var message;
    var re, val, flags = 'u';
    var valid;

    // Get the value in the pattern attribute
    test = el.getAttribute("pattern");
    if( test === null ) {
        // If there is no pattern, or the attribute is empty, fall back to data-pattern
        // data-pattern is a full regex, with slashes and flags
        test = el.getAttribute("data-pattern");
        if (test !== null) {
            // Extract the flags
            flags = test.replace(/^.*\$\/(.*)$/,'$1');
            // Remove the slashes
            test = test.substring(1,test.lastIndexOf('/'));
        } else {
            // Fall back to HTML5 browser validation
            if( test === null ) {
                valid = el.reportValidity();
            }
        }
    }

    // Try to get the validation message from the HTML
    message = el.getAttribute("data-message");
    // If there isn't a validation message, but there is a pattern
    if( message === null && test !== null )
    {
        // Extract the length constraints from the pattern
        lengthConstraints = test.replace(/^.*\{(\d+,\d+)\}.*$/, '$1');
        // If there is both a minimum and maximum length, use them to create the message
        if (lengthConstraints.indexOf(',') !== -1) {
            lengthConstraints = lengthConstraints.split(',');
            minLength = lengthConstraints[0];
            maxLength = lengthConstraints[1];
            message = "Please use between " + minLength + " and " + maxLength + " common characters and punctuation";
        }
    }

    // If there is a pattern
    if( test !== null ) {
        // Create a regex and use it
        re = RegExp(test,flags);
        val = el.value.trim();
        valid = true;
        valid = re.test(val);
    }

    // If the data is invalid
    if( valid === false ) {
        // If there is a message, use it
        if (message !== null) {
            el.setCustomValidity(message);
        }
        // Add the invalid class
        el.classList.add("invalid");
    } else {
        // Clear the message and invalid class
        el.setCustomValidity("");
        el.classList.remove("invalid");
    }
    return valid;
}
;

var forms = document.querySelectorAll("form");
var i, l = forms.length;
for( i = 0; i < l; i++ ) {
    forms[i].addEventListener("change", function (evt) {
        validate(evt.target);
    });
}

var buttons = document.querySelectorAll("button");
l = buttons.length;

// This loop allows me to use the same code to validate both forms
for( i = 0; i < l; i++ ) {
    buttons[i].addEventListener("click", function (evt) {
        var formName = this.id.replace("-btn", "");
        var activeForm = document.querySelector('form[name="' + formName + '"]');
        var inps = activeForm.querySelectorAll("input, textarea");
        var i, l = inps.length;
        var valid = activeForm.reportValidity();
        for( i = 0; i < l; i++ ) {
            if( validate(inps[i]) === false ) {
                valid = false;
            }
        }
        if( valid === true ) {
            activeForm.submit();
        }
    });
}
function validate(el) {
    var test;
    var lengthConstraints, minLength, maxLength;
    var message;
    var re, val;
    var valid;

    test = el.getAttribute("pattern");
    if( test === null ) {
        test = el.getAttribute("data-pattern");
        if( test === null ) {
            valid = el.reportValidity();
        }
    }

    message = el.getAttribute("data-message");
    if( message === null && test !== null )
    {
        lengthConstraints = test.replace(/^.*\{(\d+,\d+)\}.*$/, '$1').split(',');
        minLength = lengthConstraints[0];
        maxLength = lengthConstraints[1];
        message = "Please use between " + minLength + " and " + maxLength + " common characters and punctuation";
    }

    if( test !== null ) {
        re = RegExp(test);
        val = el.value.trim();
        valid = true;
        valid = re.test(val);
    }
    if( valid === false ) {
        if (message !== null) {
            el.setCustomValidity(message);
        }
        el.classList.add("invalid");
    } else {
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
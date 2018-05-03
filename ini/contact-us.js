document.getElementById("message").addEventListener("change", function (evt) {
    // Get the data-pattern attribute
    var test = this.getAttribute("data-pattern");
    // Get the flags
    var flags = test.replace(/^.*\$\/(.*)$/,'$1');
    // Remove the slashes
    test = test.substring(1,test.lastIndexOf('/'));
    var re = RegExp(test,flags);
    var val = this.value.trim();
    if( re.test(val) === false || val.length > this.maxLength ) {
        this.setCustomValidity("Please use not more than " + this.maxLength + " common characters and punctuation");
    } else {
        this.setCustomValidity("");
    }
});
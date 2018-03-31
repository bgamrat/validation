document.getElementById("message").addEventListener("change", function (evt) {
    var test = this.getAttribute("data-pattern");
    var re = RegExp(test);
    var val = this.value;
    if( re.test(this.value.trim()) === false || val.length > this.maxlength ) {
        this.setCustomValidity("Please use not more than " + this.maxlength + " common characters and punctuation");
    } else {
        this.setCustomValidity("");
    }
});
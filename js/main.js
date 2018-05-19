
function checkPattern(obj){
    var re = new RegExp("^"+obj.pattern+"$");
    if (!re.test(obj.value)) {
        obj.focus();
        // alert("Wrong format. Please, "+obj.title);
    }
}
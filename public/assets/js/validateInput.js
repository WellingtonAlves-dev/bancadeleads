
function isValid(element, rules) {
    let errosCount = 0;
    if(rules.required) {
        if(!element.val()) {
            errosCount++;
            if($(`#${rules.name}_required`).length == 0) {
                element.after(`<small class='text-danger' id='${rules.name}_required'>O campo ${rules.name} é obrigatório</small>`);
            }
        } else {
            $(`#${rules.name}_required`).remove();
        }
    }
    if(rules.minLenght) {
        if(element.val() && element.val().length < rules.minLenght) {
            errosCount++;
            if($(`#${rules.name}_min`)) {
                element.after(`<small class='text-danger' id='${rules.name}_min'>O campo ${rules.name} precisa ter ao menos ${rules.minLenght} caracteres</small>`);
            }
        } else {
            $(`#${rules.name}_min`).remove();
        }
    }
    if(rules.email) {
        var filter = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if(!filter.test(element.val())) {
            errosCount++;
        }
    }

    if(errosCount > 0) {
        element.addClass("is-invalid");
        return false;
    } else {
        element.removeClass("is-invalid");
        return true;
    }
}

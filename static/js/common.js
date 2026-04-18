function parseURLQueryParameters() {
    var regex = /[?&]([^=#&]+)=([^&#]*)/g,
        url = window.location.href,
        parameters = {},
        match;
    while ((match = regex.exec(url))) {
        parameters[decodeURIComponent(match[1])] = decodeURIComponent(match[2]);
    }
    return parameters;
}

function formatBRLAmount(amount) {
    var src = (+amount || 0).toString();
    src = src.indexOf('.') > -1 ? src : src + '.00';
    var r = src.replace('.', ',').replace(/^-?\d+/g, (m) => m.replace(/(?=(?!\b)(\d{3})+$)/g, '.'));
    return r;
}

function copy(text, tips) {
    var input = document.createElement('input');
    input.value = text;
    document.body.appendChild(input);
    input.select();
    document.execCommand('Copy');

    document.body.removeChild(input);
    toastr.success(tips || 'Copied: ' + text);
}
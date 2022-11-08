function setJwtLocalStorage(data) {
    let JWT = {
        access_token: data.access_token,
        token_type: data.token_type,
        expires_in: data.expires_in,
    };
    localStorage.setItem('Jwt', JSON.stringify(JWT));
}

function getJWT() {
    let stringJWT = localStorage.getItem('Jwt');
    return JSON.parse(stringJWT);
}

function convertDateToDateTime(date) {
    let m = new Date(date);
    return ("0" + m.getUTCHours()).slice(-2) + ":" +
        ("0" + m.getUTCMinutes()).slice(-2) + " | " +
        ("0" + m.getUTCDate()).slice(-2) + "/" +
        ("0" + (m.getUTCMonth() + 1)).slice(-2) + "/" +
        m.getUTCFullYear();
}

function renderPagination(links,pageNow) {
    links.forEach(function (each) {
        let disable = false;
        let page = each.label;
        if (each.label === "&laquo; Previous") {
            if (links[1].active === true) {
                disable = true;
            }
            each.label = '&laquo;';
            page = pageNow - 1;
        } else if (each.label === "Next &raquo;") {
            if (links[links.length - 2].active === true) {
                disable = true;
            }
            each.label = '&raquo;';
            page = pageNow + 1;
        }
        $('#pagination').append($('<li>').attr('class', `page-item ${each.active ? 'active' : ''} ${disable ? 'disabled' : ''}`)
            .append(`<a class="page-link" onclick="changePage(${page})">${each.label}</a>`));
    })
}

function changePage(page) {
    let urlParams = new URLSearchParams(window.location.search);
    urlParams.set('page', page);
    window.location.search = urlParams;
}

function formatToDate(date) {
    const format = new Date(date);
    const yyyy = format.getFullYear();
    let mm = format.getMonth() + 1; // Months start at 0!
    let dd = format.getDate();

    if (dd < 10) dd = '0' + dd;
    if (mm < 10) mm = '0' + mm;

    return dd + '/' + mm + '/' + yyyy;
}

function notifySuccess(message = '') {
    $.toast({
        heading: 'Success',
        text: message,
        showHideTransition: 'slide',
        position: 'bottom-right',
        icon: 'success'
    });
}

function notifyError(message = '') {
    $.toast({
        heading: 'Error',
        text: message,
        showHideTransition: 'slide',
        position: 'bottom-right',
        icon: 'error'
    });
}

function notifyInfo(message = '') {
    $.toast({
        heading: 'Info',
        text: message,
        showHideTransition: 'slide',
        position: 'bottom-right',
        icon: 'info'
    });
}

function checkJwt(route) {
    if(localStorage.getItem('Jwt') === null){
        window.location.href = `${route}`;
    }
}

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

function setJwtPayloadLocalStorage(route) {
    $.ajax({
        url: route,
        type: 'POST',
        dataType: 'JSON',
        headers: {Authorization: `${getJWT().token_type} ` + getJWT().access_token},
        success: function (response) {
            localStorage.setItem('payloadJwt', JSON.stringify(response.data));
        },
        error: function (response) {
        },
    });
}

function getJwtPayloadLocalStorage() {
    let stringJWT = localStorage.getItem('payloadJwt');
    return JSON.parse(stringJWT);
}

function renderPagination(links, pageNow) {
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
    $('#crawl-data').load(location.href + " #crawl-data");
    crawlData(page);
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

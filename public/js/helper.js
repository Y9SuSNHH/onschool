function checkJwt(login, profile) {
    let valueJwt = getJWT();
    if (valueJwt === null || valueJwt === '' || valueJwt === undefined) {
        window.location.href = `${login}`;
        try {
            JSON.parse(valueJwt);
        } catch (e) {
            window.location.href = `${login}`;
        }
    } else {
        $.ajax({
            url: `${profile}`,
            type: 'POST',
            dataType: 'JSON',
            headers: {Authorization: `${getJWT().token_type} ` + getJWT().access_token},
            processData: false,
            contentType: false,
            success: function (response) {
            },
            error: function (response) {
                if (response.status === 401) {
                    window.location.href = `${login}`;
                }
            },
        });
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
    let valueJwt = localStorage.getItem('Jwt');
    try {
        return JSON.parse(valueJwt);
    } catch (e) {
        window.location.href = `http://localhost:8080/admin/login`;
    }
}

function setJwtPayloadLocalStorage(route) {
    $.ajax({
        url: route,
        type: 'POST',
        dataType: 'JSON',
        headers: {Authorization: `${getJWT().token_type}` + getJWT().access_token},
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

function renderPagination(lastPage, pageNow) {
    let string = "";
    let previous = pageNow - 1;
    if (pageNow === 1) {
        previous = pageNow;
    }
    string += `<li class="page-item previous ${pageNow === 1 ? 'disabled' : ''}">
                <a class="page-link" href="javascript: void(0);" onclick="crawlData(${previous},false)"><i class="mdi mdi-chevron-left"></i></a>
               </li>`;
    for (let i = 1; i <= lastPage; i++) {
        string += `<li class="page-item page-${i} ${pageNow === i ? 'active' : ''}">
                    <a class="page-link" href="javascript: void(0);" onclick="crawlData(${i},false)">${i}</a>
                    </li>`;
    }
    let next = pageNow + 1;
    if (pageNow === lastPage) {
        next = pageNow;
    }
    string += `<li class="page-item next ${pageNow === lastPage ? 'disabled' : ''}">
                <a class="page-link" href="javascript: void(0);" onclick="crawlData(${next},false)"><i class="mdi mdi-chevron-right"></i></a>
               </li>`;
    $("#pagination").append(string);
}

// function renderPagination(lastPage, pageNow) {
//     let previous = pageNow - 1;
//     if (pageNow === 1) {
//         previous = pageNow;
//     }
//     $('#pagination').append($('<li>').attr('class', `page-item ${pageNow === 1 ? 'disabled' : ''} `)
//         .append(`<a class="page-link" onclick="changePage(${previous})"><i class="mdi mdi-chevron-left"></i></a>`));
//
//     for (let i = 1; i <= lastPage; i++) {
//         $('#pagination').append($('<li>').attr('class', `page-item ${pageNow === i ? 'active' : ''}`)
//             .append(`<a class="page-link" onclick="changePage(${i})">${i}</a>`));
//     }
//
//     let next = pageNow + 1;
//     if (pageNow === lastPage) {
//         next = pageNow;
//     }
//     $('#pagination').append($('<li>').attr('class', `page-item ${pageNow === lastPage ? 'disabled' : ''}`)
//         .append(`<a class="page-link" onclick="changePage(${next})"><i class="mdi mdi-chevron-right"></i></a>`));
// }

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

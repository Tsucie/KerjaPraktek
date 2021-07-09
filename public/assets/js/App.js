var sidebar = "1";
var locked = "1";

$(document).ready(function () {
    // if (typeof(Storage) !== "undefined" && localStorage.getItem("sidebar") == "0") { closeSidebar(); }
});

function toggleSidebar() {
    // if (typeof(Storage) !== "undefined" && localStorage.getItem("sidebar") == "1") {
    if (sidebar == "1") {
        closeSidebar();
    }
    else {
        openSidebar();
    }
}

function closeSidebar() {
    $('#sidenav-main').css("max-width","60px");
    $('.main-content').css("margin-left","60px");
    // if (typeof(Storage) !== "undefined")localStorage.setItem("sidebar", "0");
    sidebar = "0";
}

function openSidebar() {
    $('#sidenav-main').css("max-width","250px");
    $('.main-content').css("margin-left","250px");
    // if (typeof(Storage) !== "undefined") localStorage.setItem("sidebar", "1");
    sidebar = "1";
}

function toggleLock() {
    if (locked == "1") unlock();
    else lock();
}

function unlock() {
    $('#ps-icon').attr('class', 'fas fa-lock-open');
    $('#password').attr('type', 'text');
    locked = "0";
}

function lock() {
    $('#ps-icon').attr('class', 'fas fa-lock');
    $('#password').attr('type', 'password');
    locked = "1";
}

function Disable(selector) {
    $('#'+selector).prop('disabled', true);
    $('#'+selector).text('Tunggu ...');
}

$('#btn-lgn').on('click', function () {
    Disable(this.attributes.id.value);
    $('#form-lgn').submit();
});
function setCookie(cname, val, time) { // for time use new Date(years, months, days, hours, minutes, seconds, milliseconds);
    var now = new Date();
    if (time != null) {
        var expiry = new Date(now.getTime() + time);
        document.cookie = cname + "=" + val + "; expires=" + expiry.toUTCString() + "; path=";
    } else {
        document.cookie = cname + "=" + val + "; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=";
    }
    console.log("Set value of cookie '" + cname + "' to '" + val + "'");
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return null;
}

function listCookies() {
    var theCookies = document.cookie.split(';');
    var aString = '';
    for (var i = 1 ; i <= theCookies.length; i++) {
        aString += i + ' ' + theCookies[i-1] + "\n";
    }
    return aString;
}

function deleteCookie(cname) {
  document.cookie = cname + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT; path=';
}

function deleteAllCookies() {
    var cookies = document.cookie.split(";");

    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
    }
    console.log("Deleted all cookies.");
}

function logout() {
    deleteCookie("logcheck");
    deleteCookie("uname");
    deleteCookie("identifier");
    deleteCookie("token");
    location = location;
}

function redirect(link, delay) { // Delay in seconds
    console.log("Redirecting");
    if (delay == null) {
      delay = 0;
    }
    setTimeout(function() {
        window.location.replace(link);
    }, delay*1000);
}

function swapStyle() {
    if (getCookie("theme") == "hacker") {
        setNormalMode();
        setCookie('theme', 'normal');
    } else {
        setHackerMode();
        setCookie('theme', 'hacker');
    }
}

function applyStyle() {
    if (getCookie("theme") == "hacker") {
        setHackerMode();
    } else {
        setNormalMode();
    }
}

function setHackerMode() {
    document.documentElement.style.setProperty('--bg-color', '#202124');
    document.documentElement.style.setProperty('--accent-color', '#20c20e');
    document.documentElement.style.setProperty('--accent-color-light', '#8FE086');
    document.documentElement.style.setProperty('--color', '#e6e6e6');
    document.documentElement.style.setProperty('--title-color', 'rgba(255, 255, 255, 0.6)');
    document.querySelector("#mask").setAttribute('src', 'assets/mask-white.png');
    if (document.querySelector("#floating-button-newpost")) {
        document.querySelector("#floating-button-newpost").setAttribute('src', 'assets/newpost-button-dark.png');
    }
    if (document.querySelector("#floating-button-gotop")) {
        document.querySelector("#floating-button-gotop").setAttribute('src', 'assets/gotop-button-dark.png');
    }
    document.querySelector("#head-logo").setAttribute('src', 'assets/jaseph_hacker.png');
}

function setNormalMode() {
    document.documentElement.style.setProperty('--bg-color', '#fff');
    document.documentElement.style.setProperty('--accent-color', '#ff7614');
    document.documentElement.style.setProperty('--accent-color-light', '#ff9950');
    document.documentElement.style.setProperty('--color', '#202124');
    document.documentElement.style.setProperty('--title-color', 'rgba(0, 0, 0, 0.6)');
    document.querySelector("#mask").setAttribute('src', 'assets/mask.png');
    if (document.querySelector("#floating-button-newpost")) {
        document.querySelector("#floating-button-newpost").setAttribute('src', 'assets/newpost-button.png');
    }
    if (document.querySelector("#floating-button-gotop")) {
        document.querySelector("#floating-button-gotop").setAttribute('src', 'assets/gotop-button.png');
    }
    document.querySelector("#head-logo").setAttribute('src', 'assets/jaseph_normal.png');
}

function updateCharsLeft(maxAmt, type) {
    if(type == "title") {
        var cur = document.getElementById("titleField").value.length;
        document.getElementById("titlecharswrapper").innerHTML = maxAmt - cur;
    } else if(type == "content") {
        var cur = document.getElementById("contentArea").value.length;
        document.getElementById("contentcharswrapper").innerHTML = maxAmt - cur;
    }
}

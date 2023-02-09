function startTime() {
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();

    if ((h === 0)) {
        document.getElementById('greetings').innerHTML = "Its midnignt";
    }
    if ((h > 0) && (h <= 5)) {
        document.getElementById('greetings').innerHTML = "Its after midnignt";
    }
    if ((h > 5) && (h < 12)) {
        document.getElementById('greetings').innerHTML = "Good morning";
    }
    if ((h === 12)) {
        document.getElementById('greetings').innerHTML = "Its noon";
    }
    if ((h > 12) && (h <= 15)) {
        document.getElementById('greetings').innerHTML = "Good afternoon";
    }
    if ((h > 15) && (h <= 18)) {
        document.getElementById('greetings').innerHTML = "Good evening";
    }
    if ((h > 18) && (h < 24)) {
        document.getElementById('greetings').innerHTML = "Good night";
    }
    h = check(h);
    m = check(m);
    s = check(s);
    var dd = today.getDate();
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    mm = check(mm);
    dd = check(dd);
    var day = today.getDay();
    var weekday = "";
    var month = today.getMonth();
    var monthName = "";
    switch (day) {
        case 0:
            weekday = "Sunday";
            break;
        case 1:
            weekday = "Monday";
            break;
        case 2:
            weekday = "Tuesday";
            break;
        case 3:
            weekday = "Wednesday";
            break;
        case 4:
            weekday = "Thursday";
            break;
        case 5:
            weekday = "Friday";
            break;
        case 6:
            weekday = "Saturday";
            break;
    }
    switch (month) {
        case 0:
            monthName = "January";
            break;
        case 1:
            monthName = "February";
            break;
        case 2:
            monthName = "March";
            break;
        case 3:
            monthName = "April";
            break;
        case 4:
            monthName = "May";
            break;
        case 5:
            monthName = "June";
            break;
        case 6:
            monthName = "July";
            break;
        case 7:
            monthName = "August";
            break;
        case 8:
            monthName = "September";
            break;
        case 9:
            monthName = "October";
            break;
        case 10:
            monthName = "November";
            break;
        case 11:
            monthName = "December";
            break;
    }
    document.getElementById('time').innerHTML = h + ":" + m + ":" + s;
    document.getElementById('date').innerHTML = weekday + ", " + monthName + " " + dd + ", " + yyyy;
    var t = setTimeout(function () {
        startTime()
    }, 500);
}
function check(i)
{
    if (i < 10)
    {
        i = "0" + i;
    }  // add zero in front of numbers < 10
    return i;
}
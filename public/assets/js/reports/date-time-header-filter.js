var filter_start_hour = (typeof filter_start_time !== "undefined" && filter_start_time?.hour) || "05";
var filter_start_minutes = (typeof filter_start_time !== "undefined" && filter_start_time?.minutes) || "00";

var filter_end_hour = (typeof filter_end_time !== "undefined" && filter_end_time?.hour) || "23";
var filter_end_minutes = (typeof filter_end_time !== "undefined" && filter_end_time?.minutes) || "59";

flatpickr(".datepicker", {
    dateFormat: "m-d-Y",
});

function to24Hr(hour, ampm) {
    hour = parseInt(hour);
    if (ampm === "AM" && hour === 12) return "00";
    if (ampm === "PM" && hour !== 12)
        return (hour + 12).toString().padStart(2, "0");
    return hour.toString().padStart(2, "0");
}

function formatDateJS(dateObj) {
    const mm = String(dateObj.getMonth() + 1).padStart(2, "0");
    const dd = String(dateObj.getDate()).padStart(2, "0");
    const yyyy = dateObj.getFullYear();
    return `${mm}-${dd}-${yyyy}`;
}

function formatHourJS(hour24) {
    const ampm = hour24 >= 12 ? "PM" : "AM";
    const hour12 = hour24 % 12 || 12;
    return [String(hour12).padStart(2, "0"), ampm];
}

function animateDateTime() {
    let el = $(".dta");
    el.css("border-color", "#0980B2");
    el.css("box-shadow", "0 0 0 .1rem rgba(13,85,227,.25)");

    setTimeout(() => {
        el.css("border-color", "#ced4da");
        el.css("box-shadow", "0 0 0 0 rgba(13,85,227,0)");
    }, 100);
}

// Form submission formatting
document.getElementById("filter-form")?.addEventListener("submit", function (e) {
    const sDate = document.getElementById("start_date_date").value;
    const sHour = document.getElementById("start_hour")?.value;
    const sMin = document.getElementById("start_minute")?.value;
    const sAmPm = document.getElementById("start_ampm")?.value;

    const eDate = document.getElementById("end_date_date").value;
    const eHour = document.getElementById("end_hour")?.value;
    const eMin = document.getElementById("end_minute")?.value;
    const eAmPm = document.getElementById("end_ampm")?.value;

    document.getElementById("start_date_final").value = time_enable ? `${sDate} ${to24Hr(
        sHour,
        sAmPm
    )}:${sMin}` : sDate;
    document.getElementById("end_date_final").value = time_enable ? `${eDate} ${to24Hr(
        eHour,
        eAmPm
    )}:${eMin}`: eDate;
});

// Handle quick range buttons
document.querySelectorAll(".quick-range")?.forEach((btn) => {
    btn.addEventListener("click", () => {
        animateDateTime();

        const range = btn.dataset.range;
        let start = new Date();
        let end = new Date();

        switch (range) {
            case "today":
                start.setHours(filter_start_hour, filter_start_minutes, 0, 0);
                end.setHours(filter_end_hour, filter_end_minutes, 0, 0);
                break;
            case "yesterday":
                start.setDate(start.getDate() - 1);
                end.setDate(end.getDate() - 1);
                start.setHours(filter_start_hour, filter_start_minutes, 0, 0);
                end.setHours(filter_end_hour, filter_end_minutes, 0, 0);
                break;
            case "this_week":
                start.setDate(start.getDate() - start.getDay());
                start.setHours(filter_start_hour, filter_start_minutes, 0, 0);
                end.setHours(filter_end_hour, filter_end_minutes, 0, 0);
                break;
            case "last_week":
                start.setDate(start.getDate() - start.getDay() - 7);
                end.setDate(start.getDate() + 6);
                start.setHours(filter_start_hour, filter_start_minutes, 0, 0);
                end.setHours(filter_end_hour, filter_end_minutes, 0, 0);
                break;
            case "this_month":
                start = new Date(
                    start.getFullYear(),
                    start.getMonth(),
                    1,
                    filter_start_hour,
                    filter_start_minutes
                );
                end = new Date(
                    start.getFullYear(),
                    start.getMonth() + 1,
                    0,
                    filter_end_hour,
                    filter_end_minutes
                );
                break;
            case "last_month":
                start = new Date(
                    start.getFullYear(),
                    start.getMonth() - 1,
                    1,
                    filter_start_hour,
                    filter_start_minutes
                );
                end = new Date(
                    start.getFullYear(),
                    start.getMonth() + 1,
                    0,
                    filter_end_hour,
                    filter_end_minutes
                );
                break;
            case "last_quarter":
                let qStartMonth = Math.floor(start.getMonth() / 3) * 3 - 3;
                if (qStartMonth < 0) {
                    qStartMonth += 12;
                    start.setFullYear(start.getFullYear() - 1);
                }
                start = new Date(start.getFullYear(), qStartMonth, 1, 5, 0);
                end = new Date(start.getFullYear(), qStartMonth + 3, 0, 23, 59);
                break;
            case "last_6_months":
                start.setMonth(start.getMonth() - 6);
                start.setDate(1);
                start.setHours(5, 0, 0, 0);
                end.setHours(23, 59, 0, 0);
                break;
        }

        document.getElementById("start_date_date").value = formatDateJS(start);

        if (time_enable) {
            start.setHours(filter_start_hour, filter_start_minutes, 0, 0);
            const [sh, sap] = formatHourJS(start.getHours());
            document.getElementById("start_hour").value = sh;
            document.getElementById("start_minute").value = String(
                start.getMinutes()
            ).padStart(2, "0");
            document.getElementById("start_ampm").value = sap;
        }

        document.getElementById("end_date_date").value = formatDateJS(end);

        if (time_enable) {
            end.setHours(filter_end_hour, filter_end_minutes, 0, 0);
            const [eh, eap] = formatHourJS(end.getHours());
            document.getElementById("end_hour").value = eh;
            document.getElementById("end_minute").value = String(
                end.getMinutes()
            ).padStart(2, "0");
            document.getElementById("end_ampm").value = eap;
        }

        setTimeout(() => {
            $(".date-time-filter-submit-btn").click();
        }, 20);
    });
});

// Last 12 months buttons
document.querySelectorAll(".last-12-months")?.forEach((btn) => {
    btn.addEventListener("click", () => {
        animateDateTime();

        const [year, month] = btn.dataset.month.split("-");
        const startDate = new Date(year, month - 1, 1, 5, 0);
        const endDate = new Date(year, month, 0, 23, 59);

        document.getElementById("start_date_date").value =
            formatDateJS(startDate);
        document.getElementById("end_date_date").value = formatDateJS(endDate);

        
        if (time_enable) {
            startDate.setHours(filter_start_hour, filter_start_minutes, 0, 0);
            const [sh, sap] = formatHourJS(startDate.getHours());

            document.getElementById("start_hour").value = sh;
            document.getElementById("start_minute").value = filter_start_minutes;
            document.getElementById("start_ampm").value = sap;
        }

        if (time_enable) {
            endDate.setHours(filter_end_hour, filter_end_minutes, 0, 0);
            const [eh, eap] = formatHourJS(endDate.getHours());

            document.getElementById("end_hour").value = eh;
            document.getElementById("end_minute").value = filter_end_minutes;
            document.getElementById("end_ampm").value = eap;
        }

        setTimeout(() => {
            $(".date-time-filter-submit-btn").click();
        }, 20);
    });
});

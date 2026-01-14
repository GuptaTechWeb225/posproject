import moment from "moment";
import optional from "./Optional"

const settings = window.settings || {};
moment.locale(window.appLanguage);

export const serverDateTimeFormat = 'YYYY-MM-DD H:mm:ss';
export const serverDateFormat = 'YYYY-MM-DD';
export const serverTimeFormat = 'H:mm:ss';

export const date_format = () => {
    return {
        'd-m-Y': 'DD-MM-YYYY',
        'm-d-Y': 'MM-DD-YYYY',
        'Y-m-d': 'YYYY-MM-DD',
        'm/d/Y': 'MM/DD/YYYY',
        'd/m/Y': 'DD/MM/YYYY',
        'Y/m/d': 'YYYY/MM/DD',
        'm.d.Y': 'MM.DD.YYYY',
        'd.m.Y': 'DD.MM.YYYY',
        'Y.m.d': 'YYYY.MM.DD',
    };
};

export const getDateFormatForFrontend = (format) => {

    const dates = {
        'd-m-Y' : 'DD-MM-YYYY',
        'm-d-Y' : 'MM-DD-YYYY',
        'Y-m-d' : 'YYYY-MM-DD',
        'm/d/Y' : 'MM/DD/YYYY',
        'd/m/Y' : 'DD/MM/YYYY',
        'Y/m/d' : 'YYYY/MM/DD',
        'm.d.Y' : 'MM.DD.YYYY',
        'd.m.Y' : 'DD.MM.YYYY',
        'Y.m.d' : 'YYYY.MM.DD'
    };

    return dates[format];
}

export const getTimeFormatForFrontend = (format) => {
    const formats = {
        "h" : 12,
        "H" : 24
    }
    return formats[format];
}

export  const getTimeFromDateTime = (dateTime, timeFormat) =>{
    timeFormat = timeFormat == 12 ? 'h:mm a' : 'HH:mm';
    return moment(dateTime).format(timeFormat);
}

export  const  getDateFromNow = (date, format) => {
    return moment(date).calendar({
        sameDay: '[Today]',
        nextDay: '[Tomorrow]',
        nextWeek: 'dddd',
        lastDay: '[Yesterday]',
        lastWeek: '[Last] dddd',
        sameElse: format
    });
}

export const formatted_date = () => {
    return date_format()[optional(settings, 'date_format')];
};

export const formatted_time = () => {
    return optional(settings, 'time_format') === 'h' ? '12' : '24';
}

export const time_format = () => {
    const format = optional(settings, 'time_format');

    return format === 'h' ? `${settings.time_format}:mm A` : `${settings.time_format}:mm`;
}

export const formatDateToLocal = (date, withTime = false) => {
    if (!date)
        return '';
    const formatString = withTime ? `${formatted_date()} ${time_format()}` : formatted_date();

    return moment(date).utc(false)
        .local()
        .format(formatString);
}

export const timeInterval = (date) => {
    return moment(date).utc(false).fromNow();
}

export const onlyTime = dateTime => {
    return moment(localDateTimeFromUtc(dateTime), "YYYY-MM-DD HH:mm:ss").format(`${time_format()}`);
}

export const formatUtcToLocal = (time = null, format = false) => {
    if (!time)
        return null;

    return moment.utc(time, 'HH:mm:ss').local().format(format || time_format());
}

export const isValidDate = string => {
    if (!string)
        return false;

    if (typeof parseInt(string) === 'number' && string.split('-').length <= 1)
        return false;

    return !isNaN(new Date().getTime());
}

export const localToUtc = (time = null) => {
    if (!time) {
        return '';
    }

    moment.locale('en');
    return moment(time, time_format()).utc().format('HH:mm')
}

export const formatDateForServer = (date = null) => {
    if (!date) {
        return '';
    }

    moment.locale('en');
    return moment(date, formatted_date()).utc().format('YYYY-MM-DD');
}

export const formatDateTime = (dateTime = null) => {
    if (!dateTime)
        return null;
    let format = formatted_date()+' '+time_format();
    return moment(dateTime).format(format);
}

export const formatDayNameFromDateTime = (dateTime = null) => {

    return moment( localDateTimeFromUtc(dateTime), "YYYY-MM-DD HH:mm:ss").format('dddd')
}

export const formatMonthNameFromDateTime = (dateTime = null) => {
    return moment(localDateTimeFromUtc(dateTime), "YYYY-MM-DD HH:mm:ss").format('MMMM');
}

export const formatDayFromDateTime = (dateTime = null) => {
    return moment(localDateTimeFromUtc(dateTime), "YYYY-MM-DD HH:mm:ss").format('D');
}

export const formatYearFromDateTime = (dateTime = null) => {
    return moment(localDateTimeFromUtc(dateTime), "YYYY-MM-DD HH:mm:ss").format('Y');
}

export const formatDateTimeDateTime = (dateTime = null) => {

    return moment(localDateTimeFromUtc(dateTime), "YYYY-MM-DD HH:mm:ss").format(`${formatted_date()} ${time_format()}`);
}

export const localDateTimeFromUtc = (dateTime = null) => {
    if (!dateTime)
        return null;

    return moment.utc(dateTime, 'YYYY-MM-DD HH:mm:ss').local().format(formatDateTime());
}

export const dateTimeToLocalWithFormat = (date = null) => {
    if (!date) {
        return '';
    }

    return moment.utc(date, serverDateTimeFormat)
        .local()
        .format(serverDateTimeFormat)
}

export const calenderTime = (date, withTime = true) => {
    date = moment(formatDateToLocal(date, true), formatted_date());

    const days = moment(date).diff(moment.now(), 'days');
    if (moment(date).format('YYYY') < moment().format('YYYY')) {
        return moment(date).format('DD MMM, YY')
    }
    if (days < -7) {
        return moment(date).format('DD MMM')
    }

    moment.locale(window.appLanguage, {})
    let format = {
        sameDay: '[' + Vue.prototype.$t('today') + ']',
        lastDay: '[' + Vue.prototype.$t('yesterday') + ']',
        lastWeek: '[' + Vue.prototype.$t('last') + '] dddd',
        nextDay: '[' + Vue.prototype.$t('tomorrow') + ']',
        nextWeek: '[' + Vue.prototype.$t('next_week') + ']',
        sameElse: `${date_format()[settings.date_format]}`
    };
    if (withTime) {
        return date.calendar(format);
    }
    return date.calendar(null, format);
};

export const today = () => {
    return moment(new Date());
}

export const thisWeek = () => {
    return [
        moment().startOf('week'),
        moment().endOf('week')
    ];
}

export const lastWeek = () => {
    return [
        moment().subtract(1, 'weeks').startOf('week'),
        moment().subtract(1, 'weeks').endOf('week')
    ];
}

export const thisMonth = () => {
    return [
        moment().startOf('month'),
        moment().endOf('month')
    ]
}

export const lastMonth = () => {
    return [
        moment().subtract(1, 'month').startOf('month'),
        moment().subtract(1, 'month').endOf('month')
    ]
}

export const thisYear = () => {
    return [
        moment(new Date()).startOf('year'),
        moment(new Date()).endOf('year'),
    ]
}

export const startAndEndOf = (year, month) => {
    return [
        moment([year, month]).startOf('month'),
        moment([year, month]).endOf('month')
    ]
}

export const getDateRange = (type) => {
    return {
        today,
        thisWeek,
        lastWeek,
        thisMonth,
        lastMonth,
        thisYear
    }[type]();
}

export const differentInTime = (startTime, endTime, withDate = false) => {
    if (withDate) {
        return moment.duration(moment(endTime, serverDateTimeFormat).diff(moment(startTime, serverDateTimeFormat)));
    }

    if (moment(endTime, serverTimeFormat).diff(moment(startTime, serverTimeFormat), 'hours') < 0) {
        startTime = moment(`${today().format(serverDateFormat)} ${startTime}`);
        endTime = moment(`${today().add(1, 'day').format(serverDateFormat)} ${endTime}`)
    }

    return moment.duration(moment(endTime, serverTimeFormat).diff(moment(startTime, serverTimeFormat)));
}

export const convertSecondToHourMinutes = (seconds) => {
    const min = parseInt(parseInt(seconds) / 60);
    const hour = min / 60;
    const absHour = parseInt(hour);
    const absMin = Math.abs(min - (absHour * 60));
    return `${String(absHour).length === 1 ? `0${absHour}` : absHour}:${String(absMin).length === 1 ? `0${absMin}` : String(absMin).substr(0, 2)}`;
}

export const dateTimeFormat = (value) => {
    if (value) {
        return `${onlyTime(value)}, ${calenderTime(value, false)}`
    }
    return null;
};

export const timeToDateTime = (date, time) => {
    return moment(`${moment(date, serverDateFormat).format(serverDateFormat)} ${time}`);
}

export const formatDateTimeForServer = (dateTime = null) => {
    if (!dateTime){
        return '';
    }
    return moment(dateTime, `${date_format()} ${time_format()}`).utc().format(serverDateTimeFormat)
}

export const calenderDateWithoutFuture = (value) => {
    let date = moment(formatDateToLocal(value, true), formatted_date());

    const days = moment(date, ).diff(moment.now(), 'days');

    if(days >= 1) {
        return date.format(date_format()[settings.date_format]);
    }
    return calenderTime(value, false)
}
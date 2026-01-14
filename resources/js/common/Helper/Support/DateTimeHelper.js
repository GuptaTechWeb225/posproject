import moment from "moment";
import Vue from "vue";
import optional from "./Optional";

const settings = window.settings || {};

moment.locale(window.appLanguage)

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
};

export const timeInterval = (date) => {
    return moment(date).utc(false).fromNow();
};

export const onlyTime = date => {
    return moment(date).utc(false)
        .local()
        .format(time_format());
};

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

export const calenderTime = date => {
    const days = moment(date).diff(moment.now(), 'days');
    if (moment(date).format('YYYY') < moment().format('YYYY')) {
        return moment(date).format('DD MMM, YY')
    }
    if (days < -7) {
        return moment(date).format('DD MMM')
    }

    moment.locale(window.appLanguage, {})

    return moment(formatDateToLocal(date, true), formatted_date()).calendar({
        sameDay: '[' + Vue.prototype.$t('today') + ']',
        lastDay: '[' + Vue.prototype.$t('yesterday') + ']',
        lastWeek: '[' + Vue.prototype.$t('last') + '] dddd',
        nextDay: '[' + Vue.prototype.$t('tomorrow') + ']',
        nextWeek: '[' + Vue.prototype.$t('next_week') + ']',
        sameElse: `${date_format()[settings.date_format]}`
    });
};

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
    return moment(date, formatted_date()).format('YYYY-MM-DD');
}


export const formatDateTime = (dateTime = null) => {
    if (!dateTime)
        return null;
    let format = formatted_date()+' '+time_format();
    return moment(dateTime).format(format);
}


import Vue from "vue";

const settings = window.settings || {};

export const getThousandSeparator = () => {
    return settings.thousand_separator ? settings.thousand_separator : ' ';
}

export const getNumberOfDecimal = () => {
    return settings.number_of_decimal ? settings.number_of_decimal : 0
}

export const numberFormatter = number => {
    if (number && (String(number).indexOf('.') > -1)) {
        number = number.toFixed(getNumberOfDecimal())
    }
    if (!isNaN(parseFloat(number))) {
        let parts = number.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, getThousandSeparator());
        return parts.join(".");
    }
    return 0;
}

export const configFormatter = (format) => {
    return {
        id: format,
        value: Vue.prototype.$t(format)
    }
};



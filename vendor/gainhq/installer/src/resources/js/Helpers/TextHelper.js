export const ucWords = string => {
    return String(string).toLowerCase()
        .replace(/\b[a-z]/g, (l) => l.toUpperCase())
}

export const columnStringify = string => {
    if (string) {
        return ucWords(String(string).split('_').join(' '))
    }
}
export const ucFirst = string => {
    if (string) {
        return String(string)[0].toUpperCase() + String(string).substring(1)
    }
}

export const studly = string => {
    string = String(string).replace('-', ' ');
    string = string.replace('_', ' ');
    return string.split(' ')
        .map(str => str[0].toUpperCase() + str.substr(1).toLowerCase())
        .join('')
}

export const textTruncate = (str, length, ending) => {
    if (length == null) {
        length = 50;
    }
    if (ending == null) {
        ending = '...';
    }
    if (str.length > length) {
        return str.substring(0, length - ending.length) + ending;
    } else {
        return str;
    }
};

export const snakeCase = (string, glue = '_') => {
    return string.replace(/\W+/g, " ")
        .split(/ |\B(?=[A-Z])/)
        .map(word => word.toLowerCase())
        .join(glue);
};

export const kebabCase = string => {
    return snakeCase(string, '-');
};


// New Helper

export const ordinal = (n) => {
    let s = ["th", "st", "nd", "rd"],
        v = n%100;
    return n + (s[(v-20)%10] || s[v] || s[0]);
}

export const shortTitle = (str) => {
    str = str.replace(/(^\s*)|(\s*$)/gi, "");
    str = str.replace(/[ ]{2,}/gi, " ");
    str = str.replace(/\n /, "\n");
    let titleArray = str.split(' ');
    if (titleArray.length > 1) {
        return (titleArray[0][0] + titleArray[1][0]).toUpperCase();
    } else {
        return titleArray[0].substring(0, 2).toUpperCase();
    }
}
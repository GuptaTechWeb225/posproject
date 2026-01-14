import sanitizeHtml from 'sanitize-html';

export  const purify = (dirty) => {
    return sanitizeHtml(dirty, {
        allowedTags: sanitizeHtml.defaults.allowedTags.concat([ 'img', 'icon', 'button' ]),
        allowedAttributes: {
            a: [ 'href', 'name', 'target' ],
            i: [ 'data-feather' ],
            col: [ 'width' ],
            img: [ 'src', 'srcset', 'alt', 'title', 'width', 'height', 'loading' ],
            table: [ 'cellpadding', 'cellspacing' ],
            '*': ["style",'class'],
        },
        allowedSchemesByTag: { img: [ 'data', 'http', 'https' ]},
    });
}
export  const purifyForPrint = (dirty) => {
    return sanitizeHtml(dirty, {
        allowedTags: sanitizeHtml.defaults.allowedTags.concat(['icon', 'button' ]),
        allowedAttributes: {
            a: [ 'href', 'name', 'target' ],
            i: [ 'data-feather' ],
            col: [ 'width' ],
            table: [ 'cellpadding', 'cellspacing' ],
            '*': ["style",'class'],
        },
    });
}

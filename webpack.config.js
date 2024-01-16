const path = require('path');

module.exports = {
    stats: {
        children: true,
        warnings: false,
    },
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),
            'v@': path.resolve('resources/js/vue'),
            'p@': path.resolve('public'),
        },
    },
};

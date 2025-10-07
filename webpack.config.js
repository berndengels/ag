const path = require('path');

module.exports = {
    stats: {
        children: true,
        warnings: false,
    },
    resolve: {
        fallback: {
            "fs": false,
            "http": false,
            "https": false,
            "net": false
        },
        alias: {
            '@': path.resolve('resources/js'),
            'v@': path.resolve('resources/js/vue'),
            'c@': path.resolve('resources/js/vue/components'),
            'p@': path.resolve('public'),
        },
    },
};

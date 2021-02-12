module.exports = {
    purge: [
        './vendor/views/**/*.blade.php',
        './vendor/js/**/*.vue',
        './resources/js/views/**/*.vue',
        './resources/views/**/*.blade.php'
    ],
    theme: {
        fontFamily: {
            body: ['Open Sans', 'sans-serif']
        },
        screens: {
            'es': {'max': '400px'},
            'xs': {'max': '500px'},
            'sm': {'max': '600px'},
            'md': {'max': '768px'},
            'lg': {'max': '1024px'},
            'xl': {'max': '1300px'},
        },
        extend: {
            fontSize: {
                xxs: '0.7rem',
                xs: '0.75rem'
            },
            spacing: {
                28: '7rem'
            },
            borderWidth: {
                '3': '3px',
            }
        }
    },
    variants: {},
    plugins: []
}

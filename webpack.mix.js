const mix = require('laravel-mix')
const Dotenv = require('dotenv-webpack')

require('laravel-mix-tailwind')

mix.setPublicPath('../public/vendor/admin')
    .js('vendor/js/app.js', '../public/vendor/admin/js')
    .sass('vendor/sass/app.scss', '../public/vendor/admin/css')
    .tailwind()
    .webpackConfig({
        output: {
            publicPath: '/vendor/admin/',
            chunkFilename: 'js/[name].js'
        },
        resolve: {
            alias: {
                Admin: path.resolve(__dirname, 'resources'),
                Vendor: path.resolve(__dirname, 'vendor'),
            }
        },
        plugins: [
            new Dotenv({
                path: '../.env'
            })
        ]
    })

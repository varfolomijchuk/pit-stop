const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const { WebpackManifestPlugin } = require('webpack-manifest-plugin');

module.exports = {
    entry: {
        app: './src/js/app.js',
        editor: './src/scss/editor.scss', // For Gutenberg editor styles
    },
    output: {
        path: path.resolve(__dirname, 'dist'),
        filename: 'js/[name].[contenthash:8].js',
        publicPath: '/wp-content/themes/ai/dist/'
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                }
            },
            {
                test: /\.(sa|sc|c)ss$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'sass-loader',
                ],
            },
            {
                test: /\.(png|jpe?g|gif|svg)$/i,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: '[name].[ext]',
                            outputPath: 'images/',
                        },
                    },
                ],
            },
            {
                test: /\.(woff|woff2|eot|ttf|otf)$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: '[name].[ext]',
                            outputPath: 'fonts/',
                        },
                    },
                ],
            },
        ],
    },
    plugins: [
        new CleanWebpackPlugin(),
        new MiniCssExtractPlugin({
            filename: 'css/[name].[contenthash:8].css',
        }),
        new WebpackManifestPlugin({
            fileName: 'manifest.json',
            publicPath: '',
        }),
        new BrowserSyncPlugin({
            host: 'localhost',
            port: 3000,
            proxy: 'http://pitstop-service.local/', // <--- IMPORTANT: Change this to your local WordPress URL
            files: [
                '**/*.php',
                'dist/**/*.js',
                'dist/**/*.css',
            ],
            injectChanges: true,
        }, {
            reload: false
        })
    ],
    devtool: 'source-map', // Enable source maps for easier debugging
    mode: 'development' // or 'production' for production builds
};
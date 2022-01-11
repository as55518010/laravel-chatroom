const mix = require("laravel-mix");
const path = require("path");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js("resources/js/app.js", "public/build/js")
    .vue()
    .sass("resources/sass/app.scss", "public/build/css");

// 可視化-模組相關依賴
require("laravel-mix-bundle-analyzer");
if (mix.inProduction()) {
    mix.bundleAnalyzer({
        analyzerMode: "static",
        reportFilename: "build/report.html",
    });
}
// 編譯完成前-清除舊的編譯檔案
const { CleanWebpackPlugin } = require("clean-webpack-plugin");

// 開發環境用 (source code 顯示)
if (!mix.inProduction()) {
    mix.webpackConfig({
        devtool: "eval-source-map",
    });
}

mix.webpackConfig({
    plugins: [
        new CleanWebpackPlugin({
            cleanOnceBeforeBuildPatterns: [
                path.resolve(__dirname, "public/build/{components,css,js}/*"),
            ],
            verbose: true,
        }),
    ],
    output: {
        chunkFilename: "build/js/[name].js?id=[chunkhash]",
    },
    resolve: {
        alias: {
            "@": path.resolve("resources/js/"),
        },
    },
});

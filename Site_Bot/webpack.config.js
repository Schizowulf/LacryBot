const path = require('path');
const TerserPlugin = require('terser-webpack-plugin');


module.exports = {
    entry: {
        app: './resources/js/app.js',
        orders: './resources/js/orders.js',
        apiSettings: './resources/js/apiSettings.js'
    },
    output: {
        filename: '[name].bundle.js',
        path: path.resolve(__dirname, 'public/js'),
    },
    module: {
        rules: [
          {
            test: /\.(jsx|js)$/,
            include: path.resolve(__dirname, 'resources/js'),
            exclude: /node_modules/,
            use: [{
              loader: 'babel-loader',
              options: {
                presets: [
                  ['@babel/preset-env', {
                    "targets": "defaults" 
                  }],
                  '@babel/preset-react'
                ]
              }
            }]
          }
        ]
    },
    optimization: {
        minimizer: [new TerserPlugin({ extractComments: false })],
    },
};
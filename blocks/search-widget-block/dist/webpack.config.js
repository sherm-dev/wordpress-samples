const path = require('path');
const webpack = require('webpack');
const TerserPlugin = require("terser-webpack-plugin");

module.exports = {
	mode: 'development',
	optimization: {
		minimize: true,
		minimizer: [new TerserPlugin()],
	},
	entry: {
		"search-widget": ['./src/js/search-widget.js']
	},
	resolve: {
		alias: {
        react: path.resolve('./node_modules/react')
      },
	},
	output: {
		path: __dirname,
		publicPath: '/',
		filename: '[name].js'
	  },
	module: {
    rules: [
		{
      test: /\.(js|jsx)$/,
      exclude: /node_modules/,
      use: {
        loader: 'babel-loader',
		  options: {
          presets: [
            ['@babel/preset-env', { targets: "ie 11" }]
          ]
        }
      }
    }, 
      {
        test: /\.(scss)$/,
        use: [
          {
            loader: 'style-loader'
          },
          {
            loader: 'css-loader'
          },
          {
            loader: 'postcss-loader',
            options: {
              postcssOptions: {
                plugins: () => [
                  require('autoprefixer')
                ]
              }
            }
          },
          {
            loader: 'sass-loader'
          }
        ]
      }
    ]
  }
}

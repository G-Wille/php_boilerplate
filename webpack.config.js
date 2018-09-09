/**
* Webpack Config File
*
* @copyright   Copyright (c) 2018 Gert-Jan Wille (http://www.gert-janwille.com)
* @version     v1.0.0
* @author      Gert-Jan Wille <hello@gert-janwille.be>
*
*/

const path = require(`path`)
const webpack = require(`webpack`)
const { HotModuleReplacementPlugin, NamedModulesPlugin } = webpack
const configHtmls = require(`webpack-config-htmls`)()

const UglifyJsPlugin = require(`uglifyjs-webpack-plugin`)

const CopyWebpackPlugin = require(`copy-webpack-plugin`)
const ExtractTextWebpackPlugin = require(`extract-text-webpack-plugin`)

const { getIfUtils, removeEmpty } = require(`webpack-config-utils`)
const { ifProduction, ifDevelopment } = getIfUtils(process.env.NODE_ENV)

const extractCSS = new ExtractTextWebpackPlugin({ filename: 'css/style.css' })
const autoprefixer = require('autoprefixer')

// change for production build on different server path
const publicPath = `/`

// Define port
const port = 3000

// Define which files needs to copy to the dist folder without compiling.
const copy = new CopyWebpackPlugin([{
  from: `./src/assets`,
  to: `assets`
},
{
  from: `./src/class/`,
  to: `class`
},
{
  from: `./src/controller/`,
  to: `controller`
},
{
  from: `./src/libs/`,
  to: `libs`
},
{
  from: `./src/modules/`,
  to: `modules`
},
{
  from: `./src/view/`,
  to: `view`
},
{ from: `./src/.htaccess` },
{ from: `./src/index.php` }
], {
  ignore: [
    `.DS_Store`
  ]
})

// Declare a config object.
const config = {
  mode: ifProduction('production', 'development'),
  // Declare the entry files.
  entry: removeEmpty([
    `./src/css/style.less`,
    `./src/js/script.js`,
    ...configHtmls.entry
  ]),

  // Compile all files with extensions...
  resolve: {
    extensions: [
      `.js`,
      `.jsx`,
      `.css`
    ]
  },

  // Declare the output filder and filename.
  output: {
    path: path.join(__dirname, `dist`),
    filename: `js/[name].js`,
    publicPath
  },

  // Set the dev tool folder.
  devtool: `source-map`,

  // Declare the webpack server.
  devServer: {
    contentBase: `./src`,
    proxy: {
      '*': 'http://localhost:8888' // Your external server being proxied
    },
    historyApiFallback: true, // react-router
    hot: true,
    port
  },

  // Add additional modules and rules.
  module: {

    rules: removeEmpty([
      {
        test: /\.less$/,
        loader: extractCSS.extract([
          {
            loader: `css-loader`,
            options: {
              importLoaders: 1,
              plugins: () => autoprefixer({
                browsers: ['last 3 versions', '> 1%']
              })
            }
          },
          {
            loader: `less-loader`
          }
        ])
      },

      // Compile and optimize html code in both environments.
      {
        test: /\.html$/,
        loader: `html-loader`,
        options: {
          attrs: [
            `audio:src`,
            `img:src`,
            `video:src`,
            `source:srcset`
          ] // read src from video, img & audio tag
        }
      },

      // Compile all jsx files using babel and elslint.
      {
        test: /\.(jsx?)$/,
        exclude: /node_modules/,
        use: [
          {
            loader: `babel-loader`
          },
          {
            loader: `eslint-loader`,
            options: {
              fix: true
            }
          }
        ]
      },

      // Optimize all assets with extensions...
      {
        test: /\.(svg|png|jpe?g|gif|webp)$/,
        loader: `url-loader`,
        options: {
          limit: 1000, // inline if < 1 kb
          context: `./src`,
          name: `[path][name].[ext]`
        }
      },

      // optimize all audio and video files.
      {
        test: /\.(mp3|mp4|wav)$/,
        loader: `file-loader`,
        options: {
          context: `./src`,
          name: `[path][name].[ext]`
        }
      },

      // If env is production opimize the assets.
      ifProduction({
        test: /\.(svg|png|jpe?g|gif)$/,
        loader: `image-webpack-loader`,
        enforce: `pre`,
        options: {
          bypassOnDebug: true
        }
      })

    ])

  },

  // Declare plugins.
  plugins: removeEmpty([

    ...configHtmls.plugins,

    // If in development log files who changed.
    ifDevelopment(new NamedModulesPlugin()),
    // If in development update files without refresh.
    ifDevelopment(new HotModuleReplacementPlugin()),

    // If production copy declared files.
    ifProduction(copy),
    // If production extract ony css remove all comments.
    extractCSS,

    // If production clean up our js code.
    ifProduction(
      new UglifyJsPlugin({
        sourceMap: true // Generate a source map.
      })
    )

  ])

}

// Export our config object.
module.exports = config

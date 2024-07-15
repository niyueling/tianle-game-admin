const mode = process.env.NODE_ENV
module.exports = {
    publicPath: '/admin/',
    lintOnSave: false, // 是否开启eslint保存检测
    devServer: {
        disableHostCheck: true
    },
    css: {
        loaderOptions: {
            sass: {
                prependData: `@import "@/styles/variables.scss";`
            }
        }
    },
    productionSourceMap: false,
    transpileDependencies: ['vue-echarts', 'resize-detector'],
    chainWebpack(config) {
        if (mode == 'production') {
            config.optimization.minimizer('terser').tap(args => {
                args[0].terserOptions = {
                    compress: {
                        drop_console: true,
                        drop_debugger: true,
                        pure_funcs: ['console.log']
                    }
                }
                return args
            })
        }
    },
    configureWebpack: {
        optimization: {
            //代码分割
            splitChunks: {
                chunks: 'all', // 共有3个值"initial"，"async"和"all"。配置后，代码分割优化仅选择初始块，按需块或所有块
                minSize: 30000, // （默认值：30000）块的最小大小
                minChunks: 1, // （默认值：1）在拆分之前共享模块的最小块数
                maxAsyncRequests: 5, //（默认为5）按需加载时并行请求的最大数量
                maxInitialRequests: 5, //（默认值为3）入口点的最大并行请求数
                automaticNameDelimiter: '~', // 默认情况下，webpack将使用块的来源和名称生成名称，例如vendors~main.js
                name: true,
                cacheGroups: {
                    // 以上条件都满足后会走入cacheGroups进一步进行优化的判断
                    echarts: {
                        name: 'echarts',
                        test: /[\\/]node_modules[\\/]echarts[\\/]/,
                        priority: -10
                    },
                    element: {
                        name: 'element-ui',
                        test: /[\\/]node_modules[\\/]element-ui[\\/]/,
                        priority: -10
                    },
                    wangeditor: {
                        name: 'wangeditor',
                        test: /[\\/]node_modules[\\/]wangeditor[\\/]/,
                        priority: -10
                    }
                }
            }
        }
    }
}

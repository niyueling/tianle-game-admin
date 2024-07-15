const TransformPages = require('./js_sdk/hhyang-uni-read-pages/uni-read-pages@1.0.5')
const { webpack } = new TransformPages()

module.exports = {
	configureWebpack: {
		plugins: [
			new webpack.DefinePlugin({
				ROUTES: webpack.DefinePlugin.runtimeValue(() => {
					const tfPages = new TransformPages({
						includes: ['path', 'name', 'aliasPath','animation', 'meta']
					});
					return JSON.stringify(tfPages.routes)
				}, true )
			})
		]
	}
}
export default {
	// 公共配置
	appConfig: state => state.app.config || {},
	// 用户信息
	shopInfo: state => state.app.shopInfo || {},
	// token
	token: state => state.app.token,
	// 客户端
	client: state => state.app.client,
	// 是否登录
	isLogin: state => !!state.app.token,
	themeName: state => state.decorate.config.theme || 'red_theme',
	// 主题颜色
	themeColor: state  => {
		const {
			theme,
			config
		} = state.decorate
		return theme[config.theme] || '#FF2C3C'
	},
};

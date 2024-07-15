
import { baseURL } from '@/config/app.js'
import { ClientEnum } from '@/utils/enum'
import store from '@/store'

// 获取客户端
export const getClient = () => {
	// 判断平台
	let client = 1;
	
	// #ifdef H5
	if (navigator.userAgent.indexOf('Mobile')>-1){
		client = 6
	} else {
	    client = 5
	}
    // 判断是不是微信浏览器
	let ua = navigator.userAgent.toLowerCase()
    if (ua.match(/MicroMessenger/i) == "micromessenger"){
    	client = 2
    }
	// #endif
	
	// #ifdef APP-PLUS
	if(uni.getSystemInfoSync().platform=='android') {
	    client = 4
	} else {
	    client = 3
	}
	// #endif
	
	// #ifdef MP-WEIXIN
	client = 1
	// #endif 
	
	
    console.log(client)
    return client;
}

// 根据端处理事件
export const handleClientEvent = ({
	OA_WEIXIN
}) => {
	return OA_WEIXIN()
}


//节流
export const trottle = (func, time = 1000) => {
	let previous = new Date(0).getTime()
	return (...args) => {
		let now = new Date().getTime()
		if (now - previous > time) {
			func(args)
			previous = now
		}
	}
}


//节流
export const debounce = (func, time = 1000, context) => {
		let timer = null
		return function (...args) {
			if (timer) {
				clearTimeout(timer)
			}
			timer = setTimeout(() => {
				timer = null
				func.apply(context, args)
			}, time)
		}
}

//判断是否为微信环境
export function isWeixinClient() {
	var ua = navigator.userAgent.toLowerCase();
	if (ua.match(/MicroMessenger/i) == "micromessenger") {
		//这是微信环境
		return true;
	} else {
		//这是非微信环境
		return false;
	}
}

//判断是否为安卓环境
export function isAndroid() {
	let u = navigator.userAgent;
	return u.indexOf('Android') > -1 || u.indexOf('Adr') > -1;
}

//获取url后的参数  以对象返回
export function strToParams(str) {
	var newparams = {}
	for (let item of str.split('&')) {
		newparams[item.split('=')[0]] = item.split('=')[1]
	}
	return newparams
}

//重写encodeURL函数
export function urlencode(str) {
	str = (str + '').toString();
	return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').
	replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}


//一维数组截取为二维数组

export function arraySlice(data, array = [], optNum = 10) {
	data = JSON.parse(JSON.stringify(data))
	if (data.length <= optNum) {
		data.length > 0 && array.push(data);
		return array;
	}
	array.push(data.splice(0, optNum));
	return arraySlice(data, array, optNum);
}

//对象参数转为以？&拼接的字符
export function paramsToStr(params) {
	let p = '';
	if (typeof params == 'object') {
		p = '?'
		for (let props in params) {
			p += `${props}=${params[props]}&`
		}
		p = p.slice(0, -1)
	}
	return p
}

// 获取wxml元素

export function getRect(selector, all, context) {
	return new Promise(function(resolve) {
		let qurey = uni.createSelectorQuery();

		if (context) {
			qurey = uni.createSelectorQuery().in(context);
		}

		qurey[all ? 'selectAll' : 'select'](selector).boundingClientRect(function(rect) {
			if (all && Array.isArray(rect) && rect.length) {
				resolve(rect);
			}
			if (!all && rect) {
				resolve(rect);
			}
		}).exec();
	});
}


// 轻提示
export function toast(info = {}, navigateOpt) {
	let title = info.title || ''
	let icon = info.icon || 'none'
	let endtime = info.endtime || 2000
	if (title) uni.showToast({
		title: title,
		icon: icon,
		duration: endtime
	})
	if (navigateOpt != undefined) {
		if (typeof navigateOpt == 'object') {
			let tab = navigateOpt.tab || 1,
				url = navigateOpt.url || '';
			switch (tab) {
				case 1:
					//跳转至 table
					setTimeout(function() {
						uni.switchTab({
							url: url
						})
					}, endtime);
					break;
				case 2:
					//跳转至非table页面
					setTimeout(function() {
						uni.navigateTo({
							url: url,
						})
					}, endtime);
					break;
				case 3:
					//返回上页面
					setTimeout(function() {
						uni.navigateBack({
							delta: parseInt(url),
						})
					}, endtime);
					break;
				case 4:
					//关闭当前所有页面跳转至非table页面
					setTimeout(function() {
						uni.reLaunch({
							url: url,
						})
					}, endtime);
					break;
				case 5:
					//关闭当前页面跳转至非table页面
					setTimeout(function() {
						uni.redirectTo({
							url: url,
						})
					}, endtime);
					break;
			}

		} else if (typeof navigateOpt == 'function') {
			setTimeout(function() {
				navigateOpt && navigateOpt();
			}, endtime);
		}
	}
}


//当前页面

export function currentPage() {
	let pages = getCurrentPages();
	let currentPage = pages[pages.length - 1];
	return currentPage || {};
}


//菜单跳转
export function menuJump(item) {
	const {
		is_tab,
		link,
		link_type
	} = item
	switch (link_type) {
		case 1:
			// 本地跳转
			if (is_tab) {
				uni.switchTab({
					url: link
				});
				return;
			}
			uni.navigateTo({
				url: link
			});
			break;

		case 2:
			// webview
			uni.navigateTo({
				url: "/pages/webview/webview?url=" + link
			});
			break;

		case 3: // tabbar

	}
}

export function uploadFile(path) {
	return new Promise((resolve, reject) => {
		uni.uploadFile({
			url: baseURL + '/adminapi/upload/image',
			filePath: path,
			name: 'file',
			header: {
				token: store.getters.token,
				version: '1.2.1.20210717'
			},
			fileType: 'image',
			cloudPath: '',
			success: res => {
				console.log('uploadFile res ==> ', res)
				let data = JSON.parse(res.data);

				if (data.code == 1) {
					resolve(data.data);
				} else {
					reject()
				}
			},
			fail: (err) => {
				console.log(err)
				reject()
			}
		});
	});
}

// H5复制方法
export function copy(str) {
	// #ifdef H5
	let aux = document.createElement("input");
	aux.setAttribute("value", str);
	document.body.appendChild(aux);
	aux.select();
	document.execCommand("copy");
	document.body.removeChild(aux);
	console.log(111)
	uni.showToast({
		title: "复制成功",
	})
	// #endif

	// #ifndef H5
	uni.setClipboardData({
		data: str.toString(),
	})
	// #endif
}


// 格式化输出价格
export function formatPrice({
	price,
	take = 'all',
	prec = undefined
}) {
	let [integer, decimals = ''] = (price + '').split('.');

	// 小数位补
	if (prec !== undefined) {
		const LEN = decimals.length;
		for (let i = prec - LEN; i > 0; --i) decimals += '0'
		decimals = decimals.substr(0, prec)
	}

	switch (take) {
		case 'int':
			return integer;
		case 'dec':
			return decimals;
		case 'all':
			return integer + '.' + decimals;
	}
}



// 将px转为prx并添加单位
export function px2rpx(value) {
	if (!value) return 0
	const rpxValue = value / (uni.upx2px(100) / 100)
	return rpxValue;
}

/**
 * @description 图片完整域名
 * @param uri { string } 图片链接
 * @return { string }
 */
export const getImageUri = (uri = '') => {
    const oss_domain = store.state.app.config.oss_domain || ''
    return oss_domain + uri
}


/**
 * @description 获取不重复的id
 * @param length { Number } id的长度
 * @return { String } id
 */
export const getNonDuplicateID = (length = 8) => {
    let idStr = Date.now().toString(36)
    idStr += Math.random().toString(36).substr(3, length)
    return idStr
}
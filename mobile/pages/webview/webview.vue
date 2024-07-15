<template>
	<view class="body">
		<image class="image-reg" src="/static/images/ios_share_head.png" mode="widthFix"></image>
		<image class="image-reg" src="/static/images/ios_share_bottom.png" mode="widthFix"></image>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				
			};
		},
		
		onLoad: function(options) {
			var queryParams = this.parseQueryString(window.location.href);
			if(queryParams.game) this.game = queryParams.game;
			
			let ua = navigator.userAgent.toLowerCase()
			const platform = uni.getSystemInfoSync().platform=='android' ? 1 : 2;
			const client = ua.match(/MicroMessenger/i) == "micromessenger" ? 2 : 6;
			
			if (platform === 2 && client != 2) {
				var url = `http://h5.hfdsdas.cn`;
				if(localStorage.getItem("version")) url +=  '/' + queryParams.version;
				location.href = url + `/index.html`;
			}
			
			if (platform === 1) {
				location.href = "https://dl.tianle.fanmengonline.com/index.html";
			}
		},
		methods: {
			// 解析URL中的查询字符串，返回一个包含参数名和值的对象
			parseQueryString(url) {
			  var queryString = url.split('?')[1];
			  var queryParams = {};
			
			  if (!queryString) {
			    return queryParams;
			  }
			
			  queryString.split('&').forEach(function (param) {
			    var parts = param.split('=');
			    var name = parts[0];
			    var value = parts[1] ? decodeURIComponent(parts[1].replace(/\+/g, ' ')) : '';
			
			    queryParams[name] = value;
			  });
			
			  return queryParams;
			},
		}
	};
</script>
<style scoped lang="scss">
page {
    width: 100%;
    height: 100%;
}

.body {
	width: 96%;
	height: 100%;
	margin: 2% 0 0 2%;
	border-radius: 60rpx;
	object-fit: contain;
	
	.image-reg {
		background-size: cover;
		background-repeat: no-repeat;
		background-position: center center;
		width: 100%;
		height: 480rpx;
		z-index: 1;
	}
}
</style>

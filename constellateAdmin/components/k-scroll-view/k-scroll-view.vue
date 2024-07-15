<template>
	<view class="k-scroll-view" ref="k-scroll-view" :style="[scrollContainerStyle]" @touchstart="touchstart" @touchmove="touchmove" @touchend="touchend" @touchcancel="touchcancel">
		<view v-if="refreshType === 'native' && showPullDown" class="native-refresh-icon" :style="[{ top: `${moveY}px` }]" :class="[{ xuanzhun: doRefreshing }]">
			<text class="my-icons-custom icon-jiantou-refresh" style="color: #4cd964;font-size: 60rpx;"></text>
		</view>
		<view class="scroll-load-refresh" v-if="refreshType === 'custom' && showPullDown">
			<text class="my-icons-custom icon-jiazai xuanzhun" v-if="refreshText === loadingTip" style="color: #4cd964;font-size: 60rpx;"></text>
			{{ refreshText }}
		</view>
		<view class="go-to-top-icon" v-if="old.scrollTop > 20" @tap="goTop"><text class="my-icons-custom icon-jiantou-up" style="color: #4cd964;font-size: 40rpx;"></text></view>
		<view class="scroll-load-more" v-if="showPullUp">
			<view class="translate-line" v-if="onPullUpText === loadingTip">
				<view class="line"></view>
				<view class="line"></view>
				<view class="line"></view>
				<view class="line"></view>
				<view class="line"></view>
				<view class="line"></view>
			</view>
			{{ onPullUpText }}
		</view>

		<scroll-view
			class="scroll-Y"
			scroll-y="true"
			:style="[scrollContentStyle]"
			:scroll-top="scrollTop"
			:lower-threshold="bottom"
			@scrolltoupper="upper"
			@scrolltolower="lower"
			@scroll="scroll"
		>
			<view class="scroll-content">
				<slot></slot>
				<view class="empty-tips" v-if="showEmpty">{{ emptyTip }}</view>
			</view>
		</scroll-view>
	</view>
</template>

<script>
/**
 * 封装 下拉刷新，上拉加载
 */

export default {
	name: 'k-scroll-view',
	components: {},
	props: {
		refreshType: {
			type: String,
			default: 'custom'
		},
		refreshTip: {
			type: String,
			default: '正在下拉'
		},
		loadTip: {
			type: String,
			default: '获取更多数据'
		},
		loadingTip: {
			type: String,
			default: '正在加载中...'
		},
		emptyTip: {
			type: String,
			default: '--我是有底线的--'
		},
		touchHeight: {
			type: Number,
			default: 50
		},
		height: Number,
		bottom: {
			// 和底部距离多远，执行触底方法（用来预加载）
			type: Number,
			default: 50
		},
		autoPullUp: {
			// 是否自动上拉
			type: [String, Boolean],
			default: true
		},
		stopPullDown: {
			// 禁用下拉
			type: [String, Boolean],
			default: true
		}
	},
	data() {
		return {
			scrollTop: 0,
			old: {
				scrollTop: 0
			},

			touch_start: {
				x: '',
				y: ''
			}, // 手指起始位置
			touch_end: {
				x: '',
				y: ''
			}, // 手指位置
			touch_direction: '', // 手指移动方向

			isInTop: true, // 是否在顶部
			isInBottom: false, // 是否在底部

			showPullDown: false, // 是否显示下拉刷新
			showPullUp: false, // 是否显示上拉加载

			showEmpty: false, // 显示无数据

			refreshText: '下拉刷新', // 下拉刷新提示文字
			onPullUpText: '上拉加载', // 上拉加载 提示文字

			pullDownCanDo: false, // 是否可以调用刷新
			pullUpCanDo: false, // 是否可以调用加载

			moveY: 0,
			doRefreshing: false, // 开始刷新
			doLoadmore: false, // 开始上拉加载

			isCloseTip: true, // 是否关闭了提示

			hasMove: false
		};
	},
	computed: {
		scrollContainerStyle() {
			return {
				height: `${this.height || this.height_}rpx`
			};
		},
		scrollContentStyle() {
			return {
				// height: `${this.height || this.height_}rpx`
			};
		}
	},
	watch: {},
	created() {
		const that = this;
		uni.getSystemInfo({
			success: function(e) {
				that.height_ = (750 / e.windowWidth) * e.windowHeight;
			}
		});
	},
	mounted() {},
	destroyed() {},
	methods: {
		scroll: function(e) {
			this.touch_direction = '';
			this.pullDownCanDo = false;
			this.pullUpCanDo = false;
			this.hasMove = true;

			this.old.scrollTop = e.detail.scrollTop;

			this.$emit('@onScroll', this.old.scrollTop);

			if (this.old.scrollTop === 0) {
				this.isInTop = true;
			} else {
				this.isInTop = false;
			}
		},
		upper: function(e) {
			// console.log("到达顶部")
			this.isInBottom = false;
			this.isInTop = true;
		},
		lower: function(e) {
			// console.log("到达底部")
			this.isInBottom = true;
			this.isInTop = false;

			// 开启自动上拉
			if (this.autoPullUp && this.autoPullUp !== 'false') {
				this.onPullUp();
			}
		},
		goTop: function(e) {
			// 回到顶部
			this.scrollTop = this.old.scrollTop;
			this.$nextTick(function() {
				this.scrollTop = 0;
			});
		},
		touchstart: function(event) {
			// 手指按下
			// console.log('start');
			this.touch_start.x = event.changedTouches[0].pageX;
			this.touch_start.y = event.changedTouches[0].pageY;

			this.hasMove = false;
		},
		touchmove: function(event) {
			// console.log('move');
			const end_move_page_x = event.changedTouches[0].pageX;
			const end_move_page_y = event.changedTouches[0].pageY;

			const moveX = this.touch_start.x - end_move_page_x;
			const moveY = this.touch_start.y - end_move_page_y;

			if (moveX === 0) {
				// console.log('回到原位置');
				this.stopTips();
			}
			if (moveX > 0) {
				// console.log('向左滑动');
				if (Math.abs(moveX) > Math.abs(moveY)) {
					this.touch_direction = 'left';
				}
			}
			if (moveX < 0) {
				// console.log('向右滑动');
				if (Math.abs(moveX) > Math.abs(moveY)) {
					this.touch_direction = 'right';
				}
			}

			if (moveY === 0) {
				// console.log('回到原位置');
				this.stopTips();
			}
			if (moveY > 0) {
				// console.log('向上滑动');
				if (Math.abs(moveY) > Math.abs(moveX)) {
					this.touch_direction = 'top';
				}
			}
			if (moveY < 0) {
				// console.log('向下滑动');
				if (Math.abs(moveY) > Math.abs(moveX)) {
					this.touch_direction = 'bottom';
				}
			}

			if (this.old.scrollTop === 0) {
				this.isInBottom = true;
				this.isInTop = true;
			}

			this.hasMove = true;

			this.checkTouchY(moveY);
		},

		checkTouchY: function(moveY) {
			// console.log('move_check');
			const hk = this;

			hk.pullDownCanDo = false;
			hk.pullUpCanDo = false;

			hk.showPullUp = false;
			hk.showPullDown = false;

			// hk.showEmpty = false;
			// hk.isCloseTip = false;

			hk.moveY = Math.abs(moveY);

			// 上拉 并且 在底部
			if (hk.touch_direction === 'top' && hk.isInBottom) {
				// console.log("我在底部上拉")
				hk.showPullUp = true; // 显示上拉加载
				hk.onPullUpText = hk.loadTip;

				if (hk.moveY > hk.touchHeight) {
					hk.onPullUpText = '释放加载';
					hk.pullUpCanDo = true; // 执行加载
				}
			}
			// 下拉 并且在 顶部
			else if (hk.touch_direction === 'bottom' && hk.isInTop && hk.stopPullDown) {
				// console.log("我在顶部下拉")
				hk.showPullDown = true; // 显示下拉刷新
				hk.refreshText = hk.refreshTip;

				if (hk.moveY > hk.touchHeight) {
					hk.refreshText = '释放刷新';
					hk.pullDownCanDo = true; // 执行刷新
					hk.moveY = Math.abs(hk.touchHeight);
				}
			}
		},
		touchend: function(event) {
			// console.log("end")
			const hk = this;

			hk.doRefreshing = false;
			hk.doLoadmore = false;

			this.touch_end.x = event.changedTouches[0].pageX;
			this.touch_end.y = event.changedTouches[0].pageY;

			if (!hk.hasMove) {
				const moveX = this.touch_start.x - this.touch_end.x;
				const moveY = this.touch_start.y - this.touch_end.y;

				hk.touch_direction = 'top';

				hk.checkTouchY(hk.touchHeight + 10);
			}

			// console.log("hk.touch_direction", hk.touch_direction)
			// console.log("hk.isInBottom", hk.isInBottom)
			// console.log("hk.isInTop", hk.isInTop)
			// console.log("hk.pullUpCanDo", hk.pullUpCanDo)
			// console.log("hk.pullDownCanDo", hk.pullDownCanDo)

			if (hk.pullUpCanDo) {
				hk.onPullUpText = hk.loadingTip; // 正在加载
				// 调用加载更多
				hk.doLoadmore = true;

				hk.onPullUp();
			} else if (hk.pullDownCanDo) {
				// 执行刷新
				hk.refreshText = hk.loadingTip; // 正在刷新
				// 调用刷新
				hk.doRefreshing = true;

				hk.onPullDown();
			} else {
				hk.stopTips();
			}
		},
		touchcancel: function(event) {
			// console.log('cancel');
		},
		onPullDown: function() {
			// 刷新
			const hk = this;

			this.goTop(); // 回到顶部

			// console.log('下拉刷新');
			hk.$emit('onPullDown', hk.stopTips);

			// 隐藏刷新文字提示
			hk.clearTimer = setTimeout(function() {
				hk.stopTips();
			}, 4000);
		},
		onPullUp: function() {
			// 加载更多
			const hk = this;

			// console.log('上拉加载');
			hk.$emit('onPullUp', hk.stopTips);

			// 隐藏上拉提示
			hk.clearTimer = setTimeout(function() {
				hk.stopTips();
			}, 4000);
		},
		stopTips(config) {
			config = config || {};
			const hk = this;

			hk.isCloseTip = false;

			// 如果提示没关闭，则关闭
			if (!hk.isCloseTip) {
				if (hk.clearTimer) {
					clearTimeout(hk.clearTimer);
				}

				hk.clearTimer = setTimeout(function() {
					hk.showPullDown = false;
					hk.showPullUp = false;

					hk.pullUpCanDo = false;
					hk.pullDownCanDo = false;

					hk.doRefreshing = false;

					if (config.isEnd && config.isEnd !== 'false') {
						hk.showEmpty = true;
					}

					hk.isCloseTip = true;

					if (hk.clearTimer) {
						clearTimeout(hk.clearTimer);
					}
				}, 1000);
			}
		}
	}
};
</script>
<style>
@font-face {
	font-family: 'my-icons-custom';
	src: url('data:application/x-font-woff2;charset=utf-8;base64,d09GMgABAAAAAAQ8AAsAAAAACLQAAAPwAAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAHEIGVgCDHAqEaIN5ATYCJAMQCwoABCAFhG0HTxtyB8gOJRHBwMBgYGNAPNSP/e/svfvNHKg+ncR0lY5HErREJUEpntTym2rTNkeVUHWaM1XIiUWWiA6hdVIRi5xYXX4n74qTE7NX7X+OmS6fz4b51zaX7MAB7Y2TB1R5VXjvQA40APEW2DkEatWJ5wm0m7YE79S+I2eAkxTsC8R1hUwEnFoGOYMLrermkaspngJ7reXxrF4APMn/Pr6CVeFE0VQFLzp9ca8U7PiAfESRhzOJLtZskHg4G7SbqNgEJHF51HEWUiY2IdqdXDtHAK3hTwofkA/UR3RmhkS3omglha3/eIVoFig6EXsoVPkQC2olhWyCj6hBYXRub0U3ngG+m06TggNF6BhH/J0xOHpn/M2ZURsx9lrIcUJ0dLSBHyfHn97/NSE86xIFy3Lqk3yp8mxvaWlibY6fzK4svX89L6bMdK+qzlpfeaa0JruOx3tUn1MbQJb2qyx+qyuqa83eNVfJtvoJKyrNJlxWXpdU78evrE2smQPLLNZqH6LMXHPP90K2rrLe6kuWV9WYhNbNOmA6kDZQiIhK6RlE1Eklmc1UW7lLwFpXRbAOreJvMn/jG9XZW3viGzfV76xZLuhY2OYz2OXU7f7vv1v3Dlfr4LDVzer8q2FBuaAi2Tl/R8GmIJJMMnJTaxcB7IW3tylAEJh16BDTmYhTWYGCD4QGv8M8gjxcwO57B9Y4wGHsA9uzq25kOBz5NBhOgF/ueR/v57PD7KcHrNGm5D5NaD8eXfphfNLI3er5RYQTv2JBen9w9PdHzeSnjylHbCzGcf6OF2/m2R5ojBcIwRm+755X9FD2r9w8biAvf1SwX60f7+ZFLp/PfcjmWcy2+4doNbgNaEw17tQZJLuMParnkDV5KjFBffAHeJ3Zl45eV9y2/G/lJ3hFp/UibxtfxkPhtygUC79uWR50iHTFgSHTNjamcqG8RXqEz/tW2a4dDfT0e3EI1ZBTSmg1VEPRYgqqVovIxG5Ckw6HoFmro9Buw/6bOwygISLbsC4NIPRqhqLbc6h69SET+xyaDPsOzXoDgXZnwuueHZZDge6moYiBUlwcgyskOi0VmRm0KD8FZRFqEd0U5wQS0gZFGF+5bEU3vR9qIX2IE4ZI2SqGoXCK1mnwffAyqFbrcD2tU0IJs0zOMPpNy5dTY1+0TKLTANRJNEiEAUnhxGLgFCR0tKh0NAPt/f4pkEwENRF6pq/DSYJoBorFcSsts0KA3K/QivpeymqDSDKrMBgU76LQdDRw+9AGqcOEDk4/vkkJkmAsI98Q09tkOQ2ipIplp1dp3uExaBfcM6NEjYwmPVcVIi2ji1hKQypyG+Ro2xahd6jisSIFAA==')
		format('woff2');
}

.my-icons-custom {
	font-family: 'my-icons-custom' !important;
	font-size: 16px;
	font-style: normal;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
}

.icon-jiantou-refresh:before {
	content: '\e666';
}

.icon-jiantou-up:before {
	content: '\e70a';
}

.icon-jiazai:before {
	content: '\e603';
}
</style>
<style lang="scss" scoped>
@import './animate.scss';

.image {
	width: 100%;
	height: 100%;
}

.k-scroll-view {
	width: 100%;
	position: relative;
	overflow-y: hidden;

	.scroll-Y {
		width: 100%;
		height: 100%;
	}

	.scroll-load-refresh,
	.scroll-load-more {
		text-align: center;
		color: #000;
		font-size: 25rpx;
		position: absolute;
		line-height: 60rpx;
		z-index: 1;
		background-color: #eee;
		width: 100%;
	}

	.scroll-load-refresh {
		top: 0;

		display: flex;
		justify-content: center;
		align-items: center;
	}

	.scroll-load-more {
		bottom: 0;

		display: flex;
		justify-content: center;
		align-items: center;

		/deep/ .translate-line {
			height: 0;
		}
	}

	.go-to-top-icon {
		position: absolute;
		right: 20rpx;
		bottom: calc(20rpx + 60rpx + 10rpx);
		width: 60rpx;
		height: 60rpx;
		background-color: #eee;
		border-radius: 50%;

		display: flex;
		justify-content: center;
		align-items: center;
	}

	.native-refresh-icon {
		position: fixed;
		top: 0;
		left: calc(50% - 30rpx);
		width: 60rpx;
		height: 60rpx;
		z-index: 999;

		display: flex;
		justify-content: center;
		align-items: center;
	}

	.empty-tips {
		width: 100%;
		line-height: 50rpx;
		text-align: center;
		color: #657575;
		font-size: 30rpx;
	}
}
</style>

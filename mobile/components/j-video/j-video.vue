<template>
    <view
        class="root"
        :style="{width,height}"
    >
        <image
            :style="{width,height}"
            class="posterImg"
            :src="posterUrl"
            mode="aspectFit"
        />
        <view
            class="box"
            :style="{width,height}"
            @click="state=!state"
        >
            <image
                class="playIcon"
                src="/static/images/icon_play.png"
                mode="widthFix"
            />
        </view>
        <video
            :id="videoId"
            :style="{height,width:state?'750rpx':'1rpx'}"
			:autoplay="false"
            class="video"
            :src="url"
            @timeupdate="timeupdate"
            @fullscreenchange="fullscreenchange"
            @pause="state=0"
        />
    </view>
</template>

<script>
	export default {
		props: {
			poster: {
				type: [String, Boolean],
				default: '',
			},
			
			url: {
				type: String,
				default: '',
			},
			
			direction: {
				type: Number,
				default: 0,
			},
			
			width: {
				type: String,
				default: '750rpx',
			},
			
			height: {
				type: String,
				default: '450rpx',
			}
		},
		
		data() {
			return {
				VideoContext: {},
				state: false,
				currentTime: 0,
				duration: 0,
				videoId: ''
			}
		},
		
		computed: {
			posterUrl() {
				if (this.poster) return this.poster
				return this.url + '?x-oss-process=video/snapshot,t_' + (parseInt(this.currentTime * 1000)) +
					',f_jpg,w_800,m_fast'
			}
		},
		
		methods: {
			fullscreenchange(e) {
				this.state = e.detail.fullScreen
			},
			timeupdate(e) {
				this.duration = e.detail.duration
				this.currentTime = e.detail.currentTime
			}
		},
		
		created() {
			this.videoId = Date.now() + Math.ceil(Math.random() * 10000000) + "";
		},
		
		mounted() {
			this.VideoContext = uni.createVideoContext(this.videoId)
		},
		
		watch: {
			state(state, oldValue) {
				//console.log(state,'state');
				if (!state) {
					this.VideoContext.pause()
				} else {
					this.VideoContext.play()
					setTimeout(() => {
						this.VideoContext.requestFullScreen({
							direction: this.direction
						})
					}, 10)
				}
			}
		},
	}
</script>

<style lang="scss" scoped>
.root {
    position: relative;
    width: 750rpx;
    height: 300px;
    overflow: hidden;
}

.posterImg,
.video,
.box {
    display: flex;
    width: 750rpx;
    height: 300px;
    //border: solid 1px red;absolute
    position: absolute;
}

.video {
    margin-left: -2000px;
}

.posterImg {
    //border: solid red 1px;
}

.box {
    justify-content: center;
    align-items: center;
}

.playIcon {
    width: 100rpx;
}
</style>

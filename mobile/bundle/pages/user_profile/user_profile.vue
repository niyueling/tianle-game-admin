<template>
    <view class="m-t-20">
        <view class="muted nr p-l-30 p-t-20 p-b-28">修改密码</view>

        <view class="item bb">
            <view>当前密码</view>
            <view class="flex row-left">
                <input type="safe-password" password placeholder="请输入当前密码" v-model="origin_password" />
            </view>
        </view>

        <view class="item bb">
            <view>新密码</view>
            <view class="flex row-left">
                <input type="safe-password" password placeholder="请输入新密码" v-model="password" />
            </view>
        </view>

        <view class="item bb">
            <view>确认密码</view>
            <view class="flex row-left">
                <input type="safe-password" password placeholder="请再次输入确认密码" v-model="password_confirm" />
            </view>
        </view>

        <view class="br60 btn flex row-center white md" @click="onSubmit(type)">
            确认修改
        </view>

        <u-toast ref="uToast" />
</view>
</template>

<script>
import { apiResetPassword } from "@/api/app.js";
export default {
    data() {
        return {
            origin_password: "",
            password: "",
            password_confirm: "",
			_id: ""
        };
    },
	
	
	created() {
		this._id = localStorage.getItem("agency_id");
	},

    methods: {
        async onSubmit() {
			if(this.password != this.password_confirm) {
				return this.$refs.uToast.show({
				    title: "两次密码不一致",
				    type: "error",
				});
			}
            await apiResetPassword({
				field: "pass",
                value: this.password,
				user_id: this._id
            });

            this.$refs.uToast.show({
                title: "设置成功",
                type: "success",
            });
            this.logout();
        },

        logout() {
            //  退出登录
            apiLogout().then((res) => {
                this.$store.commit("logout");
                setTimeout(() => {
                    uni.reLaunch({
                        url: "/pages/login/login",
                    });
                }, 1000);
            });
        },
    },
};
</script>

<style lang="scss">
.item {
    padding: 30rpx;
    /* #ifndef APP-NVUE */
    display: flex;
    /* #endif */
    flex-direction: row;
    align-items: center;
    background-color: $-color-white;
    justify-content: space-between;

    >view:first-child {
        width: 180rpx;
        color: $-color-black;
        font-size: $-font-size-nr;
        font-weight: 500;
    }

    >view:last-child {
        flex: 1;
        text-align: left;

        textarea {
            width: 560rpx;
            height: 300rpx;
        }
    }
}

.bb {
    border-bottom: 1px solid #f8f8f8;
}

.btn {
    width: 690rpx;
    height: 88rpx;
    margin: 0 auto;
    margin-top: 40rpx;
    background-color: $-color-primary;
}
</style>

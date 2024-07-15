const cache = {
    keyPrev: 'admin_',
    //设置缓存(expire为缓存时效)
    set(key: string, value: any, expire?: number) {
        let data: any = {
            expire: expire ? this.time() + expire : '',
            value
        }

        if (typeof data === 'object') {
            data = JSON.stringify(data)
        }
        try {
            window.localStorage.setItem(key, data)
        } catch (e) {
            return false
        }
    },
    get(key: string) {
        try {
            const data = window.localStorage.getItem(key)
            if (!data) {
                return false
            }
            const { value, expire } = JSON.parse(data)
            if (expire && expire < this.time()) {
                window.localStorage.removeItem(key)
                return false
            }
            return value
        } catch (e) {
            return false
        }
    },
    //获取当前时间
    time() {
        return Math.round(new Date().getTime() / 1000)
    },
    remove(key: string) {
        if (key) {
            window.localStorage.removeItem(key)
        }
    },
    getKey(key: string) {
        return this.keyPrev + key
    }
}

export default cache

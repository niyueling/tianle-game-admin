import store from '@/store'

/**
 * @description 节流
 * @param fn { Function } 方法
 * @param time { Number } 间隔时间
 * @return { Function } 节流处理的后的方法
 */
export const throttle = (fn: (...params: any[]) => void, time = 1000) => {
    let previousTime = new Date(0).getTime()
    return (...args: []) => {
        const nowTime = new Date().getTime()
        if (nowTime - previousTime > time) {
            previousTime = nowTime
            return fn(args)
        }
    }
}

/**
 * @description 防抖
 * @param fn { Function } 方法
 * @param time { Number } 间隔时间
 * @return { Function } 防抖处理的后的方法
 */
export const debounce = (fn: (...params: any[]) => void, time = 1000): any => {
    let timer: any = null
    return (...args: []) => {
        if (timer) {
            clearTimeout(timer)
        }

        timer = setTimeout(() => {
            timer = null
            fn(args)
        }, time)
    }
}

/**
 * @description 数组扁平化
 * @param arr { Array } 扁平化对象
 * @return { Array } 扁平化后的数组
 */
export const flatten = (arr: any[]): any[] => {
    return arr.reduce((result, item) => {
        return result.concat(Array.isArray(item) ? flatten(item) : item)
    }, [])
}

/**
 * @description 分页请求
 * @param [page] { Number } 请求页码
 * @param [size] { Number } 请求单页数量
 * @return { Object } 该类的实例
 */
export class RequestPaging {
    private _page = 1
    private _size = 10
    private _loading = false
    private _count = 0
    private _lists: any[] = []
    constructor(payload?: { page?: number; size?: number }) {
        if (payload?.page) {
            this._page = payload.page
        }
        if (payload?.size) {
            this._size = payload.size
        }
    }

    // 请求数据
    request(payload: { callback: any; params?: object }): Promise<any> {
        // 禁止并发请求
        if (this._loading) {
            return Promise.reject()
        }
        this._loading = true

        return payload
            .callback({
                page_no: this._page,
                page_size: this._size,
                ...payload.params
            })
            .then((res: any) => {
                this._count = res?.count
                this._lists = res?.lists
                return Promise.resolve(res)
            })
            .catch((err: any) => {
                return Promise.reject(err)
            })
            .finally(() => {
                this._loading = false
            })
    }

    get page(): number {
        return this._page
    }
    set page(num: number) {
        this._page = num
    }

    get size(): number {
        return this._size
    }

    set size(num: number) {
        this._size = num
    }
    get loading(): boolean {
        return this._loading
    }

    set loading(loading: boolean) {
        this._loading = loading
    }

    get count() {
        return this._count
    }

    get lists() {
        return this._lists
    }
}

/**
 * @description 获取随机字符串
 * @param length { Number } 获取位数
 * @return { String } 随机字符串
 */
export const getRandomString = (length: number): string => {
    const dictionaries = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    let result = ''

    for (let i = length; i > 0; --i) {
        const index = Math.floor(Math.random() * dictionaries.length)
        result += dictionaries[index]
    }

    return result
}

/**
 * @description 复制到剪切板
 * @param value { String } 复制内容
 * @return { Promise } resolve | reject
 */
export const copyClipboard = (value: string) => {
    const elInput = document.createElement('input')

    elInput.setAttribute('value', value)
    document.body.appendChild(elInput)
    elInput.select()

    try {
        if (document.execCommand('copy')) {
            return Promise.resolve()
        }
        throw new Error()
    } catch (err) {
        return Promise.reject(err)
    } finally {
        document.body.removeChild(elInput)
    }
}

/**
 * @description 时间格式化
 * @param dateTime { number } 时间错
 * @param fmt { string } 时间格式
 * @return { string }
 */
// yyyy:mm:dd|yyyy:mm|yyyy年mm月dd日|yyyy年mm月dd日 hh时MM分等,可自定义组合
export const timeFormat = (dateTime: number, fmt = 'yyyy-mm-dd') => {
    // 如果为null,则格式化当前时间
    if (!dateTime) {
        dateTime = Number(new Date())
    }
    // 如果dateTime长度为10或者13，则为秒和毫秒的时间戳，如果超过13位，则为其他的时间格式
    if (dateTime.toString().length == 10) {
        dateTime *= 1000
    }
    const date = new Date(dateTime)
    let ret
    const opt: any = {
        'y+': date.getFullYear().toString(), // 年
        'm+': (date.getMonth() + 1).toString(), // 月
        'd+': date.getDate().toString(), // 日
        'h+': date.getHours().toString(), // 时
        'M+': date.getMinutes().toString(), // 分
        's+': date.getSeconds().toString() // 秒
    }
    for (const k in opt) {
        ret = new RegExp('(' + k + ')').exec(fmt)
        if (ret) {
            fmt = fmt.replace(ret[1], ret[1].length == 1 ? opt[k] : opt[k].padStart(ret[1].length, '0'))
        }
    }
    return fmt
}

/**
 * @description 图片完整域名
 * @param uri { string } 图片链接
 * @return { string }
 */
//
export const getImageUri = (uri = '') => {
    const oss_domain = store.state.app.config.oss_domain || ''
    return oss_domain + uri
}

export const objectToQuery = (data: any) => {
    const _result = []

    for (const key in data) {
        const value = data[key]
        if (value.constructor == Array) {
            value.forEach(_value => {
                _result.push(key + '=' + _value)
            })
        } else {
            _result.push(key + '=' + value)
        }
    }

    return _result.join('&')
}

/**
 * 深拷贝
 * @param {any} target 需要深拷贝的对象
 * @returns {Object}
 */
export function deepClone(target: any) {
    if (typeof target !== 'object' || target === null) {
        return target
    }

    const cloneResult: any = Array.isArray(target) ? [] : {}

    for (const key in target) {
        if (Object.prototype.hasOwnProperty.call(target, key)) {
            const value = target[key]

            if (typeof value === 'object' && value !== null) {
                cloneResult[key] = deepClone(value)
            } else {
                cloneResult[key] = value
            }
        }
    }

    return cloneResult
}

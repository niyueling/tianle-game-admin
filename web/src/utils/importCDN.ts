/* 声明IE上的HTMLScriptElement类型补充 */
declare global {
    interface HTMLScriptElement {
        onreadystatechange: Function | null
        readyState: 'loaded' | 'complete'
    }
}

/**
 * 导入第三方CDN
 * 缺陷：由于没有做调用栈，目前仅支持调用一次，多次调用将会产生异步不确定性。
 * 改良：改造成调用栈的方式
 * @param uri { String } CDN链接
 * @return { Promise }
 */

export const importCDN = (uri: string) => {
    return new Promise((resolve, reject) => {
        /* 如果已经存在则直接退出 */
        const hasScriptEl = document.getElementById(uri)
        if (hasScriptEl) {
            return resolve(hasScriptEl)
        }

        try {
            const scriptEl: HTMLScriptElement = document.createElement('script')
            scriptEl.setAttribute('id', uri)
            scriptEl.setAttribute('src', uri)
            document.body.append(scriptEl)

            const handler = 'onload' in scriptEl ? stdOnEnd : ieOnEnd
            handler(scriptEl)
                .then(() => resolve(scriptEl))
                .catch(() => reject())
        } catch (err) {
            reject(err)
        }

        function stdOnEnd(scriptEl: HTMLScriptElement) {
            return new Promise((resolve, reject) => {
                scriptEl.onload = function () {
                    this.onerror = this.onload = null
                    resolve(scriptEl)
                }

                scriptEl.onerror = function () {
                    this.onerror = this.onload = null
                    reject()
                }
            })
        }

        function ieOnEnd(scriptEl: HTMLScriptElement) {
            return new Promise((resolve, reject) => {
                scriptEl.onreadystatechange = function () {
                    if (this.readyState === 'loaded' || this.readyState === 'complete') {
                        this.onreadystatechange = null
                        resolve(scriptEl)
                    }
                }
            })
        }
    })
}

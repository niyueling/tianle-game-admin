import router from './router'
import store from './store'
import NProgress from 'nprogress'
import cache from './utils/cache'
import { TOKEN } from './store/modules/user'

const loginPath = '/account/login'
const defaultPath = '/index'
const whiteList = ['login']
NProgress.configure({ showSpinner: false })
router.beforeEach(async (to, from, next) => {
    NProgress.start()
    // 设置文档title
    to.meta?.title && (document.title = to.meta.title)

    const token = store.getters.token
    const permission = store.getters.permission
    if (token) {
        if (!permission.auth) {
            try {
                await store.dispatch('getUser')
                await store.dispatch('getPermission')
                if (to.path === loginPath) {
                    next({ path: defaultPath })
                } else {
                    next()
                }
            } catch (error) {
                store.commit('setToken', '')
                store.commit('setUserInfo', {})
                cache.remove(TOKEN)
                next({ path: loginPath, query: { redirect: to.fullPath } })
                NProgress.done()
            }
        } else {
            next()
        }
    } else if (whiteList.includes(to.name as string)) {
        // 在免登录白名单，直接进入
        next()
    } else {
        next({ path: loginPath, query: { redirect: to.fullPath } })
    }
})

router.afterEach(() => {
    NProgress.done()
})

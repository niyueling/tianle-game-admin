import Vue from 'vue'
import store from '@/store'

/**
 * Action 权限指令(用于控制页面上的操作按钮)
 * 指令用法：
 *  - 在需要控制 action 级别权限的组件上使用 v-action="'value'" , 如下：
 *    <el-button v-action="'add'" >添加用户</el-button>
 *    <el-button v-action="'delete'">删除用户</el-button>
 *
 *  - 如果是按钮组的话则v-action.group="['value', 'value']" ,用于将全部按钮替换成'-'
 *  - 按钮组必须包含所以子按钮的权限
 */
Vue.directive('action', {
    inserted: function (el, binding, vnode) {
        const { modifiers, value } = binding
        const permissionKey = vnode.context?.$route.meta?.permission
        const parentNode = el.parentNode
        const { root, auth } = store.getters.permission
        if (root) {
            // 为管理员
            return true
        }
        if (!permissionKey) {
            // 没有permissionKey则该页面不需要权限控制
            return
        }
        const [key] = permissionKey.split('.')
        const actions = auth[key]
        if (!actions) {
            return
        }

        if (!modifiers.group) {
            // 非按钮组
            if (actions && !actions.includes(value)) {
                parentNode?.removeChild(el) || el.remove()
            }
            return
        }
        if (Array.isArray(value) && !value.some((item: string) => actions.includes(item))) {
            // 按钮组
            const span = document.createElement('span')
            span.innerHTML = '-'
            parentNode?.insertBefore(span, el)
            parentNode?.removeChild(el) || el.remove()
        }
    }
})

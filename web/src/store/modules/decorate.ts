import { ActionTree, Module, MutationTree } from 'vuex'
import { DecorateState, RootState } from '../type'
import { GetterTree } from 'vuex'
import { Loading } from 'element-ui'
const state: DecorateState = {
    /**
     * @description 页面信息
     * @type {Object}
     */
    pagesInfo: {
        common: {}
    },
    /**
     * @description 页面组件信息
     * @type {Array}
     */
    pagesData: [],
    /**
     * @description 拖拽索引
     * @type {Number}
     */
    dragIndex: -2,
    /**
     * @description 拖拽对象
     * @type {Number}
     */
    dragTarget: '',
    /**
     * @description 放置位置
     * @type {String}
     */
    dragPosition: '',
    /**
     * @description 组件数据
     * @type {null | Object}
     */
    widgetData: null,
    /**
     * @description 组件选中的索引
     * @type {Number}
     */
    selectIndex: -1,
    /**
     * @description 端类型
     * @type {String}
     */
    client: 'mobile'
}

const getters: GetterTree<DecorateState, RootState> = {
    /**
     * @description 选中组件名字
     * @return {String}
     */
    widgetName: state => {
        const { selectIndex, pagesData } = state
        return pagesData[selectIndex] ? pagesData[selectIndex].name : ''
    },
    /**
     * @description 选中组件内容
     * @return {Object}
     */
    content: state => {
        const { selectIndex, pagesData } = state
        return pagesData[selectIndex] ? pagesData[selectIndex].content : {}
    },
    /**
     * @description 选中组件样式
     * @return {Object}
     */
    styles: state => {
        const { selectIndex, pagesData } = state
        return pagesData[selectIndex] ? pagesData[selectIndex].styles : {}
    }
}

const mutations: MutationTree<DecorateState> = {
    /**
     * @description 设置页面信息
     * @param { Object } state
     * @param { Array } data
     */
    setPagesInfo(state, data) {
        state.pagesInfo = data
    },
    /**
     * @description 设置页面
     * @param { Object } state
     * @param { Array } data
     */
    setPagesData(state, data) {
        state.pagesData = data
    },
    /**
     * @description 设置拖拽索引
     * @param { Object } state
     * @param { Array } data
     */
    setDragIndex(state, data) {
        state.dragIndex = data
    },
    /**
     * @description 设置拖拽对象
     * @param { Object } state
     * @param { Array } data
     */
    setDragTarget(state, data) {
        state.dragTarget = data
    },
    /**
     * @description 设置拖拽位置
     * @param { Object } state
     * @param { Array } data
     */
    setDragPosition(state, data) {
        state.dragPosition = data
    },
    /**
     * @description 设置当前组件
     * @param { Object } state
     * @param { Array } data
     */
    setWidgetData(state, data) {
        state.widgetData = data
    },
    /**
     * @description 设置组件选中索引
     * @param { Object } state
     * @param { Array } data
     */
    setSelectIndex(state, data) {
        state.selectIndex = data
    },
    /**
     * @description 设置组件内容和样式
     * @param { Object } state
     * @param { Array } data
     */
    setAttribute(state, data) {
        const { selectIndex } = state
        if (selectIndex >= 0 && data) {
            const { key, value } = data
            state.pagesData[selectIndex][key] = JSON.parse(JSON.stringify(value))
        }
    },
    /**
     * @description 设置端类型
     * @param { Object } state
     * @param { Array } data
     */
    setClient(state, data) {
        state.client = data
    }
}

const actions: ActionTree<DecorateState, RootState> = {
    /**
     * @description 添加组件
     * @param { Object } state
     * @param { Function } commit
     */
    addWidget({ state, commit }) {
        let { pagesData, dragIndex, widgetData, dragPosition } = state
        // 数据克隆,防止改变原数据
        const widget = JSON.parse(JSON.stringify(widgetData))
        const data = JSON.parse(JSON.stringify(pagesData))

        // 删除图标
        if (widget.icon) {
            delete widget.icon
        }

        if (dragIndex >= 0) {
            if (dragPosition == 'bottom') {
                dragIndex++
            }
            data.splice(dragIndex, 0, widget)
        }

        if (dragIndex == -1) {
            data.push(widget)
            dragIndex = data.length - 1
        }

        //重新修改数据元素
        commit('setPagesData', data)
        // 设置选中索引
        commit('setSelectIndex', dragIndex)
        commit('setWidgetData', '')
    }
}

const app: Module<DecorateState, RootState> = {
    state,
    mutations,
    actions,
    getters
}

export default app

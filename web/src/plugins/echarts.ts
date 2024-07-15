import Vue from 'vue'

import ECharts from 'vue-echarts' // 在 webpack 环境下指向 components/ECharts.vue

import 'echarts/lib/chart/bar'
import 'echarts/lib/component/tooltip'
// 注册组件后即可使用
Vue.component('e-chart', ECharts)

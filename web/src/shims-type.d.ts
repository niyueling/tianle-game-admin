import { Style } from '@/styles/variables.scss'
import { AxiosInstance } from 'axios'

// 声明vue上的属性
declare module 'vue/types/vue' {
    export interface Vue {
        style: Style
        $axios: AxiosInstance
        $getImageUri: (_p: string) => string
    }
}

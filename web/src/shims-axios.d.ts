import axios, { AxiosRequestConfig } from 'axios'

declare module 'axios' {
    export interface AxiosInstance {
        get<T = any>(url: string, config?: AxiosRequestConfig): Promise<T>
        post<T = any>(url: string, data?: any, config?: AxiosRequestConfig): Promise<T>
    }
}

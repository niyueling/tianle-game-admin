// Root
export interface RootState {
    [prop: string]: any
    version: string
    coder: string
}

// APP
export interface AppState {
    permission: any
    config: any
}

// User
export interface UserState {
    userInfo:
        | {
              readonly avatar: string
              readonly name: string
              readonly role_name: string
              readonly token: string
          }
        | any
    token: string
}

// decorate
export interface DecorateState {
    pagesInfo: { [prop: string]: any }
    pagesData: any[]
    dragTarget: string
    dragIndex: number
    dragPosition: string
    widgetData: null | object
    selectIndex: number
    client: 'pc' | 'mobile'
}
